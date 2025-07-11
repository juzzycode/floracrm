<?php namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    public function index()
    {
        $model = new CustomerModel();
        $data['customers'] = $model->getCustomersByCompany(session()->get('company_id'));
        
        return view('customers/list', $data);
    }

    public function add()
    {
        if ($this->request->getMethod() === 'post') {
            $model = new CustomerModel();
            $model->save([
                'company_id' => session()->get('company_id'),
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'address' => $this->request->getPost('address')
            ]);
            
            return redirect()->to('/customers')->with('message', 'Customer added successfully');
        }
        
        return view('customers/add');
    }
}