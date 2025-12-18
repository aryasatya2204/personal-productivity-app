<?php
require_once __DIR__ . '/../Models/Tag.php';

class TagController {
    
    private function ensureLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    // API: Ambil semua tags (JSON)
    public function list() {
        $this->ensureLogin();
        
        $database = new Database();
        $tagModel = new Tag($database->getConnection());
        $tags = $tagModel->getAll($_SESSION['user_id']);
        
        echo json_encode(['status' => 'success', 'data' => $tags]);
        exit;
    }

    // API: Simpan Tag Baru
    public function store() {
        $this->ensureLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $color = $_POST['color'] ?? '#3B82F6'; // Default Blue

            if (!empty($name)) {
                $database = new Database();
                $tagModel = new Tag($database->getConnection());
                
                if ($tagModel->create($_SESSION['user_id'], $name, $color)) {
                    echo json_encode(['status' => 'success']);
                    exit;
                }
            }
        }
        echo json_encode(['status' => 'error']);
        exit;
    }

    // API: Hapus Tag
    public function delete() {
        $this->ensureLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $database = new Database();
            $tagModel = new Tag($database->getConnection());
            
            if ($tagModel->delete($id, $_SESSION['user_id'])) {
                echo json_encode(['status' => 'success']);
                exit;
            }
        }
        echo json_encode(['status' => 'error']);
        exit;
    }
}