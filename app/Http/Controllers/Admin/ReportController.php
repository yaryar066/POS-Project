<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display Sales Reports & Analytics Dashboard
     */
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Query Orders in Date Range
        $ordersQuery = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        $totalSales = $ordersQuery->sum('total');
        $totalOrders = $ordersQuery->count();
        $totalDiscount = $ordersQuery->sum('discount');
        $totalTax = $ordersQuery->sum('tax');

        // Top Selling Products
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Orders List for Table Display
        $orders = (clone $ordersQuery)->with('user')->latest()->paginate(10);

        return view('admin.reports.index', compact(
            'orders', 'totalSales', 'totalOrders', 'totalDiscount', 
            'totalTax', 'topProducts', 'startDate', 'endDate'
        ));
    }

    /**
     * Export Sales Report as CSV / Excel File
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $orders = Order::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()
            ->get();

        $filename = "sales_report_{$startDate}_to_{$endDate}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order Number', 'Date', 'Cashier', 'Payment Method', 'Subtotal', 'Tax', 'Discount', 'Total']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->user->name ?? 'Staff',
                    strtoupper($order->payment_method),
                    $order->subtotal,
                    $order->tax,
                    $order->discount,
                    $order->total,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}