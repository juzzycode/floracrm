<?php
// app/Controllers/Auth.php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url', 'text']);
    }
    
    public function login()
    {
        // Check if user is already logged in
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        $data = [
            'title' => 'Login - Florist CRM',
            'validation' => \Config\Services::validation()
        ];
        
        return view('auth/login', $data);
    }
    
    public function authenticate()
    {
        // Rate limiting
        $throttler = \Config\Services::throttler();
        if ($throttler->check('login-' . $this->request->getIPAddress(), 5, MINUTE) === false) {
            return redirect()->back()->with('error', 'Too many login attempts. Try again later.');
        }

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $user = $this->userModel->where('email', $email)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            // Check if user is active
            if ($user['status'] !== 'active') {
                return redirect()->back()->with('error', 'Your account is not active. Please contact administrator.');
            }
            
            // Set session data with enhanced security
            $sessionData = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name'],
                'role' => $user['role'],
                'business_name' => $user['business_name'],
                'isLoggedIn' => true,
                'session_start' => time(),
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()
            ];
            
            session()->regenerate();
            session()->set($sessionData);
            
            // Remember me functionality
            if ($this->request->getPost('remember')) {
                $rememberToken = bin2hex(random_bytes(32));
                $this->userModel->update($user['id'], ['remember_token' => $rememberToken]);
                
                $response = service('response');
                $response->setCookie('remember_token', $rememberToken, 30 * 86400);
            }
            
            // Update last login
            $this->userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            
            // Redirect to intended page or dashboard
            $redirectTo = session()->get('redirect_to') ?? '/dashboard';
            session()->remove('redirect_to');
            
            return redirect()->to($redirectTo)->with('success', 'Welcome back, ' . $user['name'] . '!');
        } else {
            return redirect()->back()->with('error', 'Invalid email or password.');
        }
    }
    
    public function register()
    {
        $data = [
            'title' => 'Register - Florist CRM',
            'validation' => \Config\Services::validation()
        ];
        
        return view('auth/register', $data);
    }
    
    public function create()
    {
        $phone = $this->request->getPost('phone');
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'business_name' => 'required|min_length[2]|max_length[100]',
            'business_type' => 'required|in_list[retail_florist,wholesale_florist,event_designer,wedding_specialist,funeral_director,other]',
            'phone' => 'permit_empty|regex_match[/^\+?[0-9]{10,15}$/]',
            'terms' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $verificationCode = bin2hex(random_bytes(16));
        
        $userData = [
            'name' => esc($this->request->getPost('name')),
            'email' => esc($this->request->getPost('email')),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'business_name' => esc($this->request->getPost('business_name')),
            'business_type' => esc($this->request->getPost('business_type')),
            'business_address' => esc($this->request->getPost('business_address')),
            'phone' => $cleanPhone,
            'role' => 'user',
            'status' => 'pending', // Changed to pending for email verification
            'verification_code' => $verificationCode,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->userModel->insert($userData)) {
            // Send verification email
            // $this->sendVerificationEmail($userData['email'], $verificationCode);
            
            return redirect()->to('/login')->with('success', 'Registration successful! Please check your email to verify your account.');
        } else {
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
    }
    
    public function verify($code)
    {
        $user = $this->userModel->where('verification_code', $code)->first();
        
        if (!$user) {
            return redirect()->to('/login')->with('error', 'Invalid verification code.');
        }
        
        $this->userModel->update($user['id'], [
            'verification_code' => null,
            'status' => 'active'
        ]);
        
        return redirect()->to('/login')->with('success', 'Account verified successfully! You can now login.');
    }
    
    public function logout()
    {
        // Delete remember token if exists
        if (isset($_COOKIE['remember_token'])) {
            $user = $this->userModel->where('remember_token', $_COOKIE['remember_token'])->first();
            if ($user) {
                $this->userModel->update($user['id'], ['remember_token' => null]);
            }
            service('response')->deleteCookie('remember_token');
        }
        
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully.');
    }
    
    public function forgotPassword()
    {
        $data = [
            'title' => 'Forgot Password - Florist CRM'
        ];
        
        return view('auth/forgot_password', $data);
    }
    
    public function sendResetLink()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $email = $this->request->getPost('email');
        $user = $this->userModel->where('email', $email)->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'Email address not found.');
        }
        
        // Generate reset token
        $resetToken = bin2hex(random_bytes(32));
        $resetExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $this->userModel->update($user['id'], [
            'reset_token' => $resetToken,
            'reset_expires' => $resetExpiry
        ]);
        
        // Send email (implement your email service)
        // $this->sendResetEmail($user['email'], $resetToken);
        
        return redirect()->to('/login')->with('success', 'Password reset link has been sent to your email.');
    }
    
    public function showResetForm($token)
    {
        $user = $this->userModel->where('reset_token', $token)
                               ->where('reset_expires >', date('Y-m-d H:i:s'))
                               ->first();

        if (!$user) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid or expired reset token.');
        }

        return view('auth/reset_password', [
            'title' => 'Reset Password',
            'token' => $token
        ]);
    }

    public function processReset()
    {
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $this->userModel->where('reset_token', $this->request->getPost('token'))
                               ->where('reset_expires >', date('Y-m-d H:i:s'))
                               ->first();

        if (!$user) {
            return redirect()->to('/forgot-password')->with('error', 'Invalid or expired reset token.');
        }

        $this->userModel->update($user['id'], [
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_expires' => null
        ]);

        return redirect()->to('/login')->with('success', 'Password reset successfully. Please login.');
    }
    
    // Helper method to send verification email (implement according to your email service)
    protected function sendVerificationEmail($email, $code)
    {
        // Implementation depends on your email service
        // Example:
        // $emailService = \Config\Services::email();
        // $emailService->setTo($email);
        // $emailService->setSubject('Verify Your Account');
        // $emailService->setMessage(view('emails/verification', ['code' => $code]));
        // $emailService->send();
    }
    
    // Helper method to send password reset email
    protected function sendResetEmail($email, $token)
    {
        // Similar implementation to sendVerificationEmail
    }
}