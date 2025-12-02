@extends('layout.home')

@section('content')

    {{-- === HEADER STOK === --}}
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Manajemen Stok Produk</h1>
        <p class="text-gray-500 dark:text-gray-400 text-lg mt-1">Kelola dan input stok untuk produk yang sudah terdaftar.</p>
    </div>

    {{-- Notifikasi Sukses/Error --}}
    {{-- Tambahkan ID untuk memudahkan akses JS --}}
    @if(session('success'))
        <div id="alert-success" class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-700 dark:text-green-400" role="alert">
            <span class="font-medium">Success!</span> {!! session('success') !!}
        </div>
    @endif
    @if(session('error') || $errors->any())
        <div id="alert-error" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-700 dark:text-red-400" role="alert">
            <span class="font-medium">Error!</span> {{ session('error') ?? 'Terdapat kesalahan validasi input.' }}
            @if ($errors->any())
                <ul class="mt-1.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    {{-- Header & Controls (Search, Filter, Tambah Stok) --}}
    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-4 mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4">
            
            {{-- Tombol Tambah Stok (Trigger Modal) --}}
            {{-- Dipanggil tanpa parameter agar memunculkan dropdown --}}
            <button onclick="openStockModal()" class="w-full md:w-auto flex items-center justify-center text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 transition duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Input Stok Baru
            </button>

            {{-- Search dan Filter --}}
            <div class="w-full md:w-3/4 flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-3">
                <form action="{{ route('stocks.index') }}" method="GET" class="w-full">
                    <div class="flex items-center space-x-3">
                        
                        {{-- Search Input --}}
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                            </div>
                            <input type="text" name="search" id="simple-search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Kode atau Nama Produk...">
                        </div>
                        
                        {{-- Filter Dropdown --}}
                        <select name="category_id" onchange="this.form.submit()" class="w-40 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="all">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        
                        <button type="submit" class="p-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg border border-blue-700 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel Data Stok --}}
    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden">
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Kode Produk</th>
                        <th scope="col" class="px-6 py-3">Nama Produk</th>
                        <th scope="col" class="px-6 py-3">Kategori</th>
                        <th scope="col" class="px-6 py-3">Stok Saat Ini</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stocks as $stock)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $stock->product_code }}
                            </td>
                            <td class="px-6 py-4">{{ $stock->product->name ?? 'Produk Dihapus' }}</td>
                            {{-- Mengambil nama kategori dari data dummy yang sesuai --}}
                            <td class="px-6 py-4">{{ $categories->firstWhere('id', $stock->product->category_id)->name ?? 'N/A' }}</td> 
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full 
                                    @if($stock->stock < 10) bg-red-100 text-red-800 
                                    @elseif($stock->stock < 50) bg-yellow-100 text-yellow-800 
                                    @else bg-green-100 text-green-800 @endif dark:bg-gray-700 dark:text-gray-300">
                                            {{ $stock->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{-- Tombol Tambah Stok Cepat --}}
                                <button onclick="openStockModal('{{ $stock->product_code }}', '{{ $stock->product->name ?? 'N/A' }}')" 
                                        class="font-medium text-green-600 dark:text-green-500 hover:underline">
                                    Input Stok
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                Data stok tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <nav class="p-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700" aria-label="Table navigation">
            {{ $stocks->links() }}
        </nav>
    </div>

    {{-- ================================= MODAL TAMBAH STOK ================================= --}}
    <div id="stock-modal" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900 bg-opacity-50 dark:bg-opacity-80">
        <div class="relative w-full max-w-md max-h-full mx-auto my-12">
            
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                
                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="stock-modal-title">
                        Tambah Stok Barang
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="closeStockModal()">
                        <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                {{-- Modal Body/Form --}}
                <form id="stock-form" action="{{ route('stocks.store') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    
                    <div class="grid gap-4 mb-4 grid-cols-1">
                        
                        {{-- Nama Produk (Display atau Dropdown) --}}
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produk</label>
                            
                            {{-- Tampilan Nama Produk (Cepat Input) --}}
                            <p id="stock-product-name-display" class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-200 pb-1 hidden">--</p>

                            {{-- Dropdown Produk (Input Stok Baru) --}}
                            <div id="stock-product-dropdown-container" class="hidden">
                                <select name="product_code" id="product_code_select" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                    <option value="" selected disabled>Pilih Produk...</option>
                                    @foreach($allProducts as $product)
                                        <option value="{{ $product->product_code }}">{{ $product->name }} ({{ $product->product_code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Hidden Input untuk menangkap code jika dari tombol cepat --}}
                            <input type="hidden" name="product_code" id="stock-product-code-hidden">
                        </div>

                        {{-- Kuantitas Stok --}}
                        <div class="col-span-1">
                            <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kuantitas Ditambahkan</label>
                            <input type="number" name="quantity" id="quantity" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500" placeholder="Masukkan jumlah stok baru" required="">
                        </div>

                    </div>
                    <button type="submit" class="text-white inline-flex items-center bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 transition duration-150">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Stok
                    </button>
                </form>
            </div>
        </div>
    </div>
    {{-- ================================= END MODAL STOK ================================= --}}
@endsection

@push('scripts')
<script>
    // === STOCK MODAL VARIABLES ===
    let stockModal, stockForm, stockProductNameDisplay, stockProductCodeHidden, stockProductDropdownContainer, productCodeSelect;

    // =======================================================================================
    // FUNGSI GLOBAL (STOCK)
    // =======================================================================================

    /**
     * Membuka modal stok. Jika kode produk diberikan, ia mengaktifkan mode input cepat.
     * Jika tidak, ia mengaktifkan mode input baru dengan dropdown.
     * @param {string} code - product_code yang akan diisi otomatis.
     * @param {string} name - nama produk yang akan ditampilkan.
     */
    function openStockModal(code = '', name = '') {
        // Hanya buka modal jika sudah diinisialisasi
        if (stockModal && stockForm) {
            
            stockForm.reset(); // Reset form
            document.getElementById('quantity').value = ''; // Kosongkan kuantitas

            if (code && name) {
                // --- MODE INPUT CEPAT (Dari Tabel) ---
                stockProductNameDisplay.textContent = name;
                stockProductCodeHidden.value = code;
                
                // Sembunyikan dropdown, tampilkan display nama produk
                stockProductDropdownContainer.classList.add('hidden');
                stockProductNameDisplay.classList.remove('hidden');

                // Pastikan dropdown tidak memiliki nilai yang dipilih
                productCodeSelect.selectedIndex = 0; 
                productCodeSelect.removeAttribute('name');
                stockProductCodeHidden.setAttribute('name', 'product_code'); // Aktifkan hidden input
                
                // Default quantity 1
                document.getElementById('quantity').value = 1; 

            } else {
                // --- MODE INPUT BARU (Dari Tombol Atas) ---
                stockProductNameDisplay.textContent = '-- Pilih Produk --';
                stockProductCodeHidden.value = '';

                // Tampilkan dropdown, sembunyikan display nama produk
                stockProductDropdownContainer.classList.remove('hidden');
                stockProductNameDisplay.classList.add('hidden');
                
                // Pastikan dropdown digunakan untuk pengiriman data
                productCodeSelect.setAttribute('name', 'product_code');
                stockProductCodeHidden.removeAttribute('name'); // Non-aktifkan hidden input
            }


            stockModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Fokus ke input kuantitas setelah modal dibuka
            setTimeout(() => document.getElementById('quantity').focus(), 100);

        } else {
            console.error("Error: Stock modal elements not initialized.");
        }
    }

    function closeStockModal() {
        if (stockModal) {
            stockModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    // =======================================================================================
    // INISIALISASI & AUTOHIDE ALERT (GARANSI DOM READY)
    // =======================================================================================

    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Stock Modal elements
        stockModal = document.getElementById('stock-modal');
        stockForm = document.getElementById('stock-form');
        stockProductNameDisplay = document.getElementById('stock-product-name-display');
        stockProductCodeHidden = document.getElementById('stock-product-code-hidden');
        stockProductDropdownContainer = document.getElementById('stock-product-dropdown-container');
        productCodeSelect = document.getElementById('product_code_select');
        
        // Cek jika ada error validasi setelah submit (jika modal tertutup)
        // Jika ada error validation, buka kembali modal
        @if ($errors->any())
            openStockModal(); // Buka modal kembali untuk menampilkan error
        @endif
        
        // Fungsionalitas Auto-Hide Alert
        const successAlert = document.getElementById('alert-success');
        const errorAlert = document.getElementById('alert-error');

        // Sembunyikan alert sukses setelah 2 detik
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500); // Hapus elemen setelah transisi
            }, 2000); // 2000 milidetik = 2 detik
        }
        
        // Sembunyikan alert error setelah 2 detik HANYA JIKA TIDAK ADA VALIDASI ERROR
        // Jika ada validation error ($errors->any()), alert harus tetap ada.
        @if (!session('error') && !$errors->any())
            if (errorAlert) {
                setTimeout(() => {
                    errorAlert.style.transition = 'opacity 0.5s ease';
                    errorAlert.style.opacity = '0';
                    setTimeout(() => errorAlert.remove(), 500);
                }, 2000);
            }
        @endif
        
    });
</script>
@endpush