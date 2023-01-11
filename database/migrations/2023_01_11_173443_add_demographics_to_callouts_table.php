<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::table('callouts', function (Blueprint $table) {
            $table->string('age')->nullable();
            $table->enum('gender', ['Unknown', 'Male', 'Female']);
        });
    }

    public function down(): void
    {
        Schema::table('callouts', function (Blueprint $table) {
            $table->dropColumn('age');
            $table->dropColumn('gender');
        });
    }
};
