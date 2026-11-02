<?php

namespace App\Policies;

use App\Models\Borrowing;
use App\Models\User;

class BorrowingPolicy
{
    /**
     * Determine whether the user can view all borrowings.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view this borrowing.
     */
    public function view(User $user, Borrowing $borrowing): bool
    {
        return $user->id === $borrowing->user_id;
    }

    /**
     * Determine whether the user can create a borrowing.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update this borrowing.
     */
    public function update(User $user, Borrowing $borrowing): bool
    {
        return $user->id === $borrowing->user_id;
    }

    /**
     * Determine whether the user can delete this borrowing.
     */
    public function delete(User $user, Borrowing $borrowing): bool
    {
        return $user->id === $borrowing->user_id;
    }
}
