<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('defib_inspections', function (Blueprint $table): void {
            $table->string('battery_condition')->nullable()->after('battery_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defib_inspections', function (Blueprint $table): void {
            $table->dropColumn('battery_condition');
        });
    }
};
