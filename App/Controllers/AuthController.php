<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

/**
 * Authentication Controller
 */
class AuthController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        // Load language files
        $this->loadLanguage('auth');
    }
    
    /**
     * Show login page
     */
    public function login()
    {
        // If user is already logged in, redirect to dashboard
        if ($this->currentUser) {
            $this->redirect('dashboard');
        }
        
        // Handle login form submission
        if ($this->isPost()) {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Validate input
            $errors = [];
            
            if (empty($username)) {
                $errors['username'] = $this->lang('username_required');
            }
            
            if (empty($password)) {
                $errors['password'] = $this->lang('password_required');
            }
            
            // If no errors, try to login
            if (empty($errors)) {
                $userModel = new User();
                $user = $userModel->login($username, $password);
                
                if ($user) {
                    // Set session
                    $_SESSION['user_id'] = $user['id'];
                    
                    // Update online status
                    $userModel->updateOnlineStatus($user['id'], true);
                    
                    // Check if user has character
                    if ($userModel->hasCharacter($user['id'])) {
                        $this->redirect('game');
                    } else {
                        $this->redirect('character/create');
                    }
                } else {
                    $errors['login'] = $this->lang('login_error');
                }
            }
            
            // If there are errors, show login form with errors
            $this->view('auth/login', [
                'errors' => $errors,
                'username' => $username
            ]);
        } else {
            // Show login form
            $this->view('auth/login');
        }
    }
    
    /**
     * Show registration page
     */
    public function register()
    {
        // If user is already logged in, redirect to dashboard
        if ($this->currentUser) {
            $this->redirect('dashboard');
        }
        
        // Handle registration form submission
        if ($this->isPost()) {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            
            // Validate input
            $errors = [];
            
            if (empty($username)) {
                $errors['username'] = $this->lang('username_required');
            } elseif (strlen($username) < 3) {
                $errors['username'] = $this->lang('username_min');
            } elseif (strlen($username) > 20) {
                $errors['username'] = $this->lang('username_max');
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $errors['username'] = $this->lang('username_format');
            }
            
            if (empty($email)) {
                $errors['email'] = $this->lang('email_required');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = $this->lang('email_invalid');
            }
            
            if (empty($password)) {
                $errors['password'] = $this->lang('password_required');
            } elseif (strlen($password) < 6) {
                $errors['password'] = $this->lang('password_min');
            }
            
            if ($password !== $passwordConfirm) {
                $errors['password_confirm'] = $this->lang('password_match');
            }
            
            // If no errors, try to register user
            if (empty($errors)) {
                $userModel = new User();
                $userId = $userModel->register($username, $email, $password);
                
                if ($userId) {
                    // Set session
                    $_SESSION['user_id'] = $userId;
                    
                    // Redirect to character creation
                    $this->redirect('character/create');
                } else {
                    $errors['register'] = $this->lang('register_error');
                }
            }
            
            // If there are errors, show registration form with errors
            $this->view('auth/register', [
                'errors' => $errors,
                'username' => $username,
                'email' => $email
            ]);
        } else {
            // Show registration form
            $this->view('auth/register');
        }
    }
    
    /**
     * Logout user
     */
    public function logout()
    {
        // If user is logged in, update online status
        if ($this->currentUser) {
            $userModel = new User();
            $userModel->updateOnlineStatus($this->currentUser['id'], false);
        }
        
        // Destroy session
        $_SESSION = [];
        session_destroy();
        
        // Redirect to login page
        $this->redirect('auth/login');
    }
} 