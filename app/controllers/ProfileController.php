<?php
require_once __DIR__ . '/../Models/User.php';

class ProfileController {
    
    private function ensureLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    public function index() {
        $this->ensureLogin();
        $userModel = new User();
        $user = $userModel->findUserById($_SESSION['user_id']);

        $data = [
            'title' => 'Profil Saya',
            'user' => $user
        ];

        $this->view('profile/index', $data);
    }

    // Update Data Teks
    public function update() {
        $this->ensureLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $bio = trim($_POST['bio']);

            $userModel = new User();
            if ($userModel->updateProfile($_SESSION['user_id'], $name, $email, $bio)) {
                // Update session name jika berubah
                $_SESSION['user_name'] = $name;
                header('Location: ' . base_url('profile?status=success'));
            } else {
                header('Location: ' . base_url('profile?status=error'));
            }
        }
    }

    // Upload Avatar
    public function uploadAvatar() {
        $this->ensureLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar'])) {
            $file = $_FILES['avatar'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            
            if (in_array($file['type'], $allowedTypes) && $file['size'] < 2000000) { // Max 2MB
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
                $target = __DIR__ . '/../../public/uploads/' . $filename;

                // Pastikan folder uploads ada
                if (!file_exists(__DIR__ . '/../../public/uploads/')) {
                    mkdir(__DIR__ . '/../../public/uploads/', 0777, true);
                }

                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $userModel = new User();
                    $userModel->updateAvatar($_SESSION['user_id'], $filename);
                    $_SESSION['user_avatar'] = $filename;
                }
            }
        }
        header('Location: ' . base_url('profile'));
    }

    // Ganti Password
    public function changePassword() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userModel = new User();
        $user = $userModel->findUserById($_SESSION['user_id']);
        
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // 1. Verifikasi Password Lama
        if (!password_verify($old_password, $user['password'])) {
            // Jika password salah, arahkan kembali dengan pesan khusus
            $_SESSION['swal_error'] = [
                'title' => 'Password Salah',
                'html' => 'Password lama tidak sesuai. <br><br> <a href="'.base_url('auth/forgot-password').'" class="text-blue-600 font-bold underline">Lupa password? Klik di sini untuk reset via email.</a>'
            ];
            header('Location: ' . base_url('profile'));
            exit;
        }

        // 2. Validasi Password Baru & Konfirmasi
        if ($new_password !== $confirm_password) {
            $_SESSION['swal_error'] = ['title' => 'Gagal', 'text' => 'Konfirmasi password baru tidak cocok.'];
            header('Location: ' . base_url('profile'));
            exit;
        }

        // 3. Update Password
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        if ($userModel->updatePassword($_SESSION['user_id'], $hashedPassword)) {
            $_SESSION['swal_success'] = 'Password berhasil diperbarui!';
        }
        
        header('Location: ' . base_url('profile'));
        exit;
    }

}

    private function view($view, $data = []) {
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}