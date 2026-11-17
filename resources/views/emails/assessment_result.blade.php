<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Skrining Ergonomi - STAFF-ERGO</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #38bdf8;
            padding: 30px 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            letter-spacing: 2px;
            color: #06b6d4;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 13px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            padding: 30px 24px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #0f172a;
        }
        .score-box {
            margin: 20px 0;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .score-number {
            font-size: 40px;
            font-weight: bold;
            color: #0284c7;
            margin: 8px 0;
        }
        .risk-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 6px;
        }
        .risk-low { background-color: #d1fae5; color: #065f46; border: 1px solid #34d399; }
        .risk-moderate { background-color: #fef3c7; color: #92400e; border: 1px solid #fbbf24; }
        .risk-high { background-color: #ffedd5; color: #9a3412; border: 1px solid #fb923c; }
        .risk-very_high { background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }
        .details-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .details-table td:first-child {
            color: #64748b;
            width: 40%;
        }
        .details-table td:last-child {
            font-weight: 600;
            color: #1e293b;
        }
        .attachment-note {
            background-color: #e0f2fe;
            border-left: 4px solid #0284c7;
            padding: 14px 16px;
            border-radius: 4px;
            margin-top: 24px;
            font-size: 13px;
            color: #0369a1;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>STAFF-ERGO</h1>
            <p>Sistem Skrining Ergonomi & Kesehatan Kerja</p>
        </div>
        <div class="content">
            <div class="greeting">Halo, {{ $assessment->name }}</div>
            <p style="line-height: 1.6; margin-top: 0;">
                Terima kasih telah berpartisipasi melakukan pengisian skrining ergonomi mandiri di tempat kerja. Sistem kami telah melakukan komputasi skor Nordic Body Map (NBM) dan Rapid Office Strain Assessment (ROSA) dari data yang Anda masukkan.
            </p>

            <div class="score-box">
                <div style="font-size: 12px; text-transform: uppercase; color: #64748b; letter-spacing: 1px;">Total Skor Sistem (Rentang 13 - 48)</div>
                <div class="score-number">{{ $assessment->total_score }}</div>
                <div>
                    <span class="risk-badge risk-{{ $assessment->risk_level }}">
                        {{ $assessment->risk_label }}
                    </span>
                </div>
                <p style="font-size: 13px; color: #475569; margin: 12px 10px 0; line-height: 1.5;">
                    {{ $assessment->risk_description }}
                </p>
            </div>

            <table class="details-table">
                <tr>
                    <td>Nomor Referensi</td>
                    <td>{{ $assessment->uuid }}</td>
                </tr>
                <tr>
                    <td>Waktu Asesmen</td>
                    <td>{{ $assessment->created_at->format('d F Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <td>Unit / Divisi Kerja</td>
                    <td>{{ $assessment->department }}</td>
                </tr>
                <tr>
                    <td>Subtotal NBM (Keluhan Tubuh)</td>
                    <td>{{ $assessment->nbm_total_score }} / 36</td>
                </tr>
                <tr>
                    <td>Subtotal ROSA (Fasilitas Kerja)</td>
                    <td>{{ $assessment->rosa_total_score }} / 12</td>
                </tr>
            </table>

            <div class="attachment-note">
                <strong>📎 Laporan PDF Terlampir:</strong><br>
                Salinan lengkap <em>Official Workplace Ergonomic Assessment Report</em> berformat PDF telah dilampirkan pada email ini untuk keperluan arsip pribadi atau tindak lanjut K3 / Medical Check-up.
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} STAFF-ERGO. Sistem Manajemen Kesehatan & Keselamatan Kerja (K3).<br>
            Email ini dihasilkan secara otomatis oleh sistem, mohon untuk tidak membalas email ini.
        </div>
    </div>
</body>
</html>
