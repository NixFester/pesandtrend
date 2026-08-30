<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran — {{ $school->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #1a1a1a; padding: 32px; }
        .header { text-align: center; margin-bottom: 24px; border-bottom: 3px double #12462a; padding-bottom: 16px; }
        .header h1 { font-size: 20px; color: #12462a; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #666; }
        .section { margin-bottom: 16px; }
        .section-title { font-size: 12px; font-weight: 700; color: #12462a; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; border-bottom: 1px solid #e0ede4; padding-bottom: 4px; }
        .info-grid { display: grid; grid-template-columns: 140px 1fr; gap: 4px 12px; }
        .info-label { color: #666; font-size: 11px; }
        .info-value { font-weight: 600; }
        table.breakdown { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.breakdown th, table.breakdown td { border: 1px solid #c3dccb; padding: 6px 10px; text-align: left; font-size: 12px; }
        table.breakdown th { background: #f2f7f4; color: #12462a; font-weight: 700; }
        table.breakdown td.amount { text-align: right; font-family: monospace; }
        table.breakdown tr.total { background: #12462a; color: white; font-weight: 700; }
        table.breakdown tr.total td { border-color: #12462a; }
        .stamp { text-align: center; margin-top: 24px; padding: 12px; border: 2px solid #4d8669; border-radius: 8px; color: #4d8669; }
        .stamp .status { font-size: 22px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; }
        .stamp .date { font-size: 11px; margin-top: 4px; }
        .footer { text-align: center; margin-top: 32px; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $school->name }}</h1>
        <p>{{ $school->address }}</p>
    </div>

    <div class="section">
        <div class="section-title">Bukti Pembayaran Pendaftaran</div>
        <div class="info-grid">
            <span class="info-label">Kode Pendaftaran</span>
            <span class="info-value">{{ $application->public_id }}</span>

            <span class="info-label">Nama Siswa</span>
            <span class="info-value">{{ $application->student_name }}</span>

            <span class="info-label">Nama Orang Tua</span>
            <span class="info-value">{{ $application->parent_name }}</span>

            <span class="info-label">Jenjang</span>
            <span class="info-value">{{ $application->target_jenjang ?? '-' }}</span>

            <span class="info-label">Tanggal Daftar</span>
            <span class="info-value">{{ $application->created_at->translatedFormat('d F Y') }}</span>

            @if($payment->external_id)
            <span class="info-label">Invoice ID</span>
            <span class="info-value">{{ $payment->external_id }}</span>
            @endif

            <span class="info-label">Metode Pembayaran</span>
            <span class="info-value">{{ $payment->provider === 'manual' ? 'Manual / Tunai' : ($payment->payment_method ?? 'Xendit') }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Rincian Biaya</div>
        <table class="breakdown">
            <thead>
                <tr>
                    <th>Komponen</th>
                    <th style="text-align:right">Jumlah (IDR)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payment->breakdown_json as $key => $amount)
                <tr>
                    <td>{{ str_replace('_', ' ', ucwords($key, '_')) }}</td>
                    <td class="amount">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total">
                    <td>Total</td>
                    <td class="amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($payment->isPaid())
    <div class="stamp">
        <div class="status">✓ Terbayar</div>
        <div class="date">pada {{ $payment->paid_at->translatedFormat('d F Y, H:i') }} WIB</div>
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem Pesantrends.</p>
        <p>Harap disimpan sebagai bukti pembayaran yang sah.</p>
    </div>
</body>
</html>
