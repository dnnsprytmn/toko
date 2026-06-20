<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $orders = [
            [
                'order_number' => 'ORD-001',
                'customer_name' => 'John Doe',
                'customer_email' => 'john@example.com',
                'customer_phone' => '08123456789',
                'shipping_address' => 'Jl. Merdeka No. 123',
                'shipping_city' => 'Jakarta',
                'subtotal' => 150.00,
                'shipping_cost' => 10.00,
                'total' => 175.00,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'items' => json_encode([
                    ['name' => 'Product 1', 'quantity' => 2, 'price' => 50.00],
                    ['name' => 'Product 2', 'quantity' => 1, 'price' => 50.00]
                ]),
                'created_at' => now()->subDays(2),
            ],
            [
                'order_number' => 'ORD-002',
                'customer_name' => 'Jane Smith',
                'customer_email' => 'jane@example.com',
                'customer_phone' => '08987654321',
                'shipping_address' => 'Jl. Sudirman No. 456',
                'shipping_city' => 'Bandung',
                'subtotal' => 89.99,
                'shipping_cost' => 10.00,
                'total' => 108.99,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => null,
                'items' => json_encode([
                    ['name' => 'Product 3', 'quantity' => 1, 'price' => 89.99]
                ]),
                'created_at' => now()->subDays(1),
            ],
            [
                'order_number' => 'ORD-003',
                'customer_name' => 'Bob Johnson',
                'customer_email' => 'bob@example.com',
                'customer_phone' => '08223344556',
                'shipping_address' => 'Jl. Gatot Subroto No. 789',
                'shipping_city' => 'Surabaya',
                'subtotal' => 250.00,
                'shipping_cost' => 15.00,
                'total' => 290.00,
                'status' => 'processing',
                'payment_status' => 'paid',
                'payment_method' => 'credit_card',
                'items' => json_encode([
                    ['name' => 'Product 4', 'quantity' => 1, 'price' => 150.00],
                    ['name' => 'Product 5', 'quantity' => 2, 'price' => 50.00]
                ]),
                'created_at' => now(),
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}