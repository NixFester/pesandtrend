<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran Bimbel — {{ config('app.name') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #1a1a1a; padding: 32px; }
        .header { text-align: center; margin-bottom: 24px; border-bottom: 3px double #12462a; padding-bottom: 16px; }
        .header h1 { font-size: 20px; color: #12462a; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #666; }
        .section { margin-bottom: 16px; }
        .section-title { font-size: 12px; font-weight: 700; color: #12462a; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; border-bottom: 1px solid #e0ede4; padding-bottom: 4px; }
        .info-grid { display: grid; grid-template-columns: 160px 1fr; gap: 4px 12px; }
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
        <h1>{{ config('app.name') }}</h1>
        <p>Bukti Pembayaran Bimbel Online</p>
    </div>

    <div class="section">
        <div class="section-title">Informasi Booking</div>
        <div class="info-grid">
            <span class="info-label">Kode Booking</span>
            <span class="info-value">{{ $booking->code }}</span>

            <span class="info-label">Nama Klien</span>
            <span class="info-value">{{ $booking->client_name }}</span>

            <span class="info-label">Email</span>
            <span class="info-value">{{ $booking->client_email }}</span>

            <span class="info-label">No. WhatsApp</span>
            <span class="info-value">{{ $booking->client_whatsapp }}</span>

            <span class="info-label">Mentor</span>
            <span class="info-value">{{ $mentor->name }}</span>

            @if($booking->xendit_id)
            <span class="info-label">Invoice ID</span>
            <span class="info-value">{{ $booking->xendit_id }}</span>
            @endif

            <span class="info-label">Metode Pembayaran</span>
            <span class="info-value">{{ $booking->payment_method ?? 'Xendit' }}</span>
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
                <tr>
                    <td>Bimbel Online — {{ $mentor->name }}</td>
                    <td class="amount">Rp {{ number_format($booking->amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td>Total</td>
                    <td class="amount">Rp {{ number_format($booking->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($booking->isPaid())
    <div class="stamp">
        <div class="status">TERBAYAR</div>
        <div class="date">pada {{ $booking->paid_at ? $booking->paid_at->translatedFormat('d F Y, H:i') : now()->translatedFormat('d F Y, H:i') }} WIB</div>
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem Pesantrends.</p>
        <p>Harap disimpan sebagai bukti pembayaran yang sah.</p>
    </div>
</body>
</html>
