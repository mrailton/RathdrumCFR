<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @use HasFactory<\Database\Factories\AMPDSCodeFactory>
 */
class AMPDSCode extends Model
{
    /** @use HasFactory<\Database\Factories\AMPDSCodeFactory> */
    use HasFactory;
    protected $table = 'ampds_codes';
    protected $fillable = ['code', 'description', 'arrest_code', 'far_code'];

    protected function casts(): array
    {
        return [
            'arrest_code' => 'boolean',
            'far_code' => 'boolean',
        ];
    }
}
