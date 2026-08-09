<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel POS') }} - Admin Dashboard</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E2E8F0;
        }
    </style>
</head>
<body class="antialiased text-gray-800 min-h-screen p-3 sm:p-6 lg:p-8 flex items-center justify-center">

    <!-- MAIN CONTAINER CARD -->
    <div class="w-full max-w-[1360px] bg-white rounded-[32px] shadow-2xl p-6 lg:p-8 border border-gray-100">
        
        <!-- HEADER TOP BAR -->
        <header class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
            <!-- Brand Logo -->
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-[#F05423] rounded-2xl flex items-center justify-center text-white font-extrabold text-base shadow-md shadow-orange-500/30">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-gray-900">Finexy</span>
            </div>

            <!-- Search Input -->
            <div class="relative w-full md:w-[450px]">
                <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search product" class="w-full pl-10 pr-16 py-2.5 bg-[#F6F7F9] border-0 rounded-full text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#F05423] placeholder-gray-400">
                <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1 text-[10px] font-bold text-gray-400 bg-white px-2 py-0.5 rounded-md border border-gray-200">
                    K <span class="text-[9px]">⤢</span>
                </div>
            </div>

            <!-- Notifications & Profile -->
            <div class="flex items-center gap-3">
                <!-- Notification Bell with Low Stock Alert Count -->
                <a href="#low-stock-section" class="w-9 h-9 rounded-full bg-[#F6F7F9] flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-all text-sm relative">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if($lowStockCount > 0)
                        <span class="absolute -top-1 -right-1 bg-rose-600 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-sm">
                            {{ $lowStockCount }}
                        </span>
                    @endif
                </a>

                <!-- Admin Profile Card -->
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 bg-[#F6F7F9] hover:bg-gray-200 pl-1.5 pr-3 py-1 rounded-full ml-1 transition-all">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 rounded-full bg-[#F05423] text-white flex items-center justify-center font-bold text-xs shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="text-left">
                        <div class="text-xs font-bold text-gray-900 leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-gray-400 font-medium">Admin Profile</div>
                    </div>
                    <span class="text-[10px] text-gray-400 ml-1">▾</span>
                </a>
            </div>
        </header>

        <!-- DASHBOARD GRID LAYOUT -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT SIDEBAR -->
            <aside class="lg:col-span-2 space-y-6">
                <!-- Menu Category -->
                <div class="space-y-1">
                    <div class="px-3 text-[11px] font-semibold text-gray-400 mb-2">Menu</div>
                    
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-[#F05423] text-white font-semibold text-xs shadow-md shadow-orange-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.sales.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-2xl text-gray-600 hover:bg-[#F6F7F9] font-medium text-xs transition-all">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Sales Orders
                        </div>
                    </a>

                    <a href="{{ route('admin.reports.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-2xl text-gray-600 hover:bg-[#F6F7F9] font-medium text-xs transition-all">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Sales Reports
                        </div>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-gray-600 hover:bg-[#F6F7F9] font-medium text-xs transition-all">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Users & Staff
                    </a>
                </div>

                <!-- Products Category -->
                <div class="space-y-1">
                    <div class="px-3 text-[11px] font-semibold text-gray-400 mb-2">Products</div>
                    
                    <a href="{{ route('admin.products.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-2xl text-gray-600 hover:bg-[#F6F7F9] font-medium text-xs transition-all">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Products Inventory
                        </div>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-2xl text-gray-600 hover:bg-[#F6F7F9] font-medium text-xs transition-all">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            Categories
                        </div>
                    </a>
                </div>

                <!-- General Category -->
                <div class="space-y-1 pt-2">
                    <div class="px-3 text-[11px] font-semibold text-gray-400 mb-2">General</div>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-gray-600 hover:bg-[#F6F7F9] font-medium text-xs transition-all">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        My Profile
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-gray-600 hover:bg-[#F6F7F9] font-medium text-xs transition-all">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        Store Settings
                    </a>
                </div>

                <!-- Log out -->
                <div class="pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-2xl text-red-500 bg-[#F6F7F9] hover:bg-red-50 font-bold text-xs transition-all">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Log out
                        </button>
                    </form>
                </div>
            </aside>

            <!-- RIGHT MAIN PANEL -->
            <main class="lg:col-span-10 space-y-6">
                
                <!-- TITLE & DYNAMIC DATE PICKER DISPLAY -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Sales Overview</h1>
                    
                    <div class="flex items-center gap-2 bg-[#F6F7F9] px-4 py-2 rounded-full text-xs font-semibold text-gray-700">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ $startDate }} – {{ $endDate }}</span>
                        <span class="text-gray-400 ml-1">▾</span>
                    </div>
                </div>

                <!-- 4 METRIC CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Card 1 -->
                    <div class="bg-[#F6F7F9] p-5 rounded-[24px]">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-medium text-gray-500">Total Sales</span>
                            <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-xs shadow-sm">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                        </div>
                        <div class="mt-3 flex items-baseline gap-2">
                            <span class="text-2xl font-extrabold text-gray-900">{{ $totalSalesCount }}</span>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-100 px-1.5 py-0.5 rounded-md">This Month</span>
                        </div>
                        <div class="mt-2 text-[11px] text-gray-400">Processed Orders</div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-[#F6F7F9] p-5 rounded-[24px]">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-medium text-gray-500">New Customer</span>
                            <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-xs shadow-sm">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        </div>
                        <div class="mt-3 flex items-baseline gap-2">
                            <span class="text-2xl font-extrabold text-gray-900">110</span>
                            <span class="text-[10px] font-bold text-orange-600 bg-orange-100 px-1.5 py-0.5 rounded-md">↑ 7.5%</span>
                        </div>
                        <div class="mt-2 text-[11px] text-gray-400">Last month: <span class="font-semibold text-gray-600">89</span></div>
                    </div>

                    <!-- Card 3: Low Stock Warning Stat Card -->
                    <div class="bg-[#F6F7F9] p-5 rounded-[24px]">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-medium text-gray-500">Low Stock Alert</span>
                            <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-xs shadow-sm">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                        </div>
                        <div class="mt-3 flex items-baseline gap-2">
                            <span class="text-2xl font-extrabold text-rose-600">{{ $lowStockCount }} Items</span>
                            @if($lowStockCount > 0)
                                <span class="text-[10px] font-bold text-rose-600 bg-rose-100 px-1.5 py-0.5 rounded-md">Action Required</span>
                            @else
                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-100 px-1.5 py-0.5 rounded-md">Stock Healthy</span>
                            @endif
                        </div>
                        <div class="mt-2 text-[11px] text-gray-400">Products with stock <= 5</div>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-[#F6F7F9] p-5 rounded-[24px]">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-medium text-gray-500">Total Revenue</span>
                            <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-xs shadow-sm font-bold text-gray-600">$</div>
                        </div>
                        <div class="mt-3">
                            <span class="text-2xl font-extrabold text-[#F05423]">${{ number_format($totalRevenue, 2) }}</span>
                        </div>
                        <div class="mt-2 text-[11px] text-gray-400">Current Month Total</div>
                    </div>
                </div>

                <!-- STEP 13: LOW STOCK WARNING TABLE (WIDGET) -->
                @if($lowStockCount > 0)
                    <div id="low-stock-section" class="bg-rose-50 border border-rose-200 p-6 rounded-[28px] space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-rose-700 font-extrabold text-sm">
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Low Stock Inventory Alert
                            </div>
                            <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-rose-700 hover:underline">
                                Update Inventory Stock →
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-gray-700">
                                <thead class="text-rose-400 text-[11px] border-b border-rose-200 pb-2">
                                    <tr>
                                        <th class="pb-2">Product Name</th>
                                        <th class="pb-2">SKU</th>
                                        <th class="pb-2">Price</th>
                                        <th class="pb-2">Remaining Stock</th>
                                        <th class="pb-2 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-rose-200/60 font-semibold">
                                    @foreach($lowStockProducts as $lowProduct)
                                        <tr>
                                            <td class="py-2.5 font-bold text-gray-900">{{ $lowProduct->name }}</td>
                                            <td class="py-2.5 text-gray-500 font-mono">{{ $lowProduct->sku }}</td>
                                            <td class="py-2.5 font-extrabold">${{ number_format($lowProduct->price, 2) }}</td>
                                            <td class="py-2.5">
                                                <span class="{{ $lowProduct->stock == 0 ? 'bg-rose-600 text-white' : 'bg-rose-200 text-rose-800' }} px-2 py-0.5 rounded-full text-[10px] font-bold">
                                                    {{ $lowProduct->stock == 0 ? 'Out of Stock' : $lowProduct->stock . ' units left' }}
                                                </span>
                                            </td>
                                            <td class="py-2.5 text-right">
                                                <a href="{{ route('admin.products.edit', $lowProduct->id) }}" class="bg-white px-2.5 py-1 rounded-xl text-[11px] font-bold text-rose-700 border border-rose-200 hover:bg-rose-100">
                                                    Restock
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- RECENT ORDERS TABLE (DYNAMIC REAL-TIME) -->
                <div class="bg-[#F6F7F9] p-6 rounded-[28px]">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-5">
                        <h3 class="font-bold text-gray-900 text-sm">Recent orders</h3>
                        
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <a href="{{ route('admin.sales.index') }}" class="text-xs font-bold text-[#F05423] hover:underline">
                                View All Orders →
                            </a>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-gray-600">
                            <thead class="text-gray-400 text-[11px] border-b border-gray-200 pb-2">
                                <tr>
                                    <th class="pb-3">Order Id</th>
                                    <th class="pb-3">Date & Time</th>
                                    <th class="pb-3">Cashier Staff</th>
                                    <th class="pb-3">Payment</th>
                                    <th class="pb-3">Items</th>
                                    <th class="pb-3 text-right">Total</th>
                                    <th class="pb-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200/60 font-medium">
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td class="py-3 font-bold text-gray-900">{{ $order->order_number }}</td>
                                        <td class="py-3 text-gray-500">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                        <td class="py-3 font-bold text-gray-900">{{ $order->user->name ?? 'Staff' }}</td>
                                        <td class="py-3">
                                            <span class="bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase">
                                                {{ $order->payment_method }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-gray-500">{{ $order->items->sum('quantity') }} Items</td>
                                        <td class="py-3 text-right font-extrabold text-[#F05423]">${{ number_format($order->total, 2) }}</td>
                                        <td class="py-3 text-right">
                                            <a href="{{ route('admin.sales.show', $order->id) }}" class="bg-white border border-gray-200 px-2.5 py-1 rounded-xl text-[11px] font-bold text-gray-700 hover:bg-gray-100">
                                                Receipt
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-6 text-gray-400 font-semibold">No recent orders found. Open POS Terminal to process sales!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>

    </div>

</body>
</html>