<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS Terminal - Finexy POS</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #E2E8F0; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
    </style>
</head>
<body class="antialiased text-gray-800 min-h-screen p-3 sm:p-6 lg:p-8 flex items-center justify-center">

    <!-- MAIN TERMINAL CONTAINER -->
    <div class="w-full max-w-[1400px] bg-white rounded-[32px] shadow-2xl p-6 lg:p-8 border border-gray-100 flex flex-col min-h-[85vh]">
        
        <!-- HEADER TOP BAR -->
        <header class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#F05423] rounded-2xl flex items-center justify-center text-white font-extrabold text-lg shadow-md shadow-orange-500/30">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">Finexy POS</span>
                    <span class="text-xs font-semibold text-[#F05423] bg-orange-100 px-2 py-0.5 rounded-full ml-2">Cashier Terminal</span>
                </div>
            </div>

            <!-- Header Actions & Exit Button -->
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <div class="text-xs font-bold text-gray-900">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-gray-400 capitalize">{{ auth()->user()->role->value ?? auth()->user()->role }}</div>
                </div>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-2xl text-xs font-bold transition-all">
                        ← Exit Terminal
                    </a>
                @else
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-2xl text-xs font-bold transition-all">
                            Log out
                        </button>
                    </form>
                @endif
            </div>
        </header>

        <!-- TERMINAL GRID LAYOUT (PRODUCTS + CART) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 flex-1">
            
            <!-- LEFT AREA: PRODUCTS CATALOG (8 COLS) -->
            <div class="lg:col-span-7 xl:col-span-8 flex flex-col space-y-4">
                
                <!-- SEARCH & CATEGORY FILTER -->
                <div class="flex flex-col sm:flex-row gap-3 items-center justify-between">
                    <!-- Search Input -->
                    <div class="relative w-full sm:w-80">
                        <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="searchInput" placeholder="Search product name or SKU..." class="w-full pl-10 pr-4 py-2.5 bg-[#F6F7F9] border-0 rounded-full text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#F05423]">
                    </div>

                    <!-- Category Pills -->
                    <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto pb-1 custom-scrollbar">
                        <button onclick="filterCategory('all')" class="cat-btn active bg-[#F05423] text-white px-4 py-2 rounded-full text-xs font-bold shadow-sm whitespace-nowrap">
                            All Items
                        </button>
                        @foreach($categories as $category)
                            <button onclick="filterCategory('{{ $category->id }}')" class="cat-btn bg-[#F6F7F9] hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- PRODUCT CARDS GRID -->
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 overflow-y-auto max-h-[580px] p-1 custom-scrollbar" id="productGrid">
                    @forelse($products as $product)
                        <div onclick="addToCart({{ json_encode($product) }})" data-category="{{ $product->category_id }}" data-name="{{ strtolower($product->name) }}" data-sku="{{ strtolower($product->sku) }}" class="product-card bg-[#F6F7F9] hover:bg-orange-50 border border-transparent hover:border-[#F05423]/30 p-4 rounded-[24px] cursor-pointer transition-all transform hover:-translate-y-1 flex flex-col justify-between">
                            <div>
                                <div class="w-full h-28 rounded-2xl bg-white mb-3 overflow-hidden flex items-center justify-center border border-gray-100">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @endif
                                </div>
                                <h4 class="font-bold text-xs text-gray-900 line-clamp-1">{{ $product->name }}</h4>
                                <p class="text-[10px] text-gray-400 font-mono">{{ $product->sku }}</p>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-sm font-extrabold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full">Stock: {{ $product->stock }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 text-gray-400 font-semibold">No active products available.</div>
                    @endforelse
                </div>

            </div>

            <!-- RIGHT AREA: CART ORDER SUMMARY -->
            <div class="lg:col-span-5 xl:col-span-4 bg-[#F6F7F9] p-6 rounded-[28px] flex flex-col justify-between">
                
                <div>
                    <!-- Cart Title -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-200/80 mb-4">
                        <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#F05423]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Current Order
                        </h3>
                        <button onclick="clearCart()" class="text-[11px] font-bold text-rose-500 hover:underline">Clear All</button>
                    </div>

                    <!-- Cart Items Scrollable List -->
                    <div class="space-y-3 overflow-y-auto max-h-[320px] pr-1 custom-scrollbar" id="cartItemsList">
                        <!-- Items rendered via JS -->
                    </div>
                </div>

                <!-- CART TOTALS & PAYMENTS -->
                <div class="pt-4 border-t border-gray-200/80 space-y-3">
                    <div class="space-y-1.5 text-xs text-gray-500 font-medium">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-bold text-gray-900" id="subtotalText">$0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax (5%)</span>
                            <span class="font-bold text-gray-900" id="taxText">$0.00</span>
                        </div>
                        <div class="flex justify-between items-center text-emerald-600">
                            <span>Discount ($)</span>
                            <input type="number" id="discountInput" value="0" min="0" oninput="updateCartTotals()" class="w-20 text-right bg-white border border-gray-200 rounded-lg p-1 text-xs font-bold">
                        </div>
                        <div class="flex justify-between text-base font-extrabold text-gray-900 pt-2 border-t border-gray-200">
                            <span>Total Payable</span>
                            <span class="text-[#F05423]" id="totalText">$0.00</span>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-1">Paid Amount ($)</label>
                            <input type="number" id="paidAmountInput" step="0.01" value="0" oninput="calculateChange()" class="w-full bg-white border border-gray-200 rounded-xl p-2 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-[#F05423]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-1">Change Return</label>
                            <div id="changeText" class="w-full bg-gray-100 rounded-xl p-2 text-xs font-extrabold text-emerald-600">$0.00</div>
                        </div>
                    </div>

                    <!-- Payment Method Select -->
                    <div class="flex gap-2">
                        <button onclick="setPaymentMethod('cash')" id="payCashBtn" class="pay-method-btn flex-1 py-2 bg-[#F05423] text-white rounded-xl text-xs font-bold">Cash</button>
                        <button onclick="setPaymentMethod('card')" id="payCardBtn" class="pay-method-btn flex-1 py-2 bg-white text-gray-700 rounded-xl text-xs font-bold border border-gray-200">Card</button>
                        <button onclick="setPaymentMethod('mobile')" id="payMobileBtn" class="pay-method-btn flex-1 py-2 bg-white text-gray-700 rounded-xl text-xs font-bold border border-gray-200">Mobile KPay</button>
                    </div>

                    <!-- Checkout Complete Button -->
                    <button onclick="submitCheckout()" id="checkoutBtn" class="w-full py-3 bg-[#F05423] text-white font-extrabold text-xs rounded-2xl shadow-lg shadow-orange-500/30 hover:opacity-90 transition-all">
                        COMPLETE ORDER & CHECKOUT
                    </button>
                </div>

            </div>

        </div>

    </div>

    <!-- JAVASCRIPT CART LOGIC -->
    <script>
        let cart = [];
        let paymentMethod = 'cash';

        function addToCart(product) {
            let existing = cart.find(item => item.id === product.id);
            if (existing) {
                if (existing.qty < product.stock) {
                    existing.qty++;
                } else {
                    alert('Cannot add more. Stock limit reached!');
                }
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.price),
                    stock: product.stock,
                    qty: 1
                });
            }
            renderCart();
        }

        function renderCart() {
            let cartList = document.getElementById('cartItemsList');
            cartList.innerHTML = '';

            if (cart.length === 0) {
                cartList.innerHTML = `<div class="text-center py-12 text-gray-400 text-xs font-semibold">Cart is empty. Select products from left.</div>`;
                updateCartTotals();
                return;
            }

            cart.forEach((item, index) => {
                cartList.innerHTML += `
                    <div class="bg-white p-3 rounded-2xl flex items-center justify-between shadow-sm">
                        <div class="flex-1 pr-2">
                            <h5 class="font-bold text-xs text-gray-900 line-clamp-1">${item.name}</h5>
                            <span class="text-[11px] font-bold text-gray-500">$${item.price.toFixed(2)}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="changeQty(${index}, -1)" class="w-6 h-6 rounded-lg bg-gray-100 font-bold text-xs">-</button>
                            <span class="text-xs font-extrabold text-gray-900 px-1">${item.qty}</span>
                            <button onclick="changeQty(${index}, 1)" class="w-6 h-6 rounded-lg bg-gray-100 font-bold text-xs">+</button>
                            <button onclick="removeItem(${index})" class="text-rose-500 font-bold text-xs ml-2">✕</button>
                        </div>
                    </div>
                `;
            });

            updateCartTotals();
        }

        function changeQty(index, delta) {
            cart[index].qty += delta;
            if (cart[index].qty <= 0) {
                cart.splice(index, 1);
            } else if (cart[index].qty > cart[index].stock) {
                cart[index].qty = cart[index].stock;
                alert('Stock limit reached!');
            }
            renderCart();
        }

        function removeItem(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function clearCart() {
            cart = [];
            renderCart();
        }

        function updateCartTotals() {
            let subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            let tax = subtotal * 0.05;
            let discount = parseFloat(document.getElementById('discountInput').value) || 0;
            let total = Math.max(0, subtotal + tax - discount);

            document.getElementById('subtotalText').innerText = `$${subtotal.toFixed(2)}`;
            document.getElementById('taxText').innerText = `$${tax.toFixed(2)}`;
            document.getElementById('totalText').innerText = `$${total.toFixed(2)}`;

            calculateChange();
        }

        function calculateChange() {
            let subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            let tax = subtotal * 0.05;
            let discount = parseFloat(document.getElementById('discountInput').value) || 0;
            let total = Math.max(0, subtotal + tax - discount);

            let paid = parseFloat(document.getElementById('paidAmountInput').value) || 0;
            let change = paid - total;

            document.getElementById('changeText').innerText = `$${Math.max(0, change).toFixed(2)}`;
        }

        function setPaymentMethod(method) {
            paymentMethod = method;
            document.querySelectorAll('.pay-method-btn').forEach(btn => {
                btn.classList.remove('bg-[#F05423]', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700');
            });
            event.target.classList.remove('bg-white', 'text-gray-700');
            event.target.classList.add('bg-[#F05423]', 'text-white');
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            let query = e.target.value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                let name = card.dataset.name;
                let sku = card.dataset.sku;
                if (name.includes(query) || sku.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        function filterCategory(catId) {
            document.querySelectorAll('.cat-btn').forEach(btn => {
                btn.classList.remove('bg-[#F05423]', 'text-white');
                btn.classList.add('bg-[#F6F7F9]', 'text-gray-600');
            });
            event.target.classList.remove('bg-[#F6F7F9]', 'text-gray-600');
            event.target.classList.add('bg-[#F05423]', 'text-white');

            document.querySelectorAll('.product-card').forEach(card => {
                if (catId === 'all' || card.dataset.category === catId) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function submitCheckout() {
            if (cart.length === 0) {
                alert('Cart is empty!');
                return;
            }

            let subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            let tax = subtotal * 0.05;
            let discount = parseFloat(document.getElementById('discountInput').value) || 0;
            let total = Math.max(0, subtotal + tax - discount);
            let paid = parseFloat(document.getElementById('paidAmountInput').value) || 0;

            if (paid < total) {
                alert('Paid amount is less than total payable amount!');
                return;
            }

            let payload = {
                items: cart,
                subtotal: subtotal,
                tax: tax,
                discount: discount,
                total: total,
                paid_amount: paid,
                change_amount: paid - total,
                payment_method: paymentMethod
            };

            fetch('/pos/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Order Successful!\nOrder No: ' + data.order_number);
                    clearCart();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                alert('Server Error!');
            });
        }

        renderCart();
    </script>
</body>
</html>