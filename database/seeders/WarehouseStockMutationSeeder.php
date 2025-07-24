<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse\WarehouseStockMutation;
use App\Models\Warehouse\WarehouseStock;

class WarehouseStockMutationSeeder extends Seeder
{
    public function run()
    {
        $stock1 = WarehouseStock::first();

        WarehouseStockMutation::create([
            'warehouse_stock_id' => $stock1->id,
            'quantity' => 10,
            'type' => 'in',
            'note' => 'Restock dari supplier',
        ]);

        WarehouseStockMutation::create([
            'warehouse_stock_id' => $stock1->id,
            'quantity' => 5,
            'type' => 'out',  // keluar barang
            'note' => 'Penjualan ke customer',
            
        ]);
    }
}
