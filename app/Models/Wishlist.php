<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'wishlists';

    protected $fillable = [
        'userId',
        'courseId',
        'courseName',
        'courseThumbnail',
        'instructor',
        'duration',
        'totalLessons',
        'totalReviews',
        'rating',
        'createdAt',
        'updatedAt',
        '_destroy'
    ];

    protected $casts = [
        'userId' => 'string',
        'courseId' => 'string',
        'duration' => 'float',
        'totalLessons' => 'integer',
        'totalReviews' => 'integer',
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