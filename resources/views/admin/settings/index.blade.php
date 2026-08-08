<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Store Settings - Finexy POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #E2E8F0; }</style>
</head>
<body class="p-4 sm:p-6 lg:p-8 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-[800px] bg-white rounded-[32px] shadow-2xl p-6 lg:p-8 border border-gray-100 space-y-6">
        
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-[#F05423]">← Back to Dashboard</a>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mt-1">POS Store Settings</h1>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl text-xs font-bold border border-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4 text-xs font-semibold text-gray-700">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Store Name</label>
                    <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name']) }}" required class="w-full bg-[#F6F7F9] border-0 rounded-2xl p-3 focus:ring-2 focus:ring-[#F05423]">
                </div>

                <div>
                    <label class="block mb-1">Store Phone Number</label>
                    <input type="text" name="store_phone" value="{{ old('store_phone', $settings['store_phone']) }}" required class="w-full bg-[#F6F7F9] border-0 rounded-2xl p-3 focus:ring-2 focus:ring-[#F05423]">
                </div>
            </div>

            <div>
                <label class="block mb-1">Store Address (Appears on Receipts)</label>
                <textarea name="store_address" rows="3" required class="w-full bg-[#F6F7F9] border-0 rounded-2xl p-3 focus:ring-2 focus:ring-[#F05423]">{{ old('store_address', $settings['store_address']) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Default Tax Rate (%)</label>
                    <input type="number" step="0.1" name="tax_rate" value="{{ old('tax_rate', $settings['tax_rate']) }}" required class="w-full bg-[#F6F7F9] border-0 rounded-2xl p-3 focus:ring-2 focus:ring-[#F05423]">
                </div>

                <div>
                    <label class="block mb-1">Currency Symbol</label>
                    <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" required class="w-full bg-[#F6F7F9] border-0 rounded-2xl p-3 focus:ring-2 focus:ring-[#F05423]">
                </div>
            </div>

            <button type="submit" class="w-full bg-[#F05423] text-white py-3 rounded-2xl font-bold shadow-md shadow-orange-500/20 hover:opacity-90">
                Save Store Configurations
            </button>
        </form>

    </div>
</body>
</html>