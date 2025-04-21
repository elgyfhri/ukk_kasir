<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class products extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'image'
    ];

    public function detail_sales() 
    {
        return $this->hasMany(detail_sales::class, 'product_id', 'id');
    }
}
