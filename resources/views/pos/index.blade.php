<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS Terminal - Finexy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #E2E8F0; }</style>
</head>
<body class="p-3 sm:p-6 lg:p-8 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-[1360px] bg-white rounded-[32px] shadow-2xl p-6 lg:p-8 border border-gray-100 space-y-6">
        
        <!-- TOP NAVIGATION BAR -->
        <div class="flex justify-between items-center pb-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-[#F05423]">
                    ← Exit POS Terminal
                </a>
                <h1 class="text-xl font-extrabold text-gray-900">Checkout Terminal</h1>
            </div>

            <div class="flex items-center gap-2 bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold border border-emerald-100">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                Barcode Scanner, Loyalty & Rating Active
            </div>
        </div>

        <!-- POS MAIN GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT PRODUCTS AREA (7 COLS) -->
            <div class="lg:col-span-7 space-y-4">
                <!-- Search & Category Filters -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 0 0114 0z"/></svg>
                        <input type="text" id="searchInput" onkeyup="filterProducts()" placeholder="Scan Barcode or Search product..." class="w-full pl-10 pr-4 py-2.5 bg-[#F6F7F9] border-0 rounded-2xl text-xs font-medium focus:ring-2 focus:ring-[#F05423]">
                    </div>

                    <select id="categoryFilter" onchange="filterProducts()" class="bg-[#F6F7F9] border-0 rounded-2xl text-xs font-semibold px-4 py-2.5 text-gray-700 focus:ring-2 focus:ring-[#F05423]">
                        <option value="all">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- PRODUCT CARDS GRID -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 overflow-y-auto max-h-[580px] p-1" id="productGrid">
                    @forelse($products as $product)
                        <div @if($product->stock > 0) onclick="addToCart({{ json_encode($product) }})" @endif data-category="{{ $product->category_id }}" data-name="{{ strtolower($product->name) }}" data-sku="{{ strtolower($product->sku) }}" class="product-card bg-[#F6F7F9] {{ $product->stock == 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-50 border border-transparent hover:border-[#F05423]/30 cursor-pointer transform hover:-translate-y-1' }} p-4 rounded-[24px] transition-all flex flex-col justify-between">
                            <div>
                                <div class="w-full h-28 rounded-2xl bg-white mb-3 overflow-hidden flex items-center justify-center border border-gray-100 relative">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @endif

                                    @if($product->stock == 0)
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white font-extrabold text-xs uppercase tracking-wider">Out of Stock</div>
                                    @endif
                                </div>
                                <h4 class="font-bold text-xs text-gray-900 line-clamp-1">{{ $product->name }}</h4>
                                <p class="text-[10px] text-gray-400 font-mono">{{ $product->sku }}</p>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-sm font-extrabold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                @if($product->stock == 0)
                                    <span class="text-[10px] font-bold text-rose-600 bg-rose-100 px-2 py-0.5 rounded-full">Stock: 0</span>
                                @elseif($product->stock <= 5)
                                    <span class="text-[10px] font-bold text-orange-600 bg-orange-100 px-2 py-0.5 rounded-full animate-pulse">Low: {{ $product->stock }}</span>
                                @else
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full">Stock: {{ $product->stock }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 text-gray-400 font-semibold">No active products available.</div>
                    @endforelse
                </div>
            </div>

            <!-- RIGHT CART & CHECKOUT AREA (5 COLS) -->
            <div class="lg:col-span-5 bg-[#F6F7F9] p-6 rounded-[28px] flex flex-col justify-between space-y-4">
                <div>
                    <!-- CUSTOMER LOYALTY MODULE -->
                    <div class="bg-white p-3 rounded-2xl mb-3 border border-gray-100 space-y-2">
                        <div class="flex justify-between items-center text-xs font-bold text-gray-900">
                            <span>Customer Loyalty Profile</span>
                            <button onclick="createNewCustomer()" class="text-[10px] text-[#F05423] font-extrabold hover:underline">+ New Customer</button>
                        </div>
                        <div class="flex gap-2">
                            <input type="text" id="customerSearchInput" placeholder="Enter Phone Number..." class="flex-1 bg-[#F6F7F9] border-0 rounded-xl px-3 py-1.5 text-xs font-semibold focus:ring-1 focus:ring-[#F05423]">
                            <button onclick="searchCustomer()" class="bg-gray-900 text-white px-3 py-1.5 rounded-xl text-xs font-bold hover:bg-gray-800">Search</button>
                        </div>
                        <div id="customerInfo" class="hidden bg-orange-50 p-2.5 rounded-xl border border-orange-100 text-xs font-semibold flex justify-between items-center">
                            <div>
                                <div id="custName" class="font-bold text-gray-900">Guest</div>
                                <div class="text-[10px] text-gray-500">Available Points: <span id="custPoints" class="font-extrabold text-[#F05423]">0</span> pts</div>
                            </div>
                            <button onclick="redeemPoints()" id="redeemBtn" class="bg-[#F05423] text-white px-2.5 py-1 rounded-lg text-[10px] font-bold">Use 10 pts ($1 Off)</button>
                        </div>
                    </div>

                    <h3 class="font-extrabold text-gray-900 text-sm mb-2">Current Order Cart</h3>
                    
                    <!-- Cart Items Container -->
                    <div id="cartItems" class="space-y-2.5 overflow-y-auto max-h-[170px] pr-1">
                        <div class="text-center py-6 text-gray-400 text-xs font-semibold" id="emptyCartMsg">
                            Cart is empty. Scan barcode or click items to add.
                        </div>
                    </div>
                </div>

                <!-- Checkout Calculations & Feedback -->
                <div class="pt-3 border-t border-gray-200/80 space-y-2 text-xs font-semibold">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span id="cartSubtotal" class="font-extrabold text-gray-900">$0.00</span>
                    </div>

                    <div class="flex justify-between text-gray-500">
                        <span>Tax (5%)</span>
                        <span id="cartTax" class="font-extrabold text-gray-900">$0.00</span>
                    </div>

                    <div class="flex justify-between items-center text-gray-500">
                        <span>Points Discount ($)</span>
                        <span id="pointsDiscountText" class="font-bold text-emerald-600">-$0.00</span>
                    </div>

                    <div class="flex justify-between items-center text-gray-500">
                        <span>Manual Discount ($)</span>
                        <input type="number" id="discountInput" value="0" min="0" onchange="calculateTotals()" class="w-20 bg-white border-0 py-1 px-2 text-right rounded-xl font-bold text-gray-900 focus:ring-1 focus:ring-[#F05423]">
                    </div>

                    <!-- ORDER RATING & FEEDBACK -->
                    <div class="bg-white p-2.5 rounded-2xl border border-gray-100 space-y-1.5 my-1">
                        <div class="flex justify-between items-center text-[11px] font-bold text-gray-700">
                            <span>Customer Rating</span>
                            <div class="flex items-center gap-1 cursor-pointer" id="starContainer">
                                <span onclick="setRating(1)" class="star text-amber-400 text-sm">★</span>
                                <span onclick="setRating(2)" class="star text-amber-400 text-sm">★</span>
                                <span onclick="setRating(3)" class="star text-amber-400 text-sm">★</span>
                                <span onclick="setRating(4)" class="star text-amber-400 text-sm">★</span>
                                <span onclick="setRating(5)" class="star text-amber-400 text-sm">★</span>
                            </div>
                        </div>
                        <input type="text" id="orderComment" placeholder="Write feedback/notes (optional)..." class="w-full bg-[#F6F7F9] border-0 rounded-xl px-2.5 py-1 text-[11px] font-medium focus:ring-1 focus:ring-[#F05423]">
                    </div>

                    <div class="flex justify-between text-base font-extrabold text-gray-900 pt-1 border-t border-gray-200">
                        <span>Total Payable</span>
                        <span id="cartTotal" class="text-[#F05423]">$0.00</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <div>
                            <label class="block text-[10px] text-gray-400 mb-1">Payment Method</label>
                            <select id="paymentMethod" class="w-full bg-white border-0 rounded-xl py-1.5 px-3 text-xs font-bold text-gray-700">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="kpay">Mobile KPay</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-400 mb-1">Paid Amount ($)</label>
                            <input type="number" id="paidAmount" min="0" onkeyup="calculateTotals()" placeholder="0.00" class="w-full bg-white border-0 rounded-xl py-1.5 px-3 text-xs font-bold text-gray-900 focus:ring-1 focus:ring-[#F05423]">
                        </div>
                    </div>

                    <div class="flex justify-between text-xs font-bold text-gray-700 bg-white p-2 rounded-2xl">
                        <span>Change Return:</span>
                        <span id="changeReturn" class="text-emerald-600 font-extrabold">$0.00</span>
                    </div>

                    <button onclick="processCheckout()" class="w-full bg-[#F05423] text-white py-3 rounded-2xl font-extrabold text-xs shadow-lg shadow-orange-500/30 hover:opacity-90 transition-all">
                        COMPLETE ORDER & CHECKOUT
                    </button>
                </div>
            </div>

        </div>

    </div>

    <!-- POS JAVASCRIPT LOGIC -->
    <script>
        const productsList = @json($products);
        let cart = [];
        let selectedCustomer = null;
        let pointsDiscountAmount = 0;
        let redeemedPointsCount = 0;
        let selectedRating = 5;

        // STAR RATING LOGIC
        function setRating(rating) {
            selectedRating = rating;
            const stars = document.querySelectorAll('#starContainer .star');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('text-amber-400');
                    star.classList.remove('text-gray-300');
                } else {
                    star.classList.remove('text-amber-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        // WEB AUDIO API FOR BEEP SOUND
        function playBeepSound() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.value = 1200;
                gain.gain.value = 0.1;
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.1);
            } catch (e) {
                console.log("Audio feedback error", e);
            }
        }

        // CUSTOMER LOYALTY FUNCTIONS
        function searchCustomer() {
            const query = document.getElementById('customerSearchInput').value.trim();
            if (!query) return;

            fetch(`/admin/customers/search?query=${query}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        selectedCustomer = data.customer;
                        document.getElementById('customerInfo').classList.remove('hidden');
                        document.getElementById('custName').innerText = selectedCustomer.name + " (" + selectedCustomer.phone + ")";
                        document.getElementById('custPoints').innerText = selectedCustomer.points;
                    } else {
                        alert("Customer not found!");
                    }
                });
        }

        function createNewCustomer() {
            const name = prompt("Enter Customer Name:");
            const phone = prompt("Enter Customer Phone:");

            if (name && phone) {
                fetch('{{ route("admin.customers.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ name: name, phone: phone })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("Customer created successfully!");
                        selectedCustomer = data.customer;
                        document.getElementById('customerInfo').classList.remove('hidden');
                        document.getElementById('custName').innerText = selectedCustomer.name + " (" + selectedCustomer.phone + ")";
                        document.getElementById('custPoints').innerText = selectedCustomer.points;
                    } else {
                        alert("Error creating customer.");
                    }
                });
            }
        }

        function redeemPoints() {
            if (!selectedCustomer || selectedCustomer.points < 10) {
                alert("Customer needs at least 10 points to redeem $1 discount!");
                return;
            }

            pointsDiscountAmount += 1.00;
            redeemedPointsCount += 10;
            selectedCustomer.points -= 10;

            document.getElementById('custPoints').innerText = selectedCustomer.points;
            document.getElementById('pointsDiscountText').innerText = '-$' + pointsDiscountAmount.toFixed(2);
            calculateTotals();
        }

        // BARCODE SCANNER KEYBOARD LISTENER
        let barcodeBuffer = "";
        let barcodeTimer = null;

        document.addEventListener('keydown', function(e) {
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName) && document.activeElement.id !== 'searchInput') {
                return;
            }

            if (e.key === 'Enter') {
                if (barcodeBuffer.trim().length >= 3) {
                    processScannedBarcode(barcodeBuffer.trim());
                    barcodeBuffer = "";
                }
            } else if (e.key.length === 1) {
                barcodeBuffer += e.key;
                clearTimeout(barcodeTimer);
                barcodeTimer = setTimeout(() => {
                    barcodeBuffer = "";
                }, 150);
            }
        });

        function processScannedBarcode(scannedCode) {
            const matchedProduct = productsList.find(p => p.sku.toLowerCase() === scannedCode.toLowerCase());
            if (matchedProduct) {
                if (matchedProduct.stock > 0) {
                    addToCart(matchedProduct);
                } else {
                    alert("Out of stock! Product: " + matchedProduct.name);
                }
            } else {
                alert("Barcode / SKU not found: " + scannedCode);
            }
        }

        function addToCart(product) {
            playBeepSound();

            const existingItem = cart.find(item => item.id === product.id);
            if (existingItem) {
                if (existingItem.qty < product.stock) {
                    existingItem.qty++;
                } else {
                    alert("Cannot add more than available stock!");
                    return;
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

        function updateQty(id, change) {
            const item = cart.find(i => i.id === id);
            if (item) {
                item.qty += change;
                if (item.qty <= 0) {
                    cart = cart.filter(i => i.id !== id);
                } else if (item.qty > item.stock) {
                    alert("Stock limit reached!");
                    item.qty = item.stock;
                }
            }
            renderCart();
        }

        function removeFromCart(id) {
            cart = cart.filter(i => i.id !== id);
            renderCart();
        }

        function renderCart() {
            const cartContainer = document.getElementById('cartItems');
            if (cart.length === 0) {
                cartContainer.innerHTML = '<div class="text-center py-6 text-gray-400 text-xs font-semibold" id="emptyCartMsg">Cart is empty. Scan barcode or click items to add.</div>';
            } else {
                let html = '';
                cart.forEach(item => {
                    html += `
                        <div class="bg-white p-2 rounded-2xl flex items-center justify-between shadow-sm">
                            <div class="flex-1 pr-2">
                                <h5 class="font-bold text-xs text-gray-900 line-clamp-1">${item.name}</h5>
                                <span class="text-[11px] font-extrabold text-[#F05423]">$${(item.price * item.qty).toFixed(2)}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="updateQty(${item.id}, -1)" class="w-5 h-5 rounded-lg bg-gray-100 font-bold text-xs flex items-center justify-center hover:bg-gray-200">-</button>
                                <span class="text-xs font-extrabold text-gray-900">${item.qty}</span>
                                <button onclick="updateQty(${item.id}, 1)" class="w-5 h-5 rounded-lg bg-gray-100 font-bold text-xs flex items-center justify-center hover:bg-gray-200">+</button>
                                <button onclick="removeFromCart(${item.id})" class="ml-1 text-rose-500 hover:text-rose-700 text-xs font-bold">✕</button>
                            </div>
                        </div>
                    `;
                });
                cartContainer.innerHTML = html;
            }
            calculateTotals();
        }

        function calculateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const tax = subtotal * 0.05;
            const manualDiscount = parseFloat(document.getElementById('discountInput').value) || 0;
            const totalDiscount = manualDiscount + pointsDiscountAmount;
            const total = Math.max(0, subtotal + tax - totalDiscount);

            const paidAmount = parseFloat(document.getElementById('paidAmount').value) || 0;
            const changeReturn = Math.max(0, paidAmount - total);

            document.getElementById('cartSubtotal').innerText = '$' + subtotal.toFixed(2);
            document.getElementById('cartTax').innerText = '$' + tax.toFixed(2);
            document.getElementById('cartTotal').innerText = '$' + total.toFixed(2);
            document.getElementById('changeReturn').innerText = '$' + changeReturn.toFixed(2);
        }

        function filterProducts() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const category = document.getElementById('categoryFilter').value;

            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.getAttribute('data-name');
                const sku = card.getAttribute('data-sku');
                const cat = card.getAttribute('data-category');

                const matchesSearch = name.includes(search) || sku.includes(search);
                const matchesCategory = (category === 'all') || (cat === category);

                if (matchesSearch && matchesCategory) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function processCheckout() {
            if (cart.length === 0) {
                alert('Please add items to cart before checkout.');
                return;
            }

            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const tax = subtotal * 0.05;
            const manualDiscount = parseFloat(document.getElementById('discountInput').value) || 0;
            const totalDiscount = manualDiscount + pointsDiscountAmount;
            const total = Math.max(0, subtotal + tax - totalDiscount);
            const paidAmount = parseFloat(document.getElementById('paidAmount').value) || 0;
            const paymentMethod = document.getElementById('paymentMethod').value;
            const orderComment = document.getElementById('orderComment').value;

            if (paidAmount < total) {
                alert('Paid amount is less than total payable amount.');
                return;
            }

            fetch('{{ route("pos.checkout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    items: cart,
                    subtotal: subtotal,
                    tax: tax,
                    discount: totalDiscount,
                    total: total,
                    payment_method: paymentMethod,
                    paid_amount: paidAmount,
                    change_return: paidAmount - total,
                    customer_id: selectedCustomer ? selectedCustomer.id : null,
                    redeemed_points: redeemedPointsCount,
                    rating: selectedRating,
                    comment: orderComment
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Order Successful! Order No: ' + data.order_number);
                    cart = [];
                    renderCart();
                    window.location.href = data.redirect_url;
                } else {
                    alert('Checkout failed: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Server error during checkout.');
            });
        }
    </script>
</body>
</html>