<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\DiscountGroupModel;

class Admin extends BaseController
{
    public function index()
    {
        echo "Current Role: " . session()->get('role');
        echo "<br>Session Data: ";
        print_r(session()->get());
        exit;
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        return view('admin/index');
    }

    public function users()
    {
        $model = new UserModel();
        $data['users'] = $model->where('company_id', session()->get('company_id'))->findAll();
        
        return view('admin/users', $data);
    }
    public function addUser()
    {
        return view('admin/add_user');
    }

    public function saveUser()
    {
        $userModel = new \App\Models\UserModel();
        
        // Validate passwords match
        if ($this->request->getPost('password') !== $this->request->getPost('password_confirm')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Passwords do not match');
        }
        
        $data = [
            'company_id' => session()->get('company_id'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status')
        ];
        
        if (!$userModel->save($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $userModel->errors());
        }
        
        return redirect()->to(route_to('admin.users'))
            ->with('message', 'User created successfully');
    }
    public function discountGroups()
    {
        $model = new DiscountGroupModel();
        $data['discountGroups'] = $model->where('company_id', session()->get('company_id'))->findAll();
        
        return view('admin/discount_groups', $data);
    }

    public function addDiscountGroup()
    {
        return view('admin/add_discount_group');
    }

    public function saveDiscountGroup()
    {
        $model = new DiscountGroupModel();
        
        if (!$model->save([
            'company_id' => session()->get('company_id'),
            'name' => $this->request->getPost('name'),
            'discount_percent' => $this->request->getPost('discount_percent')
        ])) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
        
        return redirect()->to('/admin/discount-groups')->with('message', 'Discount group added successfully');
    }
    public function editUser($id)
    {
        $userModel = new \App\Models\UserModel();
        $data['user'] = $userModel->find($id);
        
        // Verify the user belongs to the same company
        if ($data['user']['company_id'] != session()->get('company_id')) {
            return redirect()->to('/admin/users')->with('error', 'You cannot edit this user');
        }
        
        return view('admin/edit_user', $data);
    }

    public function updateUser($id)
    {
        log_message('debug', 'Update User Data: '.print_r($this->request->getPost(), true));
        // Validate CSRF token first
        //if (!csrf_hash_is_valid($this->request->getPost('csrf_test_name'), $this->request->getPost('csrf_token'))) {
        //    return redirect()->back()->with('error', 'Invalid CSRF token');
        //}

        $userModel = new \App\Models\UserModel();
        
        // Verify user belongs to current company
        $user = $userModel->find($id);
        if (!$user || $user['company_id'] != session()->get('company_id')) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status')
        ];

        // Only update password if provided
        if ($password = $this->request->getPost('password')) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (!$userModel->update($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $userModel->errors());
        }

        return redirect()->to('/admin/users')
            ->with('message', 'User updated successfully');
    }
    public function editDiscountGroup($id)
    {
        $model = new DiscountGroupModel();
        $data['group'] = $model->find($id);
        
        return view('admin/edit_discount_group', $data);
    }

    public function updateDiscountGroup($id)
    {
        $model = new DiscountGroupModel();
        
        if (!$model->update($id, [
            'name' => $this->request->getPost('name'),
            'discount_percent' => $this->request->getPost('discount_percent')
        ])) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
        
        return redirect()->to('/admin/discount-groups')->with('message', 'Discount group updated successfully');
    }
    // Add other methods (addUser, editUser, etc.) as needed
}