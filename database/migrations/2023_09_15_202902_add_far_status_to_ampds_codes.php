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
        Schema::table('ampds_codes', function (Blueprint $table): void {
            $table->boolean('far_code')->default(false)->after('arrest_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ampds_codes', function (Blueprint $table): void {
            $table->dropColumn('far_code');
        });
    }
};
