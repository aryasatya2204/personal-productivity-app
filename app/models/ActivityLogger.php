<?php
class ActivityLogger {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Mencatat aktivitas user ke database.
     */
    public function log($userId, $actionType, $description) {
        try {
            $stmt = $this->db->prepare("INSERT INTO activity_logs (user_id, action_type, description) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $actionType, $description]);
        } catch (PDOException $e) {
            // Silent fail agar tidak mengganggu flow user
        }
    }

    /**
     * FUNGSI BARU: Mengambil log aktivitas terakhir untuk Dashboard
     */
    public function getRecentLogs($userId, $limit = 5) {
        $stmt = $this->db->prepare("SELECT * FROM activity_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
        // Bind value untuk limit harus integer eksplisit di PDO
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}