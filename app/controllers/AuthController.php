<?php
require_once __DIR__ . '/../models/User.php';

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


            if (empty($data['name'])) { $data['name_error'] = 'Nama wajib diisi'; }
            if (empty($data['email'])) { $data['email_error'] = 'Email wajib diisi'; }
            if (empty($data['password'])) { 
                $data['password_error'] = 'Password wajib diisi'; 
            } elseif (strlen($data['password']) < 6) {
                $data['password_error'] = 'Password minimal 6 karakter';
            }
            if ($data['password'] != $data['confirm_password']) {
                $data['confirm_password_error'] = 'Konfirmasi password tidak cocok';
            }

            $userModel = new User();
            if ($userModel->findUserByEmail($data['email'])) {
                $data['email_error'] = 'Email ini sudah terdaftar';
            }

            if (empty($data['name_error']) && empty($data['email_error']) && empty($data['password_error']) && empty($data['confirm_password_error'])) {
            if ($userModel->register($data)) {
                $registeredUser = $userModel->findUserByEmail($data['email']);

                if ($registeredUser) {
                    $this->createUserSession($registeredUser);
                    exit;
                }

            } else {
                die('Terjadi kesalahan sistem.');
            }
        } else {
                $this->view('auth/register', $data);
            }

        } else {
            $this->view('auth/register', $data);
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
                    if (password_verify($data['password'], $user['password'])) {
                        $this->createUserSession($user);
                        exit;
                    } else {
                        $data['password_error'] = 'Password salah';
                    }
                } else {
                    $data['email_error'] = 'Email tidak ditemukan';
                }
            }
        }

        $this->view('auth/login', $data);
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