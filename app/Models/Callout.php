<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Callout extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_number', 'incident_date', 'ampds_code', 'ohca_at_scene', 'bystander_cpr', 'source_of_aed', 'number_of_shocks_given', 'rosc_achieved', 'patient_transported', 'responders_at_scene', 'ppe_kits_used', 'waste_disposal', 'notes', 'attended',
    ];
}
