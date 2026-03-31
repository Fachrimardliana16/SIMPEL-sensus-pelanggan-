<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Sensus — {{ $record->nama ?? 'Data Sensus' }}</title>
    <style>
        @page { margin: 20mm 18mm; }
        * { box-sizing: border-box; }
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 10px; 
            color: #1e293b; 
            line-height: 1.5;
            background: #fff;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #0369a1;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .header-left h1 { margin: 0 0 2px; font-size: 16px; color: #0369a1; font-weight: 800; }
        .header-left p  { margin: 0; font-size: 9px; color: #64748b; }
        .header-right   { text-align: right; font-size: 9px; color: #64748b; }
        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }
        .badge-valid   { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-pending { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
        .badge-revisi  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── SECTION BLOCKS ── */
        .section { margin-bottom: 14px; }
        .section-title {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.6px; color: #0369a1;
            border-bottom: 1px solid #bfdbfe;
            padding-bottom: 4px; margin-bottom: 8px;
        }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0 15px; }

        .field { margin-bottom: 7px; }
        .field label { display: block; font-size: 8.5px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; }
        .field span  { display: block; font-size: 10px; font-weight: 500; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding: 2px 0; min-height: 16px; }

        /* ── QA TABLE ── */
        .qa-table { width: 100%; border-collapse: collapse; font-size: 9.5px; }
        .qa-table th { background: #0369a1; color: #fff; padding: 5px 8px; text-align: left; }
        .qa-table td { padding: 4px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .qa-table tr:nth-child(even) td { background: #f8fafc; }
        .qa-num { width: 24px; color: #94a3b8; font-weight: 700; }
        .qa-q   { width: 55%; color: #334155; }
        .qa-a   { color: #0f172a; font-weight: 600; }

        /* ── SCORE BOX ── */
        .score-box {
            display: inline-block;
            background: #eff6ff;
            border: 2px solid #93c5fd;
            border-radius: 8px;
            padding: 6px 18px;
            text-align: center;
            margin-top: 6px;
        }
        .score-box .num  { font-size: 22px; font-weight: 800; color: #1d4ed8; display: block; }
        .score-box .lbl  { font-size: 8px; color: #64748b; display: block; }

        /* ── NOTES ── */
        .notes-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 4px solid #f59e0b;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 9.5px;
            color: #78350f;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 24px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 8.5px;
            color: #94a3b8;
        }
        .signature-box {
            text-align: center;
            font-size: 8.5px;
            color: #334155;
        }
        .signature-box .sig-line {
            width: 140px;
            border-bottom: 1px solid #334155;
            margin: 40px auto 3px;
        }

        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-left">
            <h1>📋 Laporan Data Sensus Pelanggan</h1>
            <p>SIMPEL — Sensus Informasi & Manajemen Pelanggan</p>
        </div>
        <div class="header-right">
            <span class="badge badge-{{ $record->census_status ?? 'pending' }}">
                {{ strtoupper($record->census_status ?? 'PENDING') }}
            </span>
            <br><br>
            <span>Dicetak: {{ now()->format('d M Y, H:i') }}</span><br>
            <span>Surveyor: {{ $record->surveyor?->name ?? '-' }}</span>
        </div>
    </div>

    {{-- ── IDENTITAS PELANGGAN ── --}}
    <div class="section">
        <div class="section-title">Identitas Pelanggan</div>
        <div class="grid-3">
            <div class="field"><label>No. Langganan</label><span>{{ $record->nolangg ?? '-' }}</span></div>
            <div class="field"><label>Nama Lengkap</label><span>{{ $record->nama ?? '-' }}</span></div>
            <div class="field"><label>Telepon</label><span>{{ $record->telepon ?? '-' }}</span></div>
        </div>
        <div class="field"><label>Alamat Lengkap</label><span>{{ $record->alamat ?? '-' }}</span></div>
        <div class="grid-3">
            <div class="field"><label>Kelurahan</label><span>{{ $record->KEL ?? '-' }}</span></div>
            <div class="field"><label>Kecamatan</label><span>{{ $record->kecamatan ?? '-' }}</span></div>
            <div class="field"><label>Kode Unit</label><span>{{ $record->kode_unit ?? '-' }}</span></div>
        </div>
    </div>

    {{-- ── TEKNIS & METER ── --}}
    <div class="section">
        <div class="section-title">Info Teknis & Meter</div>
        <div class="grid-3">
            <div class="field"><label>No. Meter</label><span>{{ $record->nometer ?? '-' }}</span></div>
            <div class="field"><label>Merk Meter</label><span>{{ $record->merk_meter ?? '-' }}</span></div>
            <div class="field"><label>Diameter</label><span>{{ $record->diameter ?? '-' }}</span></div>
            <div class="field"><label>Tarif/Golongan</label><span>{{ $record->tarif ?? '-' }}</span></div>
            <div class="field"><label>Jenis Pelayanan</label><span>{{ $record->jenis_pelayanan ?? '-' }}</span></div>
            <div class="field">
                <label>Status Pelayanan</label>
                <span>
                    <span class="badge badge-{{ $record->pdam_status === 'aktif' ? 'valid' : 'revisi' }}">
                        {{ strtoupper($record->pdam_status ?? 'N/A') }}
                    </span>
                </span>
            </div>
        </div>
    </div>

    {{-- ── KOORDINAT ── --}}
    @if($record->lati && $record->longi)
    <div class="section">
        <div class="section-title">Koordinat Lokasi</div>
        <div class="grid-3">
            <div class="field"><label>Latitude</label><span>{{ $record->lati }}</span></div>
            <div class="field"><label>Longitude</label><span>{{ $record->longi }}</span></div>
            <div class="field"><label>Ketinggian (Alti)</label><span>{{ $record->alti ?? '-' }}</span></div>
        </div>
    </div>
    @endif

    {{-- ── JAWABAN KUESIONER ── --}}
    @php
        $answers = collect($record->answers ?? [])
            ->filter(fn($v, $k) => str_starts_with($k, 'q_'))
            ->map(function($value, $key) {
                $id = str_replace('q_', '', $key);
                $question = \App\Models\Question::find($id);
                return [
                    'question' => $question?->pertanyaan ?? "Pertanyaan #{$id}",
                    'answer'   => is_array($value) ? implode(', ', $value) : ($value ?? '-'),
                    'urutan'   => $question?->urutan ?? 99,
                ];
            })
            ->filter()
            ->sortBy('urutan')
            ->values();
    @endphp

    @if($answers->count() > 0)
    <div class="section">
        <div class="section-title">Hasil Wawancara / Kuesioner</div>
        <table class="qa-table">
            <thead>
                <tr>
                    <th class="qa-num">#</th>
                    <th class="qa-q">Pertanyaan</th>
                    <th class="qa-a">Jawaban</th>
                </tr>
            </thead>
            <tbody>
                @foreach($answers as $i => $qa)
                <tr>
                    <td class="qa-num">{{ $i + 1 }}</td>
                    <td class="qa-q">{{ $qa['question'] }}</td>
                    <td class="qa-a">{{ $qa['answer'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ── VALIDASI & CATATAN ── --}}
    <div class="section">
        <div class="section-title">Validasi & Catatan</div>
        <div style="display: flex; align-items: flex-start; gap: 20px;">
            <div class="score-box">
                <span class="num">{{ $record->total_points ?? 0 }}</span>
                <span class="lbl">Total Skor</span>
            </div>
            <div style="flex: 1;">
                @if($record->census_notes)
                    <div class="notes-box">{{ $record->census_notes }}</div>
                @else
                    <p style="color: #94a3b8; font-style: italic; font-size: 9px;">Tidak ada catatan tambahan.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ── FOOTER / TANDA TANGAN ── --}}
    <div class="footer">
        <div>
            <p style="margin:0;">SIMPEL &copy; {{ date('Y') }} — Dokumen ini digenerate secara otomatis oleh sistem.</p>
            <p style="margin:0;">Halaman ini bersifat resmi dan merupakan bagian dari arsip digital Instansi/Perusahaan.</p>
        </div>
        <div class="signature-box">
            <div class="sig-line"></div>
            <p style="margin:0; font-weight:700;">Mengetahui,</p>
            <p style="margin:0; color:#64748b;">Kepala Unit / Analyst</p>
        </div>
    </div>

</body>
</html>
