<?php
require_once __DIR__ . '/../Models/Todo.php';
require_once __DIR__ . '/../Models/Tag.php'; 
require_once __DIR__ . '/../Models/ActivityLogger.php';

class TodoController {
    
    private function ensureLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    // READ
    public function index() {
        $this->ensureLogin();
        
        // Init Models
        $todoModel = new Todo();
        $tagModel = new Tag( (new Database())->getConnection() ); // Init Tag Model manual

        // Ambil Data
        $todos = $todoModel->getAll($_SESSION['user_id']);
        $availableTags = $tagModel->getAll($_SESSION['user_id']); // Ambil semua tag untuk UI pilihan

        $data = [
            'title' => 'Tugas Saya',
            'user_name' => $_SESSION['user_name'],
            'todos' => $todos,
            'available_tags' => $availableTags // Kirim ke View
        ];

        $this->view('todos/index', $data);
    }

    // CREATE
    public function store() {
        $this->ensureLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title']);
            $desc = trim($_POST['description']);
            $dueDate = $_POST['due_date'];
            $tags = isset($_POST['tags']) ? $_POST['tags'] : [];

            if (!empty($title) && !empty($dueDate)) {
                $db = (new Database())->getConnection();
                $tagModel = new Tag($db);
                $logger = new ActivityLogger($db); // Init Logger
                
                try {
                    $db->beginTransaction();
                    $stmt = $db->prepare("INSERT INTO todos (user_id, title, description, due_date, is_done, is_pinned) VALUES (?, ?, ?, ?, 0, 0)");
                    $stmt->execute([$_SESSION['user_id'], $title, $desc, $dueDate]);
                    $todoId = $db->lastInsertId();

                    if (!empty($tags)) {
                        foreach ($tags as $tagId) {
                            $tagModel->attachToTodo($todoId, $tagId);
                        }
                    }

                    // LOG ACTIVITY
                    $logger->log($_SESSION['user_id'], 'create_todo', "Membuat tugas baru: $title");

                    $db->commit();
                } catch (Exception $e) {
                    $db->rollBack();
                }
            }
        }
        header('Location: ' . base_url('todos'));
        exit;
    }

    // UPDATE DATA (Edit Judul/Deskripsi/Tanggal/TAGS)
    public function update() {
        $this->ensureLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $title = trim($_POST['title']);
            $desc = trim($_POST['description']);
            $dueDate = $_POST['due_date'];
            $tags = isset($_POST['tags']) ? $_POST['tags'] : [];

            if (!empty($title) && !empty($dueDate)) {
                $db = (new Database())->getConnection();
                $todoModel = new Todo();
                $tagModel = new Tag($db);
                
                // Pastikan Todo milik user
                if ($todoModel->find($id, $_SESSION['user_id'])) {
                    
                    // Update Info Dasar
                    $data = [
                        'id' => $id,
                        'user_id' => $_SESSION['user_id'],
                        'title' => $title,
                        'description' => $desc,
                        'due_date' => $dueDate
                    ];
                    $todoModel->update($data);

                    // Update Tags (Reset lalu Pasang Baru)
                    $tagModel->detachAllFromTodo($id);
                    if (!empty($tags)) {
                        foreach ($tags as $tagId) {
                            $tagModel->attachToTodo($id, $tagId);
                        }
                    }
                }
            }
        }
        header('Location: ' . base_url('todos'));
        exit;
    }

    // Toggle Status
    public function toggle() {
        $this->ensureLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $db = (new Database())->getConnection(); // Butuh DB koneksi manual untuk Logger
            $todoModel = new Todo();
            $logger = new ActivityLogger($db);

            $id = $_POST['id'];
            $todo = $todoModel->find($id, $_SESSION['user_id']);
            
            if ($todo) {
                $newStatus = $todo['is_done'] ? 0 : 1;
                $todoModel->updateStatus($id, $_SESSION['user_id'], $newStatus);

                // LOG ACTIVITY
                $action = $newStatus ? "Menyelesaikan tugas" : "Membatalkan tugas";
                $logger->log($_SESSION['user_id'], 'toggle_todo', "$action: {$todo['title']}");
            }
        }
        header('Location: ' . base_url('todos'));
        exit;
    }

    // Delete
    public function delete() {
        $this->ensureLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $db = (new Database())->getConnection();
            $todoModel = new Todo();
            $logger = new ActivityLogger($db);

            // Ambil judul dulu sebelum dihapus untuk log
            $todo = $todoModel->find($_POST['id'], $_SESSION['user_id']);
            if($todo) {
                $todoModel->delete($_POST['id'], $_SESSION['user_id']);
                // LOG ACTIVITY
                $logger->log($_SESSION['user_id'], 'delete_todo', "Menghapus tugas: {$todo['title']}");
            }
        }
        header('Location: ' . base_url('todos'));
        exit;
    }

    private function view($view, $data = []) {
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}