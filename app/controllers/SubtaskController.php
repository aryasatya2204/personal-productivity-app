<?php
require_once __DIR__ . '/../models/Subtask.php';

class SubtaskController {
    private $db;
    private $subtaskModel;

    public function __construct() {
        // Init Database Manual karena ini dipanggil via AJAX/Router
        $database = new Database();
        $this->db = $database->getConnection();
        $this->subtaskModel = new Subtask($this->db);
    }

    // API: Ambil semua subtask untuk ditampilkan di Modal
    public function index() {
        $todoId = $_GET['todo_id'] ?? 0;
        $subtasks = $this->subtaskModel->getByTodoId($todoId);
        echo json_encode(['status' => 'success', 'data' => $subtasks]);
        exit;
    }

    // API: Simpan Subtask Baru
    public function store() {
        $todoId = $_POST['todo_id'] ?? 0;
        $title = trim($_POST['title'] ?? '');

        if ($todoId && $title) {
            $newId = $this->subtaskModel->create($todoId, $title);
            if ($newId) {
                echo json_encode(['status' => 'success', 'id' => $newId, 'title' => $title]);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
        exit;
    }

    // API: Toggle Status
    public function toggle() {
        $id = $_POST['id'] ?? 0;
        if ($id && $this->subtaskModel->toggle($id)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit;
    }

    // API: Delete Subtask
    public function delete() {
        $id = $_POST['id'] ?? 0;
        if ($id && $this->subtaskModel->delete($id)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit;
    }
}