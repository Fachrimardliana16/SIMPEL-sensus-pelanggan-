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
            $table->string('customer_number')->nullable()->after('customer_name');
            $table->string('meter_number')->nullable()->after('customer_number');
            $table->string('customer_category')->nullable()->after('meter_number');
            $table->string('area_code')->nullable()->after('customer_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn(['customer_number', 'meter_number', 'customer_category', 'area_code']);
        });
    }
};
