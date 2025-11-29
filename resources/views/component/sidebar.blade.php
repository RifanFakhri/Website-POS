{{-- Sidebar: w-64 di desktop, fixed/full height di mobile, transisi untuk efek geser --}}
<aside id="sidebar" class="w-64 sidebar-transition fixed lg:sticky top-0 h-screen bg-gray-900 text-white flex flex-col justify-between p-4 flex-shrink-0 z-50">
    
    {{-- Header Sidebar --}}
    <div>
        <div class="flex items-center mb-8">
            <h1 id="sidebar-logo-text" class="text-2xl font-extrabold text-yellow-400">POS F&B</h1>
            {{-- Icon kecil yang muncul saat sidebar tertutup (di desktop) --}}
            <svg id="sidebar-logo-icon" class="w-8 h-8 text-yellow-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
        </div>
        
        <nav class="space-y-2">
            
            {{-- Link Dashboard --}}
            <a href="#" class="flex items-center py-3 px-4 rounded-lg bg-yellow-500 text-gray-900 font-semibold transition duration-200 group relative">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                <span class="ml-3 sidebar-link-text">Dashboard</span>
            </a>
            
            {{-- Link Buat Pesanan --}}
            <a href="#" class="flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-gray-800 group relative">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="ml-3 sidebar-link-text">Buat Pesanan</span>
            </a>
            
            {{-- Link Kelola Pesanan --}}
            <a href="#" class="flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-gray-800 group relative">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span class="ml-3 sidebar-link-text">Kelola Pesanan</span>
            </a>
            
            {{-- Link Laporan --}}
            <a href="#" class="flex items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-gray-800 group relative">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0v-5a2 2 0 012-2h2a2 2 0 012 2v5m-6-10h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2z"></path></svg>
                <span class="ml-3 sidebar-link-text">Laporan</span>
            </a>
        </nav>
    </div>
    
    {{-- Footer Sidebar --}}
    <div class="border-t border-gray-700 pt-4">
        <div id="sidebar-user-info" class="flex items-center mb-4">
            <img class="h-10 w-10 rounded-full object-cover mr-3" src="https://i.pravatar.cc/150?img=68" alt="User Avatar">
            <div>
                <p class="font-semibold text-sm">Andi (Kasir Shift A)</p>
                <p class="text-xs text-green-400">Status: Aktif</p>
            </div>
        </div>
        <button class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-lg transition duration-200">
            <span class="sidebar-link-text">Tutup Shift</span>
            {{-- Icon kecil jika sidebar tertutup --}}
            <svg class="w-5 h-5 mx-auto hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        </button>
    </div>
</aside>