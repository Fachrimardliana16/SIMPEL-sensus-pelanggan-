<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->uuid('customer_id')->nullable()->after('surveyor_id');
            $table->string('foto')->nullable()->after('customer_id');
            
            // Add index for performance
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn(['customer_id', 'foto']);
        });
    }
};
