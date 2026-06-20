<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        
        if ($products->isEmpty()) {
            $this->command->info('No products found. Please run ProductSeeder first.');
            return;
        }

        // ===== ORDER 1: COMPLETED =====
        $order1 = Order::create([
            'order_number' => 'ORD-20260618-001',
            'customer_name' => 'Customer 1',
            'customer_email' => 'customer1@example.com',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Test No. 123',
            'shipping_city' => 'Jakarta',
            'shipping_postal_code' => '12345',
            'subtotal' => 0,
            'shipping_cost' => 10000,
            'total' => 0,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'bank_transfer',
            'items' => json_encode([]),
            'notes' => 'Sample order 1',
        ]);

        $total1 = 0;
        foreach ($products->take(3) as $product) {
            $quantity = rand(2, 5);
            $price = $product->price;
            $total1 += $price * $quantity;
            
            OrderItem::create([
                'order_id' => $order1->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }
        $order1->update([
            'subtotal' => $total1,
            'total' => $total1 + 10000,
        ]);

        // ===== ORDER 2: PENDING =====
        $order2 = Order::create([
            'order_number' => 'ORD-20260618-002',
            'customer_name' => 'Customer 2',
            'customer_email' => 'customer2@example.com',
            'customer_phone' => '08123456788',
            'shipping_address' => 'Jl. Test No. 456',
            'shipping_city' => 'Bandung',
            'shipping_postal_code' => '12346',
            'subtotal' => 0,
            'shipping_cost' => 10000,
            'total' => 0,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'credit_card',
            'items' => json_encode([]),
            'notes' => 'Sample order 2',
        ]);

        $total2 = 0;
        foreach ($products->skip(2)->take(2) as $product) {
            $quantity = rand(1, 3);
            $price = $product->price;
            $total2 += $price * $quantity;
            
            OrderItem::create([
                'order_id' => $order2->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }
        $order2->update([
            'subtotal' => $total2,
            'total' => $total2 + 10000,
        ]);

        // ===== ORDER 3: COMPLETED =====
        $order3 = Order::create([
            'order_number' => 'ORD-20260618-003',
            'customer_name' => 'Customer 3',
            'customer_email' => 'customer3@example.com',
            'customer_phone' => '08123456787',
            'shipping_address' => 'Jl. Test No. 789',
            'shipping_city' => 'Surabaya',
            'shipping_postal_code' => '12347',
            'subtotal' => 0,
            'shipping_cost' => 10000,
            'total' => 0,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'e_wallet',
            'items' => json_encode([]),
            'notes' => 'Sample order 3',
        ]);

        $total3 = 0;
        foreach ($products->take(3) as $product) {
            $quantity = rand(1, 4);
            $price = $product->price;
            $total3 += $price * $quantity;
            
            OrderItem::create([
                'order_id' => $order3->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }
        $order3->update([
            'subtotal' => $total3,
            'total' => $total3 + 10000,
        ]);

        $this->command->info('Sample orders and order items created successfully!');
        $this->command->info('Total orders: 3 (2 completed, 1 pending)');
    }
}