<?php
class Habit {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Ambil habits + status hari ini + hitung streak
    public function getMyHabits($userId) {
        // Query ini mengecek apakah hari ini sudah dicentang (is_completed_today)
        // Dan mengambil data dasar habit
        $query = "SELECT h.*, 
                 (SELECT COUNT(*) FROM habit_logs hl WHERE hl.habit_id = h.id AND hl.completed_at = CURRENT_DATE) as is_completed_today
                 FROM habits h 
                 WHERE h.user_id = ?
                 ORDER BY h.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        $habits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Hitung Streak untuk setiap habit secara manual (lebih akurat daripada SQL kompleks)
        foreach ($habits as &$habit) {
            $habit['current_streak'] = $this->calculateStreak($habit['id']);
        }

        return $habits;
    }

    public function create($userId, $title) {
        $stmt = $this->db->prepare("INSERT INTO habits (user_id, title) VALUES (?, ?)");
        return $stmt->execute([$userId, $title]);
    }

    public function delete($id, $userId) {
        $stmt = $this->db->prepare("DELETE FROM habits WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }

    // Toggle Check-in (Centang / Batal Centang)
    public function toggleLog($habitId) {
        // Cek dulu apakah sudah ada log hari ini
        $stmt = $this->db->prepare("SELECT id FROM habit_logs WHERE habit_id = ? AND completed_at = CURRENT_DATE");
        $stmt->execute([$habitId]);
        $log = $stmt->fetch();

        if ($log) {
            // Jika ada, hapus (Uncheck)
            $del = $this->db->prepare("DELETE FROM habit_logs WHERE id = ?");
            return $del->execute([$log['id']]);
        } else {
            // Jika belum, insert (Check)
            $ins = $this->db->prepare("INSERT INTO habit_logs (habit_id, completed_at) VALUES (?, CURRENT_DATE)");
            return $ins->execute([$habitId]);
        }
    }

    // Logika Hitung Streak (Berturut-turut sampai kemarin/hari ini)
    private function calculateStreak($habitId) {
        $stmt = $this->db->prepare("SELECT completed_at FROM habit_logs WHERE habit_id = ? ORDER BY completed_at DESC");
        $stmt->execute([$habitId]);
        $logs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($logs)) return 0;

        $streak = 0;
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        
        // Cek log terakhir. Jika bukan hari ini ATAU kemarin, streak putus.
        $lastLog = $logs[0];
        if ($lastLog != $today && $lastLog != $yesterday) {
            return 0; 
        }

        // Hitung mundur
        $checkDate = ($lastLog == $today) ? $today : $yesterday;
        
        foreach ($logs as $logDate) {
            if ($logDate == $checkDate) {
                $streak++;
                $checkDate = date('Y-m-d', strtotime($checkDate . ' -1 day'));
            } else {
                break; // Streak putus
            }
        }
        return $streak;
    }
}