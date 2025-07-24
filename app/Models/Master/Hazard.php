<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Report\Reports;

class Hazard extends Model
{
    use HasFactory;

    protected $table = 'hazard_potentials';
    protected $fillable = ['name'];

    public function reports() { return $this->hasMany(Reports::class); }
}
