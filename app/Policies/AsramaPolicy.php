<?php

namespace App\Policies;

use App\Models\Asrama;
use App\Models\User;

class AsramaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdminPusat() || $user->isAdminCabang() || $user->isPimpinanPusat();
    }

    public function view(User $user, Asrama $asrama): bool
    {
        if ($user->isAdminPusat()) {
            return true;
        }

        if ($user->isPimpinanPusat()) {
            return true;
        }

        if ($user->isAdminCabang()) {
            return $user->asrama_id !== null && (int) $user->asrama_id === (int) $asrama->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdminPusat();
    }

    public function update(User $user, Asrama $asrama): bool
    {
        return $user->isAdminPusat();
    }

    public function delete(User $user, Asrama $asrama): bool
    {
        return $user->isAdminPusat();
    }

    public function restore(User $user, Asrama $asrama): bool
    {
        return $user->isAdminPusat();
    }

    public function forceDelete(User $user, Asrama $asrama): bool
    {
        return $user->isAdminPusat();
    }
}
