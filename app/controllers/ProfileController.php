<?php
require_once __DIR__ . '/../models/User.php';

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
        $this->ensureLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $current = $_POST['current_password'];
            $new = $_POST['new_password'];
            $confirm = $_POST['confirm_password'];

            $userModel = new User();
            $user = $userModel->findUserById($_SESSION['user_id']);

            if (password_verify($current, $user['password'])) {
                if ($new === $confirm && strlen($new) >= 6) {
                    $hash = password_hash($new, PASSWORD_DEFAULT);
                    $userModel->updatePassword($_SESSION['user_id'], $hash);
                    header('Location: ' . base_url('profile?msg=password_updated'));
                    exit;
                }
            }
        }
        header('Location: ' . base_url('profile?msg=password_error'));
    }

    private function view($view, $data = []) {
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}