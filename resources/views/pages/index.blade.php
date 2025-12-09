@extends('layout.base_pos') {{-- Menggunakan layout minimalis --}}

@section('content')

<div class="pos-container">
    
    {{-- ====================================== --}}
    {{-- A. PRODUCT LIST AREA (Daftar Produk) --}}
    {{-- ====================================== --}}
    <div class="product-list-area">

        {{-- Header & Controls (dengan tombol Back) --}}
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Produk</h1>
            {{-- Tombol Back: Mengarahkan ke Dashboard --}}
            <a href="{{ url('/dashboard') }}" class="bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700 transition" title="Kembali ke Dashboard">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
        </div>

        {{-- Search Input (Lebih terpadu) --}}
        <form action="{{ route('pos.index') }}" method="GET" class="mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white shadow-sm" 
                       placeholder="Cari nama atau SKU produk...">
            </div>
        </form>

        {{-- Tabs/Kategori Filter --}}
        <div class="flex space-x-2 overflow-x-auto pb-3 mb-6 border-b border-gray-200 dark:border-gray-700 whitespace-nowrap">
            
            <a href="{{ route('pos.index', ['search' => request('search')]) }}" 
               class="px-4 py-2 text-sm font-medium rounded-full transition duration-150
               {{ request('category_id', 'all') == 'all' ? 'text-white bg-green-500 shadow-md' : 'text-gray-900 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600' }}">
                Semua
            </a>
            
            @foreach($categories as $category)
                <a href="{{ route('pos.index', ['category_id' => $category->id, 'search' => request('search')]) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-full transition duration-150
                   {{ request('category_id') == $category->id ? 'text-white bg-green-500 shadow-md' : 'text-gray-900 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse ($products as $product)
                @php
                    // Ambil data stok dan gambar untuk dikirim ke JS
                    $currentStock = $product->stock->stock ?? 0;
                    $imageUrl = $product->image ?? 'null'; 
                    $isAvailable = $currentStock > 0;
                @endphp
                
                {{-- Product Card --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md overflow-hidden cursor-pointer transition duration-200 
                     {{ $isAvailable ? 'hover:shadow-xl hover:scale-[1.02]' : 'opacity-50 cursor-not-allowed grayscale' }}"
                     {{-- PENTING: Mengirim data stok dan gambar langsung ke JS atau memicu modal kustom --}}
                     onclick="{{ $isAvailable ? "addToCart($product->id, '$product->name', $product->price, $currentStock, '$imageUrl')" : "showStockAlertModal('Stok Habis untuk $product->name!')" }}"> 
                    
                    {{-- Gambar Produk --}}
                    <div class="relative h-28 w-full">
                        @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 dark:bg-gray-700 dark:text-gray-400 text-xs p-2 text-center">
                                No Image ({{ $product->product_code }})
                            </div>
                        @endif
                        {{-- Badge Stok --}}
                        <span class="absolute top-0 right-0 bg-yellow-400 text-gray-900 text-xs font-semibold px-2 py-0.5 rounded-bl-lg">
                            Stok: {{ $currentStock }}
                        </span>
                    </div>

                    <div class="p-3">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2 min-h-[2.5rem]" title="{{ $product->name }}">
                            {{ $product->name }}
                        </h3>
                        
                        <div class="flex justify-between items-end mt-2">
                            {{-- Harga Menonjol --}}
                            <p class="text-xl font-extrabold text-green-600 dark:text-green-500">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-10 text-gray-500 dark:text-gray-400">
                    Produk tidak ditemukan.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex justify-center">
            {{ $products->links() }} 
        </div>

    </div>

    {{-- ================================= --}}
    {{-- B. CART AREA (Keranjang Belanja) --}}
    {{-- ================================= --}}
    <div class="cart-area bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 flex flex-col">
        
        <div class="flex items-center justify-between mb-4 pb-2 border-b dark:border-gray-700">
            <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">Keranjang</h2>
            <button id="reset-cart" class="bg-red-500 text-white text-sm font-medium px-3 py-1 rounded-lg hover:bg-red-600 transition">Reset</button>
        </div>

        {{-- Area Tampilan Item Keranjang --}}
        <div id="cart-items-display" class="space-y-3 mb-6 flex-grow max-h-[calc(100vh-250px)] overflow-y-auto">
            {{-- Konten keranjang diisi oleh JavaScript --}}
        </div>

        {{-- Total dan Tombol Checkout (Fixed Bottom) --}}
        <div class="mt-auto pt-4 border-t dark:border-gray-700">
            <div class="flex justify-between items-center text-lg font-bold mb-4">
                <span class="text-gray-700 dark:text-gray-300">TOTAL:</span>
                <span id="cart-total" class="text-2xl font-extrabold text-green-600 dark:text-green-500">Rp 0</span>
            </div>

            <button type="button" 
                    id="checkout-button"
                    class="w-full flex items-center justify-center px-5 py-3 text-base font-medium text-white rounded-lg bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 transition duration-150 shadow-lg" 
                    disabled>
                Checkout (Rp 0)
            </button>
        </div>

    </div>
</div>

{{-- ============================================= --}}
{{-- MODAL PERINGATAN STOK (Ganti alert() default) --}}
{{-- ============================================= --}}
<div id="stock-alert-modal" tabindex="-1" aria-hidden="true" 
     class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 
     flex items-center justify-center /* <-- PERBAIKAN: MEMUSATKAN MODAL */
     w-full md:inset-0 h-full max-h-full bg-gray-900/50 dark:bg-gray-900/80">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-2xl dark:bg-gray-700 transform transition-all duration-300 scale-95" id="stock-alert-content">
            
            {{-- Header Modal --}}
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t bg-yellow-500 dark:bg-yellow-600">
                <h3 class="text-xl font-semibold text-white">⚠️ Peringatan Stok</h3>
                <button type="button" class="text-white hover:text-gray-200" onclick="hideStockAlertModal()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            {{-- Body Modal --}}
            <div class="p-4 md:p-5 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.503-1.658 1.732-3.0l-6.928-12c-.77-.385-2.022-.385-2.792 0l-6.928 12c-.77 1.342.192 3.0 1.732 3.0z"></path></svg>
                <p class="mb-5 text-base font-normal text-gray-700 dark:text-gray-300" id="stock-alert-message">
                    </p>
                <button onclick="hideStockAlertModal()" 
                        class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center transition duration-150">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
{{-- ============================================= --}}

@endsection

{{-- Script untuk simulasi interaksi POS (Sudah Termasuk Local Storage Persistence) --}}
@push('scripts')
<script>
    let cart = [];
    
    function formatRupiah(number) {
        // Menggunakan Intl.NumberFormat untuk format Rupiah yang benar
        return 'Rp ' + number.toLocaleString('id-ID', { maximumFractionDigits: 0 });
    }

    // --- FUNGSI MODAL KUSTOM (Mengganti alert()) ---
    function showStockAlertModal(message) {
        document.getElementById('stock-alert-message').textContent = message;
        document.getElementById('stock-alert-modal').classList.remove('hidden');
        document.getElementById('stock-alert-content').classList.remove('scale-95');
        document.getElementById('stock-alert-content').classList.add('scale-100');
    }

    function hideStockAlertModal() {
        document.getElementById('stock-alert-content').classList.remove('scale-100');
        document.getElementById('stock-alert-content').classList.add('scale-95');
        setTimeout(() => {
            document.getElementById('stock-alert-modal').classList.add('hidden');
        }, 150);
    }
    // ----------------------------------------------


    // --- LOCAL STORAGE PERSISTENCE FUNCTIONS ---
    function saveCart() {
        localStorage.setItem('pos_cart', JSON.stringify(cart));
    }

    function loadCart() {
        const storedCart = localStorage.getItem('pos_cart');
        if (storedCart) {
            cart = JSON.parse(storedCart);
        } else {
            cart = [];
        }
    }
    // ------------------------------------------

    function renderCart() {
        const cartItemsDisplay = document.getElementById('cart-items-display');
        const cartTotalSpan = document.getElementById('cart-total');
        const checkoutButton = document.getElementById('checkout-button');
        
        let total = 0;
        cartItemsDisplay.innerHTML = ''; 
        
        saveCart(); 

        if (cart.length === 0) {
            cartItemsDisplay.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-gray-400 p-8 text-center" style="display: flex;">
                    <svg class="w-16 h-16 mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <p class="font-semibold text-gray-500 dark:text-gray-400">Keranjang Kosong</p>
                    <p class="text-sm">Pilih produk untuk memulai transaksi.</p>
                </div>
            `;
            checkoutButton.disabled = true;
            checkoutButton.innerHTML = `<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4v2h12V4H4zm1 6v8h10v-8H5zm8 0a1 1 0 01-1 1H8a1 1 0 110-2h4a1 1 0 011 1z"></path></svg>Checkout (Rp 0)`;
        } else {
            cart.forEach((item, index) => {
                const itemTotal = item.quantity * item.price;
                total += itemTotal;

                const imageUrl = item.image ? item.image : 'https://via.placeholder.com/60?text=No+Img';

                // Struktur HTML Keranjang Detail
                const itemHtml = `
                    <div class="p-3 bg-white dark:bg-gray-800 rounded-lg mb-3 border dark:border-gray-700 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="${imageUrl}" 
                                     alt="${item.name}" class="w-10 h-10 object-cover rounded">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">${item.name}</span>
                                    <span class="text-sm text-gray-900 dark:text-white block font-bold">${formatRupiah(item.price)}</span>
                                </div>
                            </div>
                            <button onclick="removeItem(${index})" class="text-red-500 hover:text-red-700 text-sm h-min">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.72-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            </button>
                        </div>
                        
                        <div class="flex justify-between items-center mt-3 pt-3 border-t dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Subtotal: ${formatRupiah(itemTotal)}</span>
                            <div class="flex items-center space-x-0">
                                <button onclick="decreaseQuantity(${index})" 
                                        class="bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white text-base w-6 h-6 rounded-l flex items-center justify-center hover:bg-gray-300 p-1">-</button>
                                <span class="text-sm font-semibold dark:text-white w-8 text-center bg-gray-100 dark:bg-gray-700">${item.quantity}</span>
                                <button onclick="increaseQuantity(${index})" 
                                        class="bg-green-500 text-white text-base w-6 h-6 rounded-r flex items-center justify-center hover:bg-green-600 p-1">+</button>
                            </div>
                        </div>
                    </div>
                `;
                cartItemsDisplay.innerHTML += itemHtml;
            });
            
            checkoutButton.disabled = false;
            checkoutButton.innerHTML = `<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4v2h12V4H4zm1 6v8h10v-8H5zm8 0a1 1 0 01-1 1H8a1 1 0 110-2h4a1 1 0 011 1z"></path></svg>Checkout (${formatRupiah(total)})`;
        }
        
        cartTotalSpan.textContent = formatRupiah(total);
    }

    // PENTING: Menerima data stok (maxStock) dan gambar (imageUrl) langsung dari Blade
    function addToCart(productId, productName, productPrice, maxStock, imageUrl) {
        
        const currentStock = maxStock;
        const existingItem = cart.find(item => item.id === productId);
        let quantityToAdd = 1;

        if (existingItem) {
            quantityToAdd = existingItem.quantity + 1;
        }

        // Cek Stok menggunakan modal kustom
        if (quantityToAdd > currentStock) {
            showStockAlertModal(`Stok ${productName} (Tersisa: ${currentStock}) tidak mencukupi. Jumlah yang diminta melebihi stok tersedia.`);
            return;
        }

        // Tambahkan ke keranjang 
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: productPrice,
                quantity: 1,
                maxStock: currentStock, 
                image: imageUrl !== 'null' ? imageUrl : null 
            });
        }

        renderCart();
    }
    
    function increaseQuantity(index) {
        const item = cart[index];
        if (item.quantity < item.maxStock) {
             item.quantity += 1;
        } else {
            showStockAlertModal(`Stok maksimal (${item.maxStock}) tercapai untuk ${item.name}.`);
        }
        renderCart();
    }

    function decreaseQuantity(index) {
        if (cart[index].quantity > 1) {
            cart[index].quantity -= 1;
        } else {
            removeItem(index);
            return;
        }
        renderCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }


    document.getElementById('reset-cart').addEventListener('click', () => {
        if(confirm("Apakah Anda yakin ingin mengosongkan keranjang?")) {
            cart = [];
            renderCart();
        }
    });

    document.getElementById('checkout-button').addEventListener('click', () => {
        // Logika checkout: kirim data array 'cart' ke API/route Laravel
        alert('Simulasi Checkout! Total: ' + document.getElementById('cart-total').textContent + '. Jumlah Item: ' + cart.length);
    });

    // --- INISIALISASI SAAT HALAMAN DIMUAT ---
    loadCart();
    renderCart();
    // ----------------------------------------

</script>
@endpush