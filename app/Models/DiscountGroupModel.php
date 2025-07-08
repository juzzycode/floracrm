<?php
namespace App\Models;

use CodeIgniter\Model;

class DiscountGroupModel extends Model
{
    protected $table = 'discount_groups';
    protected $primaryKey = 'id';
    protected $allowedFields = ['company_id', 'name', 'description', 'discount_percentage', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function getDiscountsByCompany($companyId)
    {
        return $this->where('company_id', $companyId)->where('status', 'active')->findAll();
    }
    
    public function getVendorDiscounts($vendorId)
    {
        return $this->select('discount_groups.*')
                   ->join('vendor_discount_groups', 'vendor_discount_groups.discount_group_id = discount_groups.id')
                   ->where('vendor_discount_groups.vendor_id', $vendorId)
                   ->where('discount_groups.status', 'active')
                   ->findAll();
    }
}
