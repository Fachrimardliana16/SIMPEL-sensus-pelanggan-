<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Survey;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\Permission;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Setup Roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $analyst = Role::firstOrCreate(['name' => 'Analyst', 'guard_name' => 'web']);
        $surveyor = Role::firstOrCreate(['name' => 'Surveyor', 'guard_name' => 'web']);

        // 1.5 Setup Permissions
        $permissions = [
            'manage_surveys', 'view_surveys', 'manage_users',
            'manage_roles', 'view_analytics', 'input_census', 'review_census',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        $superAdmin->syncPermissions(Permission::all());
        $admin->syncPermissions(['manage_surveys', 'view_surveys', 'manage_users', 'view_analytics', 'review_census']);
        $analyst->syncPermissions(['view_surveys', 'view_analytics', 'review_census']);
        $surveyor->syncPermissions(['view_surveys', 'input_census']);

        // 2. Setup Default Users
        $adminUser = User::firstOrCreate(['email' => 'admin@surveipro.local'], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole($superAdmin);

        $analystUser = User::firstOrCreate(['email' => 'analyst@surveipro.local'], [
            'name' => 'Analyst User',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $analystUser->assignRole($analyst);

        $surveyorUser = User::firstOrCreate(['email' => 'surveyor@surveipro.local'], [
            'name' => 'Surveyor Lapangan',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $surveyorUser->assignRole($surveyor);

        // 3. Seed Questions into questions table
        $questions = [
            ['tema' => 'Kualitas Air', 'pertanyaan' => 'Bagaimana kepuasan Anda terhadap kualitas air/layanan?', 'tipe' => 'rating', 'poin' => 10, 'wajib' => true, 'urutan' => 1],
            ['tema' => 'Tekanan & Aliran', 'pertanyaan' => 'Apakah air mengalir 24 jam?', 'tipe' => 'single_choice', 'poin' => 10, 'wajib' => true, 'urutan' => 2, 'opsi' => [
                ['label' => 'Ya, lancar 24 jam', 'value' => 'lancar'],
                ['label' => 'Sering mati', 'value' => 'sering_mati'],
                ['label' => 'Hanya malam hari', 'value' => 'malam_saja'],
            ]],
            ['tema' => 'Tekanan & Aliran', 'pertanyaan' => 'Bagaimana tekanan air di jam puncak?', 'tipe' => 'single_choice', 'poin' => 10, 'wajib' => true, 'urutan' => 3, 'opsi' => [
                ['label' => 'Kuat', 'value' => 'kuat'],
                ['label' => 'Sedang', 'value' => 'sedang'],
                ['label' => 'Lemah', 'value' => 'lemah'],
            ]],
            ['tema' => 'Meteran & Peralatan', 'pertanyaan' => 'Kondisi sekitar meteran air?', 'tipe' => 'multiple_choice', 'poin' => 5, 'wajib' => false, 'urutan' => 4, 'opsi' => [
                ['label' => 'Bersih & mudah diakses', 'value' => 'bersih'],
                ['label' => 'Tertimbun tanah', 'value' => 'tertimbun'],
                ['label' => 'Ada kebocoran pipa', 'value' => 'bocor'],
            ]],
            ['tema' => 'Tagihan & Pembayaran', 'pertanyaan' => 'Apakah tagihan sesuai pemakaian?', 'tipe' => 'single_choice', 'poin' => 10, 'wajib' => true, 'urutan' => 5, 'opsi' => [
                ['label' => 'Sesuai', 'value' => 'sesuai'],
                ['label' => 'Terlalu mahal', 'value' => 'mahal'],
                ['label' => 'Tidak menentu', 'value' => 'tidak_menentu'],
            ]],
            ['tema' => 'Pelayanan', 'pertanyaan' => 'Apakah Anda mendapat info saat ada gangguan?', 'tipe' => 'single_choice', 'poin' => 5, 'wajib' => false, 'urutan' => 6, 'opsi' => [
                ['label' => 'Selalu dapat info', 'value' => 'dapat'],
                ['label' => 'Kadang-kadang', 'value' => 'kadang'],
                ['label' => 'Tidak pernah', 'value' => 'tidak_pernah'],
            ]],
            ['tema' => 'Tagihan & Pembayaran', 'pertanyaan' => 'Metode pembayaran yang digunakan?', 'tipe' => 'multiple_choice', 'poin' => 5, 'wajib' => false, 'urutan' => 7, 'opsi' => [
                ['label' => 'M-Banking / Transfer', 'value' => 'bank'],
                ['label' => 'PPOB / Minimarket', 'value' => 'ppob'],
                ['label' => 'Kantor Pusat/Cabang', 'value' => 'kantor'],
            ]],
            ['tema' => 'Umum', 'pertanyaan' => 'Apakah menggunakan sumber alternatif lain selain dari Instansi/Perusahaan?', 'tipe' => 'single_choice', 'poin' => 5, 'wajib' => false, 'urutan' => 8, 'opsi' => [
                ['label' => 'Hanya dari Instansi', 'value' => 'hanya_instansi'],
                ['label' => 'Sumur / Air tanah', 'value' => 'sumur'],
                ['label' => 'Air isi ulang', 'value' => 'isi_ulang'],
            ]],
            ['tema' => 'Umum', 'pertanyaan' => 'Apa perbaikan paling mendesak yang Anda harapkan?', 'tipe' => 'text', 'poin' => 0, 'wajib' => false, 'urutan' => 9],
            ['tema' => 'Pelayanan', 'pertanyaan' => 'Bagaimana kesan Anda terhadap pelayanan petugas lapangan?', 'tipe' => 'rating', 'poin' => 10, 'wajib' => true, 'urutan' => 10],
        ];

        foreach ($questions as $q) {
            Question::updateOrCreate(
                ['pertanyaan' => $q['pertanyaan']],
                array_merge($q, ['is_active' => true])
            );
        }

        $this->call([
            MasterDataSeeder::class,
        ]);
    }
}
