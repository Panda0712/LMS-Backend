<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model;

class Module extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'modules';

    protected $fillable = [
        'title',
        'description',
        'duration',
        'lessons',
        'courseId',
        'createdAt',
        'updatedAt',
        '_destroy'
    ];

    protected $casts = [
        'lessons' => 'array',
        'duration' => 'float',
        'courseId' => 'string',
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
}