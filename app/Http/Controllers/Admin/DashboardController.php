<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalStaff = User::where('role', 'staff')->count();

        $totalCategories = Category::count();
        $activeCategories = Category::where('is_active', true)->count();

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalStaff',
            'totalCategories',
            'activeCategories',
            'recentUsers'
        ));
    }
}