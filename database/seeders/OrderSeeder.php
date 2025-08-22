<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\ProductVariant;
use App\Models\Location;
use App\Models\User;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $variants = ProductVariant::all();
        $locations = Location::all();
        $users = User::all();

        // Create 15 sample orders
        for ($i = 1; $i <= 15; $i++) {
            $customer = $customers->random();
            $location = $locations->random();
            $user = $users->isNotEmpty() ? $users->random() : null;

            $order = Order::create([
                'order_number' => 'ORD-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'user_id' => $user?->id,
                'location_id' => $location->id,
                'status' => collect(['awaiting_payment', 'completed', 'completed', 'completed'])->random(), // More completed orders
                'total_amount' => 0, // Will be calculated after adding items
                'notes' => $i % 3 == 0 ? 'Customer requested gift wrapping' : null,
                'created_at' => now()->subDays(rand(1, 30)) // Orders from last 30 days
            ]);

            // Add 1-4 items per order
            $itemCount = rand(1, 4);
            $totalAmount = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $variant = $variants->random();
                $quantity = rand(1, 3);
                $price = $variant->selling_price;
                $cost = $variant->cost_price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'cost_at_time_of_sale' => $cost
                ]);

                $totalAmount += ($price * $quantity);
            }

            // Update order total
            $order->update(['total_amount' => $totalAmount]);

            // Create payment if order is completed
            if ($order->status === 'completed') {
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $totalAmount,
                    'payment_method' => collect(['cash', 'mpesa', 'card'])->random(),
                    'paid_at' => $order->created_at->addMinutes(rand(5, 30))
                ]);
            }
        }
    }
}
