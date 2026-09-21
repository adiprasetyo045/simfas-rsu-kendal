<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kerusakan Fasilitas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; }
        h1 { font-size: 16px; text-align: center; margin-bottom: 5px; }
        .subtitle { text-align: center; font-size: 11px; color: #666; margin-bottom: 15px; }
        .meta { font-size: 9px; color: #999; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 5px 8px; text-align: left; }
        th { background: #0D6EFD; color: #fff; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        tr:nth-child(even) { background: #f9f9f9; }
        .stats { display: flex; margin-bottom: 15px; }
        .stat-box { border: 1px solid #ddd; padding: 8px 12px; margin-right: 10px; border-radius: 4px; text-align: center; }
        .stat-box .number { font-size: 18px; font-weight: bold; color: #0D6EFD; }
        .stat-box .label { font-size: 8px; color: #666; text-transform: uppercase; }
        .footer { text-align: center; font-size: 8px; color: #999; margin-top: 20px; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>
    <h1>LAPORAN KERUSAKAN FASILITAS RUMAH SAKIT</h1>
    <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</p>

    <table style="width:auto; border:none; margin-bottom:15px">
        <tr style="border:none">
            <td style="border:1px solid #ddd; padding:8px; text-align:center"><div style="font-size:18px; font-weight:bold; color:#0D6EFD">{{ $stats['total'] }}</div><div style="font-size:8px; color:#666">TOTAL</div></td>
            <td style="border:1px solid #ddd; padding:8px; text-align:center"><div style="font-size:18px; font-weight:bold; color:#198754">{{ $stats['completed'] }}</div><div style="font-size:8px; color:#666">SELESAI</div></td>
            <td style="border:1px solid #ddd; padding:8px; text-align:center"><div style="font-size:18px; font-weight:bold; color:#FFC107">{{ $stats['in_progress'] }}</div><div style="font-size:8px; color:#666">DIPROSES</div></td>
            <td style="border:1px solid #ddd; padding:8px; text-align:center"><div style="font-size:18px; font-weight:bold; color:#DC3545">{{ $stats['rejected'] }}</div><div style="font-size:8px; color:#666">DITOLAK</div></td>
            <td style="border:1px solid #ddd; padding:8px; text-align:center"><div style="font-size:18px; font-weight:bold; color:#6c757d">{{ $stats['pending'] }}</div><div style="font-size:8px; color:#666">PENDING</div></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th><th>No. Tiket</th><th>Tanggal</th><th>Pelapor</th><th>Ruangan</th>
                <th>Kategori</th><th>Prioritas</th><th>Status</th><th>Teknisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $i => $report)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $report->ticket_number }}</td>
                <td>{{ $report->reported_at->format('d/m/Y H:i') }}</td>
                <td>{{ $report->user->name }}</td>
                <td>{{ $report->room->name }}</td>
                <td>{{ $report->category->name }}</td>
                <td>{{ $report->priority->label() }}</td>
                <td>{{ $report->status->label() }}</td>
                <td>{{ $report->assignment?->technician?->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ $generatedAt }} | SIMFAS - RSU Muhammadiyah Kendal
    </div>
</body>
</html>
