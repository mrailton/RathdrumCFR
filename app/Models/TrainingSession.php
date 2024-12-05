<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingSession extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['date', 'topic', 'notes'];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class);
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
