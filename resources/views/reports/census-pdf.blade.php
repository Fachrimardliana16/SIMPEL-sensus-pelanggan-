<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Sensus PDAM</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #0d9488; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; color: #0d9488; }
        .header p { margin: 2px 0; color: #666; font-size: 10px; }
        .meta { margin-bottom: 15px; font-size: 10px; color: #888; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #0d9488; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) { background: #f9fafb; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-valid { background: #d1fae5; color: #065f46; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-revisi { background: #fee2e2; color: #991b1b; }
        .footer { text-align: center; font-size: 9px; color: #aaa; margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 8px; }
        .stats { display: flex; margin-bottom: 15px; }
        .stat-box { border: 1px solid #e5e7eb; padding: 8px 15px; margin-right: 10px; border-radius: 6px; text-align: center; }
        .stat-box .num { font-size: 20px; font-weight: bold; color: #0d9488; }
        .stat-box .label { font-size: 9px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Laporan Sensus Pelanggan PDAM</h1>
        <p>SurveiPro — Sistem Sensus Digital</p>
        <p>{{ $title ?? 'Laporan Sensus' }}</p>
    </div>

    <div class="meta">
        Dicetak: {{ now()->format('d M Y H:i') }} |
        Total Data: {{ count($records) }} |
        @if(isset($surveyor)) Surveyor: {{ $surveyor }} @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>No. Pel</th>
                <th>Nama</th>
                <th>Kelurahan</th>
                <th>No. Meter</th>
                <th>Status PDAM</th>
                <th>Validasi</th>
                <th>Tgl Input</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r->id_pelanggan ?? '-' }}</td>
                <td>{{ $r->nama ?? '-' }}</td>
                <td>{{ $r->KEL ?? '-' }}</td>
                <td>{{ $r->nometer ?? '-' }}</td>
                <td>{{ $r->pdam_status ?? '-' }}</td>
                <td>
                    <span class="badge badge-{{ $r->census_status ?? 'pending' }}">
                        {{ ucfirst($r->census_status ?? 'pending') }}
                    </span>
                </td>
                <td>{{ $r->created_at?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        SurveiPro &copy; {{ date('Y') }} — Dicetak secara otomatis oleh sistem
    </div>
</body>
</html>
