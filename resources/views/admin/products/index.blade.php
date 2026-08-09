<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Products Inventory & Barcodes - Finexy POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- JsBarcode Library CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #E2E8F0; }</style>
</head>
<body class="p-4 sm:p-6 lg:p-8 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-[1360px] bg-white rounded-[32px] shadow-2xl p-6 lg:p-8 border border-gray-100 space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-[#F05423]">← Back to Dashboard</a>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mt-1">Products & Barcode Inventory</h1>
            </div>
            <a href="{{ route('admin.products.create') }}" class="bg-[#F05423] text-white px-5 py-2.5 rounded-2xl text-xs font-bold shadow-md shadow-orange-500/20 hover:opacity-90">
                + Add New Product
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-2xl text-xs font-bold border border-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#F6F7F9] p-6 rounded-[28px] overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="text-gray-400 text-[11px] border-b border-gray-200 pb-2">
                    <tr>
                        <th class="pb-3">Image</th>
                        <th class="pb-3">Product Name</th>
                        <th class="pb-3">Category</th>
                        <th class="pb-3">Barcode (SKU)</th>
                        <th class="pb-3">Price</th>
                        <th class="pb-3">Stock</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 font-medium">
                    @forelse($products as $product)
                        <tr>
                            <td class="py-3">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-10 h-10 rounded-xl object-cover border border-gray-200">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-gray-200 flex items-center justify-center text-gray-400 text-[10px] font-bold">No Image</div>
                                @endif
                            </td>
                            <td class="py-3 font-bold text-gray-900">{{ $product->name }}</td>
                            <td class="py-3 text-gray-500">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="py-3">
                                <div class="flex flex-col items-start">
                                    <svg class="barcode-svg" data-barcode="{{ $product->sku }}"></svg>
                                    <span class="text-[10px] font-mono text-gray-400">{{ $product->sku }}</span>
                                </div>
                            </td>
                            <td class="py-3 font-extrabold text-[#F05423]">${{ number_format($product->price, 2) }}</td>
                            <td class="py-3">
                                <span class="{{ $product->stock <= 5 ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }} px-2 py-0.5 rounded-full text-[10px] font-bold">
                                    {{ $product->stock }} units
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="bg-white border border-gray-200 px-3 py-1.5 rounded-xl text-gray-700 hover:bg-gray-100 font-bold">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1.5 rounded-xl font-bold hover:bg-rose-100">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-6 text-gray-400">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>

    <script>
        // Generate SVG Barcodes automatically for all SKU items
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.barcode-svg').forEach(function(svgElement) {
                const skuCode = svgElement.getAttribute('data-barcode');
                if (skuCode) {
                    JsBarcode(svgElement, skuCode, {
                        format: "CODE128",
                        width: 1.2,
                        height: 30,
                        displayValue: false,
                        margin: 0
                    });
                }
            });
        });
    </script>
</body>
</html>