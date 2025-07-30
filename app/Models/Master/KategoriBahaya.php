<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Report\Reports;

class KategoriBahaya extends Model
{
    use HasFactory;

    protected $table = 'bahaya';
    protected $fillable = ['name','deskripsi'];

    public function reports() { return $this->hasMany(Reports::class); }
}
