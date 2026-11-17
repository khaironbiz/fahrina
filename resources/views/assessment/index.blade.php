<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>STAFF-ERGO | Sistem Skrining Ergonomi Tempat Kerja</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 for Modern Glass Notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #020617; /* Deep slate */
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(6, 182, 212, 0.08), transparent 30%),
                radial-gradient(circle at 85% 30%, rgba(139, 92, 246, 0.08), transparent 30%),
                linear-gradient(rgba(2, 6, 23, 0.9), rgba(2, 6, 23, 0.9)),
                repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(255,255,255,0.01) 2px, rgba(255,255,255,0.01) 4px);
            background-attachment: fixed;
            color: #f8fafc;
        }

        .font-heading {
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 0.05em;
        }

        .glass-panel {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(148, 163, 184, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .glass-input {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.2);
            transition: all 0.3s ease;
        }
        
        .glass-input:focus {
            outline: none;
            border-color: #06b6d4;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
            background: rgba(30, 41, 59, 0.8);
        }

        /* Neon Text Utilities */
        .neon-text-cyan { text-shadow: 0 0 10px rgba(6, 182, 212, 0.6); }
        .neon-text-purple { text-shadow: 0 0 10px rgba(168, 85, 247, 0.6); }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }

        /* Hexagon button effect */
        .btn-cyber {
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }
        .btn-cyber::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: all 0.5s;
        }
        .btn-cyber:hover::before { left: 100%; }

        /* Custom SweetAlert Glass Theme */
        .swal2-popup.swal-custom-dark {
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(6, 182, 212, 0.4);
            color: #f8fafc !important;
            border-radius: 16px;
            box-shadow: 0 0 30px rgba(6, 182, 212, 0.3);
        }
        .swal2-popup.swal-custom-dark .swal2-title {
            color: #38bdf8 !important;
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 1px;
        }
    </style>
