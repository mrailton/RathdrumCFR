<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('callouts', function (Blueprint $table) {
            $table->string('ohca_at_scene')->nullable()->change();
            $table->string('bystander_cpr')->nullable()->change();
            $table->string('source_of_aed')->nullable()->change();
            $table->string('bystander_cpr')->nullable()->change();
            $table->string('rosc_achieved')->nullable()->change();
            $table->string('patient_transported')->nullable()->change();
            $table->string('responders_at_scene')->nullable()->change();
            $table->string('ppe_kits_used')->nullable()->change();
            $table->string('waste_disposal')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('callouts', function (Blueprint $table) {
            $table->enum('ohca_at_scene', ['Yes', 'No', 'Unknown'])->default('No')->change();
            $table->enum('bystander_cpr', ['Yes', 'No', 'Unknown'])->default('No')->change();
            $table->enum('source_of_aed', ['PAD', 'CFR', 'NAS', 'Fire', 'Garda', 'Other'])->default('CFR')->change();
            $table->integer('number_of_shocks_given')->default(0)->change();
            $table->enum('rosc_achieved', ['Yes', 'No', 'Unknown'])->default('No')->change();
            $table->enum('patient_transported', ['Yes', 'No', 'Unknown'])->default('No')->change();
            $table->integer('responders_at_scene')->change();
            $table->integer('ppe_kits_used')->change();
            $table->enum('waste_disposal', ['NAS Crew', 'DFB Crew', 'NAS Station', 'Hospital', 'Responder', 'Other'])->default('NAS Crew')->change();
        });
    }
};
