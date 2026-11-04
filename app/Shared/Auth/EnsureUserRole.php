<?php

// BAGIAN: SHARED - Role Guard & Autorisasi Multi-Cabang

namespace App\Shared\Auth;

use App\Http\Middleware\EnsureUserRole as BaseEnsureUserRole;

class EnsureUserRole extends BaseEnsureUserRole {}
