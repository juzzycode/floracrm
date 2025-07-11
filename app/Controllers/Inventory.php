<?php namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\VendorModel;
use App\Models\DiscountGroupModel;

class Inventory extends BaseController
{
    public function index()
    {
        $model = new InventoryModel();
        $data['inventory'] = $model->where('company_id', session()->get('company_id'))->findAll();
        
        return view('inventory/list', $data);
    }

    public function add()
    {
        $vendorModel = new VendorModel();
        $discountModel = new DiscountGroupModel();
        
        $data = [
            'vendors' => $vendorModel->where('company_id', session()->get('company_id'))->findAll(),
            'discounts' => $discountModel->where('company_id', session()->get('company_id'))->findAll()
        ];
        
        if ($this->request->getMethod() === 'post') {
            $model = new InventoryModel();
            $model->save([
                'company_id' => session()->get('company_id'),
                'vendor_id' => $this->request->getPost('vendor_id'),
                'sku' => $this->request->getPost('sku'),
                'description' => $this->request->getPost('description'),
                'options' => $this->request->getPost('options'),
                'price' => $this->request->getPost('price'),
                'discount_group_id' => $this->request->getPost('discount_group_id'),
                'msrp' => $this->request->getPost('msrp'),
                'quantity_on_hand' => $this->request->getPost('quantity_on_hand'),
                'backordered' => $this->request->getPost('backordered')
            ]);
            
            return redirect()->to('/inventory')->with('message', 'Inventory item added successfully');
        }
        
        return view('inventory/add', $data);
    }
}