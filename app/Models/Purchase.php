<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'vendor_id',
        'date',
        'grand_total',
        'is_produced',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function productions()
    {
        return $this->belongsToMany(Production::class, 'production_purchases');
    }
}
