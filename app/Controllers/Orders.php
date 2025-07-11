<?php namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\CustomerModel;
use App\Models\InventoryModel;
use App\Models\VendorModel;

class Orders extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $orderModel = new OrderModel();
        $orders = $orderModel->where('company_id', session()->get('company_id'))
                            ->orderBy('order_date', 'DESC')
                            ->findAll();

        return view('orders/list', ['orders' => $orders]);
    }

    public function new()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $customerModel = new CustomerModel();
        $inventoryModel = new InventoryModel();
        
        $data = [
            'customers' => $customerModel->where('company_id', session()->get('company_id'))->findAll(),
            'inventory' => $inventoryModel->where('company_id', session()->get('company_id'))->findAll()
        ];

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'customer_id' => 'required|numeric',
                'delivery_type' => 'required|in_list[pickup,delivery]',
                'items' => 'required'
            ];

            if (!$this->validate($rules)) {
                return view('orders/new', $data);
            }

            // Create order
            $orderModel = new OrderModel();
            $orderData = [
                'company_id' => session()->get('company_id'),
                'customer_id' => $this->request->getPost('customer_id'),
                'user_id' => session()->get('user_id'),
                'order_number' => 'ORD-' . time(),
                'delivery_type' => $this->request->getPost('delivery_type'),
                'delivery_address' => $this->request->getPost('delivery_address'),
                'delivery_date' => $this->request->getPost('delivery_date'),
                'status' => 'pending',
                'total_amount' => 0, // Will be calculated
                'notes' => $this->request->getPost('notes')
            ];
            
            $orderId = $orderModel->insert($orderData);
            
            // Add order items
            $items = json_decode($this->request->getPost('items'), true);
            $totalAmount = 0;
            
            foreach ($items as $item) {
                $inventory = $inventoryModel->find($item['inventory_id']);
                $discountAmount = 0;
                
                // Calculate discount if applicable
                if ($inventory['discount_group_id']) {
                    $discountGroupModel = new \App\Models\DiscountGroupModel();
                    $discountGroup = $discountGroupModel->find($inventory['discount_group_id']);
                    $discountAmount = $inventory['price'] * ($discountGroup['discount_percent'] / 100) * $item['quantity'];
                }
                
                $itemTotal = ($inventory['price'] * $item['quantity']) - $discountAmount;
                $totalAmount += $itemTotal;
                
                $orderItemData = [
                    'order_id' => $orderId,
                    'inventory_id' => $item['inventory_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $inventory['price'],
                    'discount_amount' => $discountAmount,
                    'total_price' => $itemTotal
                ];
                
                $orderModel->addOrderItem($orderItemData);
            }
            
            // Update order total
            $orderModel->update($orderId, ['total_amount' => $totalAmount]);
            
            return redirect()->to('/orders')->with('message', 'Order created successfully');
        }

        return view('orders/new', $data);
    }

    public function searchInventory()
    {
        $searchTerm = $this->request->getGet('term');
        $inventoryModel = new InventoryModel();
        
        $results = $inventoryModel->getInventoryWithVendorPrices(
            session()->get('company_id'),
            $searchTerm
        );
        
        return $this->response->setJSON($results);
    }
}