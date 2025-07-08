<?php
// app/Controllers/DashboardController.php
namespace App\Controllers;

use App\Models\VendorModel;
use App\Models\InventoryModel;
use App\Models\InventoryPricingModel;
use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $companyId = $session->get('company_id');
        
        $vendorModel = new VendorModel();
        $inventoryModel = new InventoryModel();
        $pricingModel = new InventoryPricingModel();
        
        $data = [
            'totalVendors' => count($vendorModel->getVendorsByCompany($companyId)),
            'totalProducts' => count($inventoryModel->getInventoryByCompany($companyId)),
            'totalPricing' => count($pricingModel->where('status', 'active')->findAll()),
            'categories' => $inventoryModel->getCategories($companyId)
        ];
        
        return view('dashboard/index', $data);
    }
}
