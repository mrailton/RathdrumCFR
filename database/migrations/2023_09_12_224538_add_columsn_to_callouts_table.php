<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('callouts', function (Blueprint $table) {
            $table->enum('mobilised', ['Yes', 'No'])->default('No');
            $table->enum('medical_facility', ['Yes', 'No'])->default('No');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('callouts', function (Blueprint $table) {
            $table->dropColumn('mobilised');
            $table->dropColumn('medical_facility');
        });
    }
};
