<?php namespace App\Controllers;

class Admin extends BaseController
{
    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        return view('admin/index');
    }

    public function users()
    {
        $userModel = new \App\Models\UserModel();
        $data['users'] = $userModel->where('company_id', session()->get('company_id'))->findAll();
        
        return view('admin/users', $data);
    }

    // Add other admin methods (addUser, editUser, deleteUser, discountGroups, etc.)
}