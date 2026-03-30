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
            $table->string('id_pelanggan')->nullable();
            $table->string('tahun')->nullable();
            $table->string('nolangg')->nullable();
            $table->string('nama')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('pdam_status')->nullable();
            $table->string('tarif')->nullable();
            $table->string('nometer')->nullable();
            $table->string('merk_meter')->nullable();
            $table->string('diameter')->nullable();
            $table->string('BApasang')->nullable();
            $table->string('BAtutup')->nullable();
            $table->string('BAbuka')->nullable();
            $table->string('tglPasang')->nullable();
            $table->string('tglTutup')->nullable();
            $table->string('tglBuka')->nullable();
            $table->string('tglBongkar')->nullable();
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
            $table->string('photo_home')->nullable();
            $table->string('photo_meter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn([
                'id_pelanggan', 'tahun', 'nolangg', 'nama', 'alamat', 'telepon', 'pdam_status', 'tarif', 'nometer', 
                'merk_meter', 'diameter', 'BApasang', 'BAtutup', 'BAbuka', 'tglPasang', 'tglTutup', 'tglBuka', 
                'tglBongkar', 'kas', 'kode_alamat', 'kode_unit', 'jenis_pelayanan', 'KEL', 'lastEdit', 'editBy', 
                'lati', 'longi', 'alti', 'photo_home', 'photo_meter'
            ]);
        });
    }
};
