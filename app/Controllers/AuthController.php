<?php
// app/Controllers/AuthController.php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $userModel = new UserModel();
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            
            $user = $userModel->authenticateUser($username, $password);
            
            if ($user) {
                $session = session();
                $session->set([
                    'user_id' => $user['id'],
                    'company_id' => $user['company_id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'logged_in' => true
                ]);
                
                // Update last login
                $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
                
                return redirect()->to('/dashboard');
            } else {
                return redirect()->back()->with('error', 'Invalid username or password');
            }
        }
        
        return view('auth/login');
    }
    
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
