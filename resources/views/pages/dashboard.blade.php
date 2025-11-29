@extends('layout.home')

@section('content')

    <header class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center">
            
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Selamat Datang, Andi!</h2>
                <p class="text-gray-500" id="current-date">Mengambil data tanggal...</p>
            </div>
        </div>
        
        <div class="text-right bg-white p-4 rounded-xl card-shadow border-l-4 border-blue-500">
            <div class="flex items-center justify-end gap-2">
                <i class="fas fa-clock text-blue-500"></i>
                <p class="text-xl font-bold text-gray-900" id="live-time">Mengambil waktu...</p>
            </div>
            <a href="#" class="text-sm text-blue-600 hover:underline flex items-center justify-end gap-1 mt-1">
                <i class="fas fa-sync-alt text-xs"></i>
                Refresh Data
            </a>
        </div>
    </header>
    
    {{-- Cards ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl card-shadow border-t-4 border-yellow-500 hover:transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Total Penjualan Hari Ini</p>
                <i class="fas fa-shopping-cart text-yellow-500"></i>
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mt-1">Rp 5.500.000</h3>
            <p class="text-sm mt-2 text-green-500 flex items-center">
                <i class="fas fa-arrow-up mr-1"></i>
                +12% dari kemarin
            </p>
        </div>
        
        <div class="bg-white p-6 rounded-xl card-shadow border-t-4 border-blue-500 hover:transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                <i class="fas fa-receipt text-blue-500"></i>
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mt-1">125</h3>
            <p class="text-sm mt-2 text-gray-400">Pesanan selesai hari ini</p>
        </div>
        
        <div class="bg-white p-6 rounded-xl card-shadow border-t-4 border-green-500 hover:transform hover:-translate-y-1 transition duration-300">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500">Rata-rata Nilai Transaksi</p>
                <i class="fas fa-chart-line text-green-500"></i>
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mt-1">Rp 44.000</h3>
            <p class="text-sm mt-2 text-red-500 flex items-center">
                <i class="fas fa-arrow-down mr-1"></i>
                -5% dari kemarin
            </p>
        </div>
    </div>
    
    <hr class="my-6 border-gray-200">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Produk Terlaris --}}
        <div class="lg:col-span-1 bg-white p-6 rounded-xl card-shadow hover:transform hover:-translate-y-1 transition duration-300">
            <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-fire text-red-500"></i>
                Produk Terlaris Hari Ini
            </h4>
            <ul class="space-y-3">
                <li class="flex justify-between items-center p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border-l-4 border-yellow-500">
                    <span class="font-medium">1. Kopi Latte Hangat</span>
                    <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-sm font-bold">45</span>
                </li>
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border-l-4 border-gray-400">
                    <span class="font-medium">2. Nasi Goreng Spesial</span>
                    <span class="bg-gray-500 text-white px-2 py-1 rounded-full text-sm font-bold">32</span>
                </li>
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border-l-4 border-gray-400">
                    <span class="font-medium">3. Air Mineral Botol</span>
                    <span class="bg-gray-500 text-white px-2 py-1 rounded-full text-sm font-bold">28</span>
                </li>
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border-l-4 border-gray-400">
                    <span class="font-medium">4. Es Teh Manis</span>
                    <span class="bg-gray-500 text-white px-2 py-1 rounded-full text-sm font-bold">25</span>
                </li>
            </ul>
        </div>
        
        {{-- Metode Pembayaran --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl card-shadow hover:transform hover:-translate-y-1 transition duration-300">
            <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-credit-card text-purple-500"></i>
                Metode Pembayaran
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div class="h-64 flex justify-center items-center bg-gray-50 rounded-xl">
                    <canvas id="paymentChart"></canvas>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-money-bill-wave text-blue-600"></i>
                            Tunai
                        </span>
                        <span class="font-bold text-blue-700">40% (50 Transaksi)</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-mobile-alt text-green-600"></i>
                            E-Wallet/QRIS
                        </span>
                        <span class="font-bold text-green-700">35% (44 Transaksi)</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-credit-card text-yellow-600"></i>
                            Kartu Debit/Kredit
                        </span>
                        <span class="font-bold text-yellow-700">25% (31 Transaksi)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <hr class="my-6 border-gray-200">
    
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pesanan Aktif --}}
        <div class="bg-white p-6 rounded-xl card-shadow hover:transform hover:-translate-y-1 transition duration-300">
            <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-orange-500"></i>
                Pesanan Aktif (Belum Selesai)
            </h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#ODR891</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Dine-in (Meja 5)</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Rp 120.000</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg transition duration-200">
                                    Bayar
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#ODR890</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Takeout</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Rp 45.000</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg transition duration-200">
                                    Bayar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Stok Kritis --}}
        <div class="bg-white p-6 rounded-xl card-shadow hover:transform hover:-translate-y-1 transition duration-300">
            <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
                Stok Kritis
            </h4>
            <ul class="space-y-3">
                <li class="flex justify-between items-center p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
                    <span class="font-medium text-red-700">Biji Kopi Arabica</span>
                    <span class="bg-red-500 text-white px-2 py-1 rounded-full text-sm font-bold">Sisa 1.5 kg</span>
                </li>
                <li class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                    <span class="font-medium text-yellow-700">Daging Sapi Slice</span>
                    <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-sm font-bold">Sisa 3.0 kg</span>
                </li>
                <li class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                    <span class="font-medium text-yellow-700">Telur Ayam</span>
                    <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-sm font-bold">Sisa 1 Tray</span>
                </li>
            </ul>
            <div class="mt-4 text-center">
                <a href="#" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 hover:underline transition duration-150">
                    Lihat Semua Stok
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
    
@endsection 

@push('scripts')
<script>
    // Fungsi untuk update waktu real-time dan tanggal
    function updateTime() {
        const now = new Date();
        
        // Format waktu (HH:MM:SS)
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;
        
        // Format tanggal Indonesia (Contoh: Sabtu, 29 November 2025)
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateString = now.toLocaleDateString('id-ID', options);
        
        // Update elemen
        const liveTimeElement = document.getElementById('live-time');
        const currentDateElement = document.getElementById('current-date');
        
        if (liveTimeElement) liveTimeElement.textContent = timeString;
        if (currentDateElement) currentDateElement.textContent = dateString;
    }
    
    // Update setiap 1 detik
    setInterval(updateTime, 1000);
    updateTime(); // Panggil sekali saat dimuat
    
    // Chart untuk metode pembayaran
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('paymentChart');
        if (ctx) { // Pastikan elemen canvas ditemukan
            const paymentChart = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Tunai', 'E-Wallet/QRIS', 'Kartu Debit/Kredit'],
                    datasets: [{
                        data: [40, 35, 25],
                        backgroundColor: [
                            '#3b82f6', // blue-500
                            '#10b981', // green-500
                            '#f59e0b'  // yellow-500
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush