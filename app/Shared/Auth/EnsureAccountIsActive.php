<?php

// BAGIAN: SHARED - Middleware Pemeriksaan Akun Aktif

namespace App\Shared\Auth;

use App\Http\Middleware\EnsureAccountIsActive as BaseEnsureAccountIsActive;

class EnsureAccountIsActive extends BaseEnsureAccountIsActive {}
