<?php

namespace App\Services;

use App\Models\ApplicationDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class DocumentStorageService
{
    private string $disk = 'public';

    private string $basePath = 'documents/applications';

    /**
     * Store an uploaded document file.
     */
    public function store(UploadedFile $file, int $applicationId, string $kind, ?int $uploadedByUserId = null): ApplicationDocument
    {
        $directory = "{$this->basePath}/{$applicationId}";
        $path = $file->store($directory, $this->disk);

        return ApplicationDocument::create([
            'application_id' => $applicationId,
            'kind' => $kind,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by_user_id' => $uploadedByUserId,
        ]);
    }

    /**
     * Generate a temporary signed download URL for a document.
     */
    public function signedUrl(ApplicationDocument $document, int $minutes = 60): string
    {
        return URL::temporarySignedRoute(
            'documents.download',
            now()->addMinutes($minutes),
            ['document' => $document->id]
        );
    }

    /**
     * Get the full storage path.
     */
    public function fullPath(ApplicationDocument $document): string
    {
        $disk = Storage::disk('public')->exists($document->path) ? 'public' : 'local';

        return Storage::disk($disk)->path($document->path);
    }

    /**
     * Delete a document from storage.
     */
    public function delete(ApplicationDocument $document): void
    {
        if (Storage::disk('public')->exists($document->path)) {
            Storage::disk('public')->delete($document->path);
        } elseif (Storage::disk('local')->exists($document->path)) {
            Storage::disk('local')->delete($document->path);
        }

        $document->delete();
    }
}
