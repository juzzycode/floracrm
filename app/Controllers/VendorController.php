<?php
// app/Controllers/VendorController.php
namespace App\Controllers;

use App\Models\VendorModel;
use App\Models\DiscountGroupModel;
use App\Models\VendorDiscountGroupModel;
use CodeIgniter\Controller;

class VendorController extends Controller
{
    public function index()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $vendorModel = new VendorModel();
        $companyId = $session->get('company_id');
        
        $data['vendors'] = $vendorModel->getVendorsByCompany($companyId);
        
        return view('vendors/index', $data);
    }
    
    public function create()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if ($this->request->getMethod() === 'POST') {
            $vendorModel = new VendorModel();
            $companyId = $session->get('company_id');
            
            $data = [
                'company_id' => $companyId,
                'name' => $this->request->getPost('name'),
                'contact_person' => $this->request->getPost('contact_person'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'address' => $this->request->getPost('address'),
                'city' => $this->request->getPost('city'),
                'state' => $this->request->getPost('state'),
                'zip_code' => $this->request->getPost('zip_code'),
                'payment_terms' => $this->request->getPost('payment_terms'),
                'delivery_days' => $this->request->getPost('delivery_days'),
                'minimum_order' => $this->request->getPost('minimum_order')
            ];
            
            if ($vendorModel->insert($data)) {
                return redirect()->to('/vendors')->with('success', 'Vendor created successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to create vendor');
            }
        }
        
        return view('vendors/create');
    }
    
    public function edit($id)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $vendorModel = new VendorModel();
        $discountModel = new DiscountGroupModel();
        $vendorDiscountModel = new VendorDiscountGroupModel();
        
        $companyId = $session->get('company_id');
        
        $vendor = $vendorModel->find($id);
        if (!$vendor || $vendor['company_id'] != $companyId) {
            return redirect()->to('/vendors')->with('error', 'Vendor not found');
        }
        
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'name' => $this->request->getPost('name'),
                'contact_person' => $this->request->getPost('contact_person'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'address' => $this->request->getPost('address'),
                'city' => $this->request->getPost('city'),
                'state' => $this->request->getPost('state'),
                'zip_code' => $this->request->getPost('zip_code'),
                'payment_terms' => $this->request->getPost('payment_terms'),
                'delivery_days' => $this->request->getPost('delivery_days'),
                'minimum_order' => $this->request->getPost('minimum_order')
            ];
            
            if ($vendorModel->update($id, $data)) {
                return redirect()->to('/vendors')->with('success', 'Vendor updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update vendor');
            }
        }
        
        $data = [
            'vendor' => $vendor,
            'discountGroups' => $discountModel->getDiscountsByCompany($companyId),
            'vendorDiscounts' => $discountModel->getVendorDiscounts($id)
        ];
        
        return view('vendors/edit', $data);
    }
}
