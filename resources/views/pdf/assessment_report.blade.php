<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Asesmen Ergonomi - {{ $assessment->name }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
            size: a4 portrait;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            color: #0369a1;
            letter-spacing: 1px;
        }
        .brand-subtitle {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-meta {
            text-align: right;
            font-size: 10px;
            color: #475569;
        }
        .doc-meta strong {
            color: #0f172a;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #f1f5f9;
            padding: 6px 10px;
            border-left: 4px solid #0284c7;
            margin-top: 14px;
            margin-bottom: 8px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
            font-size: 10.5px;
        }
        .info-label {
            width: 20%;
            color: #64748b;
            font-weight: bold;
        }
        .info-value {
            width: 30%;
            color: #0f172a;
        }

        /* Score Summary Box */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #cbd5e1;
        }
        .score-cell {
            width: 32%;
            background-color: #f8fafc;
            text-align: center;
            vertical-align: middle;
            padding: 15px;
            border-right: 1px solid #cbd5e1;
        }
        .score-big {
            font-size: 38px;
            font-weight: bold;
            color: #0369a1;
            margin: 4px 0;
        }
        .risk-cell {
            width: 68%;
            padding: 15px;
            vertical-align: middle;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
            margin-bottom: 6px;
        }
        .badge-low { background-color: #d1fae5; color: #065f46; border: 1px solid #34d399; }
        .badge-moderate { background-color: #fef3c7; color: #92400e; border: 1px solid #fbbf24; }
        .badge-high { background-color: #ffedd5; color: #9a3412; border: 1px solid #fb923c; }
        .badge-very_high { background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; }

        /* Assessment Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10px;
        }
        .data-table th {
            background-color: #e2e8f0;
            color: #334155;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
        }
        .data-table td {
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .severity-1 { color: #166534; font-weight: bold; }
        .severity-2 { color: #854d0e; font-weight: bold; }
        .severity-3 { color: #c2410c; font-weight: bold; }
        .severity-4 { color: #b91c1c; font-weight: bold; background-color: #fef2f2; }

        /* Smart Recommendations */
        .rec-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 4px solid #16a34a;
            padding: 8px 12px;
            margin-bottom: 6px;
            border-radius: 2px;
        }
        .rec-title {
            font-weight: bold;
            color: #166534;
            margin-bottom: 2px;
        }
        .rec-desc {
            font-size: 10px;
            color: #374151;
        }
        .rec-action {
            font-size: 9.5px;
            color: #0369a1;
            font-style: italic;
            margin-top: 2px;
        }

        .footer-note {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #cbd5e1;
            font-size: 8.5px;
            color: #94a3b8;
            text-align: justify;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="brand-title">STAFF-ERGO</div>
                <div class="brand-subtitle">Sistem Skrining Ergonomi & Kesehatan Kerja</div>
            </td>
            <td class="doc-meta" style="width: 40%;">
                <div><strong>No. Laporan:</strong> {{ $assessment->uuid }}</div>
                <div><strong>Tanggal:</strong> {{ $assessment->created_at->format('d/m/Y H:i') }} WIB</div>
                <div><strong>Status:</strong> Terverifikasi Sistem Otomatis</div>
            </td>
        </tr>
    </table>

    <!-- Profil Pegawai -->
    <div class="section-title">A. Profil & Demografi Pegawai</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Pegawai:</td>
            <td class="info-value"><strong>{{ $assessment->name }}</strong></td>
            <td class="info-label">Unit / Divisi:</td>
            <td class="info-value">{{ $assessment->department }}</td>
        </tr>
        <tr>
            <td class="info-label">Email:</td>
            <td class="info-value">{{ $assessment->email }}</td>
            <td class="info-label">Masa Kerja:</td>
            <td class="info-value">{{ $assessment->work_tenure }}</td>
        </tr>
        <tr>
            <td class="info-label">Usia / Gender:</td>
            <td class="info-value">{{ $assessment->age }} Tahun / {{ $assessment->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td class="info-label">Layar / Hari:</td>
            <td class="info-value">{{ $assessment->screen_time }}</td>
        </tr>
    </table>

    <!-- Ringkasan Hasil -->
    <div class="section-title">B. Ringkasan Tingkat Risiko Ergonomi</div>
    <table class="summary-table">
        <tr>
            <td class="score-cell">
                <div style="font-size: 9.5px; text-transform: uppercase; color: #64748b;">Total Skor Sistem</div>
                <div class="score-big">{{ $assessment->total_score }}</div>
                <div style="font-size: 8.5px; color: #94a3b8;">Rentang Skor: 13 - 48</div>
            </td>
            <td class="risk-cell">
                <div>
                    <span class="badge badge-{{ $assessment->risk_level }}">
                        {{ $assessment->risk_label }}
                    </span>
                </div>
                <div style="font-size: 10px; color: #334155; line-height: 1.5; margin-top: 4px;">
                    {{ $assessment->risk_description }}
                </div>
                <div style="font-size: 9.5px; color: #64748b; margin-top: 6px;">
                    <em>Subtotal Skor Keluhan Fisik (NBM): <strong>{{ $assessment->nbm_total_score }}/36</strong> | Subtotal Skor Fasilitas (ROSA): <strong>{{ $assessment->rosa_total_score }}/12</strong></em>
                </div>
            </td>
        </tr>
    </table>

    <!-- Detail NBM -->
    <div class="section-title">C. Rincian Keluhan Otot & Rangka (Nordic Body Map)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 48%;">Area Anatomi Tubuh</th>
                <th style="width: 16%;">Skor Terpilih</th>
                <th style="width: 30%;">Tingkat Keparahan / Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nbmParts as $index => $part)
                @php
                    $val = $assessment->nbm_responses[$part['id']] ?? 1;
                    $scale = $nbmScales[$val] ?? null;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $part['name'] }}</td>
                    <td style="text-align: center;" class="severity-{{ $val }}">Skor {{ $val }}</td>
                    <td class="severity-{{ $val }}">{{ $scale ? $scale['label'] . ' (' . $scale['desc'] . ')' : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Detail ROSA -->
    <div class="section-title">D. Rincian Postur & Workstation (ROSA Modifikasi)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 34%;">Faktor Lingkungan / Postur</th>
                <th style="width: 14%;">Skor Evaluasi</th>
                <th style="width: 46%;">Kondisi Nyata di Tempat Kerja</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rosaQuestions as $index => $q)
                @php
                    $optVal = $assessment->rosa_responses[$q['id']] ?? 1;
                    $selectedOpt = collect($q['options'])->firstWhere('val', $optVal);
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $q['title'] }}</strong></td>
                    <td style="text-align: center;">
                        <span class="severity-{{ $optVal == 1 ? '1' : ($optVal == 2 ? '2' : '4') }}">
                            Skor {{ $optVal }} / 3
                        </span>
                    </td>
                    <td>{{ $selectedOpt ? $selectedOpt['text'] : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Smart Recommendations (If any) -->
    @if(!empty($assessment->smart_recommendations) && count($assessment->smart_recommendations) > 0)
        <div class="section-title">E. Rekomendasi Tindakan & Edukasi Khusus</div>
        @foreach($assessment->smart_recommendations as $rec)
            <div class="rec-box">
                <div class="rec-title">{{ $rec['title'] }}</div>
                <div class="rec-desc">{{ $rec['desc'] }}</div>
                <div class="rec-action">Langkah Terapi Mandiri: {{ $rec['action'] }}</div>
            </div>
        @endforeach
    @endif

    <!-- Footer Note -->
    <div class="footer-note">
        <strong>Pernyataan K3 & Medis:</strong> Hasil skrining ini merupakan asesmen awal berbasis instrumen ergonomi standar Nordic Body Map (NBM) dan Rapid Office Strain Assessment (ROSA). Dokumen ini diterbitkan secara otomatis oleh sistem STAFF-ERGO untuk memandu intervensi pencegahan dini cedera kerja. Apabila keluhan nyeri menetap atau tergolong dalam kategori Risiko Tinggi / Sangat Tinggi, staf disarankan untuk melakukan konsultasi langsung dengan dokter okupasi atau tim Keselamatan & Kesehatan Kerja (K3).
    </div>

</body>
</html>
