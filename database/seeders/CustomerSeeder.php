<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            ['name' => 'Grace Wanjiku', 'phone' => '+254712345678'],
            ['name' => 'Mary Akinyi', 'phone' => '+254723456789'],
            ['name' => 'Sarah Muthoni', 'phone' => '+254734567890'],
            ['name' => 'Faith Njeri', 'phone' => '+254745678901'],
            ['name' => 'Joyce Wanjiru', 'phone' => '+254756789012'],
            ['name' => 'Catherine Nyambura', 'phone' => '+254767890123'],
            ['name' => 'Elizabeth Wangari', 'phone' => '+254778901234'],
            ['name' => 'Margaret Wairimu', 'phone' => '+254789012345'],
            ['name' => 'Rose Wanjiku', 'phone' => '+254790123456'],
            ['name' => 'Jane Mwangi', 'phone' => '+254701234567'],
            ['name' => 'Agnes Wambui', 'phone' => '+254712345679'],
            ['name' => 'Lucy Njoki', 'phone' => '+254723456780'],
            ['name' => 'Esther Wangui', 'phone' => '+254734567891'],
            ['name' => 'Rebecca Wanjiru', 'phone' => '+254745678902'],
            ['name' => 'Walk-in Customer', 'phone' => null] // For cash customers without phone
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
