<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\DiscountGroupModel;

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
        $model = new UserModel();
        $data['users'] = $model->where('company_id', session()->get('company_id'))->findAll();
        
        return view('admin/users', $data);
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
        $userModel = new \App\Models\UserModel();
        
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status')
        ];
        
        // Only update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }
        
        if (!$userModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }
        
        return redirect()->to('/admin/users')->with('message', 'User updated successfully');
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