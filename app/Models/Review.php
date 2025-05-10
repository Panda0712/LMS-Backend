<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model;

class Review extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'reviews';

    protected $fillable = [
        'userAvatar',
        'userName',
        'rating',
        'content',
        'userId',
        'courseId',
        'createdAt',
        'updatedAt',
        '_destroy'
    ];

    protected $casts = [
        'userId' => 'string',
        'courseId' => 'string',
        'rating' => 'integer',
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