<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../services/MailerService.php';
use App\Services\MailerService;

// Pastikan base_url() tersedia
if (!function_exists('base_url')) {
    function base_url($uri = '') {
        $script_name = dirname($_SERVER['SCRIPT_NAME']);
        $script_name = str_replace('\\', '/', $script_name);
        return $script_name . '/' . ltrim($uri, '/');
    }
}

class AuthController {
    
    public function register() {
        $data = [
            'name' => '',
            'email' => '',
            'password' => '',
            'confirm_password' => '',
            'name_error' => '',
            'email_error' => '',
            'password_error' => '',
            'confirm_password_error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT); 
        
            $data['name'] = trim($_POST['name']);
            $data['email'] = trim($_POST['email']);
            $data['password'] = trim($_POST['password']);
            $data['confirm_password'] = trim($_POST['confirm_password']);

            // Validasi Input (Logika Lama)
            if (empty($data['name'])) { $data['name_error'] = 'Nama wajib diisi'; }
            if (empty($data['email'])) { $data['email_error'] = 'Email wajib diisi'; }
            if (empty($data['password'])) { $data['password_error'] = 'Password wajib diisi'; }
            if ($data['password'] != $data['confirm_password']) { $data['confirm_password_error'] = 'Konfirmasi password tidak cocok'; }

            $userModel = new User();
            if ($userModel->findUserByEmail($data['email'])) {
                $data['email_error'] = 'Email sudah terdaftar';
            }

            if (empty($data['name_error']) && empty($data['email_error']) && empty($data['password_error']) && empty($data['confirm_password_error'])) {
                
                // 1. Register User (is_verified masih 0)
                if ($userModel->register($data)) { 
                    
                    // --- IMPLEMENTASI MAGIC LINK ---
                    try {
                        // Generate Token
                        $token = bin2hex(random_bytes(32));
                        
                        // Simpan Token ke User yg baru dibuat (berdasarkan email)
                        $userModel->setActivationToken($data['email'], $token);

                        // Siapkan Email
                        $mailer = new MailerService();
                        $verifyLink = base_url('/auth/verify?token=' . $token); // Pastikan routing Anda mendukung query string ini
                        
                        $subject = "Verifikasi Akun Anda";
                        $body = "
                            <h3>Halo " . htmlspecialchars($data['name']) . "!</h3>
                            <p>Terima kasih telah mendaftar. Langkah terakhir, klik tombol di bawah ini untuk mengaktifkan akun:</p>
                            <a href='$verifyLink' style='background:#007bff; color:white; padding:10px 15px; text-decoration:none; border-radius:5px;'>Verifikasi Akun Saya</a>
                            <br><br>
                            <p>Atau copy link ini: $verifyLink</p>
                        ";

                        $mailer->sendEmail($data['email'], $data['name'], $subject, $body);

                        // Redirect ke Login dengan pesan
                        // Anda bisa membuat view khusus 'verify_sent.php' jika mau
                        echo "<script>alert('Registrasi berhasil! Silakan cek email Anda untuk verifikasi.'); window.location.href='./login.php';</script>";
                        exit;

                    } catch (Exception $e) {
                        die("Gagal mengirim email: " . $e->getMessage());
                    }

                } else {
                    die('Terjadi kesalahan database.');
                }
            }
        }

        $this->view('auth/register', $data);
    }

    public function verify() {
        // Ambil token dari URL
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            die("Token tidak valid.");
        }

        $userModel = new User();
        $user = $userModel->findUserByActivationToken($token);

