<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel POS') }} - Staff Terminal</title>

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
                <span class="text-xl font-bold tracking-tight text-gray-900">Finexy <span class="text-xs font-semibold text-[#F05423] bg-orange-100 px-2 py-0.5 rounded-full ml-1">POS Staff</span></span>
            </div>

            <!-- Search Bar -->
            <div class="relative w-full md:w-[450px]">
                <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search product for checkout..." class="w-full pl-10 pr-16 py-2.5 bg-[#F6F7F9] border-0 rounded-full text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#F05423] placeholder-gray-400">
                <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1 text-[10px] font-bold text-gray-400 bg-white px-2 py-0.5 rounded-md border border-gray-200">
                    K <span class="text-[9px]">⤢</span>
                </div>
            </div>

            <!-- Notifications & Profile -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 bg-emerald-50 px-3 py-1.5 rounded-full text-emerald-600 text-xs font-bold border border-emerald-100">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span> Terminal Active
                </div>

                <!-- Staff Profile Card -->
                <div class="flex items-center gap-2.5 bg-[#F6F7F9] pl-1.5 pr-3 py-1 rounded-full ml-1">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 rounded-full bg-[#F05423] text-white flex items-center justify-center font-bold text-xs shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="text-left">
                        <div class="text-xs font-bold text-gray-900 leading-tight">{{ $user->name }}</div>
                        <div class="text-[10px] text-gray-400 font-medium">Cashier Staff</div>
                    </div>
                    <span class="text-[10px] text-gray-400 ml-1">▾</span>
                </div>
            </div>
        </header>

        <!-- DASHBOARD GRID LAYOUT -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT SIDEBAR -->
            <aside class="lg:col-span-2 space-y-6">
                <!-- Menu Category -->
                <div class="space-y-1">
                    <div class="px-3 text-[11px] font-semibold text-gray-400 mb-2">POS Operations</div>
                    
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-[#F05423] text-white font-semibold text-xs shadow-md shadow-orange-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        POS Terminal
                    </a>

                    <a href="#" class="flex items-center justify-between px-4 py-2.5 rounded-2xl text-gray-600 hover:bg-[#F6F7F9] font-medium text-xs transition-all">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Sales Orders
                        </div>
                        <span class="bg-orange-100 text-[#F05423] text-[10px] font-bold px-2 py-0.5 rounded-full">New</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-gray-600 hover:bg-[#F6F7F9] font-medium text-xs transition-all">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Products List
                    </a>

                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-gray-600 hover:bg-[#F6F7F9] font-medium text-xs transition-all">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Receipt History
                    </a>
                </div>

                <!-- Account Category -->
                <div class="space-y-1 pt-4 border-t border-gray-100">
                    <div class="px-3 text-[11px] font-semibold text-gray-400 mb-2">Account</div>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-gray-600 hover:bg-[#F6F7F9] font-medium text-xs transition-all">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        My Profile
                    </a>
                </div>

                <!-- Log out -->
                <div class="pt-6">
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
                
                <!-- WELCOME BANNER -->
                <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-[#F05423] p-6 rounded-[28px] text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 shadow-xl">
                    <div class="flex items-center gap-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-12 h-12 rounded-2xl object-cover border-2 border-white/20">
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-white/10 text-white flex items-center justify-center font-bold text-lg border-2 border-white/20">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">Shift Active</span>
                            <h1 class="text-xl font-bold mt-1">Welcome, {{ $user->name }}!</h1>
                            <p class="text-xs text-gray-300">Ready to handle customer transactions and barcode checkout.</p>
                        </div>
                    </div>
                    <a href="{{ route('pos.index') }}" class="bg-white text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-2xl text-xs font-bold transition-all shadow-md inline-block">
                        Open POS Terminal
                    </a>
                </div>

                <!-- 3 STAT CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Card 1 -->
                    <div class="bg-[#F6F7F9] p-5 rounded-[24px]">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-medium text-gray-500">Available Categories</span>
                            <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-xs shadow-sm">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            </div>
                        </div>
                        <div class="mt-3 flex items-baseline gap-2">
                            <span class="text-2xl font-extrabold text-gray-900">{{ $activeCategoryCount }}</span>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-100 px-1.5 py-0.5 rounded-md">Active</span>
                        </div>
                        <div class="mt-2 text-[11px] text-gray-400">Ready for store item filtering</div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-[#F6F7F9] p-5 rounded-[24px]">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-medium text-gray-500">Today Sales Processed</span>
                            <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-xs shadow-sm">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                        </div>
                        <div class="mt-3 flex items-baseline gap-2">
                            <span class="text-2xl font-extrabold text-gray-900">0 Orders</span>
                        </div>
                        <div class="mt-2 text-[11px] text-gray-400">Shift started recently</div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-[#F6F7F9] p-5 rounded-[24px]">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-medium text-gray-500">Terminal Status</span>
                            <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-xs shadow-sm">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                        </div>
                        <div class="mt-3 flex items-baseline gap-2">
                            <span class="text-xl font-extrabold text-emerald-600">Online & Ready</span>
                        </div>
                        <div class="mt-2 text-[11px] text-gray-400">Connected to POS Local Server</div>
                    </div>
                </div>

                <!-- POS CASHIER TERMINAL CARD -->
                <div class="bg-[#F6F7F9] p-8 rounded-[28px] text-center space-y-4">
                    <div class="w-16 h-16 bg-white rounded-3xl shadow-sm flex items-center justify-center mx-auto text-2xl">
                        <svg class="w-8 h-8 text-[#F05423]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">POS Cashier Terminal Standby</h3>
                        <p class="text-xs text-gray-400 max-w-md mx-auto mt-1">
                            Product catalog grid, quick barcode scanning, order cart, and receipt printing modules are fully ready.
                        </p>
                    </div>
                </div>

            </main>

        </div>

    </div>

</body>
</html>