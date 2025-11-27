<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_date',
        'total_kue',
        'grand_total',
        'notes',
    ];

    public function productionWorkers()
    {
        return $this->hasMany(ProductionWorker::class);
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'production_purchases');
    }
}

