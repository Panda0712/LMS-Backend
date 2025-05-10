<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'userId',
        'courseId',
        'userEmail',
        'userName',
        'courseName',
        'courseThumbnail',
        'instructor',
        'totalPrice',
        'paymentMethod',
        'status',
        'createdAt',
        'updatedAt',
        '_destroy'
    ];

    protected $casts = [
        'userId' => 'string',
        'courseId' => 'string',
        'totalPrice' => 'integer',
        'createdAt' => 'timestamp',
        'updatedAt' => 'timestamp',
        '_destroy' => 'boolean'
    ];

    protected $dates = [
        'createdAt',
        'updatedAt'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', '_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'courseId', '_id');
    }
}