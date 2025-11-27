<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name', 'phone', 'address', 'is_active',
        'salary_type', 'base_salary_per_kg', 'base_salary_per_day'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'base_salary_per_kg' => 'decimal:2',
        'base_salary_per_day' => 'decimal:2',
    ];

    public function productionWorkers()
    {
        return $this->hasMany(ProductionWorker::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}

