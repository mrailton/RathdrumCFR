<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('user_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->boolean('cfr_cert_expiry')->default(false);
            $table->boolean('defib_battery_expiry')->default(false);
            $table->boolean('defib_inspection')->default(false);
            $table->boolean('defib_pad_expiry')->default(false);
            $table->boolean('garda_vetting_expiry')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_reports');
    }
};
