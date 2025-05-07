<?php
namespace App\Models;

use App\Core\Model;

/**
 * User Model
 */
class User extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'users';
    
    /**
     * Register a new user
     * 
     * @param string $username Username
     * @param string $email Email
     * @param string $password Password
     * @return int|false User ID or false on failure
     */
    public function register($username, $email, $password)
    {
        // Check if username already exists
        if ($this->getByUsername($username)) {
            return false;
        }
        
        // Check if email already exists
        if ($this->getByEmail($email)) {
            return false;
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => HASH_COST]);
        
        // Create user
        return $this->create([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s'),
            'last_login' => date('Y-m-d H:i:s'),
            'status' => 'active'
        ]);
    }
    
    /**
     * Authenticate user
     * 
     * @param string $username Username or email
     * @param string $password Password
     * @return array|false User data or false on failure
     */
    public function login($username, $password)
    {
        // Check if input is email
        $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);
        
        // Get user by username or email
        $user = $isEmail ? $this->getByEmail($username) : $this->getByUsername($username);
        
        // Check if user exists
        if (!$user) {
            return false;
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            return false;
        }
        
        // Update last login
        $this->update($user['id'], [
            'last_login' => date('Y-m-d H:i:s')
        ]);
        
        return $user;
    }
    
    /**
     * Get user by username
     * 
     * @param string $username Username
     * @return array|false User data or false if not found
     */
    public function getByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = :username LIMIT 1");
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Get user by email
     * 
     * @param string $email Email
     * @return array|false User data or false if not found
     */
    public function getByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Check if user has a character
     * 
     * @param int $userId User ID
     * @return bool Whether user has a character
     */
    public function hasCharacter($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM characters WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Update user's online status
     * 
     * @param int $userId User ID
     * @param bool $isOnline Online status
     * @return bool Success status
     */
    public function updateOnlineStatus($userId, $isOnline)
    {
        return $this->update($userId, [
            'is_online' => $isOnline ? 1 : 0,
            'last_activity' => date('Y-m-d H:i:s')
        ]);
    }
} 