<?php namespace App\Controllers;

use App\Models\VendorModel;

class Vendors extends BaseController
{
    public function index()
    {
        $model = new VendorModel();
        $data['vendors'] = $model->where('company_id', session()->get('company_id'))->findAll();
        
        return view('vendors/list', $data);
    }

    public function add()
    {
        if ($this->request->getMethod() === 'post') {
            $model = new VendorModel();
            $model->save([
                'company_id' => session()->get('company_id'),
                'name' => $this->request->getPost('name'),
                'contact_person' => $this->request->getPost('contact_person'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'address' => $this->request->getPost('address'),
                'lead_time_days' => $this->request->getPost('lead_time_days')
            ]);
            
            return redirect()->to('/vendors')->with('message', 'Vendor added successfully');
        }
        
        return view('vendors/add');
    }
}