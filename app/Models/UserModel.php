<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['company_id', 'first_name', 'last_name', 'email', 'password', 'role', 'last_login', 'status'];
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    protected $validationRules = [
    'company_id' => 'required|numeric',
    'first_name' => 'required|min_length[2]|max_length[50]',
    'last_name' => 'required|min_length[2]|max_length[50]',
    'email' => 'required|valid_email|is_unique[users.email]',
    'password' => 'required|min_length[8]',
    'role' => 'required|in_list[free,paid,admin]',
    'status' => 'required|in_list[active,inactive]'
    ];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
    public function getUsersByCompany($companyId)
    {
        return $this->where('company_id', $companyId)->findAll();
    }
}