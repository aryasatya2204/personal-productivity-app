<?php
require_once __DIR__ . '/../models/Note.php';
require_once __DIR__ . '/../models/Folder.php';

class NoteController {
    
    private function ensureLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    // Tampilkan Notes + Folders
    public function index() {
        $this->ensureLogin();
        
        $userId = $_SESSION['user_id'];
        $noteModel = new Note();
        $folderModel = new Folder();

        // Cek apakah ada parameter folder di URL (misal: ?folder=5)
        $currentFolderId = isset($_GET['folder']) ? $_GET['folder'] : null;
        $currentFolder = null;
        $notes = [];

        if ($currentFolderId) {
            // SKENARIO: DALAM FOLDER
            $currentFolder = $folderModel->find($currentFolderId, $userId);
            // Jika folder tidak ditemukan/bukan milik user, kembalikan ke root
            if (!$currentFolder) {
                header('Location: ' . base_url('notes'));
                exit;
            }
            $notes = $noteModel->getByFolder($userId, $currentFolderId);
        } else {
            // SKENARIO: ROOT (HALAMAN UTAMA)
            // Ambil catatan yang tidak punya folder
            $notes = $noteModel->getRootNotes($userId);
        }

        // Kita tetap butuh daftar semua folder untuk Dropdown "Pindah Folder" & Tampilan Grid Folder
        $allFolders = $folderModel->getAll($userId);

        $data = [
            'title' => $currentFolder ? $currentFolder['name'] : 'Catatan Pribadi',
            'user_name' => $_SESSION['user_name'],
            'notes' => $notes,            // Catatan yang ditampilkan (tergantung posisi)
            'folders' => $allFolders,     // Semua folder (untuk grid & dropdown)
            'current_folder' => $currentFolder // Info folder aktif (jika ada)
        ];

        $this->view('notes/index', $data);
    }

    // --- LOGIKA NOTE ---

    public function store() {
        $this->ensureLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $folderId = isset($_POST['folder_id']) ? $_POST['folder_id'] : null;

            if (!empty($title) && !empty($content)) {
                $noteModel = new Note();
                $data = [
                    'user_id' => $_SESSION['user_id'],
                    'folder_id' => $folderId,
                    'title' => $title,
                    'content' => $content
                ];
                $noteModel->create($data);
            }
        }
        header('Location: ' . base_url('notes'));
        exit;
    }

    public function update() {
        $this->ensureLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $folderId = isset($_POST['folder_id']) ? $_POST['folder_id'] : null;

            if (!empty($title) && !empty($content)) {
                $noteModel = new Note();
                // Validasi kepemilikan dulu
                if ($noteModel->find($_POST['id'], $_SESSION['user_id'])) {
                    $data = [
                        'id' => $_POST['id'],
                        'user_id' => $_SESSION['user_id'],
                        'folder_id' => $folderId,
                        'title' => $title,
                        'content' => $content
                    ];
                    $noteModel->update($data);
                }
            }
        }
        header('Location: ' . base_url('notes'));
        exit;
    }

    public function delete() {
        $this->ensureLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $noteModel = new Note();
            $noteModel->delete($_POST['id'], $_SESSION['user_id']);
        }
        header('Location: ' . base_url('notes'));
        exit;
    }

    public function pin() {
        $this->ensureLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $noteModel = new Note();
            $id = $_POST['id'];
            $userId = $_SESSION['user_id'];
            $note = $noteModel->find($id, $userId);
            if ($note) {
                $newStatus = $note['is_pinned'] ? 0 : 1;
                $noteModel->togglePin($id, $userId, $newStatus);
            }
        }
        header('Location: ' . base_url('notes'));
        exit;
    }

    // --- LOGIKA FOLDER ---

    public function storeFolder() {
        $this->ensureLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            if (!empty($name)) {
                $folderModel = new Folder();
                $folderModel->create($name, $_SESSION['user_id']);
            }
        }
        header('Location: ' . base_url('notes'));
        exit;
    }

    public function deleteFolder() {
        $this->ensureLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $folderModel = new Folder();
            $folderModel->delete($_POST['id'], $_SESSION['user_id']);
        }
        header('Location: ' . base_url('notes'));
        exit;
    }

    private function view($view, $data = []) {
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}