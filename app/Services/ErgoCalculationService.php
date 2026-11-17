<?php

namespace App\Services;

class ErgoCalculationService
{
    /**
     * Definition of Nordic Body Map (NBM) anatomical areas
     */
    public const NBM_PARTS = [
        ['id' => 'b1', 'name' => 'Leher bagian atas & bawah (Neck)'],
        ['id' => 'b2', 'name' => 'Bahu kiri & kanan (Shoulders)'],
        ['id' => 'b3', 'name' => 'Lengan atas & siku (Upper Arms & Elbows)'],
        ['id' => 'b4', 'name' => 'Punggung atas (Upper Back)'],
        ['id' => 'b5', 'name' => 'Punggung bawah / pinggang (Lower Back)'],
        ['id' => 'b6', 'name' => 'Lengan bawah & pergelangan tangan (Wrists/Hands)'],
        ['id' => 'b7', 'name' => 'Bokong & paha (Buttocks & Thighs)'],
        ['id' => 'b8', 'name' => 'Lutut kiri & kanan (Knees)'],
        ['id' => 'b9', 'name' => 'Betis, pergelangan kaki & telapak kaki (Ankles/Feet)'],
    ];

    public const NBM_SCALES = [
        ['val' => 1, 'label' => 'Tidak Sakit', 'desc' => 'Nyaman'],
        ['val' => 2, 'label' => 'Agak Sakit', 'desc' => 'Pegal ringan'],
        ['val' => 3, 'label' => 'Sakit', 'desc' => 'Cukup mengganggu'],
        ['val' => 4, 'label' => 'Sangat Sakit', 'desc' => 'Nyeri hebat'],
    ];

    /**
     * Definition of ROSA (Rapid Office Strain Assessment) parameters
     */
    public const ROSA_QUESTIONS = [
        [
            'id' => 'c1',
            'title' => 'Posisi Kursi & Kaki',
            'options' => [
                ['val' => 1, 'text' => 'Telapak kaki rata, lutut 90°, punggung bersandar penuh.'],
                ['val' => 2, 'text' => 'Kaki menapak, sandaran lumbar tidak menopang / jarang digunakan.'],
                ['val' => 3, 'text' => 'Kaki menggantung / duduk membungkuk tanpa sandaran.'],
            ],
        ],
        [
            'id' => 'c2',
            'title' => 'Posisi Layar Monitor / Laptop',
            'options' => [
                ['val' => 1, 'text' => 'Atas layar sejajar tinggi mata, jarak 1 lengan (45–70 cm).'],
                ['val' => 2, 'text' => 'Terlalu rendah/tinggi (leher menunduk/mendongak ringan 20°).'],
                ['val' => 3, 'text' => 'Laptop tanpa stand (menunduk tajam) / layar di samping (menoleh).'],
            ],
        ],
        [
            'id' => 'c3',
            'title' => 'Posisi Keyboard, Mouse, dan Pergelangan Tangan',
            'options' => [
                ['val' => 1, 'text' => 'Siku 90° di samping badan, pergelangan tangan lurus.'],
                ['val' => 2, 'text' => 'Mouse terlalu jauh (bahu menjangkau ke depan/samping).'],
                ['val' => 3, 'text' => 'Pergelangan menekuk tajam atau bertumpu tepi meja tajam lama.'],
            ],
        ],
        [
            'id' => 'c4',
            'title' => 'Kebiasaan Istirahat (Micro-Break)',
            'options' => [
                ['val' => 1, 'text' => 'Peregangan atau berdiri setiap 30–60 menit.'],
                ['val' => 2, 'text' => 'Berdiri/istirahat hanya setiap 2–3 jam.'],
                ['val' => 3, 'text' => 'Duduk terus-menerus > 3 jam tanpa berpindah.'],
            ],
        ],
    ];

    /**
     * Calculate NBM total score
     */
    public function calculateNbmScore(array $inputs): int
    {
        $score = 0;
        foreach (self::NBM_PARTS as $part) {
            $score += (int) ($inputs[$part['id']] ?? 1);
        }
        return $score;
    }

    /**
     * Calculate ROSA total score
     */
    public function calculateRosaScore(array $inputs): int
    {
        $score = 0;
        foreach (self::ROSA_QUESTIONS as $q) {
            $score += (int) ($inputs[$q['id']] ?? 1);
        }
        return $score;
    }

