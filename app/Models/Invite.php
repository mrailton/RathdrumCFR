<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email'];

    protected $casts = [
        'expires_at' => 'datetime',
        'registered_at' => 'datetime',
    ];
}
