<?php

namespace App\Jobs;

use App\Mail\AssessmentResultMail;
use App\Models\Assessment;
use App\Services\PdfReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ProcessAssessmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(
        public Assessment $assessment
    ) {}

    public function handle(PdfReportService $pdfService): void
    {
        try {
            // 1. Generate PDF
            $pdfFullPath = $pdfService->generateAssessmentPdf($this->assessment);

            // 2. Send Email with PDF Attachment
            Mail::to($this->assessment->email)
                ->send(new AssessmentResultMail($this->assessment, $pdfFullPath));

            // 3. Update Status
            $this->assessment->update([
                'email_status' => 'sent',
                'email_sent_at' => now(),
            ]);

            Log::info("Assessment PDF sent to {$this->assessment->email} for assessment ID: {$this->assessment->id}");
        } catch (Throwable $e) {
            Log::error("Failed to process assessment job: {$e->getMessage()}", [
                'assessment_id' => $this->assessment->id,
                'email' => $this->assessment->email,
                'trace' => $e->getTraceAsString(),
            ]);

            $this->assessment->update([
                'email_status' => 'failed',
            ]);

            throw $e;
        }
    }

    public function failed(?Throwable $exception): void
    {
        Log::error("ProcessAssessmentJob ultimately failed for ID {$this->assessment->id}: " . ($exception?->getMessage() ?? 'Unknown error'));
        $this->assessment->update([
            'email_status' => 'failed',
        ]);
    }
}
