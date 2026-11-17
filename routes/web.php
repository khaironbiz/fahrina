<?php

use App\Http\Controllers\AssessmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AssessmentController::class, 'index'])->name('assessment.index');
Route::post('/assessment', [AssessmentController::class, 'store'])->name('assessment.store');
Route::get('/assessment/download/{uuid}', [AssessmentController::class, 'downloadPdf'])->name('assessment.download');

// Helper route khusus hosting cPanel tanpa akses Terminal / SSH
Route::get('/run-migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        return "<div style='font-family: sans-serif; padding: 30px; background: #0f172a; color: #38bdf8;'>
                    <h2>✅ Berhasil Migrasi Basis Data SQLite!</h2>
                    <pre style='background: #1e293b; color: #a5f3fc; padding: 15px; border-radius: 8px;'>" . e($output) . "</pre>
                    <p><a href='/' style='color: #38bdf8; text-decoration: underline;'>Klik di sini untuk kembali ke Formulir STAFF-ERGO</a></p>
                </div>";
    } catch (\Throwable $e) {
        return "<div style='font-family: sans-serif; padding: 30px; background: #450a0a; color: #fca5a5;'>
                    <h2>❌ Gagal Menjalankan Migrasi:</h2>
                    <pre style='background: #7f1d1d; color: #fee2e2; padding: 15px; border-radius: 8px;'>" . e($e->getMessage()) . "</pre>
                </div>";
    }
});
