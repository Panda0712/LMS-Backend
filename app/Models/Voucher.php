<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'vouchers';

    protected $fillable = [
        'name',
        'discount',
        'courseIds',
        'code',
        'usageLimit',
        'usedCount',
        'minOrderValue',
        'expiredAt',
        'createdAt',
        'updatedAt',
        '_destroy'
    ];

    protected $casts = [
        'courseIds' => 'array',
        'discount' => 'integer',
        'usageLimit' => 'integer',
        'usedCount' => 'integer',
        'minOrderValue' => 'integer',
        'expiredAt' => 'datetime:Y-m-d',
        'updatedAt' => 'timestamp',
        '_destroy' => 'boolean'
    ];

    protected $dates = [
        'expiredAt',
        'createdAt',
        'updatedAt'
    ];
}