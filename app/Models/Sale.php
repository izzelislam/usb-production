<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'buyer_id',
        'date',
        'grand_total',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }
}
