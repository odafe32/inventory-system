<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get table columns
        $orderItemColumns = Schema::getColumnListing('order_items');
        
        // Get products
        $products = Product::all();
        
        // Get user
        $user = User::first();
        
        // Skip if no products or user found
        if ($products->isEmpty() || !$user) {
            return;
        }
        
        // Create 10 sample orders
        for ($i = 0; $i < 10; $i++) {
            // Generate order data
            $orderStatus = $this->getRandomStatus();
            $paymentStatus = $this->getRandomPaymentStatus();
            
            // Calculate values
            $subtotal = rand(5000, 50000) / 100; // Random amount between $50 and $500
            $tax = $subtotal * 0.075; // 7.5% tax
            $total = $subtotal + $tax;
            
            // Create the order
            $order = Order::create([
                'order_id' => Order::generateUniqueOrderId(),
                'customer_name' => $this->getRandomCustomerName(),
                'email' => $this->getRandomEmail(),
                'phone' => $this->getRandomPhone(),
                'address' => $this->getRandomAddress(),
                'priority' => rand(0, 1) ? 'Normal' : 'High',
                'payment_status' => $paymentStatus,
                'order_status' => $orderStatus,
                'delivery_number' => ($orderStatus == 'Shipping' || $orderStatus == 'Completed') ? 'TRK' . rand(1000000, 9999999) : null,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'created_at' => now()->subDays(rand(1, 30)), // Order created between 1-30 days ago
            ]);
            
            // Create 1-5 order items for this order
            $itemCount = rand(1, 5);
            $orderSubtotal = 0;
            
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->price;
                $discount = $product->discount ?? 0;
                $discountedPrice = $price - ($price * ($discount / 100));
                $itemTotal = $discountedPrice * $quantity;
                
                // Build item data based on existing columns
                $orderItemData = [];
                
                // Required fields - order_id and product_id should always exist
                $orderItemData['order_id'] = $order->id;
                if (in_array('product_id', $orderItemColumns)) {
                    $orderItemData['product_id'] = $product->id;
                }
                
                // Optional fields - add only if they exist in the table
                if (in_array('product_name', $orderItemColumns)) {
                    $orderItemData['product_name'] = $product->name;
                }
                
                if (in_array('quantity', $orderItemColumns)) {
                    $orderItemData['quantity'] = $quantity;
                }
                
                if (in_array('unit_price', $orderItemColumns)) {
                    $orderItemData['unit_price'] = $price;
                }
                
                if (in_array('price', $orderItemColumns)) {
                    $orderItemData['price'] = $price;
                }
                
                if (in_array('discount', $orderItemColumns)) {
                    $orderItemData['discount'] = $discount;
                }
                
                if (in_array('total', $orderItemColumns)) {
                    $orderItemData['total'] = $itemTotal;
                }
                
                // Create the order item only if we have data to insert
                if (count($orderItemData) > 1) { // More than just order_id
                    OrderItem::create($orderItemData);
                    $orderSubtotal += $itemTotal;
                }
            }
            
            // Update order with actual calculations from order items
            $orderTax = $orderSubtotal * 0.075;
            $orderTotal = $orderSubtotal + $orderTax;
            
            $order->update([
                'subtotal' => $orderSubtotal,
                'tax' => $orderTax,
                'total' => $orderTotal,
            ]);
        }
    }
    
    private function getRandomStatus()
    {
        $statuses = ['Draft', 'Packaging', 'Shipping', 'Completed', 'Canceled'];
        return $statuses[array_rand($statuses)];
    }
    
    private function getRandomPaymentStatus()
    {
        $statuses = ['Paid', 'Unpaid', 'Refund'];
        return $statuses[array_rand($statuses)];
    }
    
    private function getRandomCustomerName()
    {
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Jennifer', 'William', 'Jessica'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Garcia', 'Wilson', 'Taylor'];
        
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }
    
    private function getRandomEmail()
    {
        $domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'icloud.com'];
        $username = strtolower(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, rand(5, 10)));
        
        return $username . '@' . $domains[array_rand($domains)];
    }
    
    private function getRandomPhone()
    {
        return '(' . rand(100, 999) . ') ' . rand(100, 999) . '-' . rand(1000, 9999);
    }
    
    private function getRandomAddress()
    {
        $streetNames = ['Main St', 'Oak Ave', 'Maple Rd', 'Washington Blvd', 'Park Ln', 'Cedar Dr', 'Lake View Rd'];
        $cities = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio'];
        $states = ['NY', 'CA', 'IL', 'TX', 'AZ', 'PA', 'TX'];
        
        $streetNumber = rand(100, 9999);
        $streetName = $streetNames[array_rand($streetNames)];
        $city = $cities[array_rand($cities)];
        $state = $states[array_rand($states)];
        $zip = rand(10000, 99999);
        
        return "$streetNumber $streetName, $city, $state $zip";
    }
}