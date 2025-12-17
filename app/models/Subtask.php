<?php
class Subtask {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Ambil semua subtask berdasarkan Todo ID
    public function getByTodoId($todoId) {
        $stmt = $this->db->prepare("SELECT * FROM subtasks WHERE todo_id = ? ORDER BY created_at ASC");
        $stmt->execute([$todoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tambah subtask baru
    public function create($todoId, $title) {
        $stmt = $this->db->prepare("INSERT INTO subtasks (todo_id, title, is_done) VALUES (?, ?, 0)");
        if ($stmt->execute([$todoId, $title])) {
            // Return ID yang baru dibuat untuk update UI via JS
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Toggle status (Selesai / Belum)
    public function toggle($id) {
        $stmt = $this->db->prepare("UPDATE subtasks SET is_done = NOT is_done WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Hapus subtask
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM subtasks WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Hitung progress (Selesai / Total) untuk tampilan progress bar
    public function getProgress($todoId) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total, 
                SUM(CASE WHEN is_done = 1 THEN 1 ELSE 0 END) as completed 
            FROM subtasks WHERE todo_id = ?
        ");
        $stmt->execute([$todoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}