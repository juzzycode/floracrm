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

    // Add other methods (addUser, editUser, etc.) as needed
}