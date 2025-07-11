<?php namespace App\Models;

use CodeIgniter\Model;

class DiscountGroupModel extends Model
{
    protected $table = 'discount_groups';
    protected $primaryKey = 'id';
    protected $allowedFields = ['company_id', 'name', 'discount_percent'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}