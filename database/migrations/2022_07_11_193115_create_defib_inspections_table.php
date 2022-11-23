<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('defib_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('member_id')->constrained('members');
            $table->foreignId('defib_id')->constrained('defibs')->cascadeOnDelete();
            $table->timestamp('inspected_at');
            $table->timestamp('pads_expire_at');
            $table->timestamp('battery_expires_at');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('defib_inspections');
    }
};
