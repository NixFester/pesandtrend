<?php

namespace App\Policies;

use App\Models\ApplicationDocument;
use App\Models\User;

class ApplicationDocumentPolicy
{
    /**
     * Admin, applicant, or parent of the application can download.
     */
    public function download(User $user, ApplicationDocument $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        $application = $document->application;

        return $application->parent_user_id === $user->id
            || $application->created_by_user_id === $user->id;
    }
}
