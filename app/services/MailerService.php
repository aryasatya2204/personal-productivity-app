<?php
// app/services/MailerService.php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;

class MailerService {
    private $emailPengirim;
    private $emailNama; // Opsional: Biar nama pengirim dinamis
    private $clientId;
    private $clientSecret;
    private $refreshToken;

    public function __construct() {
        // MENGAMBIL DATA DARI .env
        // Menggunakan operator ?? '' untuk mencegah error jika variabel belum diset
        $this->emailPengirim = $_ENV['MAIL_FROM_ADDRESS'] ?? '';
        $this->emailNama     = $_ENV['MAIL_FROM_NAME'] ?? 'Personal Productivity App';
        $this->clientId      = $_ENV['GOOGLE_CLIENT_ID'] ?? '';
        $this->clientSecret  = $_ENV['GOOGLE_CLIENT_SECRET'] ?? '';
        $this->refreshToken  = $_ENV['GOOGLE_REFRESH_TOKEN'] ?? '';

        // Validasi sederhana (Opsional, agar developer sadar jika lupa setting .env)
        if (empty($this->refreshToken) || empty($this->clientId)) {
            throw new \Exception("Konfigurasi Email belum lengkap. Pastikan file .env sudah diisi.");
        }
    }

    public function sendEmail($toEmail, $toName, $subject, $body) {
        $mail = new PHPMailer(true);

        try {
            // 1. Setup SMTP dengan OAuth
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->AuthType   = 'XOAUTH2';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // 2. Provider OAuth Google
            $provider = new Google([
                'clientId'     => $this->clientId,
                'clientSecret' => $this->clientSecret,
            ]);

            // 3. Set OAuth ke PHPMailer
            $mail->setOAuth(
                new OAuth([
                    'provider'     => $provider,
                    'clientId'     => $this->clientId,
                    'clientSecret' => $this->clientSecret,
                    'refreshToken' => $this->refreshToken,
                    'userName'     => $this->emailPengirim,
                ])
            );

            // 4. Konten Email
            $mail->setFrom($this->emailPengirim, $this->emailNama);
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return ['status' => true, 'message' => 'Email terkirim!'];

        } catch (\Exception $e) {
            // Log error untuk developer
            error_log("Mailer Error: " . $mail->ErrorInfo);
            return ['status' => false, 'message' => $mail->ErrorInfo];
        }
    }
}