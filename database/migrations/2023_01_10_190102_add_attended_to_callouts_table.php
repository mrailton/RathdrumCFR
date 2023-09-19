<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('callouts', function (Blueprint $table): void {
            $table->enum('attended', ['Yes', 'No'])->default('Yes');
        });
    }

    public function down(): void
    {
        Schema::table('callouts', function (Blueprint $table): void {
            $table->dropColumn('attended');
        });
    }
};
