<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class saless extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'saless';
    protected $fillable = [
        'sale_date',
        'total_price',
        'total_pay',
        'total_return',
        'customer_id',
        'user_id',
        'point',
        'total_point',
    ];
    public function customer() 
    {
        return $this->belongsTo(customers::class);
    }
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    public function detail_sales() 
    {
        return $this->hasMany(detail_sales::class, 'sale_id', 'id');
    }
}
