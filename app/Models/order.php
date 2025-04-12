<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'merchant_id',
        'order_number',
        'order_date',
        'delivery_date',
        'status',
        'total_price',
    ];
}