        if ($user) {
            // Aktifkan User
            $userModel->activateUser($user['id']);
            
            // Auto Login (Magic)
            $this->createUserSession($user);
            exit;
        } else {
            die("Link verifikasi salah atau sudah kadaluarsa.");
        }
    }

    public function login() {
        $data = [
            'email' => '',
            'password' => '',
            'email_error' => '',
            'password_error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $data['email'] = trim($_POST['email']);
            $data['password'] = trim($_POST['password']);

            if (empty($data['email'])) { $data['email_error'] = 'Email wajib diisi'; }
            if (empty($data['password'])) { $data['password_error'] = 'Password wajib diisi'; }

            if (empty($data['email_error']) && empty($data['password_error'])) {
                $userModel = new User();
                $user = $userModel->findUserByEmail($data['email']);

                if ($user) {
                    // CEK APAKAH SUDAH VERIFIED
                    if ($user['is_verified'] == 0) {
                        $data['email_error'] = 'Akun belum diverifikasi. Cek email Anda.';
                    } else {
                        if (password_verify($data['password'], $user['password'])) {
                            $this->createUserSession($user);
                            exit;
                        } else {
                            $data['password_error'] = 'Password salah';
                        }
                    }
                } else {
                    $data['email_error'] = 'Email tidak ditemukan';
                }
            }
        }

        $this->view('auth/login', $data);
    }

    public function forgotPassword() {
        $data = ['email' => '', 'error' => '', 'success' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $userModel = new User();

            if ($userModel->findUserByEmail($email)) {
                $token = bin2hex(random_bytes(32));
                $userModel->setResetToken($email, $token);

                $resetLink = base_url('/auth/reset?token=' . $token);
                $mailer = new MailerService();
                
                $mailer->sendEmail($email, 'User', 'Reset Password', 
                    "Klik link ini untuk reset password: <a href='$resetLink'>Reset Password</a>");
                
                $data['success'] = 'Link reset password telah dikirim ke email Anda.';
            } else {
                $data['error'] = 'Email tidak terdaftar.';
            }
        }
        // Pastikan Anda membuat file view: app/views/auth/forgot_password.php
        $this->view('auth/forgot_password', $data);
    }

    // --- FITUR BARU: RESET PASSWORD FORM ---
    public function reset() {
        $token = $_GET['token'] ?? '';
        $userModel = new User();
        $user = $userModel->findUserByResetToken($token);

        if (!$user) {
            die("Link tidak valid atau sudah expired.");
        }

        $data = [
            'token' => $token,
            'password' => '', 
            'confirm_password' => '',
            'password_error' => '',
            'confirm_password_error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['password'] = $_POST['password'];
            $data['confirm_password'] = $_POST['confirm_password'];

            if (empty($data['password'])) { $data['password_error'] = 'Password wajib diisi'; }
            if ($data['password'] != $data['confirm_password']) { $data['confirm_password_error'] = 'Password tidak sama'; }

            if (empty($data['password_error']) && empty($data['confirm_password_error'])) {
                $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
                $userModel->updatePasswordNew($user['id'], $hashed);
                
                echo "<script>alert('Password berhasil diubah! Silakan login.'); window.location.href='./login.php';</script>";
                exit;
            }
        }

        // Pastikan Anda membuat file view: app/views/auth/reset_password.php
        $this->view('auth/reset_password', $data);
    }

    public function google() {
        // Ambil config dari .env
        $clientId     = $_ENV['GOOGLE_CLIENT_ID'];
        $clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
        // URL Callback harus SAMA PERSIS dengan yang didaftarkan di Console
        $redirectUri  = base_url('/auth/google/callback'); 

        $provider = new \League\OAuth2\Client\Provider\Google([
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri'  => $redirectUri,
        ]);

        // Dapatkan URL Login Google
        $authUrl = $provider->getAuthorizationUrl([
            'scope' => ['email', 'profile'] // Kita butuh akses email & profil dasar
        ]);

        // Simpan state untuk keamanan (CSRF Protection)
        $_SESSION['oauth2state'] = $provider->getState();

        // Redirect user
        header('Location: ' . $authUrl);
        exit;
    }

    // 2. Handle Callback dari Google
    public function googleCallback() {
        $clientId     = $_ENV['GOOGLE_CLIENT_ID'];
        $clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
        $redirectUri  = base_url('/auth/google/callback');

        $provider = new \League\OAuth2\Client\Provider\Google([
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri'  => $redirectUri,
        ]);

        // Validasi State (Mencegah CSRF Attack)
        if (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            die('Invalid state. Silakan coba login lagi.');
        }

        try {
            // Tukar "Code" dengan "Access Token"
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            // Ambil Data User dari Google
            $googleUser = $provider->getResourceOwner($token);
            
            $gId     = $googleUser->getId();
            $gEmail  = $googleUser->getEmail();
            $gName   = $googleUser->getName();
            $gAvatar = $googleUser->getAvatar();

            $userModel = new User();
            
            // Cek 1: Apakah user sudah pernah login pakai Google sebelumnya?
            $user = $userModel->findUserByGoogleId($gId);

            if (!$user) {
                // Cek 2: Jika belum, apakah emailnya sudah terdaftar via register biasa?
                $existingUser = $userModel->findUserByEmail($gEmail);
                
                if ($existingUser) {
                    // LINK ACCOUNT: Gabungkan akun Google dengan akun lama
                    $userModel->linkGoogleAccount($gEmail, $gId, $gAvatar);
                    $user = $userModel->findUserByEmail($gEmail); // Refresh data
                } else {
                    // REGISTER BARU: Buat akun baru otomatis
                    $newUser = [
                        'name' => $gName,
                        'email' => $gEmail,
                        'google_id' => $gId,
                        'avatar' => $gAvatar
                    ];
                    $userModel->registerViaGoogle($newUser);
                    $user = $userModel->findUserByEmail($gEmail); // Ambil user yang baru dibuat
                }
            }

            // Login Sukses
            $this->createUserSession($user);
            exit;

        } catch (\Exception $e) {
            die('Gagal login dengan Google: ' . $e->getMessage());
        }
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        session_destroy();
        
        header('location: ./');
        exit;
    }

    private function createUserSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_avatar'] = $user['avatar'];
        header('location: ./');
    }

    // Helper untuk memanggil view
    private function view($view, $data = []) {
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}