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
        $diskName = config('filesystems.default_private', 'private');
        $disk = Storage::disk(Storage::exists($document->storage_path) ? config('filesystems.default', 'local') : 'private');

        $path = $document->storage_path ?? $document->path;

        if (! Storage::disk('private')->exists($path) && ! Storage::disk('local')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $activeDisk = Storage::disk('private')->exists($path) ? 'private' : 'local';

        return Storage::disk($activeDisk)->download($path, $document->original_name);
    }
}
