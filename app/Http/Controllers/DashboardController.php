<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('dashboard.admin', compact('user'));
        }

        if ($user->role === 'company') {
            return view('dashboard.company', compact('user'));
        }

        return view('dashboard.user', compact('user'));
    }
}

