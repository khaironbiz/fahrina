<?php

namespace Tests\Feature;

use App\Jobs\ProcessAssessmentJob;
use App\Models\Assessment;
use App\Services\PdfReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AssessmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_assessment_form(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('STAFF-ERGO');
        $response->assertSee('Alamat Email Pegawai');
        $response->assertSee('Skrining Keluhan Otot');
        $response->assertSee('Skrining Postur');
    }

    public function test_validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->postJson('/assessment', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'age', 'gender', 'b1', 'c1']);
    }

    public function test_can_submit_assessment_and_dispatches_job(): void
    {
        Queue::fake();

        $payload = [
            'name' => 'Budi Santoso',
            'email' => 'budi@perusahaan.co.id',
            'age' => 32,
            'gender' => 'L',
            'department' => 'Non Medis',
            'work_tenure' => '1-3 tahun',
            'screen_time' => '> 6 jam',
            // Section B: NBM (all 2s = 18)
            'b1' => 3, // Leher sakit
            'b2' => 2,
            'b3' => 2,
            'b4' => 2,
            'b5' => 4, // Pinggang sangat sakit
            'b6' => 4, // Pergelangan tangan sangat sakit
            'b7' => 2,
            'b8' => 2,
            'b9' => 2,
            // Section C: ROSA (3 + 3 + 2 + 3 = 11)
            'c1' => 3, // Kursi bungkuk
            'c2' => 3, // Monitor laptop tanpa stand
            'c3' => 2,
            'c4' => 3,
        ];

        $response = $this->postJson('/assessment', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('assessments', [
            'name' => 'Budi Santoso',
            'email' => 'budi@perusahaan.co.id',
            'age' => 32,
        ]);

        $assessment = Assessment::where('email', 'budi@perusahaan.co.id')->first();
        $this->assertNotNull($assessment);

        // b total = 3+2+2+2+4+4+2+2+2 = 23
        // c total = 3+3+2+3 = 11
        // total score = 23 + 11 = 34 -> High Risk (30 - 38)
        $this->assertEquals(23, $assessment->nbm_total_score);
        $this->assertEquals(11, $assessment->rosa_total_score);
        $this->assertEquals(34, $assessment->total_score);
        $this->assertEquals('high', $assessment->risk_level);

        // Smart recommendations should trigger for neck, lumbar, and wrist!
        $this->assertCount(3, $assessment->smart_recommendations);

        Queue::assertPushed(ProcessAssessmentJob::class, function ($job) use ($assessment) {
            return $job->assessment->id === $assessment->id;
        });
    }

    public function test_can_download_pdf_report(): void
    {
        $assessment = Assessment::create([
            'name' => 'Dr. Rina',
            'email' => 'rina@rumah-sakit.id',
            'age' => 28,
            'gender' => 'P',
            'department' => 'Medis',
            'work_tenure' => '< 1 tahun',
            'screen_time' => '4-6 jam',
            'nbm_responses' => ['b1' => 1, 'b2' => 1, 'b3' => 1, 'b4' => 1, 'b5' => 1, 'b6' => 1, 'b7' => 1, 'b8' => 1, 'b9' => 1],
            'rosa_responses' => ['c1' => 1, 'c2' => 1, 'c3' => 1, 'c4' => 1],
            'nbm_total_score' => 9,
            'rosa_total_score' => 4,
            'total_score' => 13,
            'risk_level' => 'low',
            'risk_label' => 'Risiko Rendah (Low Risk)',
            'risk_description' => 'Postur ergonomis.',
            'smart_recommendations' => [],
        ]);

        $response = $this->get(route('assessment.download', ['uuid' => $assessment->uuid]));

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'application/pdf'));
    }

    public function test_process_assessment_job_generates_pdf_and_sends_email(): void
    {
        Mail::fake();

        $assessment = Assessment::create([
            'name' => 'Ahmad Faisal',
            'email' => 'faisal@kantor.id',
            'age' => 35,
            'gender' => 'L',
            'department' => 'Non Medis',
            'work_tenure' => '> 3 tahun',
            'screen_time' => '> 6 jam',
            'nbm_responses' => ['b1' => 2, 'b2' => 2, 'b3' => 1, 'b4' => 2, 'b5' => 3, 'b6' => 1, 'b7' => 1, 'b8' => 1, 'b9' => 1],
            'rosa_responses' => ['c1' => 2, 'c2' => 2, 'c3' => 1, 'c4' => 2],
            'nbm_total_score' => 14,
            'rosa_total_score' => 7,
            'total_score' => 21,
            'risk_level' => 'moderate',
            'risk_label' => 'Risiko Sedang (Moderate Risk)',
            'risk_description' => 'Mulai kelelahan otot.',
            'smart_recommendations' => [],
        ]);

        $job = new ProcessAssessmentJob($assessment);
        $job->handle(app(PdfReportService::class));

        $assessment->refresh();
        $this->assertEquals('sent', $assessment->email_status);
        $this->assertNotNull($assessment->email_sent_at);
        $this->assertNotNull($assessment->pdf_path);
        $this->assertFileExists(\Illuminate\Support\Facades\Storage::disk('local')->path($assessment->pdf_path));

        Mail::assertSent(\App\Mail\AssessmentResultMail::class, function ($mail) use ($assessment) {
            return $mail->hasTo($assessment->email);
        });
    }
}
