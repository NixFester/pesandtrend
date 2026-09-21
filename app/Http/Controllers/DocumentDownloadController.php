<?php

namespace App\Http\Controllers;

use App\Models\ApplicationDocument;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentDownloadController extends Controller
{
    /**
     * Download a document file (requires signed URL).
     */
    public function download(ApplicationDocument $document): StreamedResponse
    {
        $this->authorize('download', $document);

        $path = $document->storage_path ?? $document->path;

        $activeDisk = null;
        if (Storage::disk('public')->exists($path)) {
            $activeDisk = 'public';
        } elseif (Storage::disk('local')->exists($path)) {
            $activeDisk = 'local';
        } elseif (Storage::disk('private')->exists($path)) {
            $activeDisk = 'private';
        }

        if (! $activeDisk) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk($activeDisk)->download($path, $document->original_name);
    }
}
