<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDeduction extends Model
{
    protected $fillable = ['payroll_id', 'type', 'description', 'amount'];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}

