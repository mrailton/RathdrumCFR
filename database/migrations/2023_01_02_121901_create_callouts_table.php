<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('callouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('incident_number');
            $table->dateTime('incident_date');
            $table->string('ampds_code');
            $table->enum('ohca_at_scene', ['Yes', 'No', 'Unknown'])->default('No');
            $table->enum('bystander_cpr', ['Yes', 'No', 'Unknown'])->default('No');
            $table->enum('source_of_aed', ['PAD', 'CFR', 'NAS', 'Fire', 'Garda', 'Other'])->default('CFR');
            $table->integer('number_of_shocks_given')->default(0);
            $table->enum('rosc_achieved', ['Yes', 'No', 'Unknown'])->default('No');
            $table->enum('patient_transported', ['Yes', 'No', 'Unknown'])->default('No');
            $table->integer('responders_at_scene');
            $table->integer('ppe_kits_used');
            $table->enum('waste_disposal', ['NAS Crew', 'DFB Crew', 'NAS Station', 'Hospital', 'Responder', 'Other'])->default('NAS Crew');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('callouts');
    }
};
