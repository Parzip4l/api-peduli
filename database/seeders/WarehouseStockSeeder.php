<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse\WarehouseStock;
use App\Models\Warehouse\WarehouseLocation;
use App\Models\Warehouse\WarehouseItem;

class WarehouseStockSeeder extends Seeder
{
    public function run()
    {
        $location1 = WarehouseLocation::where('code', 'WH-001')->first();
        $location2 = WarehouseLocation::where('code', 'WH-002')->first();

        $item1 = WarehouseItem::where('name', 'Laptop Asus')->first();
        $item2 = WarehouseItem::where('name', 'Kopi Arabika')->first();

        WarehouseStock::create([
            'warehouse_location_id' => $location1->id,
            'warehouse_item_id' => $item1->id,
            'quantity' => 50,
        ]);

        WarehouseStock::create([
            'warehouse_location_id' => $location1->id,
            'warehouse_item_id' => $item2->id,
            'quantity' => 200,
        ]);

        WarehouseStock::create([
            'warehouse_location_id' => $location2->id,
            'warehouse_item_id' => $item1->id,
            'quantity' => 30,
        ]);
    }
}
