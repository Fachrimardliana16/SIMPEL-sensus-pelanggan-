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
            $table->foreignUuid('surveyor_id')->nullable()->constrained('users')->nullOnDelete()->after('survey_id');
            $table->string('customer_name')->nullable()->after('surveyor_id');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->text('customer_address')->nullable()->after('customer_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropForeign(['surveyor_id']);
            $table->dropColumn(['surveyor_id', 'customer_name', 'customer_phone', 'customer_address']);
        });
    }
};
