<?php
// app/Models/VendorDiscountGroupModel.php
namespace App\Models;

use CodeIgniter\Model;

class VendorDiscountGroupModel extends Model
{
    protected $table = 'vendor_discount_groups';
    protected $primaryKey = 'id';
    protected $allowedFields = ['vendor_id', 'discount_group_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    
    public function assignDiscountToVendor($vendorId, $discountGroupId)
    {
        return $this->insert([
            'vendor_id' => $vendorId,
            'discount_group_id' => $discountGroupId
        ]);
    }
    
    public function removeDiscountFromVendor($vendorId, $discountGroupId)
    {
        return $this->where('vendor_id', $vendorId)
                   ->where('discount_group_id', $discountGroupId)
                   ->delete();
    }
}
