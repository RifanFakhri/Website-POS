@extends('layout.home')

@section('content')

    {{-- === HEADER TRANSAKSI === --}}
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Manajemen Transaksi</h1>
        <p class="text-gray-500 dark:text-gray-400 text-lg mt-1">Daftar riwayat transaksi yang tercatat.</p>
    </div>

    {{-- Notifikasi Sukses/Error (Sesuaikan jika Anda menambahkan fungsi Create/Update) --}}
    @if(session('success'))
        <div id="success-alert"
             class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200"
             role="alert">
            <span class="font-medium">Success!</span> {{ session('success') }}
        </div>
    @endif
    
    {{-- Header & Controls (Search, Filter, Tambah) --}}
    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-4 mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4">
            
            {{-- Tombol Buat Transaksi Baru (Sesuaikan dengan Flowbite Modal/Link) --}}
            <a href="{{ route('transactions.create') }}" 
                class="w-full md:w-auto flex items-center justify-center text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 transition duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Transaksi
            </a>

            {{-- Search dan Filter --}}
            <div class="w-full md:w-3/4 flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-3">
                <form action="{{ route('transactions.index') }}" method="GET" class="w-full">
                    <div class="flex items-center space-x-3">
                        
                        {{-- Search Input --}}
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                            </div>
                            <input type="text" name="search" id="simple-search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari No. Transaksi atau Nama Pemesan...">
                        </div>
                        
                        {{-- Filter Dropdown Pembayaran --}}
                        <select name="payment_method" onchange="this.form.submit()" class="w-40 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @foreach($paymentMethods as $key => $value)
                                <option value="{{ $key }}" {{ request('payment_method') == $key ? 'selected' : '' }}>
                                    {{ $value }}
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

    {{-- Tabel Data Transaksi --}}
    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden">
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">No. Transaksi</th>
                        <th scope="col" class="px-6 py-3">Nama Pemesan</th>
                        <th scope="col" class="px-6 py-3">Total Harga</th>
                        <th scope="col" class="px-6 py-3">Pembayaran</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $transaction->transaction_code }}
                            </td>
                            <td class="px-6 py-4">{{ $transaction->customer_name }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold">
                                    Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $color = match($transaction->payment_method) {
                                        'QRIS' => 'bg-green-100 text-green-800',
                                        'Tunai' => 'bg-blue-100 text-blue-800',
                                        'Debit' => 'bg-purple-100 text-purple-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $color }} dark:bg-gray-700 dark:text-gray-300">
                                    {{ $transaction->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex items-center space-x-3">
                                {{-- Tombol Cetak --}}
                                <button type="button" 
                                    onclick="alert('Cetak Transaksi: {{ $transaction->transaction_code }}')"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m0 0v1a2 2 0 002 2h6a2 2 0 002-2v-1M7 17h10"></path></svg>
                                    Cetak
                                </button>
                                
                                {{-- Tombol Detail/Lainnya (Misalnya titik tiga) --}}
                                <button id="dropdown-button-{{ $transaction->id }}" data-dropdown-toggle="dropdown-{{ $transaction->id }}" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-2 2a2 2 0 104 0 2 2 0 00-4 0z"></path></svg>
                                </button>
                                <div id="dropdown-{{ $transaction->id }}" class="z-10 hidden w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button-{{ $transaction->id }}">
                                        <li>
                                            <a href="{{ route('transactions.show', $transaction->id) }}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Detail</a>
                                        </li>
                                        <li>
                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="block w-full text-left py-2 px-4 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-red-500">Hapus</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                Data transaksi tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <nav class="p-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700" aria-label="Table navigation">
            {{ $transactions->links() }}
        </nav>
    </div>


@endsection

{{-- PENTING: Jika menggunakan Flowbite, pastikan scripts-nya dimuat --}}
@push('scripts')
<script>
    // Hilangkan alert sukses setelah 3 detik
    const successAlert = document.getElementById("success-alert");
    if (successAlert) {
        setTimeout(() => successAlert.classList.add("hidden"), 3000);
    }
</script>
@endpush