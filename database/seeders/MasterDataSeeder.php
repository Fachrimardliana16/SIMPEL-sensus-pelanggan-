<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\Unit;
use App\Models\Tarif;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Master Status
        $statuses = [
            ['kode' => '1', 'nama' => 'Baru'],
            ['kode' => '2', 'nama' => 'Aktif'],
            ['kode' => '3', 'nama' => 'Tutup'],
            ['kode' => '4', 'nama' => 'Tutup Sementara'],
            ['kode' => '5', 'nama' => 'Bongkar'],
        ];
        foreach ($statuses as $s) Status::create($s);

        // Master Unit
        $units = [
            ['kode' => '03', 'namaunit' => 'Cabang Usman Janatin', 'alamat' => 'Jl. Raya Bobotsari', 'ka_unit' => 'Rakhmanto, S.St', 'pref' => 'BBS'],
            ['kode' => '04', 'namaunit' => 'Cabang Ardi Lawet', 'alamat' => 'Ds. Kutasari', 'ka_unit' => 'Teguh Kuntjoro S.St', 'pref' => 'KTS'],
            ['kode' => '05', 'namaunit' => 'Cabang Ardi Lawet', 'alamat' => 'Kecamatan Bojongsari', 'ka_unit' => 'Teguh Kuntjoro S.St', 'pref' => 'BJS'],
            ['kode' => '06', 'namaunit' => 'Cabang Usman Janatin', 'alamat' => 'Ds. Mangunegara', 'ka_unit' => 'Rakhmanto, S.St', 'pref' => 'MRB'],
            ['kode' => '07', 'namaunit' => 'Unit Kemangkon', 'alamat' => 'Ds Penican', 'ka_unit' => 'Ma\'un Suseno, Amd', 'pref' => 'KMK'],
            ['kode' => '08', 'namaunit' => 'Kejobong', 'alamat' => 'Kec. Kejobong', 'ka_unit' => '-', 'pref' => 'KJB'],
            ['kode' => '09', 'namaunit' => 'Unit Rembang', 'alamat' => 'Kec. Rembang', 'ka_unit' => 'Margono', 'pref' => 'RMB'],
            ['kode' => '12', 'namaunit' => 'Cabang Jend. Soedirman', 'alamat' => 'Kec. Padamara', 'ka_unit' => 'Sutarman A.Md', 'pref' => 'PDR'],
            ['kode' => '13', 'namaunit' => 'Cabang Jend. Soedirman', 'alamat' => 'Kec. Kalimanah', 'ka_unit' => 'Sutarman A.Md', 'pref' => 'KLM'],
            ['kode' => '01', 'namaunit' => 'Cabang Purbalingga', 'alamat' => 'JL JL S PARMAN', 'ka_unit' => 'Tur Tjahjoto, S.St', 'pref' => 'PAM'],
            ['kode' => '02', 'namaunit' => 'Unit Bukateja', 'alamat' => 'Kecamatan Bukateja', 'ka_unit' => 'Suyitno', 'pref' => 'BKT'],
            ['kode' => '14', 'namaunit' => 'Unit Karangreja', 'alamat' => 'Kec. Karang Reja', 'ka_unit' => 'Riyadi', 'pref' => 'KRJ'],
            ['kode' => '15', 'namaunit' => 'Cabang Goentoer Darjono', 'alamat' => 'Ds.Kaligondang', 'ka_unit' => 'Sugeng, A.Md', 'pref' => 'KLG'],
        ];
        foreach ($units as $u) Unit::create($u);

        // Master Tarif
        $tarifs = [
            ['id_tarif' => '1', 'kode_tarif' => 'A1', 'golongan' => 'ABRI/TNI POLRI', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 1380, 'rp2' => 1850, 'rp3' => 2500, 'rp4' => 3380, 'minimun' => 0, 'jasameter' => 40000, 'kas1' => '10', 'kas2' => '1101', 'kas3' => '10', 'denda' => 5000],
            ['id_tarif' => '2', 'kode_tarif' => 'A2', 'golongan' => 'TNI Yonif 406 CK', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 1380, 'rp2' => 1850, 'rp3' => 2500, 'rp4' => 3380, 'minimun' => 0, 'jasameter' => 101000, 'kas1' => '10', 'kas2' => '1101', 'kas3' => '10', 'denda' => 5000],
            ['id_tarif' => '3', 'kode_tarif' => 'I1', 'golongan' => 'Industri Kecil', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 2060, 'rp2' => 2920, 'rp3' => 3950, 'rp4' => 5160, 'minimun' => 0, 'jasameter' => 28000, 'kas1' => '10', 'kas2' => '1101', 'kas3' => '10', 'denda' => 5000],
            ['id_tarif' => '4', 'kode_tarif' => 'I2', 'golongan' => 'Industri Besar', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 2290, 'rp2' => 3100, 'rp3' => 4190, 'rp4' => 5650, 'minimun' => 0, 'jasameter' => 34000, 'kas1' => '10', 'kas2' => '1101', 'kas3' => '10', 'denda' => 5000],
            ['id_tarif' => '5', 'kode_tarif' => 'IP', 'golongan' => 'Inst. Pemerinta', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 1380, 'rp2' => 1850, 'rp3' => 2500, 'rp4' => 3380, 'minimun' => 0, 'jasameter' => 20000, 'kas1' => '10', 'kas2' => '1101', 'kas3' => '10', 'denda' => 5000],
            ['id_tarif' => '6', 'kode_tarif' => 'N1', 'golongan' => 'Niaga Kecil', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 1840, 'rp2' => 2480, 'rp3' => 3350, 'rp4' => 4530, 'minimun' => 0, 'jasameter' => 23000, 'kas1' => '10', 'kas2' => '1101', 'kas3' => '10', 'denda' => 5000],
            ['id_tarif' => '7', 'kode_tarif' => 'N2', 'golongan' => 'Niaga Besar', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 2060, 'rp2' => 2920, 'rp3' => 3950, 'rp4' => 5160, 'minimun' => 0, 'jasameter' => 26000, 'kas1' => '10', 'kas2' => '1101', 'kas3' => '10', 'denda' => 5000],
            ['id_tarif' => '8', 'kode_tarif' => 'R1', 'golongan' => 'Rumah Tangga A', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 1100, 'rp2' => 1480, 'rp3' => 2000, 'rp4' => 2700, 'minimun' => 0, 'jasameter' => 11500, 'kas1' => '10', 'kas2' => '1101', 'kas3' => '10', 'denda' => 5000],
            ['id_tarif' => '9', 'kode_tarif' => 'R2', 'golongan' => 'Rumah Tangga B', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 1190, 'rp2' => 1600, 'rp3' => 2160, 'rp4' => 2930, 'minimun' => 0, 'jasameter' => 12000, 'kas1' => '10', 'kas2' => '1101', 'kas3' => '10', 'denda' => 5000],
            ['id_tarif' => '10', 'kode_tarif' => 'R3', 'golongan' => 'Rumah Tangga C', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 1280, 'rp2' => 1730, 'rp3' => 2340, 'rp4' => 3160, 'minimun' => 0, 'jasameter' => 15000, 'kas1' => '10', 'kas2' => '1101', 'kas3' => '10', 'denda' => 5000],
            ['id_tarif' => '11', 'kode_tarif' => 'S1', 'golongan' => 'Sosial Umum', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 780, 'rp2' => 780, 'rp3' => 780, 'rp4' => 780, 'minimun' => 0, 'jasameter' => 10000, 'kas1' => '10', 'kas2' => '1101', 'kas3' => '10', 'denda' => 5000],
            ['id_tarif' => '12', 'kode_tarif' => 'S2', 'golongan' => 'Sosial Khusus', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 850, 'rp2' => 1140, 'rp3' => 1550, 'rp4' => 2090, 'minimun' => 0, 'jasameter' => 11000, 'kas1' => '10', 'kas2' => '1101', 'kas3' => '10', 'denda' => 5000],
            ['id_tarif' => '13', 'kode_tarif' => 'R4', 'golongan' => 'RT Serang', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 1100, 'rp2' => 1480, 'rp3' => 2000, 'rp4' => 2700, 'minimun' => 0, 'jasameter' => 11500, 'kas1' => '', 'kas2' => '', 'kas3' => '', 'denda' => 5000],
            ['id_tarif' => '14', 'kode_tarif' => 'S3', 'golongan' => 'Sosial Umum KRJ', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 240, 'rp2' => 240, 'rp3' => 240, 'rp4' => 240, 'minimun' => 0, 'jasameter' => 5500, 'kas1' => '', 'kas2' => '', 'kas3' => '', 'denda' => 5000],
            ['id_tarif' => '15', 'kode_tarif' => 'R5', 'golongan' => 'RT Kutabawa', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 1280, 'rp2' => 1730, 'rp3' => 2340, 'rp4' => 3160, 'minimun' => 0, 'jasameter' => 15000, 'kas1' => '', 'kas2' => '', 'kas3' => '', 'denda' => 5000],
            ['id_tarif' => '16', 'kode_tarif' => 'R6', 'golongan' => 'RT KRETE', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 1280, 'rp2' => 1730, 'rp3' => 2340, 'rp4' => 3160, 'minimun' => 0, 'jasameter' => 15000, 'kas1' => '', 'kas2' => '', 'kas3' => '', 'denda' => 5000],
            ['id_tarif' => '17', 'kode_tarif' => 'R7', 'golongan' => 'RT Khusus', 'batas1' => 10, 'batas2' => 20, 'batas3' => 30, 'rp1' => 890, 'rp2' => 1140, 'rp3' => 1509, 'rp4' => 1968, 'minimun' => 0, 'jasameter' => 11500, 'kas1' => '', 'kas2' => '', 'kas3' => '', 'denda' => 5000],
        ];
        foreach ($tarifs as $t) Tarif::create($t);
    }
}
