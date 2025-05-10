<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model;

class Blog extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'blogs';

    protected $fillable = [
        'title',
        'summary',
        'content',
        'tags',
        'coverImage',
        'author',
        'authorId',
        'createdAt',
        'updatedAt',
        '_destroy'
    ];

    protected $casts = [
        'tags' => 'array',
        'authorId' => 'string',
        'createdAt' => 'timestamp',
        'updatedAt' => 'timestamp',
        '_destroy' => 'boolean'
    ];

    protected $dates = [
        'createdAt',
        'updatedAt'
    ];

    public function authorUser()
    {
        return $this->belongsTo(User::class, 'authorId', '_id');
    }
}