<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'lessons';

    protected $fillable = [
        'name',
        'video_url',
        'courseId',
        'moduleId',
        'createdAt',
        'updatedAt',
        '_destroy'
    ];

    protected $casts = [
        'courseId' => 'string',
        'moduleId' => 'string',
        'createdAt' => 'timestamp',
        'updatedAt' => 'timestamp',
        '_destroy' => 'boolean'
    ];

    protected $dates = [
        'createdAt',
        'updatedAt'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'courseId', '_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'moduleId', '_id');
    }
}