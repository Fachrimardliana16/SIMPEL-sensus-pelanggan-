<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('id_tarif')->nullable();
            $table->string('kode_tarif')->nullable();
            $table->string('golongan');
            $table->integer('batas1')->default(0);
            $table->integer('batas2')->default(0);
            $table->integer('batas3')->default(0);
            $table->decimal('rp1', 15, 2)->default(0);
            $table->decimal('rp2', 15, 2)->default(0);
            $table->decimal('rp3', 15, 2)->default(0);
            $table->decimal('rp4', 15, 2)->default(0);
            $table->decimal('minimun', 15, 2)->default(0);
            $table->decimal('jasameter', 15, 2)->default(0);
            $table->string('kas1')->nullable();
            $table->string('kas2')->nullable();
            $table->string('kas3')->nullable();
            $table->decimal('denda', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifs');
    }
};
