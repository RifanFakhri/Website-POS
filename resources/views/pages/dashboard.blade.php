@extends('layout.home') 

@section('content')

    <header class="mb-6 flex justify-between items-center">
        
        {{-- Tombol Toggle Sidebar --}}
        <div class="flex items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Selamat Datang, Andi!</h2>
                <p class="text-gray-500">Rabu, 26 November 2025</p>
            </div>
        </div>
        
        <div class="text-right">
            <p class="text-xl font-bold text-gray-900" id="live-time">14:00:54</p>
            <a href="#" class="text-sm text-blue-600 hover:underline">Refresh Data</a>
        </div>
    </header>
    <hr class="mb-6 border-gray-300">
    
    {{-- Cards ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-xl card-shadow border-t-4 border-yellow-500">
            <p class="text-sm font-medium text-gray-500">Total Penjualan Hari Ini</p>
            <h3 class="text-4xl font-extrabold text-gray-900 mt-1">Rp 5.500.000</h3>
            <p class="text-sm mt-2 text-green-500 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                +12% dari kemarin
            </p>
        </div>
        <div class="bg-white p-6 rounded-xl card-shadow border-t-4 border-blue-500">
            <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
            <h3 class="text-4xl font-extrabold text-gray-900 mt-1">125</h3>
            <p class="text-sm mt-2 text-gray-400">Pesanan selesai hari ini</p>
        </div>
        <div class="bg-white p-6 rounded-xl card-shadow border-t-4 border-green-500">
            <p class="text-sm font-medium text-gray-500">Rata-rata Nilai Transaksi</p>
            <h3 class="text-4xl font-extrabold text-gray-900 mt-1">Rp 44.000</h3>
            <p class="text-sm mt-2 text-red-500 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                -5% dari kemarin
            </p>
        </div>
    </div>
    
    <hr class="my-6 border-gray-200">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Produk Terlaris --}}
        <div class="lg:col-span-1 bg-white p-6 rounded-xl card-shadow">
            <h4 class="text-xl font-semibold text-gray-800 mb-4">🔥 Produk Terlaris Hari Ini</h4>
            <ul class="space-y-3">
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium">1. Kopi Latte Hangat</span>
                    <span class="text-yellow-600 font-bold">45</span>
                </li>
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium">2. Nasi Goreng Spesial</span>
                    <span class="text-yellow-600 font-bold">32</span>
                </li>
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium">3. Air Mineral Botol</span>
                    <span class="text-yellow-600 font-bold">28</span>
                </li>
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium">4. Es Teh Manis</span>
                    <span class="text-yellow-600 font-bold">25</span>
                </li>
            </ul>
        </div>
        
        {{-- Metode Pembayaran --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl card-shadow">
            <h4 class="text-xl font-semibold text-gray-800 mb-4">💳 Metode Pembayaran</h4>
            
            <div class="grid grid-cols-2 gap-4 items-center">
                <div class="h-48 flex justify-center items-center bg-gray-100 rounded">
                    [Placeholder: Pie Chart Persentase Pembayaran]
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-2 bg-blue-50 rounded">
                        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-600 mr-2"></span> Tunai</span>
                        <span class="font-bold">40% (50 Transaksi)</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-600 mr-2"></span> E-Wallet/QRIS</span>
                        <span class="font-bold">35% (44 Transaksi)</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-yellow-50 rounded">
                        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-yellow-600 mr-2"></span> Kartu Debit/Kredit</span>
                        <span class="font-bold">25% (31 Transaksi)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <hr class="my-6 border-gray-200">
    
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pesanan Aktif --}}
        <div class="bg-white p-6 rounded-xl card-shadow">
            <h4 class="text-xl font-semibold text-gray-800 mb-4">🧾 Pesanan Aktif (Belum Selesai)</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#ODR891</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-600 font-medium">Dine-in (Meja 5)</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Rp 120.000</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <button class="text-green-600 hover:text-green-900">Bayar</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#ODR890</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-yellow-600 font-medium">Takeout</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Rp 45.000</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <button class="text-green-600 hover:text-green-900">Bayar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Stok Kritis --}}
        <div class="bg-white p-6 rounded-xl card-shadow">
            <h4 class="text-xl font-semibold text-gray-800 mb-4">⚠️ Stok Kritis</h4>
            <ul class="space-y-3">
                <li class="flex justify-between items-center p-3 bg-red-50 rounded">
                    <span class="font-medium text-red-700">Biji Kopi Arabica</span>
                    <span class="text-red-700 font-bold">Sisa 1.5 kg</span>
                </li>
                <li class="flex justify-between items-center p-3 bg-yellow-50 rounded">
                    <span class="font-medium text-yellow-700">Daging Sapi Slice</span>
                    <span class="text-yellow-700 font-bold">Sisa 3.0 kg</span>
                </li>
                <li class="flex justify-between items-center p-3 bg-yellow-50 rounded">
                    <span class="font-medium text-yellow-700">Telur Ayam</span>
                    <span class="text-yellow-700 font-bold">Sisa 1 Tray</span>
                </li>
            </ul>
            <div class="mt-4 text-center">
                <a href="#" class="text-sm text-blue-600 hover:underline">Lihat Semua Stok &gt;</a>
            </div>
        </div>
    </div>
    
@endsection 

@push('scripts') 
{{-- Script untuk jam live --}}
<script>
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('live-time').textContent = timeString;
    }
    // Update setiap 1 detik
    setInterval(updateTime, 1000);
    updateTime(); // Panggil sekali saat dimuat
</script>
@endpush