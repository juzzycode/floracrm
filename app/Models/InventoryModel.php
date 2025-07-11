<?php namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $allowedFields = ['vendor_id', 'company_id', 'sku', 'description', 'options', 'price', 'discount_group_id', 'msrp', 'quantity_on_hand', 'backordered'];

    public function searchInventory($companyId, $searchTerm)
    {
        return $this->select('inventory.*, vendors.name as vendor_name, discount_groups.name as discount_group_name, discount_groups.discount_percent')
            ->join('vendors', 'vendors.id = inventory.vendor_id')
            ->join('discount_groups', 'discount_groups.id = inventory.discount_group_id', 'left')
            ->where('inventory.company_id', $companyId)
            ->groupStart()
                ->like('inventory.description', $searchTerm)
                ->orLike('inventory.sku', $searchTerm)
                ->orLike('vendors.name', $searchTerm)
            ->groupEnd()
            ->findAll();
    }

    public function getInventoryWithVendorPrices($companyId, $searchTerm)
    {
        return $this->select('inventory.description, inventory.options, inventory.sku, inventory.quantity_on_hand, 
                            vendors.name as vendor_name, vendors.lead_time_days,
                            inventory.price as base_price,
                            CASE 
                                WHEN discount_groups.discount_percent IS NOT NULL 
                                THEN inventory.price * (1 - (discount_groups.discount_percent/100))
                                ELSE inventory.price
                            END as final_price')
            ->join('vendors', 'vendors.id = inventory.vendor_id')
            ->join('discount_groups', 'discount_groups.id = inventory.discount_group_id', 'left')
            ->where('inventory.company_id', $companyId)
            ->groupStart()
                ->like('inventory.description', $searchTerm)
                ->orLike('inventory.sku', $searchTerm)
            ->groupEnd()
            ->orderBy('final_price', 'ASC')
            ->findAll();
    }
}