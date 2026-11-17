<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssessmentRequest;
use App\Jobs\ProcessAssessmentJob;
use App\Models\Assessment;
use App\Services\ErgoCalculationService;
use App\Services\PdfReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssessmentController extends Controller
{
    public function __construct(
        protected ErgoCalculationService $calculationService,
        protected PdfReportService $pdfService
    ) {}

    /**
     * Display the assessment questionnaire form
     */
    public function index()
    {
        return view('assessment.index', [
            'nbmParts' => ErgoCalculationService::NBM_PARTS,
            'nbmScales' => ErgoCalculationService::NBM_SCALES,
            'rosaQuestions' => ErgoCalculationService::ROSA_QUESTIONS,
        ]);
    }

    /**
     * Store and calculate assessment results
     */
    public function store(StoreAssessmentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // 1. Separate NBM & ROSA responses
        $nbmResponses = [];
        for ($i = 1; $i <= 9; $i++) {
            $key = "b{$i}";
            $nbmResponses[$key] = (int) $validated[$key];
        }

        $rosaResponses = [];
        for ($j = 1; $j <= 4; $j++) {
            $key = "c{$j}";
            $rosaResponses[$key] = (int) $validated[$key];
        }

        // 2. Calculate scores & risk
        $nbmScore = $this->calculationService->calculateNbmScore($nbmResponses);
        $rosaScore = $this->calculationService->calculateRosaScore($rosaResponses);
        $totalScore = $nbmScore + $rosaScore;

        $riskData = $this->calculationService->determineRisk($totalScore);
        $smartRules = $this->calculationService->evaluateSmartRules(array_merge($nbmResponses, $rosaResponses));

        // 3. Save to database
        $assessment = Assessment::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'age' => $validated['age'],
            'gender' => $validated['gender'],
            'department' => $validated['department'],
            'work_tenure' => $validated['work_tenure'],
            'screen_time' => $validated['screen_time'],
            'nbm_responses' => $nbmResponses,
            'rosa_responses' => $rosaResponses,
            'nbm_total_score' => $nbmScore,
            'rosa_total_score' => $rosaScore,
            'total_score' => $totalScore,
            'risk_level' => $riskData['level'],
            'risk_label' => $riskData['label'],
            'risk_description' => $riskData['rec'],
            'smart_recommendations' => $smartRules,
            'email_status' => 'pending',
        ]);

        // 4. Dispatch Job to process PDF & email in background queue
        ProcessAssessmentJob::dispatch($assessment);

        // 5. Return JSON for instant UI update & on-screen notification
        return response()->json([
            'success' => true,
            'message' => "Asesmen berhasil disimpan! Hasil skrining resmi dalam bentuk PDF sedang dikirimkan ke email: {$assessment->email}",
            'data' => [
                'id' => $assessment->id,
                'uuid' => $assessment->uuid,
                'name' => $assessment->name,
                'email' => $assessment->email,
                'nbm_total_score' => $nbmScore,
                'rosa_total_score' => $rosaScore,
                'total_score' => $totalScore,
                'risk' => $riskData,
                'smart_recommendations' => $smartRules,
                'download_url' => route('assessment.download', ['uuid' => $assessment->uuid]),
            ],
        ]);
    }

    /**
     * Download generated PDF directly on-demand
     */
    public function downloadPdf(string $uuid): BinaryFileResponse
    {
        $assessment = Assessment::where('uuid', $uuid)->firstOrFail();

        // If PDF not generated yet, generate immediately
        if (!$assessment->pdf_path || !Storage::disk('local')->exists($assessment->pdf_path)) {
            $pdfFullPath = $this->pdfService->generateAssessmentPdf($assessment);
        } else {
            $pdfFullPath = Storage::disk('local')->path($assessment->pdf_path);
        }

        $downloadFilename = "Laporan-Ergonomi-{$assessment->name}.pdf";

        return response()->download($pdfFullPath, $downloadFilename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
