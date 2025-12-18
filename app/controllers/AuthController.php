<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../services/MailerService.php';
use App\Services\MailerService;


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

        // Validasi Input
        if (empty($data['name'])) { $data['name_error'] = 'Nama wajib diisi'; }
        if (empty($data['email'])) { $data['email_error'] = 'Email wajib diisi'; }
        if (empty($data['password'])) { $data['password_error'] = 'Password wajib diisi'; }
        if ($data['password'] != $data['confirm_password']) { $data['confirm_password_error'] = 'Konfirmasi password tidak cocok'; }

        $userModel = new User();
        if ($userModel->findUserByEmail($data['email'])) {
            $data['email_error'] = 'Email sudah terdaftar';
        }

        if (empty($data['name_error']) && empty($data['email_error']) && empty($data['password_error']) && empty($data['confirm_password_error'])) {
            
            // Register User (is_verified masih 0)
            if ($userModel->register($data)) { 
                
                try {
                    // Generate Token
                    $token = bin2hex(random_bytes(32));
                    
                    // Simpan Token
                    $userModel->setActivationToken($data['email'], $token);

                    // Siapkan Email dengan Template Modern
                    $mailer = new MailerService();
                    $verifyLink = base_url('/auth/verify?token=' . $token);
                    
                    // Load email template
                    ob_start();
                    $userName = htmlspecialchars($data['name']);
                    require __DIR__ . '/../views/emails/verification.php';
                    $emailBody = ob_get_clean();

                    $subject = "🚀 Verifikasi Akun Anda - Productivity App";
                    $mailer->sendEmail($data['email'], $data['name'], $subject, $emailBody);

                    // Set session untuk popup
                    $_SESSION['registration_pending'] = true;
                    $_SESSION['registered_email'] = $data['email'];
                    header('Location: /register');
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
            try {
                // Generate Token
                $token = bin2hex(random_bytes(32));
                $userModel->setResetToken($email, $token);

                // Siapkan Email dengan Template Modern
                $resetLink = base_url('auth/reset?token=' . $token);
                $mailer = new MailerService();
                
                // Load email template
                ob_start();
                require __DIR__ . '/../views/emails/reset_password.php';
                $emailBody = ob_get_clean();

                $subject = "🔐 Reset Password - Productivity App";
                $mailer->sendEmail($email, 'User', $subject, $emailBody);
                
                // PENTING: Set session DAN redirect
                $_SESSION['reset_email_sent'] = true;
                $_SESSION['reset_email_address'] = $email;
                
                // Redirect ke halaman yang sama untuk trigger popup
                header('Location: ' . base_url('auth/forgot-password'));
                exit;

            } catch (Exception $e) {
                $data['error'] = 'Gagal mengirim email: ' . $e->getMessage();
            }
        } else {
            $data['error'] = 'Email tidak terdaftar.';
        }
    }
    
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
    // Pastikan session sudah dimulai
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $clientId     = $_ENV['GOOGLE_CLIENT_ID'];
    $clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
    
    // PENTING: Gunakan URL langsung dari .env, JANGAN pakai base_url()
    $redirectUri = $_ENV['GOOGLE_REDIRECT_URI'];

    // Validasi config
    if (empty($clientId) || empty($clientSecret) || empty($redirectUri)) {
        die('Google OAuth configuration is incomplete. Please check your .env file.');
    }

    try {
        $provider = new \League\OAuth2\Client\Provider\Google([
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri'  => $redirectUri,
        ]);

        $authUrl = $provider->getAuthorizationUrl([
            'scope' => ['email', 'profile'],
            'access_type' => 'online', // Tidak perlu refresh token
            'prompt' => 'select_account' // User bisa pilih akun
        ]);

        // Simpan state untuk CSRF protection
        $_SESSION['oauth2state'] = $provider->getState();

        header('Location: ' . $authUrl);
        exit;
        
    } catch (\Exception $e) {
        error_log('Google OAuth Init Error: ' . $e->getMessage());
        die('Failed to initialize Google login: ' . $e->getMessage());
    }
}

    // 2. Handle Callback dari Google
    public function googleCallback() {
    // Pastikan session aktif
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Cek error dari Google terlebih dahulu
    if (isset($_GET['error'])) {
        error_log('Google OAuth Error: ' . $_GET['error']);
        die('Google login cancelled or failed: ' . htmlspecialchars($_GET['error']));
    }

    // Validasi parameter yang diperlukan
    if (empty($_GET['code'])) {
        die('Authorization code missing. Please try logging in again.');
    }

    if (empty($_GET['state'])) {
        die('State parameter missing. Please try logging in again.');
    }

    // Validasi state untuk CSRF protection
    if (!isset($_SESSION['oauth2state'])) {
        die('Session expired or invalid. Please try logging in again.');
    }

    if ($_GET['state'] !== $_SESSION['oauth2state']) {
        unset($_SESSION['oauth2state']);
        die('Invalid state. CSRF protection failed. Please try logging in again.');
    }

    // State valid, hapus dari session
    unset($_SESSION['oauth2state']);

    $clientId     = $_ENV['GOOGLE_CLIENT_ID'];
    $clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
    $redirectUri  = $_ENV['GOOGLE_REDIRECT_URI']; // JANGAN pakai fallback base_url()

    try {
        $provider = new \League\OAuth2\Client\Provider\Google([
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri'  => $redirectUri,
        ]);

        // Tukar authorization code dengan access token
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // Ambil data user dari Google
        $googleUser = $provider->getResourceOwner($token);
        
        $gId     = $googleUser->getId();
        $gEmail  = $googleUser->getEmail();
        $gName   = $googleUser->getName();
        $gAvatar = $googleUser->getAvatar();

        // Validasi data yang diterima
        if (empty($gId) || empty($gEmail)) {
            throw new \Exception('Failed to get user information from Google');
        }

        $userModel = new User();
        
        // Cek apakah user sudah pernah login dengan Google
        $user = $userModel->findUserByGoogleId($gId);

        if (!$user) {
            // Cek apakah email sudah terdaftar
            $existingUser = $userModel->findUserByEmail($gEmail);
            
            if ($existingUser) {
                // Link akun Google ke akun existing
                $userModel->linkGoogleAccount($gEmail, $gId, $gAvatar);
                $user = $userModel->findUserByEmail($gEmail);
            } else {
                // Register user baru
                $newUser = [
                    'name' => $gName,
                    'email' => $gEmail,
                    'google_id' => $gId,
                    'avatar' => $gAvatar
                ];
                $userModel->registerViaGoogle($newUser);
                $user = $userModel->findUserByEmail($gEmail);
            }
        }

        if (!$user) {
            throw new \Exception('Failed to create or retrieve user account');
        }

        // Login sukses
        $this->createUserSession($user);

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        error_log('Google OAuth API Error: ' . $e->getMessage());
        die('Failed to authenticate with Google: ' . $e->getMessage());
    } catch (\Exception $e) {
        error_log('Google OAuth Error: ' . $e->getMessage());
        die('An error occurred during Google login: ' . $e->getMessage());
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
        header('Location: /');
      	exit;
    }

    // Helper untuk memanggil view
    private function view($view, $data = []) {
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}