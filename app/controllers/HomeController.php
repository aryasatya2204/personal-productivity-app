<?php
require_once __DIR__ . '/../models/Todo.php';
require_once __DIR__ . '/../models/Note.php';
require_once __DIR__ . '/../models/ActivityLogger.php';

// Pastikan base_url() tersedia
if (!function_exists('base_url')) {
    function base_url($uri = '') {
        $script_name = dirname($_SERVER['SCRIPT_NAME']);
        $script_name = str_replace('\\', '/', $script_name);
        return $script_name . '/' . ltrim($uri, '/');
    }
}

class HomeController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ./login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $db = (new Database())->getConnection(); 
        
        $todoModel = new Todo(); 
        $noteModel = new Note();
        $logger = new ActivityLogger($db); 

        $pendingCount = $todoModel->countByStatus($userId, 0); 
        $doneCount = $todoModel->countByStatus($userId, 1);  
        $noteCount = $noteModel->countAll($userId);
        
        $upcomingTodos = $todoModel->getUpcoming($userId);
        $pinnedNotes = $noteModel->getPinned($userId);
        
        $recentActivities = $logger->getRecentLogs($userId, 5);

        $data = [
            'title' => 'Dashboard',
            'user_name' => $_SESSION['user_name'],
            'stats' => [
                'pending' => $pendingCount,
                'done' => $doneCount,
                'notes' => $noteCount
            ],
            'upcoming_todos' => $upcomingTodos,
            'pinned_notes' => $pinnedNotes,
            'recent_activities' => $recentActivities // Kirim ke View
        ];

        $this->view('dashboard/index', $data);
    }

    private function view($view, $data = []) {
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}