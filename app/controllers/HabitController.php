<?php
require_once __DIR__ . '/../Models/Habit.php';
require_once __DIR__ . '/../Models/ActivityLogger.php';

class HabitController {
    
    private function ensureLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    public function index() {
        $this->ensureLogin();
        
        // Init Database manual karena dipanggil via Router
        $database = new Database();
        $db = $database->getConnection();
        $habitModel = new Habit($db);

        $habits = $habitModel->getMyHabits($_SESSION['user_id']);

        $data = [
            'title' => 'Habit Tracker',
            'user_name' => $_SESSION['user_name'],
            'habits' => $habits
        ];

        // Kita akan buat view ini nanti
        require_once __DIR__ . '/../views/habits/index.php';
    }

    public function store() {
        $this->ensureLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title']);
            if (!empty($title)) {
                $database = new Database();
                $db = $database->getConnection();
                $habitModel = new Habit($db);
                $logger = new ActivityLogger($db); // Init Logger
                
                $habitModel->create($_SESSION['user_id'], $title);
                
                // LOG
                $logger->log($_SESSION['user_id'], 'create_habit', "Memulai kebiasaan baru: $title");
            }
        }
        header('Location: ' . base_url('habits'));
        exit;
    }

    public function delete() {
        $this->ensureLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $database = new Database();
            $habitModel = new Habit($database->getConnection());
            $habitModel->delete($id, $_SESSION['user_id']);
        }
        header('Location: ' . base_url('habits'));
        exit;
    }

    // AJAX Toggle
    public function toggle() {
        $this->ensureLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $database = new Database();
            $db = $database->getConnection();
            $habitModel = new Habit($db);
            $logger = new ActivityLogger($db);
            
            // Ambil nama habit untuk log (Optional, tapi lebih bagus)
            // Kita bisa pakai query manual cepat
            $stmt = $db->prepare("SELECT title FROM habits WHERE id = ?");
            $stmt->execute([$id]);
            $habitTitle = $stmt->fetchColumn();

            if ($habitModel->toggleLog($id)) {
                $logger->log($_SESSION['user_id'], 'checkin_habit', "Check-in kebiasaan: $habitTitle");
                
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
        exit;
    }
}