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

    protected $validationRules = [
        'company_id' => 'required|numeric',
        'first_name' => 'required|min_length[2]|max_length[50]',
        'last_name' => 'required|min_length[2]|max_length[50]',
        'email' => 'permit_empty|valid_email',
        'phone' => 'required|min_length[6]|max_length[20]',
        'address' => 'permit_empty|max_length[255]'
    ];
    
    public function getCustomersByCompany($companyId)
    {
        return $this->where('company_id', $companyId)->findAll();
    }
}