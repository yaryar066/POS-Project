<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $order->order_number }} - Finexy POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #E2E8F0; }
        .receipt-mono { font-family: 'Space Mono', monospace; }
        
        /* Print Settings for Thermal / Standard Printers */
        @media print {
            body { background-color: white !important; p: 0 !important; }
            .no-print { display: none !important; }
            .receipt-card { box-shadow: none !important; border: none !important; width: 100% !important; max-width: 100% !important; }
        }
    </style>
</head>
<body class="p-4 sm:p-6 lg:p-8 min-h-screen flex flex-col items-center justify-center space-y-4">

    <!-- Action Buttons (Hidden on Print) -->
    <div class="w-full max-w-[400px] flex justify-between items-center no-print">
        <a href="{{ route('admin.sales.index') }}" class="text-xs font-bold text-gray-600 hover:text-gray-900">← Back to History</a>
        <button onclick="window.print()" class="bg-[#F05423] text-white px-4 py-2 rounded-xl text-xs font-bold shadow-md shadow-orange-500/20 hover:opacity-90">
            Print Receipt
        </button>
    </div>

    <!-- THERMAL RECEIPT CONTAINER -->
    <div class="receipt-card w-full max-w-[400px] bg-white rounded-3xl shadow-2xl p-6 border border-gray-100 text-gray-800 text-xs receipt-mono">
        
        <!-- Store Header -->
        <div class="text-center pb-4 border-b border-dashed border-gray-300 space-y-1">
            <h2 class="text-lg font-bold tracking-widest text-gray-900 uppercase">FINEXY POS</h2>
            <p class="text-[10px] text-gray-500">123 Store Street, Yangon, Myanmar</p>
            <p class="text-[10px] text-gray-500">Tel: +95 9 123 456 789</p>
        </div>

        <!-- Order Meta Data -->
        <div class="py-3 border-b border-dashed border-gray-300 space-y-1 text-[11px]">
            <div class="flex justify-between"><span>ORDER NO:</span><span class="font-bold">{{ $order->order_number }}</span></div>
            <div class="flex justify-between"><span>DATE:</span><span>{{ $order->created_at->format('Y-m-d H:i') }}</span></div>
            <div class="flex justify-between"><span>CASHIER:</span><span>{{ $order->user->name ?? 'Staff' }}</span></div>
            <div class="flex justify-between"><span>PAYMENT:</span><span class="uppercase font-bold">{{ $order->payment_method }}</span></div>
        </div>

        <!-- Items Table -->
        <div class="py-3 border-b border-dashed border-gray-300 space-y-2">
            <div class="flex justify-between font-bold text-[10px] text-gray-400 uppercase">
                <span>Item</span>
                <span>Qty x Price</span>
                <span class="text-right">Total</span>
            </div>

            @foreach($order->items as $item)
                <div class="flex justify-between items-start text-[11px]">
                    <div class="flex-1 pr-2">
                        <div class="font-bold text-gray-900">{{ $item->product_name }}</div>
                    </div>
                    <div class="text-gray-500 px-2">{{ $item->quantity }} x ${{ number_format($item->unit_price, 2) }}</div>
                    <div class="font-bold text-gray-900 text-right">${{ number_format($item->subtotal, 2) }}</div>
                </div>
            @endforeach
        </div>

        <!-- Calculations -->
        <div class="py-3 border-b border-dashed border-gray-300 space-y-1 text-[11px]">
            <div class="flex justify-between text-gray-500"><span>Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
            <div class="flex justify-between text-gray-500"><span>Tax (5%)</span><span>${{ number_format($order->tax, 2) }}</span></div>
            <div class="flex justify-between text-gray-500"><span>Discount</span><span>-${{ number_format($order->discount, 2) }}</span></div>
            <div class="flex justify-between font-extrabold text-sm text-gray-900 pt-1 border-t border-gray-200">
                <span>TOTAL</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="py-3 border-b border-dashed border-gray-300 space-y-1 text-[11px]">
            <div class="flex justify-between text-gray-500"><span>PAID AMOUNT:</span><span>${{ number_format($order->paid_amount, 2) }}</span></div>
            <div class="flex justify-between font-bold text-emerald-600"><span>CHANGE RETURN:</span><span>${{ number_format($order->change_amount, 2) }}</span></div>
        </div>

        <!-- Footer Thank You Note -->
        <div class="text-center pt-4 space-y-1">
            <p class="font-bold text-[11px] text-gray-900">THANK YOU FOR YOUR PURCHASE!</p>
            <p class="text-[9px] text-gray-400">Please keep this receipt for returns or exchanges.</p>
        </div>

    </div>

</body>
</html>