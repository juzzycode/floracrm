<?php
// app/Controllers/SearchController.php
namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\InventoryPricingModel;
use App\Models\DiscountGroupModel;
use CodeIgniter\Controller;

class SearchController extends Controller
{
    public function index()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $inventoryModel = new InventoryModel();
        $pricingModel = new InventoryPricingModel();
        $companyId = $session->get('company_id');
        
        $searchTerm = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $quantity = $this->request->getGet('quantity') ?: 1;
        
        $data = [
            'searchTerm' => $searchTerm,
            'category' => $category,
            'quantity' => $quantity,
            'categories' => $inventoryModel->getCategories($companyId),
            'results' => []
        ];
        
        if ($searchTerm || $category) {
            $results = $pricingModel->searchPricing($companyId, $searchTerm, $category);
            
            // Calculate pricing for requested quantity
            foreach ($results as &$result) {
                $result['calculated_price'] = $pricingModel->calculatePrice($result, $quantity);
                $result['total_price'] = $result['calculated_price'] * $quantity;
            }
            
            $data['results'] = $results;
        }
        
        return view('search/index', $data);
    }
    
    public function compare()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $inventoryId = $this->request->getGet('product_id');
        $quantity = $this->request->getGet('quantity') ?: 1;
        
        if (!$inventoryId) {
            return redirect()->to('/search')->with('error', 'Product not specified');
        }
        
        $inventoryModel = new InventoryModel();
        $pricingModel = new InventoryPricingModel();
        $discountModel = new DiscountGroupModel();
        
        $companyId = $session->get('company_id');
        
        $product = $inventoryModel->find($inventoryId);
        if (!$product || $product['company_id'] != $companyId) {
            return redirect()->to('/search')->with('error', 'Product not found');
        }
        
        $pricing = $pricingModel->getPricingByInventory($inventoryId);
        
        // Calculate pricing for each vendor
        foreach ($pricing as &$p) {
            $p['calculated_price'] = $pricingModel->calculatePrice($p, $quantity);
            $p['total_price'] = $p['calculated_price'] * $quantity;
            $p['vendor_discounts'] = $discountModel->getVendorDiscounts($p['vendor_id']);
        }
        
        // Sort by total price
        usort($pricing, function($a, $b) {
            return $a['total_price'] <=> $b['total_price'];
        });
        
        $data = [
            'product' => $product,
            'quantity' => $quantity,
            'pricing' => $pricing,
            'discountGroups' => $discountModel->getDiscountsByCompany($companyId)
        ];
        
        return view('search/compare', $data);
    }
}
