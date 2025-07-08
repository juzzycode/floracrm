<?php
// app/Controllers/InventoryController.php
namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\InventoryPricingModel;
use App\Models\VendorModel;
use CodeIgniter\Controller;

class InventoryController extends Controller
{
    public function index()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $inventoryModel = new InventoryModel();
        $companyId = $session->get('company_id');
        
        $data['inventory'] = $inventoryModel->getInventoryByCompany($companyId);
        
        return view('inventory/index', $data);
    }
    
    public function create()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if ($this->request->getMethod() === 'POST') {
            $inventoryModel = new InventoryModel();
            $companyId = $session->get('company_id');
            
            $data = [
                'company_id' => $companyId,
                'sku' => $this->request->getPost('sku'),
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'category' => $this->request->getPost('category'),
                'unit' => $this->request->getPost('unit')
            ];
            
            if ($inventoryModel->insert($data)) {
                return redirect()->to('/inventory')->with('success', 'Product created successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to create product');
            }
        }
        
        return view('inventory/create');
    }
    
    public function pricing($id)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $inventoryModel = new InventoryModel();
        $pricingModel = new InventoryPricingModel();
        $vendorModel = new VendorModel();
        
        $companyId = $session->get('company_id');
        
        $product = $inventoryModel->find($id);
        if (!$product || $product['company_id'] != $companyId) {
            return redirect()->to('/inventory')->with('error', 'Product not found');
        }
        
        $data = [
            'product' => $product,
            'pricing' => $pricingModel->getPricingByInventory($id),
            'vendors' => $vendorModel->getVendorsByCompany($companyId)
        ];
        
        return view('inventory/pricing', $data);
    }
    
    public function addPricing()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        if ($this->request->getMethod() === 'POST') {
            $pricingModel = new InventoryPricingModel();
            
            $data = [
                'vendor_id' => $this->request->getPost('vendor_id'),
                'inventory_id' => $this->request->getPost('inventory_id'),
                'base_price' => $this->request->getPost('base_price'),
                'quantity_break_1' => $this->request->getPost('quantity_break_1'),
                'price_break_1' => $this->request->getPost('price_break_1'),
                'quantity_break_2' => $this->request->getPost('quantity_break_2'),
                'price_break_2' => $this->request->getPost('price_break_2'),
                'quantity_break_3' => $this->request->getPost('quantity_break_3'),
                'price_break_3' => $this->request->getPost('price_break_3'),
                'lead_time_days' => $this->request->getPost('lead_time_days'),
                'minimum_quantity' => $this->request->getPost('minimum_quantity')
            ];
            
            if ($pricingModel->insert($data)) {
                return redirect()->to('/inventory/pricing/' . $this->request->getPost('inventory_id'))
                               ->with('success', 'Pricing added successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to add pricing');
            }
        }
    }
}
