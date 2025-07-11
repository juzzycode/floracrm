<?php namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
    protected $table = 'companies';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'address', 'phone', 'email', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'email' => 'required|valid_email|is_unique[companies.email]',
        'phone' => 'permit_empty|min_length[8]|max_length[20]'
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Company name is required',
            'min_length' => 'Company name must be at least 3 characters long'
        ],
        'email' => [
            'is_unique' => 'This email is already registered'
        ]
    ];
}