<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_id',
        'purchase_id',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
