<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class SalesController extends Controller
{
    /**
     * Display List of All Completed Orders
     */
    public function index(): View
    {
        $orders = Order::with(['user', 'items'])->latest()->paginate(10);
        return view('admin.sales.index', compact('orders'));
    }

    /**
     * Display Single Order Receipt Detail & Print Preview
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'items.product']);
        return view('admin.sales.show', compact('order'));
    }
}