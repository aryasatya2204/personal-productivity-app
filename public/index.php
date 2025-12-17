<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

date_default_timezone_set('Asia/Jakarta');

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$script_name = str_replace('\\', '/', $script_name);

if (strpos($request_uri, $script_name) === 0) {
    $path = substr($request_uri, strlen($script_name));
} else {
    $path = $request_uri;
}

if (strpos($path, '/index.php') === 0) {
    $path = str_replace('/index.php', '', $path);
}

if (empty($path)) {
    $path = '/';
}

function base_url($uri = '')
{
    $script_name = dirname($_SERVER['SCRIPT_NAME']);
    $script_name = str_replace('\\', '/', $script_name);
    return $script_name . '/' . ltrim($uri, '/');
}

// Router Switch
switch ($path) {
    case '/':
    case '/index.php':
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    case '/login':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case '/logout':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case '/register':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->register();
        break;

    case '/todos':
        require_once __DIR__ . '/../app/controllers/TodoController.php';
        $controller = new TodoController();
        $controller->index();
        break;

    case '/todos/store':
        require_once __DIR__ . '/../app/controllers/TodoController.php';
        $controller = new TodoController();
        $controller->store();
        break;

    case '/todos/toggle':
        require_once __DIR__ . '/../app/controllers/TodoController.php';
        $controller = new TodoController();
        $controller->toggle();
        break;

    case '/todos/delete':
        require_once __DIR__ . '/../app/controllers/TodoController.php';
        $controller = new TodoController();
        $controller->delete();
        break;

    case '/todos/update':
        require_once __DIR__ . '/../app/controllers/TodoController.php';
        $controller = new TodoController();
        $controller->update();
        break;

    case '/notes':
        require_once __DIR__ . '/../app/controllers/NoteController.php';
        $controller = new NoteController();
        $controller->index();
        break;

    case '/notes/store':
        require_once __DIR__ . '/../app/controllers/NoteController.php';
        $controller = new NoteController();
        $controller->store();
        break;

    case '/notes/update':
        require_once __DIR__ . '/../app/controllers/NoteController.php';
        $controller = new NoteController();
        $controller->update();
        break;

    case '/notes/folders/store':
        require_once __DIR__ . '/../app/controllers/NoteController.php';
        $controller = new NoteController();
        $controller->storeFolder();
        break;

    case '/notes/folders/delete':
        require_once __DIR__ . '/../app/controllers/NoteController.php';
        $controller = new NoteController();
        $controller->deleteFolder();
        break;

    case '/notes/delete':
        require_once __DIR__ . '/../app/controllers/NoteController.php';
        $controller = new NoteController();
        $controller->delete();
        break;

    case '/notes/pin':
        require_once __DIR__ . '/../app/controllers/NoteController.php';
        $controller = new NoteController();
        $controller->pin();
        break;

    case '/subtasks/list':
        require_once __DIR__ . '/../app/controllers/SubtaskController.php';
        $controller = new SubtaskController();
        $controller->index();
        break;

    case '/subtasks/store':
        require_once __DIR__ . '/../app/controllers/SubtaskController.php';
        $controller = new SubtaskController();
        $controller->store();
        break;

    case '/subtasks/toggle':
        require_once __DIR__ . '/../app/controllers/SubtaskController.php';
        $controller = new SubtaskController();
        $controller->toggle();
        break;

    case '/subtasks/delete':
        require_once __DIR__ . '/../app/controllers/SubtaskController.php';
        $controller = new SubtaskController();
        $controller->delete();
        break;

    case '/habits':
        require_once __DIR__ . '/../app/controllers/HabitController.php';
        $controller = new HabitController();
        $controller->index();
        break;

    case '/habits/store':
        require_once __DIR__ . '/../app/controllers/HabitController.php';
        $controller = new HabitController();
        $controller->store();
        break;

    case '/habits/delete':
        require_once __DIR__ . '/../app/controllers/HabitController.php';
        $controller = new HabitController();
        $controller->delete();
        break;
        
    case '/habits/toggle':
        require_once __DIR__ . '/../app/controllers/HabitController.php';
        $controller = new HabitController();
        $controller->toggle();
        break;

    case '/focus':
        require_once __DIR__ . '/../app/controllers/FocusController.php';
        $controller = new FocusController();
        $controller->index();
        break;

    case '/focus/store':
        require_once __DIR__ . '/../app/controllers/FocusController.php';
        $controller = new FocusController();
        $controller->store();
        break;

    case '/tags/list':
        require_once __DIR__ . '/../app/controllers/TagController.php';
        $controller = new TagController();
        $controller->list();
        break;

    case '/tags/store':
        require_once __DIR__ . '/../app/controllers/TagController.php';
        $controller = new TagController();
        $controller->store();
        break;

    case '/tags/delete':
        require_once __DIR__ . '/../app/controllers/TagController.php';
        $controller = new TagController();
        $controller->delete();
        break;
    
    case '/search':
        require_once __DIR__ . '/../app/controllers/SearchController.php';
        $controller = new SearchController();
        $controller->index();
        break;

    case '/profile':
        require_once __DIR__ . '/../app/controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->index();
        break;
    
    case '/profile/update':
        require_once __DIR__ . '/../app/controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->update();
        break;

    case '/profile/avatar':
        require_once __DIR__ . '/../app/controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->uploadAvatar();
        break;

    case '/profile/password':
        require_once __DIR__ . '/../app/controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->changePassword();
        break;

    default:
        http_response_code(404);
        echo "404 - Halaman Tidak Ditemukan<br>";
        echo "<small>System Path detected: " . htmlspecialchars($path) . "</small>";
        break;
}
