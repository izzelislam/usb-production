<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionWorker extends Model
{
    protected $fillable = [
        'production_id', 'employee_id', 'is_present', 'workload'
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollDetails()
    {
        return $this->hasMany(PayrollDetail::class);
    }
}

