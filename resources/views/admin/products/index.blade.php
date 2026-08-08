<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Products Management - Finexy POS</title>
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
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mt-1">Product Inventory</h1>
            </div>
            <a href="{{ route('admin.products.create') }}" class="bg-[#F05423] text-white px-5 py-2.5 rounded-2xl text-xs font-bold shadow-md shadow-orange-500/20 hover:opacity-90 transition-all">
                + Add New Product
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl text-xs font-bold border border-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        <!-- Products Table -->
        <div class="bg-[#F6F7F9] p-6 rounded-[28px]">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="text-gray-400 text-[11px] border-b border-gray-200 pb-2">
                        <tr>
                            <th class="pb-3">Image</th>
                            <th class="pb-3">Product Name</th>
                            <th class="pb-3">SKU</th>
                            <th class="pb-3">Category</th>
                            <th class="pb-3">Price</th>
                            <th class="pb-3">Stock</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/60 font-medium">
                        @forelse($products as $product)
                            <tr>
                                <td class="py-3">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-10 h-10 rounded-xl object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-gray-200 flex items-center justify-center text-xs">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 font-bold text-gray-900">{{ $product->name }}</td>
                                <td class="py-3 text-gray-500 font-mono">{{ $product->sku }}</td>
                                <td class="py-3 text-gray-500">{{ $product->category->name }}</td>
                                <td class="py-3 font-extrabold text-gray-900">${{ number_format($product->price, 2) }}</td>
                                <td class="py-3 font-bold {{ $product->stock > 5 ? 'text-gray-700' : 'text-rose-600' }}">{{ $product->stock }}</td>
                                <td class="py-3">
                                    <span class="{{ $product->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-200 text-gray-600' }} px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="py-3 text-right flex justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="bg-white px-3 py-1.5 rounded-xl text-xs font-bold text-gray-700 border border-gray-100 hover:bg-gray-100">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Delete product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-50 text-red-600 px-3 py-1.5 rounded-xl text-xs font-bold hover:bg-red-100">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-6 text-gray-400">No products found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $products->links() }}</div>
        </div>

    </div>
</body>
</html>