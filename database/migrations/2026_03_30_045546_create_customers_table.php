<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('id_pelanggan')->unique();
            $table->string('tahun')->nullable();
            $table->string('nolangg')->nullable();
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('status')->default('aktif');
            $table->string('tarif')->nullable();
            $table->string('nometer')->nullable();
            $table->string('merk_meter')->nullable();
            $table->string('diameter')->nullable();
            $table->string('BApasang')->nullable();
            $table->string('BAtutup')->nullable();
            $table->string('BAbuka')->nullable();
            $table->date('tglPasang')->nullable();
            $table->date('tglTutup')->nullable();
            $table->date('tglBuka')->nullable();
            $table->string('kas')->nullable();
            $table->string('kode_alamat')->nullable();
            $table->string('kode_unit')->nullable();
            $table->string('jenis_pelayanan')->nullable();
            $table->string('KEL')->nullable();
            $table->string('lastEdit')->nullable();
            $table->string('editBy')->nullable();
            $table->string('lati')->nullable();
            $table->string('longi')->nullable();
            $table->string('alti')->nullable();
            $table->date('tglBongkar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
