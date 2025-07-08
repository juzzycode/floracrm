<?php
// app/Models/CompanyModel.php
namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
    protected $table = 'companies';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'phone', 'address', 'city', 'state', 'zip_code', 'country', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function getActiveCompanies()
    {
        return $this->where('status', 'active')->findAll();
    }
}
