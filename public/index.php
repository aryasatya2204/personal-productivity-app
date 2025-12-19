<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

date_default_timezone_set('Asia/Jakarta');

// --- LOGIKA PATH ADAPTIF (XAMPP & CLOUD) ---

// 1. Ambil Request URI (misal: /project/public/login atau /login)
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 2. Ambil Folder Script (misal: /project/public atau /)
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$script_name = str_replace('\\', '/', $script_name); // Normalisasi Windows

// 3. Hapus Folder Script dari Request URI untuk dapatkan Path murni
// Jika script_name adalah /, jangan di-replace karena bisa menghapus root slash
if ($script_name !== '/') {
    $path = str_replace($script_name, '', $request_uri);
} else {
    $path = $request_uri;
}

// 4. Bersihkan sisa-sisa
$path = '/' . ltrim($path, '/'); // Pastikan selalu diawali /
if (strpos($path, '/index.php') === 0) {
    $path = substr($path, 10); // Hapus /index.php jika ada
}
if (empty($path) || $path === '//') {
    $path = '/';
}


// --- FUNGSI BASE_URL ADAPTIF ---
function base_url($uri = '')
{
    // Deteksi HTTPS (Support Dewa Cloud Proxy)
    $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    $protocol = $isHttps ? "https://" : "http://";
    
    $host = $_SERVER['HTTP_HOST'];
    
    // Deteksi Subfolder (PENTING UNTUK XAMPP)
    $script_dir = dirname($_SERVER['SCRIPT_NAME']);
    $script_dir = str_replace('\\', '/', $script_dir);
    
    // Hapus trailing slash jika ada, kecuali root
    $base_path = ($script_dir === '/') ? '' : $script_dir;

    return $protocol . $host . $base_path . '/' . ltrim($uri, '/');
}

// Router Switch
switch ($path) {
    case '/':
    case '/index.php':
    case '/dashboard':
        if (isset($_SESSION['user_id'])) {
            require_once __DIR__ . '/../app/Controllers/HomeController.php';
            $controller = new HomeController();
            $controller->index();
        } else {
            require_once __DIR__ . '/../app/views/landing.php';
        }
        break;

    case '/login':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case '/logout':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case '/auth/verify':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->verify();
        break;

    case '/auth/forgot-password': 
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->forgotPassword();
        break;

    case '/auth/reset': 
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->reset();
        break;

    case '/register':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;

    case '/auth/google':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->google();
        break;

    case '/auth/google/callback':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->googleCallback();
        break;

    case '/todos':
        require_once __DIR__ . '/../app/Controllers/TodoController.php';
        $controller = new TodoController();
        $controller->index();
        break;

    case '/todos/store':
        require_once __DIR__ . '/../app/Controllers/TodoController.php';
        $controller = new TodoController();
        $controller->store();
        break;

    case '/todos/toggle':
        require_once __DIR__ . '/../app/Controllers/TodoController.php';
        $controller = new TodoController();
        $controller->toggle();
        break;

    case '/todos/delete':
        require_once __DIR__ . '/../app/Controllers/TodoController.php';
        $controller = new TodoController();
        $controller->delete();
        break;

    case '/todos/update':
        require_once __DIR__ . '/../app/Controllers/TodoController.php';
        $controller = new TodoController();
        $controller->update();
        break;

    case '/notes':
        require_once __DIR__ . '/../app/Controllers/NoteController.php';
        $controller = new NoteController();
        $controller->index();
        break;

    case '/notes/store':
        require_once __DIR__ . '/../app/Controllers/NoteController.php';
        $controller = new NoteController();
        $controller->store();
        break;

    case '/notes/update':
        require_once __DIR__ . '/../app/Controllers/NoteController.php';
        $controller = new NoteController();
        $controller->update();
        break;

    case '/notes/folders/store':
        require_once __DIR__ . '/../app/Controllers/NoteController.php';
        $controller = new NoteController();
        $controller->storeFolder();
        break;

    case '/notes/folders/delete':
        require_once __DIR__ . '/../app/Controllers/NoteController.php';
        $controller = new NoteController();
        $controller->deleteFolder();
        break;

    case '/notes/delete':
        require_once __DIR__ . '/../app/Controllers/NoteController.php';
        $controller = new NoteController();
        $controller->delete();
        break;

    case '/notes/pin':
        require_once __DIR__ . '/../app/Controllers/NoteController.php';
        $controller = new NoteController();
        $controller->pin();
        break;

    case '/subtasks/list':
        require_once __DIR__ . '/../app/Controllers/SubtaskController.php';
        $controller = new SubtaskController();
        $controller->index();
        break;

    case '/subtasks/store':
        require_once __DIR__ . '/../app/Controllers/SubtaskController.php';
        $controller = new SubtaskController();
        $controller->store();
        break;

    case '/subtasks/toggle':
        require_once __DIR__ . '/../app/Controllers/SubtaskController.php';
        $controller = new SubtaskController();
        $controller->toggle();
        break;

    case '/subtasks/delete':
        require_once __DIR__ . '/../app/Controllers/SubtaskController.php';
        $controller = new SubtaskController();
        $controller->delete();
        break;

    case '/habits':
        require_once __DIR__ . '/../app/Controllers/HabitController.php';
        $controller = new HabitController();
        $controller->index();
        break;

    case '/habits/store':
        require_once __DIR__ . '/../app/Controllers/HabitController.php';
        $controller = new HabitController();
        $controller->store();
        break;

    case '/habits/delete':
        require_once __DIR__ . '/../app/Controllers/HabitController.php';
        $controller = new HabitController();
        $controller->delete();
        break;
        
    case '/habits/toggle':
        require_once __DIR__ . '/../app/Controllers/HabitController.php';
        $controller = new HabitController();
        $controller->toggle();
        break;

    case '/focus':
        require_once __DIR__ . '/../app/Controllers/FocusController.php';
        $controller = new FocusController();
        $controller->index();
        break;

    case '/focus/store':
        require_once __DIR__ . '/../app/Controllers/FocusController.php';
        $controller = new FocusController();
        $controller->store();
        break;

    case '/tags/list':
        require_once __DIR__ . '/../app/Controllers/TagController.php';
        $controller = new TagController();
        $controller->list();
        break;

    case '/tags/store':
        require_once __DIR__ . '/../app/Controllers/TagController.php';
        $controller = new TagController();
        $controller->store();
        break;

    case '/tags/delete':
        require_once __DIR__ . '/../app/Controllers/TagController.php';
        $controller = new TagController();
        $controller->delete();
        break;
    
    case '/search':
        require_once __DIR__ . '/../app/Controllers/SearchController.php';
        $controller = new SearchController();
        $controller->index();
        break;

    case '/profile':
        require_once __DIR__ . '/../app/Controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->index();
        break;
    
    case '/profile/update':
        require_once __DIR__ . '/../app/Controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->update();
        break;

    case '/profile/avatar':
        require_once __DIR__ . '/../app/Controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->uploadAvatar();
        break;

    case '/profile/password':
        require_once __DIR__ . '/../app/Controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->changePassword();
        break;

    case '/cron/reminders':
        require_once __DIR__ . '/../app/Controllers/ReminderController.php';
        $controller = new ReminderController();
        $controller->run();
        break;

    default:
        http_response_code(404);
        echo "404 - Halaman Tidak Ditemukan<br>";
        echo "<small>System Path detected: " . htmlspecialchars($path) . "</small>";
        break;
}

