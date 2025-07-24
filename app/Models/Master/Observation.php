<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Report\Reports;

class Observation extends Model
{
    use HasFactory;

    protected $table = 'observation_types';
    protected $fillable = ['name'];

    public function reports() { return $this->hasMany(Reports::class); }
}
