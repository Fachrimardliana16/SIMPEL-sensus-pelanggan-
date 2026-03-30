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
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->string('meter_condition')->nullable();
            $table->string('meter_accuracy')->nullable();
            $table->string('water_pressure')->nullable();
            $table->string('meter_seal')->nullable();
            $table->string('other_water_sources')->nullable();
            $table->string('meter_location')->nullable();
            $table->text('census_notes')->nullable();
            
            // Allow survey_id to be nullable for direct census
            $table->foreignUuid('survey_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn([
                'meter_condition',
                'meter_accuracy',
                'water_pressure',
                'meter_seal',
                'other_water_sources',
                'meter_location',
                'census_notes'
            ]);
        });
    }
};
