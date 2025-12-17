<?php
class Focus {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Mencatat sesi baru
    public function logSession($userId, $minutes) {
        $stmt = $this->db->prepare("INSERT INTO focus_sessions (user_id, duration_minutes) VALUES (?, ?)");
        return $stmt->execute([$userId, $minutes]);
    }

    // Mendapatkan total menit fokus hari ini
    public function getDailyFocusTime($userId) {
        $stmt = $this->db->prepare("SELECT SUM(duration_minutes) as total_time FROM focus_sessions WHERE user_id = ? AND DATE(started_at) = CURRENT_DATE");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_time'] ?? 0;
    }
}