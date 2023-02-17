<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->boolean('defib_inspected')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->dropColumn('defib_inspected');
        });
    }
};
