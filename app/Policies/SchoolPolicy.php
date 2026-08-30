<?php

namespace App\Policies;

use App\Models\School;
use App\Models\User;

class SchoolPolicy
{
    /**
     * Public can view published schools.
     */
    public function view(?User $user, School $school): bool
    {
        if ($user && $user->isAdmin()) {
            return true;
        }

        return $school->is_published ?? true;
    }

    /**
     * Only admin can update.
     */
    public function update(User $user, School $school): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admin can delete.
     */
    public function delete(User $user, School $school): bool
    {
        return $user->isAdmin();
    }
}
