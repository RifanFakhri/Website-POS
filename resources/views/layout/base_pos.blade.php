<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir (POS)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <style>
        .pos-container {
            display: grid;
            grid-template-columns: 3fr 1fr; 
            gap: 1.5rem;
            min-height: 100vh;
            padding: 1.5rem;
        }
        .cart-area {
            min-width: 300px;
            position: sticky;
            top: 1.5rem; 
            height: fit-content;
        }
        @media (max-width: 1024px) {
            .pos-container {
                grid-template-columns: 1fr;
            }
            .cart-area {
                position: static;
            }
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    @yield('content')
    
    {{-- Import Flowbite/Tailwind JS dan scripts kustom --}}
    @stack('scripts')
</body>
</html>