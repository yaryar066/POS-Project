<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sales Analytics & Reports - Finexy POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #E2E8F0; }</style>
</head>
<body class="p-4 sm:p-6 lg:p-8 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-[1360px] bg-white rounded-[32px] shadow-2xl p-6 lg:p-8 border border-gray-100 space-y-6">
        
        <!-- Header & Date Filter Form -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-[#F05423]">← Back to Dashboard</a>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mt-1">Sales Reports & Analytics</h1>
            </div>

            <!-- Date Range Filter -->
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-center gap-2 bg-[#F6F7F9] p-2 rounded-2xl border border-gray-100">
                <input type="date" name="start_date" value="{{ $startDate }}" class="bg-white border-0 py-1.5 px-3 rounded-xl text-xs font-semibold text-gray-700">
                <span class="text-xs font-bold text-gray-400">to</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="bg-white border-0 py-1.5 px-3 rounded-xl text-xs font-semibold text-gray-700">
                <button type="submit" class="bg-gray-900 text-white px-4 py-1.5 rounded-xl text-xs font-bold hover:bg-gray-800">
                    Filter
                </button>
                <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="bg-emerald-600 text-white px-4 py-1.5 rounded-xl text-xs font-bold hover:bg-emerald-700">
                    Export CSV
                </a>
            </form>
        </div>

        <!-- 4 SUMMARY CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-[#F6F7F9] p-5 rounded-[24px]">
                <span class="text-xs font-medium text-gray-500">Total Revenue</span>
                <div class="text-2xl font-extrabold text-[#F05423] mt-2">${{ number_format($totalSales, 2) }}</div>
            </div>

            <div class="bg-[#F6F7F9] p-5 rounded-[24px]">
                <span class="text-xs font-medium text-gray-500">Total Orders</span>
                <div class="text-2xl font-extrabold text-gray-900 mt-2">{{ $totalOrders }}</div>
            </div>

            <div class="bg-[#F6F7F9] p-5 rounded-[24px]">
                <span class="text-xs font-medium text-gray-500">Total Discount Given</span>
                <div class="text-2xl font-extrabold text-gray-900 mt-2">${{ number_format($totalDiscount, 2) }}</div>
            </div>

            <div class="bg-[#F6F7F9] p-5 rounded-[24px]">
                <span class="text-xs font-medium text-gray-500">Total Tax Collected</span>
                <div class="text-2xl font-extrabold text-gray-900 mt-2">${{ number_format($totalTax, 2) }}</div>
            </div>
        </div>

        <!-- TOP SELLING PRODUCTS & ORDERS TABLE GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Top Selling Products (4 Cols) -->
            <div class="lg:col-span-4 bg-[#F6F7F9] p-6 rounded-[28px] space-y-4">
                <h3 class="font-bold text-gray-900 text-sm">Top Selling Products</h3>
                <div class="space-y-3">
                    @forelse($topProducts as $top)
                        <div class="bg-white p-3 rounded-2xl flex items-center justify-between shadow-sm">
                            <div>
                                <div class="font-bold text-xs text-gray-900">{{ $top->product_name }}</div>
                                <div class="text-[10px] text-gray-400">{{ $top->total_qty }} units sold</div>
                            </div>
                            <span class="text-xs font-extrabold text-[#F05423]">${{ number_format($top->total_revenue, 2) }}</span>
                        </div>
                    @empty
                        <div class="text-center py-8 text-xs text-gray-400 font-semibold">No sales recorded.</div>
                    @endforelse
                </div>
            </div>

            <!-- Detailed Sales Orders (8 Cols) -->
            <div class="lg:col-span-8 bg-[#F6F7F9] p-6 rounded-[28px]">
                <h3 class="font-bold text-gray-900 text-sm mb-4">Filtered Sales Orders</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="text-gray-400 text-[11px] border-b border-gray-200 pb-2">
                            <tr>
                                <th class="pb-3">Order No</th>
                                <th class="pb-3">Date</th>
                                <th class="pb-3">Cashier</th>
                                <th class="pb-3">Payment</th>
                                <th class="pb-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/60 font-medium">
                            @forelse($orders as $order)
                                <tr>
                                    <td class="py-3 font-bold text-gray-900">{{ $order->order_number }}</td>
                                    <td class="py-3 text-gray-500">{{ $order->created_at->format('Y-m-d') }}</td>
                                    <td class="py-3 text-gray-900 font-semibold">{{ $order->user->name ?? 'Staff' }}</td>
                                    <td class="py-3"><span class="bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase">{{ $order->payment_method }}</span></td>
                                    <td class="py-3 text-right font-extrabold text-[#F05423]">${{ number_format($order->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-6 text-gray-400">No orders in this date range.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $orders->appends(request()->query())->links() }}</div>
            </div>

        </div>

    </div>
</body>
</html>