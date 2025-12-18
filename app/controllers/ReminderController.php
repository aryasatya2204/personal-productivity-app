<?php
require_once __DIR__ . '/../Models/Todo.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Services/MailerService.php';

use App\Services\MailerService;

class ReminderController {
    
    private $todoModel;
    private $userModel;
    private $mailer;

    public function __construct() {
        $this->todoModel = new Todo();
        $this->userModel = new User();
        $this->mailer = new MailerService();
    }

    public function run() {
        // Keamanan Sederhana: Hanya boleh dijalankan lewat CLI atau request khusus
        // Anda bisa menambahkan pengecekan IP atau Secret Key di sini jika mau
        
        echo "Memulai proses pengiriman reminder...<br>";

        // 1. Ambil semua Todo yang deadline-nya HARI INI dan BELUM SELESAI
        // Saya asumsikan tabel 'todos' punya kolom 'due_date' (DATE) dan 'is_completed' (BOOLEAN/INT)
        $today = date('Y-m-d');
        
        // Kita butuh fungsi custom di Todo Model, tapi saya gunakan raw query logic di sini
        // agar tidak mengubah Model Todo.php Anda terlalu banyak.
        // Sebaiknya Anda tambahkan fungsi getPendingTodosByDate($date) di Todo.php
        $db = (new Database())->getConnection();
        
        $sql = "SELECT t.*, u.email, u.name as user_name 
                FROM todos t 
                JOIN users u ON t.user_id = u.id 
                WHERE t.is_completed = 0 
                AND DATE(t.due_date) = :today";
                
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':today', $today);
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($tasks)) {
            echo "Tidak ada tugas pending untuk hari ini.";
            return;
        }

        $count = 0;
        foreach ($tasks as $task) {
            $subject = "⏰ Pengingat: Tugas Belum Selesai Hari Ini";
            $body = "
                <h3>Halo, {$task['user_name']}!</h3>
                <p>Ini adalah pengingat bahwa Anda memiliki tugas yang harus diselesaikan hari ini:</p>
                <div style='background:#f3f4f6; padding:15px; border-radius:8px; margin:10px 0;'>
                    <strong>{$task['title']}</strong><br>
                    <small>Deadline: {$task['due_date']}</small>
                </div>
                <p>Segera selesaikan sebelum hari berakhir! Semangat! 🚀</p>
                <a href='".base_url('login')."'>Buka Aplikasi</a>
            ";

            try {
                $this->mailer->sendEmail($task['email'], $task['user_name'], $subject, $body);
                echo "Email terkirim ke: {$task['email']}<br>";
                $count++;
            } catch (Exception $e) {
                echo "Gagal kirim ke {$task['email']}: " . $e->getMessage() . "<br>";
            }
        }

        echo "Selesai. Total reminder terkirim: $count";
    }
}