<?php
// app/Models/VendorModel.php
namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table = 'vendors';
    protected $primaryKey = 'id';
    protected $allowedFields = ['company_id', 'name', 'contact_person', 'email', 'phone', 'address', 'city', 'state', 'zip_code', 'country', 'payment_terms', 'delivery_days', 'minimum_order', 'status', 'notes'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function getVendorsByCompany($companyId)
    {
        return $this->where('company_id', $companyId)->where('status', 'active')->findAll();
    }
    
    public function getVendorWithDiscounts($vendorId)
    {
        return $this->select('vendors.*, GROUP_CONCAT(discount_groups.name) as discount_groups')
                   ->join('vendor_discount_groups', 'vendor_discount_groups.vendor_id = vendors.id', 'left')
                   ->join('discount_groups', 'discount_groups.id = vendor_discount_groups.discount_group_id', 'left')
                   ->where('vendors.id', $vendorId)
                   ->groupBy('vendors.id')
                   ->first();
    }
}
