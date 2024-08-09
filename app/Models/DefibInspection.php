<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefibInspection extends Model
{
    use HasFactory;
    use HasUser;
    use SoftDeletes;

    protected $fillable = ['member_id', 'inspected_at', 'pads_expire_at', 'battery_expires_at', 'battery_condition', 'notes'];

    protected function casts(): array
    {
        return [
            'inspected_at' => 'date:Y-m-d',
            'pads_expire_at' => 'date:Y-m-d',
            'battery_expires_at' => 'date:Y-m-d',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function defib(): BelongsTo
    {
        return $this->belongsTo(Defib::class);
    }
}
