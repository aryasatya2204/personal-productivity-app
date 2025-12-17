<?php
class Tag {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Ambil semua tags milik user
    public function getAll($userId) {
        $stmt = $this->db->prepare("SELECT * FROM tags WHERE user_id = ? ORDER BY name ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Membuat tag baru
    public function create($userId, $name, $colorHex) {
        $stmt = $this->db->prepare("INSERT INTO tags (user_id, name, color_hex) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $name, $colorHex]);
    }

    // Hapus tag
    public function delete($id, $userId) {
        $stmt = $this->db->prepare("DELETE FROM tags WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }

    // --- RELASI TODO ---
    
    // Attach tag ke Todo
    public function attachToTodo($todoId, $tagId) {
        $stmt = $this->db->prepare("INSERT IGNORE INTO todo_tags (todo_id, tag_id) VALUES (?, ?)");
        return $stmt->execute([$todoId, $tagId]);
    }

    // Lepas semua tag dari Todo (berguna saat update/edit todo)
    public function detachAllFromTodo($todoId) {
        $stmt = $this->db->prepare("DELETE FROM todo_tags WHERE todo_id = ?");
        return $stmt->execute([$todoId]);
    }

    // Ambil tags milik spesifik Todo
    public function getTagsByTodo($todoId) {
        $query = "SELECT t.* FROM tags t 
                  JOIN todo_tags tt ON t.id = tt.tag_id 
                  WHERE tt.todo_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$todoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- RELASI NOTE ---

    public function attachToNote($noteId, $tagId) {
        $stmt = $this->db->prepare("INSERT IGNORE INTO note_tags (note_id, tag_id) VALUES (?, ?)");
        return $stmt->execute([$noteId, $tagId]);
    }

    public function detachAllFromNote($noteId) {
        $stmt = $this->db->prepare("DELETE FROM note_tags WHERE note_id = ?");
        return $stmt->execute([$noteId]);
    }
}