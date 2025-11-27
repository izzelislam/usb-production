<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    protected $fillable = [
        'payroll_id', 'production_worker_id',
        'workload', 'salary_amount'
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function productionWorker()
    {
        return $this->belongsTo(ProductionWorker::class);
    }
}

