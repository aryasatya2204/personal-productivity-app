<?php
require_once __DIR__ . '/../models/Todo.php';
require_once __DIR__ . '/../models/Note.php';

class SearchController {
    
    private function ensureLogin() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }
    }

    public function index() {
        $this->ensureLogin();
        
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (strlen($keyword) < 2) {
            echo json_encode(['status' => 'success', 'data' => []]);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $todoModel = new Todo();
        $noteModel = new Note();

        // Cari di kedua tabel
        $todos = $todoModel->search($userId, $keyword);
        $notes = $noteModel->search($userId, $keyword);

        // Gabungkan hasil
        $results = array_merge($todos, $notes);

        echo json_encode(['status' => 'success', 'data' => $results]);
        exit;
    }
}