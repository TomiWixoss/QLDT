<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('admin.dashboard', [
            'user' => $user,
        ]);
    }
}
