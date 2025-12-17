<?php
require_once __DIR__ . '/../../config/database.php';

class Note {
    private $conn;
    private $table = 'notes';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // 1. GET ALL (Join dengan Folder)
    public function getAll($userId) {
        // Kita gunakan LEFT JOIN agar note yang tidak punya folder tetap muncul
        $query = "SELECT notes.*, note_folders.name as folder_name 
                  FROM " . $this->table . " 
                  LEFT JOIN note_folders ON notes.folder_id = note_folders.id
                  WHERE notes.user_id = :user_id 
                  ORDER BY notes.is_pinned DESC, notes.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. CREATE (Support Folder ID)
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (user_id, folder_id, title, content, is_pinned) 
                  VALUES (:user_id, :folder_id, :title, :content, 0)";
        $stmt = $this->conn->prepare($query);
        
        $title = htmlspecialchars(strip_tags($data['title']));
        $content = htmlspecialchars(strip_tags($data['content']));
        // Folder ID boleh NULL
        $folderId = !empty($data['folder_id']) ? $data['folder_id'] : NULL;
        
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':folder_id', $folderId);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);

        return $stmt->execute();
    }

    // 3. UPDATE (Edit Note & Folder Pindah)
    public function update($data) {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, content = :content, folder_id = :folder_id 
                  WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        
        $title = htmlspecialchars(strip_tags($data['title']));
        $content = htmlspecialchars(strip_tags($data['content']));
        $folderId = !empty($data['folder_id']) ? $data['folder_id'] : NULL;

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':folder_id', $folderId);
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':user_id', $data['user_id']);

        return $stmt->execute();
    }

    // 4. FIND
    public function find($id, $userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. DELETE
    public function delete($id, $userId) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    // 6. TOGGLE PIN
    public function togglePin($id, $userId, $status) {
        $query = "UPDATE " . $this->table . " SET is_pinned = :status WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    // 7. Ambil catatan yang TIDAK punya folder (Root)
    public function getRootNotes($userId) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE user_id = :user_id AND folder_id IS NULL 
                  ORDER BY is_pinned DESC, created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 8. Ambil catatan berdasarkan Folder ID
    public function getByFolder($userId, $folderId) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE user_id = :user_id AND folder_id = :folder_id 
                  ORDER BY is_pinned DESC, created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':folder_id', $folderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Helper Dashboard
    public function countAll($userId) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getPinned($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id AND is_pinned = 1 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($userId, $keyword) {
        $keyword = "%$keyword%";
        $query = "SELECT id, title, 'note' as type, created_at as meta FROM " . $this->table . " 
                  WHERE user_id = :user_id AND (title LIKE :keyword OR content LIKE :keyword)
                  ORDER BY created_at DESC LIMIT 5";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}