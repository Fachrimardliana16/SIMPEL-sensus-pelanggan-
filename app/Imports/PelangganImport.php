<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Str;

class PelangganImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        $idPelanggan = trim($row['id_pelanggan'] ?? $row['id_pel'] ?? $row['no_pel'] ?? '');
        
        if (empty($idPelanggan)) {
            return null;
        }

        $customer = Customer::where('id_pelanggan', $idPelanggan)->first();

        if (!$customer) {
            $customer = new Customer();
            $customer->id = (string) Str::uuid();
            $customer->id_pelanggan = $idPelanggan;
        }

        $unitCode = trim($row['kode_unit'] ?? $row['unit'] ?? '');
        if (is_numeric($unitCode) && strlen($unitCode) < 2) {
            $unitCode = str_pad($unitCode, 2, '0', STR_PAD_LEFT);
        }

        $customer->fill([
            'nolangg' => $row['nolangg'] ?? $row['no_langganan'] ?? null,
            'tahun' => $row['tahun'] ?? null,
            'nama' => $row['nama'] ?? 'TANPA NAMA',
            'alamat' => $row['alamat'] ?? null,
            'telepon' => $row['telepon'] ?? $row['no_telp'] ?? null,
            'status' => $row['status'] ?? 'aktif',
            'tarif' => $row['tarif'] ?? $row['golongan'] ?? null,
            'nometer' => $row['nometer'] ?? $row['no_meter'] ?? null,
            'merk_meter' => $row['merk_meter'] ?? null,
            'diameter' => $row['diameter'] ?? null,
            'jenis_pelayanan' => $row['jenis_pelayanan'] ?? null,
            'KEL' => $row['kel'] ?? $row['kelurahan'] ?? null,
            'kode_unit' => $unitCode,
            'kode_alamat' => $row['kode_alamat'] ?? null,
            'kas' => $row['kas'] ?? null,
            'lati' => $row['lati'] ?? $row['latitude'] ?? null,
            'longi' => $row['longi'] ?? $row['longitude'] ?? null,
        ]);

        return $customer;
    }

    public function rules(): array
    {
        return [
            'id_pelanggan' => 'required',
            'nama' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'id_pelanggan.required' => 'Kolom ID Pelanggan wajib diisi.',
            'nama.required' => 'Kolom Nama wajib diisi.',
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
