<?php
// app/Models/InventoryModel.php
namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $allowedFields = ['company_id', 'sku', 'name', 'description', 'category', 'unit', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function getInventoryByCompany($companyId)
    {
        return $this->where('company_id', $companyId)->where('status', 'active')->findAll();
    }
    
    public function getCategories($companyId)
    {
        return $this->distinct()->select('category')
                   ->where('company_id', $companyId)
                   ->where('status', 'active')
                   ->findAll();
    }
    
    public function searchInventory($companyId, $searchTerm)
    {
        return $this->where('company_id', $companyId)
                   ->where('status', 'active')
                   ->groupStart()
                   ->like('name', $searchTerm)
                   ->orLike('description', $searchTerm)
                   ->orLike('sku', $searchTerm)
                   ->orLike('category', $searchTerm)
                   ->groupEnd()
                   ->findAll();
    }
}
