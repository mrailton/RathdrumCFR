<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('eircode')->nullable();
            $table->string('title')->default('Responder');
            $table->string('status')->default('Inactive');
            $table->string('cfr_certificate_number')->nullable();
            $table->date('cfr_certificate_expiry')->nullable();
            $table->date('volunteer_declaration')->nullable();
            $table->string('garda_vetting_id')->nullable();
            $table->date('garda_vetting_date')->nullable();
            $table->date('cism_completed')->nullable();
            $table->date('child_first_completed')->nullable();
            $table->date('ppe_community_completed')->nullable();
            $table->date('ppe_acute_completed')->nullable();
            $table->date('hand_hygiene_completed')->nullable();
            $table->date('hiqa_completed')->nullable();
            $table->date('covid_return_completed')->nullable();
            $table->date('ppe_assessment_completed')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
