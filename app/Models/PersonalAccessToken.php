<?php
// app/Models/PersonalAccessToken.php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $connection = 'mongodb';
    protected $collection = 'personal_access_tokens';
    
    protected $primaryKey = '_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expires_at',
        'last_used_at',
        'tokenable_id',
        'tokenable_type',
    ];
    
    protected $casts = [
        'abilities' => 'json',
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];
}