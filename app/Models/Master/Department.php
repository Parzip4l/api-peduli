<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'name',
        'code',
    ];

    public function division()
    {
        return $this->belongsTo(Divisions::class, 'division_id');
    }
}
