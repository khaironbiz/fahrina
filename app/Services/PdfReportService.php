<?php

namespace App\Services;

use App\Models\Assessment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfReportService
{
    /**
     * Generate PDF report for an assessment and store it locally
     */
    public function generateAssessmentPdf(Assessment $assessment): string
    {
        $dir = 'reports';
        if (!Storage::disk('local')->exists($dir)) {
            Storage::disk('local')->makeDirectory($dir);
        }

        $filename = "ergo_report_{$assessment->uuid}.pdf";
        $filePath = "{$dir}/{$filename}";

        $pdf = Pdf::loadView('pdf.assessment_report', [
            'assessment' => $assessment,
            'nbmParts' => ErgoCalculationService::NBM_PARTS,
            'nbmScales' => collect(ErgoCalculationService::NBM_SCALES)->keyBy('val'),
            'rosaQuestions' => ErgoCalculationService::ROSA_QUESTIONS,
        ]);

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
        ]);

        Storage::disk('local')->put($filePath, $pdf->output());

        $assessment->update([
            'pdf_path' => $filePath,
        ]);

        return Storage::disk('local')->path($filePath);
    }
}
