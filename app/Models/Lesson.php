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
        'title',
        'content',
        'video',
        'video_url', // Cloudinary video URL
        'course_id',
        'module_id',
        'order',
        'created_at',
        'updated_at',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', '_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', '_id');
    }
}