<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add Product - Finexy POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #E2E8F0; }</style>
</head>
<body class="p-4 sm:p-6 lg:p-8 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-[800px] bg-white rounded-[32px] shadow-2xl p-6 lg:p-8 border border-gray-100 space-y-6">
        
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-extrabold text-gray-900">Add New Product</h1>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-gray-400 hover:text-[#F05423]">Cancel</a>
        </div>

        <!-- Validation Errors Display -->
        @if ($errors->any())
            <div class="bg-rose-50 text-rose-600 p-4 rounded-2xl text-xs font-bold border border-rose-100">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs font-semibold text-gray-700">
            @csrf
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Product Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-[#F6F7F9] border-0 rounded-2xl p-3 focus:ring-2 focus:ring-[#F05423]">
                </div>

                <div>
                    <label class="block mb-1">SKU Code</label>
                    <input type="text" name="sku" value="{{ old('sku', 'PRD-' . rand(1000, 9999)) }}" required class="w-full bg-[#F6F7F9] border-0 rounded-2xl p-3 focus:ring-2 focus:ring-[#F05423]">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block mb-1">Category</label>
                    <select name="category_id" required class="w-full bg-[#F6F7F9] border-0 rounded-2xl p-3 focus:ring-2 focus:ring-[#F05423]">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1">Price ($)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" required class="w-full bg-[#F6F7F9] border-0 rounded-2xl p-3 focus:ring-2 focus:ring-[#F05423]">
                </div>

                <div>
                    <label class="block mb-1">Stock Quantity</label>
                    <input type="number" name="stock" value="{{ old('stock', 10) }}" required class="w-full bg-[#F6F7F9] border-0 rounded-2xl p-3 focus:ring-2 focus:ring-[#F05423]">
                </div>
            </div>

            <div>
                <label class="block mb-1">Product Image</label>
                <input type="file" name="image" class="w-full bg-[#F6F7F9] rounded-2xl p-2">
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active" checked value="1" class="rounded text-[#F05423]">
                <label for="is_active">Active Product</label>
            </div>

            <button type="submit" class="w-full bg-[#F05423] text-white py-3 rounded-2xl font-bold shadow-md shadow-orange-500/20 hover:opacity-90">
                Save Product
            </button>
        </form>
    </div>
</body>
</html>