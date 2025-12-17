<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Ambil data user berdasarkan ID
    public function findUserById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($data) {
        $query = "INSERT INTO " . $this->table . " (name, email, password, is_verified) VALUES (:name, :email, :password, 0)";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($data['name']));
        $email = htmlspecialchars(strip_tags($data['email']));
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        return $stmt->execute();
    }


    // 1. Simpan Token Aktivasi setelah Register
    public function setActivationToken($email, $token) {
        $query = "UPDATE " . $this->table . " SET activation_token = :token WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    // 2. Cari User berdasarkan Token Aktivasi
    public function findUserByActivationToken($token) {
        $query = "SELECT * FROM " . $this->table . " WHERE activation_token = :token LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Verifikasi User (Aktifkan Akun)
    public function activateUser($id) {
        $query = "UPDATE " . $this->table . " SET is_verified = 1, activation_token = NULL WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // 4. Simpan Token Reset Password (Expired 1 Jam)
    public function setResetToken($email, $token) {
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $query = "UPDATE " . $this->table . " SET reset_token = :token, reset_token_expires_at = :expiry WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', $expiry);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    // 5. Cari User berdasarkan Token Reset yang Valid
    public function findUserByResetToken($token) {
        $now = date('Y-m-d H:i:s');
        $query = "SELECT * FROM " . $this->table . " WHERE reset_token = :token AND reset_token_expires_at > :now LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':now', $now);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 6. Update Password Baru & Hapus Token Reset
    public function updatePasswordNew($id, $hashedPassword) {
        $query = "UPDATE " . $this->table . " SET password = :password, reset_token = NULL, reset_token_expires_at = NULL WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Update Profil Dasar
    public function updateProfile($id, $name, $email, $bio) {
        $query = "UPDATE " . $this->table . " SET name = :name, email = :email, bio = :bio WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Update Avatar
    public function updateAvatar($id, $filename) {
        $query = "UPDATE " . $this->table . " SET avatar = :avatar WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':avatar', $filename);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Update Password
    public function updatePassword($id, $newPasswordHash) {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $newPasswordHash);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function findUserByGoogleId($googleId) {
        $query = "SELECT * FROM " . $this->table . " WHERE google_id = :google_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':google_id', $googleId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Register User via Google (Otomatis Verified)
    public function registerViaGoogle($data) {
        $query = "INSERT INTO " . $this->table . " (name, email, google_id, avatar, is_verified, password) VALUES (:name, :email, :google_id, :avatar, 1, '')";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':google_id', $data['google_id']);
        $stmt->bindParam(':avatar', $data['avatar']);
        
        return $stmt->execute();
    }

    // Update Google ID ke User yang sudah ada (jika email sama)
    public function linkGoogleAccount($email, $googleId, $avatar) {
        $query = "UPDATE " . $this->table . " SET google_id = :google_id, avatar = IF(avatar IS NULL OR avatar = '', :avatar, avatar), is_verified = 1 WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':google_id', $googleId);
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }
}