<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Bruno Arruda',
                'email' => 'bruno.arruda@example.com',
            ],
            [
                'name' => 'Leonardo Arruda',
                'email' => 'leonardo.arruda@example.com',
            ],
            [
                'name' => 'Pedro Silva',
                'email' => 'pedro.silva@example.com',
            ],
            [
                'name' => 'Ana Silva',
                'email' => 'ana.silva@example.com',
            ],
            [
                'name' => 'Carlos Silva',
                'email' => 'carlos.silva@example.com',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
