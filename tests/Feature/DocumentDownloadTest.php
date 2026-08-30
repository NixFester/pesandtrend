<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class DocumentDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_signed_document_download_works_for_owner(): void
    {
        Storage::fake('private');

        $user = User::factory()->create();
        $school = School::factory()->create();
        $app = Application::create([
            'public_id' => 'APP-DOC-001',
            'school_id' => $school->id,
            'parent_user_id' => $user->id,
            'created_by_user_id' => $user->id,
            'student_name' => 'Doni',
            'parent_name' => 'Bapak Doni',
            'status' => 'submitted',
        ]);

        Storage::disk('private')->put('documents/kk_doni.pdf', 'Sample Document Content');

        $doc = ApplicationDocument::create([
            'application_id' => $app->id,
            'kind' => 'kk',
            'path' => 'documents/kk_doni.pdf',
            'original_name' => 'kk_doni.pdf',
            'mime' => 'application/pdf',
            'size' => 100,
        ]);

        $signedUrl = URL::signedRoute('documents.download', ['document' => $doc->id]);

        $response = $this->actingAs($user)->get($signedUrl);
        $response->assertOk();
    }
}
