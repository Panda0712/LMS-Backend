<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model;

class Progress extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'progresses';

    protected $fillable = [
        'userId',
        'courseId',
        'completedLessons',
        'totalLessons',
        'percentComplete',
        'lastAccessedAt',
        'createdAt',
        'updatedAt',
        '_destroy'
    ];

    protected $casts = [
        'userId' => 'string',
        'courseId' => 'string',
        'completedLessons' => 'array',
        'totalLessons' => 'integer',
        'percentComplete' => 'float',
        'lastAccessedAt' => 'timestamp',
        'createdAt' => 'timestamp',
        'updatedAt' => 'timestamp',
        '_destroy' => 'boolean'
    ];

    protected $dates = [
        'lastAccessedAt',
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