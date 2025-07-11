<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CompanyModel;

class Auth extends BaseController
{
    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'company_name' => 'required|min_length[3]|max_length[255]',
                'first_name' => 'required|min_length[3]|max_length[50]',
                'last_name' => 'required|min_length[3]|max_length[50]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[8]',
                'password_confirm' => 'matches[password]'
            ];

            if (!$this->validate($rules)) {
                return view('auth/register', ['validation' => $this->validator]);
            }

            // Create company
            $companyModel = new CompanyModel();
            $companyData = [
                'name' => $this->request->getPost('company_name'),
                'address' => $this->request->getPost('company_address'),
                'phone' => $this->request->getPost('company_phone'),
                'email' => $this->request->getPost('email')
            ];
            $companyId = $companyModel->insert($companyData);

            // Create user
            $userModel = new UserModel();
            $userData = [
                'company_id' => $companyId,
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'role' => 'admin' // First user is admin
            ];
            $userModel->save($userData);

            return redirect()->to('/login')->with('message', 'Registration successful. Please login.');
        }

        return view('auth/register');
    }

    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                return view('auth/login', ['validation' => $this->validator]);
            }

            $userModel = new UserModel();
            $user = $userModel->where('email', $this->request->getPost('email'))->first();

            if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
                return redirect()->back()->withInput()->with('error', 'Invalid email or password');
            }

            // Set user session
            $sessionData = [
                'user_id' => $user['id'],
                'company_id' => $user['company_id'],
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ];
            session()->set($sessionData);

            // Update last login
            $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

            return redirect()->to('/orders');
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}