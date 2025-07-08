<?php
// app/Models/InventoryPricingModel.php
namespace App\Models;

use CodeIgniter\Model;

class InventoryPricingModel extends Model
{
    protected $table = 'inventory_pricing';
    protected $primaryKey = 'id';
    protected $allowedFields = ['vendor_id', 'inventory_id', 'base_price', 'quantity_break_1', 'price_break_1', 'quantity_break_2', 'price_break_2', 'quantity_break_3', 'price_break_3', 'lead_time_days', 'minimum_quantity', 'status', 'effective_date', 'expiry_date'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function getPricingByInventory($inventoryId)
    {
        return $this->select('inventory_pricing.*, vendors.name as vendor_name, vendors.delivery_days, vendors.minimum_order as vendor_minimum_order')
                   ->join('vendors', 'vendors.id = inventory_pricing.vendor_id')
                   ->where('inventory_pricing.inventory_id', $inventoryId)
                   ->where('inventory_pricing.status', 'active')
                   ->where('vendors.status', 'active')
                   ->findAll();
    }
    
    public function searchPricing($companyId, $searchTerm, $category = null)
    {
        $builder = $this->select('inventory_pricing.*, inventory.name as product_name, inventory.sku, inventory.category, inventory.unit, vendors.name as vendor_name, vendors.delivery_days, vendors.minimum_order as vendor_minimum_order')
                       ->join('inventory', 'inventory.id = inventory_pricing.inventory_id')
                       ->join('vendors', 'vendors.id = inventory_pricing.vendor_id')
                       ->where('inventory.company_id', $companyId)
                       ->where('inventory_pricing.status', 'active')
                       ->where('vendors.status', 'active');
        
        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('inventory.name', $searchTerm)
                   ->orLike('inventory.description', $searchTerm)
                   ->orLike('inventory.sku', $searchTerm)
                   ->orLike('vendors.name', $searchTerm)
                   ->groupEnd();
        }
        
        if (!empty($category)) {
            $builder->where('inventory.category', $category);
        }
        
        return $builder->orderBy('inventory.name', 'ASC')
                      ->orderBy('inventory_pricing.base_price', 'ASC')
                      ->findAll();
    }
    
    public function getInventoryWithPricing($companyId)
    {
        return $this->select('inventory.*, COUNT(inventory_pricing.id) as vendor_count, MIN(inventory_pricing.base_price) as min_price, MAX(inventory_pricing.base_price) as max_price')
                   ->join('inventory', 'inventory.id = inventory_pricing.inventory_id')
                   ->where('inventory.company_id', $companyId)
                   ->where('inventory_pricing.status', 'active')
                   ->groupBy('inventory.id')
                   ->findAll();
    }
    
    public function calculatePrice($pricingRow, $quantity)
    {
        $price = $pricingRow['base_price'];
        
        if ($quantity >= $pricingRow['quantity_break_3'] && $pricingRow['quantity_break_3'] > 0) {
            $price = $pricingRow['price_break_3'];
        } elseif ($quantity >= $pricingRow['quantity_break_2'] && $pricingRow['quantity_break_2'] > 0) {
            $price = $pricingRow['price_break_2'];
        } elseif ($quantity >= $pricingRow['quantity_break_1'] && $pricingRow['quantity_break_1'] > 0) {
            $price = $pricingRow['price_break_1'];
        }
        
        return $price;
    }
}
