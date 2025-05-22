<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model;

class Course extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'courses';

    protected $fillable = [
        'thumbnail',
        'instructor',
        'instructorRole',
        'instructorDescription',
        'name',
        'description',
        'duration',
        'students',
        'price',
        'discount',
        'courseModules',
        'category',
        'createdAt',
        'updatedAt',
        '_destroy'
    ];

    protected $casts = [
        'courseModules' => 'array',
        'duration' => 'float',
        'students' => 'integer',
        'price' => 'integer',
        'discount' => 'integer',
        'category' => 'string',
        'createdAt' => 'timestamp',
        'updatedAt' => 'timestamp',
        '_destroy' => 'boolean'
    ];

    protected $dates = [
        'createdAt',
        'updatedAt'
    ];
}