<?php

declare(strict_types=1);

use App\Models\Callout;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('callouts', function (Blueprint $table): void {
            $table->string('age_new')->nullable()->after('ampds_code');
            $table->string('gender_new')->default('Unknown')->after('age_new')->nullable();
            $table->boolean('mobilised_new')->default(false)->after('gender_new');
            $table->boolean('medical_facility_new')->default(false)->after('mobilised_new');
            $table->boolean('attended_new')->default(false)->after('medical_facility_new');
            $table->boolean('ohca_at_scene_new')->default(false)->after('attended_new');
            $table->boolean('bystander_cpr_new')->default(false)->after('ohca_at_scene_new');
            $table->boolean('rosc_achieved_new')->default(false)->after('bystander_cpr_new');
            $table->boolean('patient_transported_new')->default(false)->after('rosc_achieved_new');
        });

        Schema::table('callouts', function (Blueprint $table): void {
            $callouts = Callout::all();

            foreach ($callouts as $callout) {
                $callout->age_new = $callout->age;
                $callout->gender_new = $callout->gender;
                $callout->mobilised_new = 'Yes' === $callout->mobilised ? 1 : 0;
                $callout->medical_facility_new = 'Yes' === $callout->medical_facility ? 1 : 0;
                $callout->attended_new = 'Yes' === $callout->attended ? 1 : 0;
                $callout->ohca_at_scene_new = 'Yes' === $callout->ohca_at_scene ? 1 : 0;
                $callout->bystander_cpr_new = 'Yes' === $callout->bystander_cpr ? 1 : 0;
                $callout->rosc_achieved_new = 'Yes' === $callout->rosc_achieved ? 1 : 0;
                $callout->patient_transported_new = 'Yes' === $callout->patient_transported ? 1 : 0;
                $callout->save();
            }
        });

        Schema::table('callouts', function (Blueprint $table): void {
            $table->dropColumn(['age', 'gender', 'mobilised', 'medical_facility', 'attended', 'ohca_at_scene', 'bystander_cpr', 'rosc_achieved', 'patient_transported']);
        });

        Schema::table('callouts', function (Blueprint $table): void {
            $table->renameColumn('age_new', 'age');
            $table->renameColumn('gender_new', 'gender');
            $table->renameColumn('mobilised_new', 'mobilised');
            $table->renameColumn('medical_facility_new', 'medical_facility');
            $table->renameColumn('attended_new', 'attended');
            $table->renameColumn('ohca_at_scene_new', 'ohca_at_scene');
            $table->renameColumn('bystander_cpr_new', 'bystander_cpr');
            $table->renameColumn('rosc_achieved_new', 'rosc_achieved');
            $table->renameColumn('patient_transported_new', 'patient_transported');
        });

        Schema::table('callouts', function (Blueprint $table): void {
            $table->integer('responders_at_scene')->default(0)->change();
            $table->string('source_of_aed')->nullable(true)->default(null)->change();
            $table->string('waste_disposal')->nullable(true)->default(null)->change();
        });
    }
};
