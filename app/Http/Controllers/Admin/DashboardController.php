<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Dynamic Current Month Date Range
        $startDate = now()->startOfMonth()->format('F j, Y');
        $endDate = now()->endOfMonth()->format('F j, Y');

        // Dynamic Real-Time Calculations
        $totalSalesCount = Order::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();

        $totalRevenue = Order::whereMonth('created_at', now()->month)
                             ->whereYear('created_at', now()->year)
                             ->sum('total');

        // Real-Time Recent Orders (Latest 5 Orders)
        $recentOrders = Order::with(['user', 'items'])->latest()->take(5)->get();

        // Step 13: Low Stock Products (Stock <= 5)
        $lowStockProducts = Product::where('stock', '<=', 5)->where('is_active', true)->get();
        $lowStockCount = $lowStockProducts->count();

        return view('admin.dashboard', compact(
            'startDate',
            'endDate',
            'totalSalesCount',
            'totalRevenue',
            'recentOrders',
            'lowStockProducts',
            'lowStockCount'
        ));
    }
}