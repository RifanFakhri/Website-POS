@extends('layout.home')

@section('content')

    {{-- === HEADER PRODUK === --}}
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Manajemen Detail Produk</h1>
        <p class="text-gray-500 dark:text-gray-400 text-lg mt-1">Kelola data dasar produk (kode, nama, harga), termasuk gambar.</p>
    </div>

    {{-- Notifikasi Sukses/Error --}}
    @if(session('success'))
        <div id="success-alert"
             class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200"
             role="alert">
            <span class="font-medium">Success!</span> {{ session('success') }}
        </div>
    @endif
    {{-- Memastikan error dari validation ditangani dengan baik --}}
    @if(session('error') || $errors->any()) 
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200" role="alert">
            <span class="font-medium">Error!</span> {{ session('error') ?? 'Terjadi kesalahan saat memproses data.' }}
            @if ($errors->any())
                <ul class="mt-1.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif


    {{-- Header & Controls (Search, Filter, Tambah) --}}
    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-4 mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4">
            
            {{-- Tombol Tambah Produk (Flowbite Trigger) --}}
            <button 
                type="button" 
                data-modal-target="tambah-produk-modal" {{-- ID MODAL TAMBAH --}}
                data-modal-toggle="tambah-produk-modal"
                class="w-full md:w-auto flex items-center justify-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Produk
            </button>

            {{-- Search dan Filter --}}
            <div class="w-full md:w-3/4 flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-3">
                <form action="{{ route('products.index') }}" method="GET" class="w-full">
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

    {{-- Tabel Data Produk --}}
    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden">
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Kode Produk</th>
                        <th scope="col" class="px-6 py-3">Nama Produk</th>
                        <th scope="col" class="px-6 py-3">Kategori</th>
                        <th scope="col" class="px-6 py-3">Harga</th>
                        <th scope="col" class="px-6 py-3">Gambar</th> {{-- Kolom Baru --}}
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $product->product_code }}
                            </td>
                            <td class="px-6 py-4">{{ $product->name }}</td>
                            {{-- Menggunakan optional chaining dan null coalescing untuk kategori aman --}}
                            <td class="px-6 py-4">{{ optional($categories->firstWhere('id', $product->category_id))->name ?? 'Kategori-'.$product->category_id }}</td> 
                            <td class="px-6 py-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    N/A
                                @endif
                            </td> {{-- Tampilan Gambar --}}
                            <td class="px-6 py-4 flex items-center space-x-3">
                                {{-- Tombol Edit (Trigger Modal) --}}
                                <button type="button" 
                                        data-modal-target="edit-produk-modal-{{ $product->id }}" {{-- ID MODAL EDIT UNIK --}}
                                        data-modal-toggle="edit-produk-modal-{{ $product->id }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                                
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini akan menghapus data produk dan stoknya.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>

                        {{-- ============================================= --}}
                        {{-- MODAL EDIT PRODUK (Di dalam loop)             --}}
                        {{-- ============================================= --}}
                        <div id="edit-produk-modal-{{ $product->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full max-h-full">
                            <div class="relative p-4 w-full max-w-lg max-h-full">
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Produk: {{ $product->name }}</h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="edit-produk-modal-{{ $product->id }}">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                            <span class="sr-only">Tutup modal</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data"> {{-- TAMBAH enctype --}}
                                        @csrf
                                        @method('PUT')
                                        <div class="p-4 md:p-5 space-y-4">
                                            
                                            {{-- Kode Produk (PENTING: Hapus DISABLED) --}}
                                            <div>
                                                <label for="product_code-edit-{{ $product->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Produk</label>
                                                <input type="text" name="product_code" id="product_code-edit-{{ $product->id }}" value="{{ old('product_code', $product->product_code) }}" 
                                                    class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white cursor-not-allowed" 
                                                    required **readonly**> {{-- SOLUSI: Hapus 'disabled' agar nilai terkirim --}}
                                            </div>

                                            {{-- Nama Produk --}}
                                            <div>
                                                <label for="name-edit-{{ $product->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Produk</label>
                                                <input type="text" name="name" id="name-edit-{{ $product->id }}" value="{{ old('name', $product->name) }}" 
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                            </div>
                                            
                                            {{-- Kategori --}}
                                            <div>
                                                <label for="category_id-edit-{{ $product->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                                                <select name="category_id" id="category_id-edit-{{ $product->id }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Harga --}}
                                            <div>
                                                <label for="price-edit-{{ $product->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                                                <input type="number" name="price" id="price-edit-{{ $product->id }}" value="{{ old('price', $product->price) }}" 
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                            </div>

                                            {{-- Gambar Produk (Update) --}}
                                            <div>
                                                <label for="image-edit-{{ $product->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ganti Gambar Produk (Opsional)</label>
                                                
                                                @if($product->image)
                                                    <div class="mb-2">
                                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Gambar Saat Ini:</span>
                                                        <img src="{{ $product->image }}" alt="Gambar Produk {{ $product->name }}" class="w-16 h-16 object-cover rounded mt-1">
                                                    </div>
                                                @endif

                                                <input type="file" name="image" id="image-edit-{{ $product->id }}" 
                                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                    accept="image/*">
                                                
                                                @if($product->image)
                                                    <div class="flex items-center mt-2">
                                                        {{-- PENTING: Value harus 1 untuk dikirim ke controller --}}
                                                        <input id="remove_image-edit-{{ $product->id }}" name="remove_image" type="checkbox" value="1" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                        <label for="remove_image-edit-{{ $product->id }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Hapus Gambar Lama</label>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                        </div>
                                        <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update Produk</button>
                                            <button type="button" data-modal-hide="edit-produk-modal-{{ $product->id }}" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                Data produk tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <nav class="p-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700" aria-label="Table navigation">
            {{ $products->links() }}
        </nav>
    </div>

    {{-- ============================================= --}}
    {{-- MODAL TAMBAH PRODUK BARU (Di luar loop)       --}}
    {{-- ============================================= --}}
    <div id="tambah-produk-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tambah Produk Baru</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="tambah-produk-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"> {{-- WAJIB TAMBAH enctype --}}
                    @csrf
                    <div class="p-4 md:p-5 space-y-4">
                        
                        {{-- Kode Produk --}}
                        <div>
                            <label for="product_code-tambah" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Produk</label>
                            <input type="text" name="product_code" id="product_code-tambah" value="{{ old('product_code') }}" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>

                        {{-- Nama Produk --}}
                        <div>
                            <label for="name-tambah" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Produk</label>
                            <input type="text" name="name" id="name-tambah" value="{{ old('name') }}" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        
                        {{-- Kategori --}}
                        <div>
                            <label for="category_id-tambah" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                            <select name="category_id" id="category_id-tambah"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label for="price-tambah" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                            <input type="number" name="price" id="price-tambah" value="{{ old('price') }}" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>

                        {{-- Gambar Produk (Baru) --}}
                        <div>
                            <label for="image-tambah" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gambar Produk (Opsional)</label>
                            <input type="file" name="image" id="image-tambah" 
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                accept="image/*">
                        </div>
                        
                    </div>
                    <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Simpan Produk</button>
                        <button type="button" data-modal-hide="tambah-produk-modal" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ================================= END MODAL TAMBAH ================================= --}}


@endsection

{{-- MENGGUNAKAN PUSH UNTUK MEMASTIKAN SCRIPT DIMUAT DI AKHIR BODY --}}
@push('scripts')
<script>
    // Hilangkan alert sukses setelah 3 detik
    const successAlert = document.getElementById("success-alert");
    if (successAlert) {
        setTimeout(() => successAlert.classList.add("hidden"), 3000);
    }
</script>
@endpush