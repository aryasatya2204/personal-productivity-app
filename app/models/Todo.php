<?php
require_once __DIR__ . '/../../config/database.php';

class Todo {
    private $conn;
    private $table = 'todos';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // 1. GET ALL (Read)
    public function getAll($userId) {
        // Query ini menggunakan GROUP_CONCAT untuk menggabungkan semua tag menjadi satu string
        // Format string tag: "id:nama:warna||id:nama:warna"
        $query = "SELECT t.*, 
                  (SELECT COUNT(*) FROM subtasks s WHERE s.todo_id = t.id) as total_subtasks,
                  (SELECT COUNT(*) FROM subtasks s WHERE s.todo_id = t.id AND s.is_done = 1) as completed_subtasks,
                  GROUP_CONCAT(tags.id, '~', tags.name, '~', tags.color_hex SEPARATOR '||') as tags_info
                  FROM " . $this->table . " t 
                  LEFT JOIN todo_tags tt ON t.id = tt.todo_id
                  LEFT JOIN tags ON tt.tag_id = tags.id
                  WHERE t.user_id = :user_id 
                  GROUP BY t.id
                  ORDER BY t.is_pinned DESC, t.is_done ASC, t.due_date ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. CREATE (Tambah)
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (user_id, title, description, due_date, is_done, is_pinned) 
                  VALUES (:user_id, :title, :description, :due_date, 0, 0)";
        
        $stmt = $this->conn->prepare($query);
        
        $title = htmlspecialchars(strip_tags($data['title']));
        $desc = htmlspecialchars(strip_tags($data['description']));
        
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $desc);
        $stmt->bindParam(':due_date', $data['due_date']);

        return $stmt->execute();
    }

    // 3. FIND (Cek apakah Todo milik User ini?)
    public function find($id, $userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. UPDATE STATUS (Toggle Checkbox)
    public function updateStatus($id, $userId, $status) {
        $query = "UPDATE " . $this->table . " SET is_done = :status WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    // 5. DELETE (Hapus)
    public function delete($id, $userId) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    // 6. UPDATE DATA (Edit Judul/Deskripsi/Tanggal)
    public function update($data) {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, description = :description, due_date = :due_date 
                  WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        
        $title = htmlspecialchars(strip_tags($data['title']));
        $desc = htmlspecialchars(strip_tags($data['description']));
        
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $desc);
        $stmt->bindParam(':due_date', $data['due_date']);
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':user_id', $data['user_id']);

        return $stmt->execute();
    }

    public function createWithSubtasks($data, $subtasks = []) {
    try {
        $this->conn->beginTransaction();
        
        // 1. Insert Todo
        $query = "INSERT INTO " . $this->table . " (user_id, title, description, due_date, is_done, is_pinned) 
                  VALUES (:user_id, :title, :description, :due_date, 0, 0)";
        
        $stmt = $this->conn->prepare($query);
        
        $title = htmlspecialchars(strip_tags($data['title']));
        $desc = htmlspecialchars(strip_tags($data['description']));
        
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $desc);
        $stmt->bindParam(':due_date', $data['due_date']);
        
        $stmt->execute();
        $todoId = $this->conn->lastInsertId();
        
        // 2. Insert Subtasks (jika ada)
        if (!empty($subtasks) && is_array($subtasks)) {
            $querySubtask = "INSERT INTO subtasks (todo_id, title, is_done) VALUES (?, ?, 0)";
            $stmtSubtask = $this->conn->prepare($querySubtask);
            
            foreach ($subtasks as $subtaskTitle) {
                $subtaskTitle = trim($subtaskTitle);
                if (!empty($subtaskTitle)) {
                    $stmtSubtask->execute([$todoId, htmlspecialchars($subtaskTitle)]);
                }
            }
        }
        
        $this->conn->commit();
        return $todoId;
        
    } catch (Exception $e) {
        $this->conn->rollBack();
        return false;
    }
}
    
    // Helper untuk Statistik Dashboard
    public function countByStatus($userId, $status) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE user_id = :user_id AND is_done = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getUpcoming($userId, $limit = 5) {
        $query = "SELECT t.*, 
                  GROUP_CONCAT(tags.id, '~', tags.name, '~', tags.color_hex SEPARATOR '||') as tags_info
                  FROM " . $this->table . " t 
                  LEFT JOIN todo_tags tt ON t.id = tt.todo_id
                  LEFT JOIN tags ON tt.tag_id = tags.id
                  WHERE t.user_id = :user_id 
                  AND t.is_done = 0 
                  AND t.due_date >= CURRENT_DATE 
                  GROUP BY t.id
                  ORDER BY t.due_date ASC 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($userId, $keyword) {
        $keyword = "%$keyword%";
        $query = "SELECT id, title, 'todo' as type, due_date as meta FROM " . $this->table . " 
                  WHERE user_id = :user_id AND (title LIKE :keyword OR description LIKE :keyword)
                  ORDER BY due_date ASC LIMIT 5";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}