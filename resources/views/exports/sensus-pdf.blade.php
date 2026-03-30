<!DOCTYPE html>
<html>
<head>
    <title>Laporan Sensus Pelanggan</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; color: #666; }
        .meta { margin-bottom: 15px; background: #f9f9f9; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        table th { background: #eee; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        .status-badge { padding: 2px 5px; border-radius: 3px; font-weight: bold; font-size: 8px; color: #fff; }
        .status-valid { background-color: #22c55e; }
        .status-pending { background-color: #f59e0b; }
        .status-revisi { background-color: #ef4444; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 8px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Sensus Pelanggan</h1>
        <p>PDAM Tirta Perwira Purbalingga - Sistem SIMPEL</p>
    </div>

    <div class="meta">
        <strong>Periode:</strong> {{ $period }}<br>
        <strong>Surveyor:</strong> {{ $surveyor_name }}<br>
        <strong>Total Data:</strong> {{ $data->count() }} Entri
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="70">No. Pel</th>
                <th>Nama Pelanggan</th>
                <th>Alamat / Unit</th>
                <th width="60">Status Review</th>
                <th width="40">Poin</th>
                <th width="80">Tgl Input</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nolangg }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->alamat }} ({{ $item->kode_unit }})</td>
                <td>
                    <span class="status-badge status-{{ $item->census_status }}">
                        {{ strtoupper($item->census_status) }}
                    </span>
                </td>
                <td>{{ $item->total_points }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }} | SIMPEL v1.0.2 | Created by fachrimardliana
    </div>
</body>
</html>
