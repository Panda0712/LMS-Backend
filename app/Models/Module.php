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
        'course_id',
        'lessonIds',
        'order',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'lessonIds' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', '_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'module_id', '_id');
    }
}