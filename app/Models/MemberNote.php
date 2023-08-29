<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use function parent;

class MemberNote extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['note'];

    protected static function boot(): void
    {
        static::creating(function (MemberNote $note) {
            $note->user_id = auth()->id();
        });

        parent::boot();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
