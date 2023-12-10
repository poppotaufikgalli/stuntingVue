<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

trait HasRole
{
    public function allowed($request)
    {
        $akses = explode(', ', Auth::user()->akses->akses);
        $currentRouteName = Route::currentRouteName();

        return in_array($currentRouteName, $akses);
    }
}