<?php namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['company_id', 'customer_id', 'user_id', 'order_number', 'order_date', 
                              'delivery_type', 'delivery_address', 'delivery_date', 'status', 
                              'total_amount', 'notes'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function addOrderItem($data)
    {
        $db = \Config\Database::connect();
        return $db->table('order_items')->insert($data);
    }
}