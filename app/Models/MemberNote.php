<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @use HasFactory<\Database\Factories\MemberNoteFactory>
 * @use SoftDeletes<MemberNote>
 */
class MemberNote extends Model
{
    /** @use HasFactory<\Database\Factories\MemberNoteFactory> */
    use HasFactory;
    use HasUser;
    use SoftDeletes;

    protected $fillable = ['note'];

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
