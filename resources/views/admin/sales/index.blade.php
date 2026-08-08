<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sales History - Finexy POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #E2E8F0; }</style>
</head>
<body class="p-4 sm:p-6 lg:p-8 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-[1360px] bg-white rounded-[32px] shadow-2xl p-6 lg:p-8 border border-gray-100 space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-[#F05423]">← Back to Dashboard</a>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mt-1">Sales Orders & Receipts</h1>
            </div>
            <a href="{{ route('pos.index') }}" class="bg-[#F05423] text-white px-5 py-2.5 rounded-2xl text-xs font-bold shadow-md shadow-orange-500/20 hover:opacity-90 transition-all">
                Open POS Terminal
            </a>
        </div>

        <!-- Sales Orders Table -->
        <div class="bg-[#F6F7F9] p-6 rounded-[28px]">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="text-gray-400 text-[11px] border-b border-gray-200 pb-2">
                        <tr>
                            <th class="pb-3">Order No</th>
                            <th class="pb-3">Date & Time</th>
                            <th class="pb-3">Cashier Staff</th>
                            <th class="pb-3">Payment</th>
                            <th class="pb-3">Items Qty</th>
                            <th class="pb-3">Total Amount</th>
                            <th class="pb-3 text-right">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/60 font-medium">
                        @forelse($orders as $order)
                            <tr>
                                <td class="py-3 font-bold text-gray-900">{{ $order->order_number }}</td>
                                <td class="py-3 text-gray-500">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td class="py-3 text-gray-900 font-semibold">{{ $order->user->name ?? 'Staff' }}</td>
                                <td class="py-3">
                                    <span class="bg-gray-200 text-gray-700 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase">
                                        {{ $order->payment_method }}
                                    </span>
                                </td>
                                <td class="py-3 text-gray-500">{{ $order->items->sum('quantity') }} items</td>
                                <td class="py-3 font-extrabold text-[#F05423]">${{ number_format($order->total, 2) }}</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.sales.show', $order->id) }}" class="bg-white border border-gray-200 px-3 py-1.5 rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-100 transition-all">
                                        View Receipt
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-6 text-gray-400">No completed orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $orders->links() }}</div>
        </div>

    </div>
</body>
</html>