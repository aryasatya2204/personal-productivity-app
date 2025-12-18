<?php
require_once __DIR__ . '/../Models/Focus.php';

class FocusController {
    
    private function ensureLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    public function index() {
        $this->ensureLogin();
        
        $database = new Database();
        $db = $database->getConnection();
        $focusModel = new Focus($db);

        // Ambil total waktu fokus hari ini untuk penyemangat
        $todayMinutes = $focusModel->getDailyFocusTime($_SESSION['user_id']);

        $data = [
            'title' => 'Mode Fokus',
            'user_name' => $_SESSION['user_name'],
            'today_minutes' => $todayMinutes
        ];

        require_once __DIR__ . '/../views/focus/index.php';
    }

    // API: Simpan Sesi Fokus (AJAX)
    public function store() {
        $this->ensureLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $minutes = isset($_POST['minutes']) ? (int)$_POST['minutes'] : 0;
            
            if ($minutes > 0) {
                $database = new Database();
                $focusModel = new Focus($database->getConnection());
                
                if ($focusModel->logSession($_SESSION['user_id'], $minutes)) {
                    echo json_encode(['status' => 'success']);
                    exit;
                }
            }
        }
        echo json_encode(['status' => 'error']);
        exit;
    }
}