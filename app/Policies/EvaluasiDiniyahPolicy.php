<?php

namespace App\Policies;

use App\Models\EvaluasiDiniyah;
use App\Models\User;

class EvaluasiDiniyahPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdminPusat() || $user->isAdminCabang() || $user->isPimpinanPusat();
    }

    public function view(User $user, EvaluasiDiniyah $evaluasiDiniyah): bool
    {
        if ($user->isAdminPusat() || $user->isPimpinanPusat()) {
            return true;
        }

        if ($user->isAdminCabang()) {
            return $user->asrama_id !== null && (int) $user->asrama_id === (int) $evaluasiDiniyah->asrama_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdminCabang() && $user->asrama_id !== null;
    }

    public function update(User $user, EvaluasiDiniyah $evaluasiDiniyah): bool
    {
        if ($user->isAdminPusat()) {
            return true;
        }

        if ($user->isAdminCabang()) {
            return $user->asrama_id !== null
                && (int) $user->asrama_id === (int) $evaluasiDiniyah->asrama_id
                && $evaluasiDiniyah->isPerluRevisi();
        }

        return false;
    }

    public function delete(User $user, EvaluasiDiniyah $evaluasiDiniyah): bool
    {
        return false;
    }

    public function restore(User $user, EvaluasiDiniyah $evaluasiDiniyah): bool
    {
        return false;
    }

    public function forceDelete(User $user, EvaluasiDiniyah $evaluasiDiniyah): bool
    {
        return false;
    }
}