    /**
     * Determine risk category based on total score (13 - 48)
     */
    public function determineRisk(int $score): array
    {
        if ($score <= 20) {
            return [
                'level' => 'low',
                'label' => 'Risiko Rendah (Low Risk)',
                'colorClass' => 'border-emerald-500',
                'bgClass' => 'bg-emerald-500',
                'textClass' => 'text-emerald-400',
                'badgeClass' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40',
                'shadowClass' => 'shadow-[0_0_15px_rgba(16,185,129,0.4)]',
                'hexColor' => '#10b981',
                'rec' => 'Postur kerja sudah cukup ergonomis dan keluhan minimal. Pertahankan posisi kerja, lakukan micro-break stretching rutin 2 menit setiap 1 jam.',
            ];
        }

        if ($score <= 29) {
            return [
                'level' => 'moderate',
                'label' => 'Risiko Sedang (Moderate Risk)',
                'colorClass' => 'border-yellow-400',
                'bgClass' => 'bg-yellow-400',
                'textClass' => 'text-yellow-300',
                'badgeClass' => 'bg-yellow-500/20 text-yellow-300 border-yellow-400/40',
                'shadowClass' => 'shadow-[0_0_15px_rgba(250,204,21,0.4)]',
                'hexColor' => '#facc15',
                'rec' => 'Mulai ditemukan kelelahan otot lokal dan ketidaksesuaian meja/kursi. Lihat panduan penyesuaian dan paket latihan peregangan spesifik.',
            ];
        }

        if ($score <= 38) {
            return [
                'level' => 'high',
                'label' => 'Risiko Tinggi (High Risk)',
                'colorClass' => 'border-orange-500',
                'bgClass' => 'bg-orange-500',
                'textClass' => 'text-orange-400',
                'badgeClass' => 'bg-orange-500/20 text-orange-300 border-orange-500/40',
                'shadowClass' => 'shadow-[0_0_15px_rgba(249,115,22,0.4)]',
                'hexColor' => '#f97316',
                'rec' => 'Keluhan nyeri cukup signifikan dan berpotensi menjadi cedera kronis. Wajib dilakukan penyesuaian workstation segera, latihan terapi peregangan 2 kali sehari, serta notifikasi evaluasi ke petugas K3.',
            ];
        }

        return [
            'level' => 'very_high',
            'label' => 'Risiko Sangat Tinggi (Very High Risk)',
            'colorClass' => 'border-red-600',
            'bgClass' => 'bg-red-600',
            'textClass' => 'text-red-400',
            'badgeClass' => 'bg-red-500/20 text-red-300 border-red-500/40',
            'shadowClass' => 'shadow-[0_0_15px_rgba(220,38,38,0.6)]',
            'hexColor' => '#dc2626',
            'rec' => 'Gangguan muskuloskeletal berat yang mengganggu produktivitas kerja. Sistem mengirim peringatan merah ke dasbor K3/HRD untuk investigasi ergonomi langsung dan rujukan medis.',
        ];
    }

    /**
     * Evaluate rule engine for smart recommendations
     */
    public function evaluateSmartRules(array $inputs): array
    {
        $rules = [];

        // Rule 1: Neck > 2 and Screen == 3
        if ((int) ($inputs['b1'] ?? 0) > 2 && (int) ($inputs['c2'] ?? 0) === 3) {
            $rules[] = [
                'id' => 'rule_neck',
                'title' => 'Edukasi: Cara Mengatur Tinggi Layar Eye-Level',
                'desc' => 'Posisi layar Anda menyebabkan tekanan pada leher. Naikkan monitor atau gunakan penyangga laptop.',
                'action' => 'Video Neck Isometric & Chin Tuck Stretch (60 detik)',
            ];
        }

        // Rule 2: Lower Back > 3 and Chair == 3
        if ((int) ($inputs['b5'] ?? 0) > 3 && (int) ($inputs['c1'] ?? 0) === 3) {
            $rules[] = [
                'id' => 'rule_lumbar',
                'title' => 'Edukasi: Penempatan Bantal Penyangga Lumbar',
                'desc' => 'Kurangnya dukungan pada punggung bawah memicu nyeri hebat. Terapkan Posisi Duduk 90-90-90.',
                'action' => 'Video Seated Cat-Cow & Pelvic Tilt Stretch',
            ];
        }

        // Rule 3: Wrist > 3
        if ((int) ($inputs['b6'] ?? 0) > 3) {
            $rules[] = [
                'id' => 'rule_wrist',
                'title' => 'Edukasi: Posisi Netral Pergelangan Tangan',
                'desc' => 'Risiko tinggi pada pergelangan tangan. Evaluasi posisi keyboard & mouse agar sejajar tanpa tekukan tajam.',
                'action' => 'Video Wrist Flexor/Extensor Stretch',
            ];
        }

        return $rules;
    }
}
