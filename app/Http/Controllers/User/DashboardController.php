<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $activeCategoryCount = Category::where('is_active', true)->count();

        return view('user.dashboard', compact('user', 'activeCategoryCount'));
    }
}