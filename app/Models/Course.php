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
        'title',
        'description',
        'image', // Cloudinary thumbnail
        'video', // Cloudinary video
        'tags',
        'courseModules',
        'price',
        'status',
        'author_id',
        'lessonIds',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'courseModules' => 'array',
        'lessonIds' => 'array',
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id', '_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', '_id');
    }
}