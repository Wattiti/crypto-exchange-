<?php
class Auth {
    private $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }
    
    public function register($email, $password) {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address");
        }
        
        // Check if email exists
        $this->db->query("SELECT id FROM users WHERE email = ?")
                ->bind(1, $email);
                
        if ($this->db->single()) {
            throw new Exception("Email already registered");
        }
        
        // Validate password strength
        if (strlen($password) < 8 || 
            !preg_match('/[0-9]/', $password) || 
            !preg_match('/[^A-Za-z0-9]/', $password)) {
            throw new Exception("Password must be at least 8 characters with a number and special character");
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $verificationToken = bin2hex(random_bytes(32));
        
        // Create user
        $this->db->query("
            INSERT INTO users (email, password, verification_token, created_at)
            VALUES (?, ?, ?, NOW())
        ")->bind(1, $email)
          ->bind(2, $hashedPassword)
          ->bind(3, $verificationToken)
          ->execute();
        
        return $this->db->lastInsertId();
    }
    
    public function login($email, $password) {
        // Get user by email
        $this->db->query("
            SELECT id, email, password, 2fa_enabled, status 
            FROM users 
            WHERE email = ?
        ")->bind(1, $email);
        
        $user = $this->db->single();
        
        if (!$user) {
            throw new Exception("Invalid email or password");
        }
        
        // Check account status
        if ($user['status'] === 'suspended') {
            throw new Exception("Account suspended. Please contact support.");
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            throw new Exception("Invalid email or password");
        }
        
        // Check if email is verified
        if ($user['status'] === 'pending') {
            throw new Exception("Please verify your email address first");
        }
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['logged_in'] = true;
        
        // Update last login
        $this->db->query("UPDATE users SET last_login = NOW() WHERE id = ?")
                ->bind(1, $user['id'])
                ->execute();
        
        return true;
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    public function logout() {
        $_SESSION = array();
        session_destroy();
    }
    
    public function getUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        $this->db->query("SELECT id, email, created_at FROM users WHERE id = ?")
                ->bind(1, $_SESSION['user_id']);
        return $this->db->single();
    }
}
?>
