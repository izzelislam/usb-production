<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id', 'start_date', 'end_date',
        'salary_type', 'days_present', 'total_workload',
        'base_salary', 'total_salary',
        'total_bonus', 'total_deduction', 'final_salary',
        'mode', 'notes', 'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function details()
    {
        return $this->hasMany(PayrollDetail::class);
    }

    public function bonuses()
    {
        return $this->hasMany(PayrollBonus::class);
    }

    public function deductions()
    {
        return $this->hasMany(PayrollDeduction::class);
    }
}

