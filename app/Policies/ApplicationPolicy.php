<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    /**
     * Admin can view any; parent can view own.
     */
    public function view(User $user, Application $application): bool
    {
        return $user->isAdmin() || $application->parent_user_id === $user->id || $application->created_by_user_id === $user->id;
    }

    /**
     * Admin can update any; parent can update own drafts.
     */
    public function update(User $user, Application $application): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return ($application->parent_user_id === $user->id || $application->created_by_user_id === $user->id)
            && $application->status->value === 'draft';
    }

    /**
     * Only admin can verify.
     */
    public function verify(User $user, Application $application): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admin can reject.
     */
    public function reject(User $user, Application $application): bool
    {
        return $user->isAdmin();
    }

    /**
     * Admin or application owner can create payment.
     */
    public function createPayment(User $user, Application $application): bool
    {
        return $user->isAdmin() || $application->parent_user_id === $user->id;
    }
}
