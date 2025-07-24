<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Report\reports;

class Locations extends Model
{
    use HasFactory;

    protected $fillable = ['kode', 'nama_lokasi'];

    public function reports() { return $this->hasMany(reports::class); }
}
