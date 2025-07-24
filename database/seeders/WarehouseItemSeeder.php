<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse\WarehouseItem;

class WarehouseItemSeeder extends Seeder
{
    public function run()
    {
        WarehouseItem::create([
            'name' => 'Item A',
            'category' => 'Elektronik',
            'unit' => 'Kg',
            'code' => '001',
            'minimum_qty' => '1',
        ]);

        WarehouseItem::create([
            'name' => 'Item B',
            'category' => 'Makanan',
            'unit' => 'Kg',
            'code' => '002',
            'minimum_qty' => '1',
        ]);
    }
}
