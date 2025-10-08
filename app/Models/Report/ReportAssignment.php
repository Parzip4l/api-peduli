<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\User;
use App\Models\Master\Divisions;
use App\Models\Master\Department;
use App\Models\Report\Reports;

class ReportAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'assigned_by',
        'assigned_to',
        'division_id',
        'department_id',
        'is_agree',
        'note',
    ];

    // Relasi ke laporan
    public function report()
    {
        return $this->belongsTo(Reports::class);
    }

    // Relasi ke user yang meng-assign (misalnya QSHE)
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Relasi ke user yang menerima tugas (PIC)
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Divisi tujuan
    public function division()
    {
        return $this->belongsTo(Divisions::class, 'division_id');
    }

    // ✅ Departemen tujuan
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
