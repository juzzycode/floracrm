<?php namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table = 'vendors';
    protected $primaryKey = 'id';
    protected $allowedFields = ['company_id', 'name', 'contact_person', 'email', 'phone', 
                              'address', 'lead_time_days', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}