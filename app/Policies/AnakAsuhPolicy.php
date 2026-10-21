<?php

namespace App\Policies;

use App\Models\AnakAsuh;
use App\Models\User;

class AnakAsuhPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdminPusat() || $user->isAdminCabang() || $user->isPimpinanPusat();
    }

    public function view(User $user, AnakAsuh $anakAsuh): bool
    {
        if ($user->isAdminPusat() || $user->isPimpinanPusat()) {
            return true;
        }

        if ($user->isAdminCabang()) {
            return $user->asrama_id !== null && (int) $user->asrama_id === (int) $anakAsuh->asrama_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdminPusat();
    }

    public function update(User $user, AnakAsuh $anakAsuh): bool
    {
        return $user->isAdminPusat();
    }

    public function delete(User $user, AnakAsuh $anakAsuh): bool
    {
        return $user->isAdminPusat();
    }

    public function restore(User $user, AnakAsuh $anakAsuh): bool
    {
        return $user->isAdminPusat();
    }

    public function forceDelete(User $user, AnakAsuh $anakAsuh): bool
    {
        return $user->isAdminPusat();
    }
}
