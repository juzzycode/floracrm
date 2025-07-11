<?php namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $allowedFields = ['company_id', 'first_name', 'last_name', 'email', 'phone', 'address'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getCustomersByCompany($companyId)
    {
        return $this->where('company_id', $companyId)->findAll();
    }
}