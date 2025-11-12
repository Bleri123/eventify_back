<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payments = [
            [
                'booking_id' => 1,
                'total_price' => 5.50,
                'status' => 'pending',
                'transaction_ref' => 'EV1234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 2,
                'total_price' => 11.00,
                'status' => 'paid',
                'transaction_ref' => 'EV1234567891',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'booking_id' => 3,
                'total_price' => 5.50,
                'status' => 'failed',
                'transaction_ref' => 'EV1234567892',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('payments')->insert($payments);
    }
}
