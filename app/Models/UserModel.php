<?php
// app/Models/UserModel.php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'name',
        'email',
        'password',
        'business_name',
        'business_type',
        'business_address',
        'phone',
        'role',
        'status',
        'email_verified_at',
        'reset_token',
        'reset_expires',
        'last_login',
        'preferences',
        'avatar',
        'newsletter_subscribed'
    ];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'business_name' => 'required|min_length[2]|max_length[150]',
        'business_type' => 'required|in_list[retail_florist,wholesale_florist,event_designer,wedding_specialist,funeral_director,other]',
        'phone' => 'permit_empty|regex_match[/^[\+]?[1-9][\d]{0,15}$/]',
        'role' => 'permit_empty|in_list[admin,manager,user]',
        'status' => 'permit_empty|in_list[active,inactive,pending,suspended]'
    ];
    
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email address is already registered.'
        ],
        'password' => [
            'min_length' => 'Password must be at least 8 characters long.'
        ],
        'business_type' => [
            'in_list' => 'Please select a valid business type.'
        ]
    ];
    
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    /**
     * Hash password before saving to database
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
    
    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
    
    /**
     * Find active users
     */
    public function findActive()
    {
        return $this->where('status', 'active')->findAll();
    }
    
    /**
     * Find users by role
     */
    public function findByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }
    
    /**
     * Find users by business type
     */
    public function findByBusinessType($businessType)
    {
        return $this->where('business_type', $businessType)->findAll();
    }
    
    /**
     * Verify user password
     */
    public function verifyPassword($userId, $password)
    {
        $user = $this->find($userId);
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false;
    }
    
    /**
     * Update last login timestamp
     */
    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }
    
    /**
     * Set password reset token
     */
    public function setResetToken($userId, $token, $expiry)
    {
        return $this->update($userId, [
            'reset_token' => $token,
            'reset_expires' => $expiry
        ]);
    }
    
    /**
     * Clear password reset token
     */
    public function clearResetToken($userId)
    {
        return $this->update($userId, [
            'reset_token' => null,
            'reset_expires' => null
        ]);
    }
    
    /**
     * Verify email address
     */
    public function verifyEmail($userId)
    {
        return $this->update($userId, [
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Update user preferences
     */
    public function updatePreferences($userId, $preferences)
    {
        return $this->update($userId, [
            'preferences' => json_encode($preferences)
        ]);
    }
    
    /**
     * Get user preferences
     */
    public function getPreferences($userId)
    {
        $user = $this->find($userId);
        if ($user && $user['preferences']) {
            return json_decode($user['preferences'], true);
        }
        return [];
    }
    
    /**
     * Get user statistics
     */
    public function getUserStats()
    {
        $total = $this->countAllResults();
        $active = $this->where('status', 'active')->countAllResults();
        $pending = $this->where('status', 'pending')->countAllResults();
        $thisMonth = $this->where('created_at >=', date('Y-m-01'))->countAllResults();
        
        return [
            'total' => $total,
            'active' => $active,
            'pending' => $pending,
            'inactive' => $total - $active - $pending,
            'this_month' => $thisMonth
        ];
    }
    
    /**
     * Get business type statistics
     */
    public function getBusinessTypeStats()
    {
        $businessTypes = [
            'retail_florist' => 'Retail Florist',
            'wholesale_florist' => 'Wholesale Florist',
            'event_designer' => 'Event Designer',
            'wedding_specialist' => 'Wedding Specialist',
            'funeral_director' => 'Funeral Director',
            'other' => 'Other'
        ];
        
        $stats = [];
        foreach ($businessTypes as $type => $label) {
            $count = $this->where('business_type', $type)->countAllResults();
            $stats[$type] = [
                'label' => $label,
                'count' => $count
            ];
        }
        
        return $stats;
    }
    
    /**
     * Search users
     */
    public function searchUsers($query, $limit = 10, $offset = 0)
    {
        return $this->like('name', $query)
                   ->orLike('email', $query)
                   ->orLike('business_name', $query)
                   ->limit($limit, $offset)
                   ->findAll();
    }
    
    /**
     * Get recent users
     */
    public function getRecentUsers($limit = 10)
    {
        return $this->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}