</head>
<body class="min-h-screen p-4 md:p-8 antialiased selection:bg-cyan-500/30 selection:text-cyan-200">

    <div class="max-w-5xl mx-auto space-y-8">
        
        <!-- HEADER -->
        <header class="text-center py-8 relative">
            <div class="absolute inset-0 flex justify-center items-center opacity-20 pointer-events-none">
                <div class="w-64 h-64 bg-cyan-500 rounded-full blur-[100px]"></div>
            </div>
            <div class="inline-block px-4 py-1.5 rounded-full border border-cyan-500/30 bg-cyan-950/40 text-cyan-300 text-xs font-semibold uppercase tracking-wider mb-3">
                Occupational Health & Ergonomic Assessment
            </div>
            <h1 class="font-heading text-5xl md:text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-sky-300 to-blue-500 neon-text-cyan relative z-10">
                STAFF-ERGO
            </h1>
            <p class="font-heading text-xl md:text-2xl text-slate-300 mt-2 tracking-widest uppercase relative z-10">
                Sistem Skrining Ergonomi & Muskuloskeletal Tempat Kerja
            </p>
        </header>

        <!-- MAIN FORM -->
        <form id="ergoForm" action="{{ route('assessment.store') }}" method="POST" class="space-y-8 relative z-10">
            @csrf
            
            <!-- SECTION A: Demografi & Profil Pekerjaan -->
            <section class="glass-panel rounded-2xl p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 border-b border-slate-700/50 pb-4">
                    <div class="w-8 h-8 rounded bg-cyan-500/20 border border-cyan-500/50 flex items-center justify-center text-cyan-400 font-heading font-bold">A</div>
                    <div>
                        <h2 class="font-heading text-2xl font-semibold text-slate-100 uppercase tracking-wide">Data Demografi & Profil Pekerjaan</h2>
                        <p class="text-xs text-slate-400">Informasi profil pegawai untuk penerbitan laporan resmi K3</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama / Inisial -->
                    <div>
                        <label class="block text-sm text-slate-300 mb-2 font-medium">Nama / Inisial Pegawai <span class="text-cyan-400">*</span></label>
                        <input type="text" name="name" required class="glass-input w-full rounded-lg px-4 py-3 text-slate-100 placeholder-slate-500" placeholder="Masukkan nama atau inisial...">
                    </div>

                    <!-- Email Input (Field Baru) -->
                    <div>
                        <label class="block text-sm text-slate-300 mb-2 font-medium">
                            Alamat Email Pegawai <span class="text-cyan-400">*</span>
                            <span class="text-xs text-cyan-400 font-normal ml-1">(Penerima Hasil PDF)</span>
                        </label>
                        <input type="email" name="email" required class="glass-input w-full rounded-lg px-4 py-3 text-slate-100 placeholder-slate-500" placeholder="nama.pegawai@instansi.com">
                        <p class="text-[11px] text-slate-400 mt-1">Laporan resmi berformat PDF akan dikirimkan secara otomatis ke alamat email ini.</p>
                    </div>

                    <!-- Usia & Jenis Kelamin -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-slate-300 mb-2 font-medium">Usia <span class="text-cyan-400">*</span></label>
                            <input type="number" name="age" min="18" max="100" required class="glass-input w-full rounded-lg px-4 py-3 text-slate-100 placeholder-slate-500" placeholder="Tahun">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-300 mb-2 font-medium">Jenis Kelamin <span class="text-cyan-400">*</span></label>
                            <select name="gender" required class="glass-input w-full rounded-lg px-4 py-3 text-slate-100">
                                <option value="" disabled selected>Pilih...</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Unit / Divisi Kerja -->
                    <div>
                        <label class="block text-sm text-slate-300 mb-2 font-medium">Unit / Divisi Kerja <span class="text-cyan-400">*</span></label>
                        <select name="department" required class="glass-input w-full rounded-lg px-4 py-3 text-slate-100">
                            <option value="" disabled selected>Pilih Unit...</option>
                            <option value="Medis">Medis</option>
                            <option value="Non Medis">Non Medis</option>
                        </select>
                    </div>

                    <!-- Masa Kerja -->
                    <div>
                        <label class="block text-sm text-slate-300 mb-2 font-medium">Masa Kerja di Posisi Saat Ini <span class="text-cyan-400">*</span></label>
                        <select name="work_tenure" required class="glass-input w-full rounded-lg px-4 py-3 text-slate-100">
                            <option value="" disabled selected>Pilih Masa Kerja...</option>
                            <option value="< 1 tahun">&lt; 1 tahun</option>
                            <option value="1-3 tahun">1 - 3 tahun</option>
                            <option value="> 3 tahun">&gt; 3 tahun</option>
                        </select>
                    </div>

                    <!-- Durasi Kerja Depan Layar -->
                    <div>
                        <label class="block text-sm text-slate-300 mb-2 font-medium">Rata-rata Durasi Bekerja Depan Layar / Hari <span class="text-cyan-400">*</span></label>
                        <select name="screen_time" required class="glass-input w-full rounded-lg px-4 py-3 text-slate-100">
                            <option value="" disabled selected>Pilih Durasi...</option>
                            <option value="< 4 jam">&lt; 4 jam</option>
                            <option value="4-6 jam">4 - 6 jam</option>
                            <option value="> 6 jam">&gt; 6 jam</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- SECTION B: NBM (Nordic Body Map) -->
            <section class="glass-panel rounded-2xl p-6 md:p-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded bg-cyan-500/20 border border-cyan-500/50 flex items-center justify-center text-cyan-400 font-heading font-bold">B</div>
                    <h2 class="font-heading text-2xl font-semibold text-slate-100 uppercase tracking-wide">Skrining Keluhan Otot & Tulang Rangka (NBM)</h2>
                </div>
                <p class="text-slate-400 text-sm mb-6 pb-4 border-b border-slate-700/50">
                    Tentukan tingkat keluhan nyeri, pegal, kaku, atau kesemutan yang Anda rasakan dalam <strong>7 hari terakhir</strong>.
                </p>
                
                <div class="space-y-4">
                    @foreach($nbmParts as $index => $part)
                        <div class="bg-slate-900/50 p-4 rounded-xl border border-slate-700/50 hover:border-cyan-500/30 transition-colors">
                            <p class="font-medium text-slate-200 mb-3">{{ $index + 1 }}. {{ $part['name'] }}</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                @foreach($nbmScales as $scale)
                                    @php $inputId = $part['id'] . '_' . $scale['val']; @endphp
                                    <div>
                                        <input type="radio" id="{{ $inputId }}" name="{{ $part['id'] }}" value="{{ $scale['val'] }}" required class="peer sr-only">
                                        <label for="{{ $inputId }}" class="block w-full text-center p-2 rounded-lg border border-slate-700 bg-slate-800/50 text-slate-400 cursor-pointer text-sm transition-all duration-200 hover:bg-slate-700
                                        peer-checked:bg-cyan-500/20 peer-checked:text-cyan-300 peer-checked:border-cyan-400 peer-checked:shadow-[0_0_10px_rgba(6,182,212,0.3)]">
                                            <div class="font-semibold">{{ $scale['val'] }} - {{ $scale['label'] }}</div>
                                            <div class="text-[10px] opacity-70 mt-1">{{ $scale['desc'] }}</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- SECTION C: ROSA (Rapid Office Strain Assessment) -->
            <section class="glass-panel rounded-2xl p-6 md:p-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded bg-purple-500/20 border border-purple-500/50 flex items-center justify-center text-purple-400 font-heading font-bold">C</div>
                    <h2 class="font-heading text-2xl font-semibold text-slate-100 uppercase tracking-wide">Skrining Postur & Fasilitas Kerja (ROSA Modifikasi)</h2>
                </div>
                <p class="text-slate-400 text-sm mb-6 pb-4 border-b border-slate-700/50">
                    Pilih kondisi yang paling menggambarkan posisi dan peralatan workstation kerja Anda sehari-hari.
                </p>
                
                <div class="space-y-6">
                    @foreach($rosaQuestions as $index => $q)
                        <div class="bg-slate-900/50 p-5 rounded-xl border border-slate-700/50 hover:border-purple-500/30 transition-colors">
                            <p class="font-medium text-slate-200 mb-3">{{ $index + 1 }}. {{ $q['title'] }}</p>
                            <div class="flex flex-col gap-3">
                                @foreach($q['options'] as $opt)
                                    @php $inputId = $q['id'] . '_' . $opt['val']; @endphp
                                    <label class="flex items-start cursor-pointer group">
                                        <input type="radio" id="{{ $inputId }}" name="{{ $q['id'] }}" value="{{ $opt['val'] }}" required class="peer sr-only">
                                        <div class="w-5 h-5 rounded-full border-2 border-slate-600 mr-3 mt-0.5 flex-shrink-0 flex items-center justify-center group-hover:border-purple-400 peer-checked:border-purple-500 peer-checked:bg-purple-500/20 peer-checked:shadow-[0_0_8px_rgba(168,85,247,0.5)] transition-all">
                                            <div class="w-2.5 h-2.5 rounded-full bg-purple-400 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                        </div>
                                        <div class="text-sm text-slate-400 group-hover:text-slate-300 peer-checked:text-purple-200 transition-colors">
                                            <span class="font-semibold mr-1">Skor {{ $opt['val'] }}:</span> {{ $opt['text'] }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- SUBMIT BUTTON -->
            <div class="text-center pt-6 pb-6">
                <button type="submit" id="btnSubmit" class="btn-cyber bg-cyan-600 hover:bg-cyan-500 text-white font-heading text-xl tracking-wider py-4 px-12 rounded-lg border border-cyan-400 shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:shadow-[0_0_30px_rgba(6,182,212,0.7)] uppercase transition-all duration-300 inline-flex items-center justify-center gap-3">
                    <span id="btnText">Kalkulasi Skor & Kirim Laporan PDF</span>
                    <svg id="btnSpinner" class="hidden animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </button>
                <p class="text-xs text-slate-400 mt-3">Hasil kalkulasi risiko akan langsung tampil di bawah, dan berkas PDF otomatis dikirimkan ke email Anda.</p>
            </div>
        </form>

        <!-- RESULTS DASHBOARD (Hidden by default, shown dynamically) -->
        <section id="resultsDashboard" class="hidden glass-panel rounded-2xl p-6 md:p-8 border-t-4 mb-20 transition-all duration-500 opacity-0 transform translate-y-10">
            <h2 class="font-heading text-3xl text-center mb-8 uppercase tracking-widest text-slate-100 neon-text-cyan">
                Hasil Analisis Sistem STAFF-ERGO
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Score Box -->
                <div class="bg-slate-900/80 rounded-xl p-6 flex flex-col justify-center items-center border border-slate-700">
                    <p class="text-slate-400 text-sm uppercase tracking-wider mb-2">Total Skor Sistem</p>
                    <div class="text-6xl font-heading font-bold text-white mb-2" id="resScore">00</div>
                    <p class="text-xs text-slate-500">Rentang Skor 13 - 48</p>
                    <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-800 text-center w-full">
                        NBM: <span id="resScoreNbm" class="text-cyan-400 font-semibold">0</span> | ROSA: <span id="resScoreRosa" class="text-purple-400 font-semibold">0</span>
                    </div>
                </div>
                
                <!-- Risk Level Box -->
                <div id="resRiskBox" class="md:col-span-2 rounded-xl p-6 flex flex-col justify-center border relative overflow-hidden bg-slate-900/80">
                    <div class="absolute inset-0 opacity-10 bg-current"></div>
                    <p class="text-sm uppercase tracking-wider mb-2 z-10 opacity-80 text-white">Kategori Tingkat Risiko</p>
                    <div class="text-3xl md:text-4xl font-heading font-bold mb-3 z-10 text-white" id="resRiskLevel">Risiko</div>
                    <p class="text-sm md:text-base z-10 text-white/90 leading-relaxed" id="resGeneralRec">Deskripsi rekomendasi umum akan muncul di sini.</p>
                    
                    <!-- Direct PDF Download Link -->
                    <div class="mt-6 pt-4 border-t border-slate-700/60 z-10 flex flex-wrap items-center justify-between gap-4">
                        <div class="text-xs text-slate-300 flex items-center gap-2">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Laporan resmi PDF dikirim ke: <strong id="resTargetEmail" class="text-cyan-300"></strong>
                        </div>
                        <a id="btnDownloadPdf" href="#" target="_blank" class="text-xs font-semibold bg-cyan-600/80 hover:bg-cyan-500 text-white px-4 py-2 rounded-lg border border-cyan-400 shadow-md inline-flex items-center gap-2 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh Laporan PDF Langsung
                        </a>
                    </div>
                </div>
            </div>

            <!-- Smart Recommendations (Section E) -->
            <div id="smartRecContainer" class="hidden space-y-4 mt-8 pt-8 border-t border-slate-700/50">
                <h3 class="font-heading text-xl text-cyan-300 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Rekomendasi Cerdas Otomatis
                </h3>
                <div id="smartRecList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Injected via AJAX response -->
                </div>
            </div>
        </section>

    </div>

    <!-- Script AJAX submission & UI handling -->
    <script>
        document.getElementById('ergoForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = this;
            const btnSubmit = document.getElementById('btnSubmit');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            // 1. Disable submit button & show spinner
            btnSubmit.disabled = true;
            btnText.innerText = "Memproses Kalkulasi & Membuat PDF...";
            btnSpinner.classList.remove('hidden');

            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    let errorMessages = [];
                    if (result.errors) {
                        for (const key in result.errors) {
                            errorMessages.push(result.errors[key].join(', '));
                        }
                    } else {
                        errorMessages.push(result.message || 'Terjadi kesalahan pada sistem.');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Belum Lengkap',
                        html: '<div class="text-left text-sm text-slate-300">' + errorMessages.join('<br>') + '</div>',
                        customClass: { popup: 'swal-custom-dark' },
                        confirmButtonColor: '#06b6d4',
                    });
                    return;
                }

                // 2. Success handling
                const data = result.data;
                const risk = data.risk;

                // Update UI elements
                document.getElementById('resScore').innerText = data.total_score;
                document.getElementById('resScoreNbm').innerText = data.nbm_total_score + ' / 36';
                document.getElementById('resScoreRosa').innerText = data.rosa_total_score + ' / 12';
                
                const riskLevelEl = document.getElementById('resRiskLevel');
                riskLevelEl.innerText = risk.label;
                riskLevelEl.className = `text-3xl md:text-4xl font-heading font-bold mb-3 z-10 ${risk.textClass}`;

                document.getElementById('resGeneralRec').innerText = risk.rec;
                document.getElementById('resTargetEmail').innerText = data.email;
                document.getElementById('btnDownloadPdf').href = data.download_url;

                // Risk Box styling
                const riskBox = document.getElementById('resRiskBox');
                riskBox.className = `md:col-span-2 rounded-xl p-6 flex flex-col justify-center border relative overflow-hidden bg-slate-900/80 ${risk.colorClass} ${risk.shadowClass}`;

                // Smart recommendations
                const smartContainer = document.getElementById('smartRecContainer');
                const smartList = document.getElementById('smartRecList');

                if (data.smart_recommendations && data.smart_recommendations.length > 0) {
                    smartContainer.classList.remove('hidden');
                    smartList.innerHTML = data.smart_recommendations.map(rule => `
                        <div class="bg-slate-800/80 p-4 rounded-lg border border-cyan-500/30 border-l-4 border-l-cyan-500 shadow-lg">
                            <h4 class="font-bold text-cyan-200 mb-1">${rule.title}</h4>
                            <p class="text-sm text-slate-300 mb-3">${rule.desc}</p>
                            <span class="text-xs bg-cyan-900/50 border border-cyan-700 text-cyan-200 py-1.5 px-3 rounded inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-cyan-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                </svg>
                                ${rule.action}
                            </span>
                        </div>
                    `).join('');
                } else {
                    smartContainer.classList.add('hidden');
                }

                // Show Dashboard
                const dashboard = document.getElementById('resultsDashboard');
                dashboard.classList.remove('hidden');
                setTimeout(() => {
                    dashboard.classList.remove('opacity-0', 'translate-y-10');
                    dashboard.classList.add('opacity-100', 'translate-y-0');
                    dashboard.className = `glass-panel rounded-2xl p-6 md:p-8 border-t-4 mb-20 transition-all duration-500 opacity-100 translate-y-0 ${risk.colorClass}`;
                    dashboard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 50);

                // Show SweetAlert2 Success Notification
                Swal.fire({
                    icon: 'success',
                    title: 'Skrining Ergonomi Berhasil!',
                    html: `
                        <div class="text-sm text-slate-300 space-y-2 text-left">
                            <p>Data penilaian berhasil dianalisis dengan total skor: <strong class="text-cyan-400 font-bold">${data.total_score} (${risk.label})</strong>.</p>
                            <div class="p-3 bg-slate-800/80 rounded-lg border border-cyan-500/30 text-xs text-cyan-200">
                                📩 <strong>Notifikasi Email:</strong> Laporan resmi hasil skrining berformat PDF telah digenerate dan dikirimkan ke alamat email <strong>${data.email}</strong>.
                            </div>
                        </div>
                    `,
                    customClass: { popup: 'swal-custom-dark' },
                    confirmButtonText: 'Lihat Detail Hasil',
                    confirmButtonColor: '#06b6d4',
                });

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kendala Jaringan',
                    text: err.message || 'Tidak dapat terhubung ke server. Silakan coba kembali.',
                    customClass: { popup: 'swal-custom-dark' },
                    confirmButtonColor: '#06b6d4',
                });
            } finally {
                btnSubmit.disabled = false;
                btnText.innerText = "Kalkulasi Skor & Kirim Laporan PDF";
                btnSpinner.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
