<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse\WarehouseLocation;

class WarehouseLocationSeeder extends Seeder
{
    public function run()
    {
        WarehouseLocation::create([
            'name' => 'Gudang Utama',
            'code' => 'WH-001',
            'address' => 'Jl. Merdeka No. 1, Jakarta',
            'description' => 'Gudang pusat untuk semua produk',
        ]);

        WarehouseLocation::create([
            'name' => 'Gudang Cabang Surabaya',
            'code' => 'WH-002',
            'address' => 'Jl. Pahlawan No. 12, Surabaya',
            'description' => 'Gudang cabang untuk wilayah Surabaya',
        ]);
    }
}
