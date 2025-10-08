<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Report\Reports;

class Divisions extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function reports() { return $this->hasMany(Reports::class); }

    public function departments()
    {
        return $this->hasMany(Department::class, 'division_id');
    }
}
