<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class detail_sales extends Model
{
    use HasFactory, Notifiable;

    // Tambahkan ini:
    protected $table = 'detail_sales';

    protected $fillable = [
        'sale_id',
        'product_id',
        'amount',
        'subtotal'
    ];

    public function saless() 
    {
        return $this->belongsTo(saless::class);
    }

    public function product() 
    {
        return $this->belongsTo(products::class);
    }
}
