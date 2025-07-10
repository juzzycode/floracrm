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
        helper(['form', 'url']);
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
            
            // Set session data
            $sessionData = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ];
            
            session()->set($sessionData);
            
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
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone); // Remove everything except digits and +
        $this->request->setGlobal('post', array_merge($this->request->getPost(), ['phone' => $cleanPhone]));
        $rules = [
            'name' => 'required|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'business_name' => 'required|min_length[2]|max_length[100]',
            'phone' => 'permit_empty|regex_match[/^[\+]?[1-9][\d]{0,15}$/]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $userData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'business_name' => $this->request->getPost('business_name'),
            'phone' => $cleanPhone,
            'role' => 'user',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->userModel->insert($userData)) {
            return redirect()->to('/login')->with('success', 'Registration successful! Please login.');
        } else {
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
    }
    
    public function logout()
    {
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
    
    public function resetPassword()
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
}
