<?php namespace App\Controllers;

use App\Models\OrderModel;

class Reports extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $data['orders'] = $orderModel->where('company_id', session()->get('company_id'))
                                    ->orderBy('order_date', 'DESC')
                                    ->findAll(10);
        
        return view('reports/index', $data);
    }
}