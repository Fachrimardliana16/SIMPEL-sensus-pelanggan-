<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tema');
            $table->text('pertanyaan');
            $table->string('tipe')->default('text'); // text, single_choice, multiple_choice, rating
            $table->json('opsi')->nullable();
            $table->integer('poin')->default(0);
            $table->boolean('wajib')->default(true);
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
