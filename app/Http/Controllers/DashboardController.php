<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectionRequest;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'company') {
            return $this->companyDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    protected function adminDashboard()
    {
        $users = User::where('role', 'user')->get();
        $companies = User::where('role', 'company')->get();
        $requests = CollectionRequest::all();

        return view('dashboard.admin', compact('users', 'companies', 'requests'));
    }

    protected function companyDashboard()
    {
        $user = Auth::user();
        $pendingRequests = CollectionRequest::where('status', 'pending')->get();
        $companyRequests = CollectionRequest::where('company_id', $user->id)->get();
        $vehicles = Vehicle::where('company_id', $user->id)->get();

        return view('dashboard.company', compact('pendingRequests', 'companyRequests', 'vehicles'));
    }

    protected function userDashboard()
    {
        $user = Auth::user();
        $requests = $user->requests;

        return view('dashboard.user', compact('requests'));
    }
}
