<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->ipAddress('last_login_from')->nullable()->after('email_verified_at');
            $table->dateTime('last_login_at')->nullable()->after('last_login_from');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('last_login_from');
            $table->dropColumn('last_login_at');
        });
    }
};
