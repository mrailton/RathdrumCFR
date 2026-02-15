<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('defibs', function (Blueprint $table) {
            $table->string('defib_lot_number')->nullable();
            $table->date('defib_manufacture_date')->nullable();
            $table->string('battery_lot_number')->nullable();
            $table->date('battery_manufacture_date')->nullable();
            $table->string('pads_lot_number')->nullable();
            $table->date('pads_manufacture_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defibs', function (Blueprint $table) {
            $table->dropColumn('defib_lot_number');
            $table->dropColumn('defib_manufacture_date');
            $table->dropColumn('battery_lot_number');
            $table->dropColumn('battery_manufacture_date');
            $table->dropColumn('pads_lot_number');
            $table->dropColumn('pads_manufacture_date');
        });
    }
};
