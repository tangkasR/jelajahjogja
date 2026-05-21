<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- ===== SEO Meta Tags ===== --}}
    <title>@yield('title', 'Jelajah Jogja — Temukan Wisata Yogyakarta Terbaik')</title>
    <meta name="description" content="@yield('meta_description', 'Jelajah Jogja adalah platform direktori wisata Yogyakarta terkurasi. Temukan ratusan destinasi terbaik, dari candi bersejarah, pantai eksotis, hingga kuliner autentik Kota Gudeg.')">
    <meta name="keywords" content="@yield('meta_keywords', 'wisata jogja, wisata yogyakarta, destinasi jogja, tempat wisata yogyakarta, trip planner jogja, kuliner jogja, hotel jogja')">
    <meta name="author" content="JelajahJogja">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#2563eb">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- ===== Open Graph (Facebook, WhatsApp, LinkedIn) ===== --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="JelajahJogja">
    <meta property="og:title" content="@yield('og_title', 'Jelajah Jogja — Temukan Wisata Yogyakarta Terbaik')">
    <meta property="og:description" content="@yield('og_description', 'Platform direktori wisata Yogyakarta terkurasi oleh komunitas. Temukan ratusan destinasi terbaik Kota Gudeg.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('imglogocolapse.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="id_ID">

    {{-- ===== Twitter Card ===== --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Jelajah Jogja — Temukan Wisata Yogyakarta Terbaik')">
    <meta name="twitter:description" content="@yield('og_description', 'Platform direktori wisata Yogyakarta terkurasi oleh komunitas.')">
    <meta name="twitter:image" content="@yield('og_image', asset('imglogocolapse.png'))">

    {{-- ===== Favicon & App Icons ===== --}}
    <link rel="icon" type="image/png" href="{{ asset('imglogocolapse.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('imglogocolapse.png') }}">
    <link rel="shortcut icon" href="{{ asset('imglogocolapse.png') }}">

    {{-- ===== Performance ===== --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://unpkg.com">

    {{-- ===== Fonts ===== --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- ===== CSS ===== --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.0/css/glightbox.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.7) 100%);
        }

        .card-hover {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.13);
        }

        .nav-underline {
            position: relative;
        }

        .nav-underline::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 2px;
            background: #2563eb;
            border-radius: 2px;
            transition: width 0.25s;
        }

        .nav-underline:hover::after {
            width: 100%;
        }

        #mobile-menu {
            display: none;
        }

        #mobile-menu.open {
            display: block;
            animation: slideDown 0.2s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .star-rating {
            display: flex;
            gap: 4px;
            cursor: pointer;
            flex-direction: row-reverse;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 24px;
            color: #d1d5db;
            cursor: pointer;
            transition: color 0.15s;
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #fbbf24 !important;
        }
    </style>

    {{-- Extra head per halaman --}}
    @stack('head')
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <nav class="bg-white/95 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('/imglogo.png') }}" class="w-24 h-full" alt="logo" />
                </a>
                {{-- Desktop nav --}}
                <div class="hidden md:flex items-center gap-7">
                    <a href="{{ route('home') }}"
                        class="nav-underline text-sm font-medium text-gray-600 hover:text-blue-600 transition">
                        <i class="fa-solid fa-house mr-1.5 text-xs"></i>Beranda
                    </a>
                    <a href="{{ route('destinations.index') }}"
                        class="nav-underline text-sm font-medium text-gray-600 hover:text-blue-600 transition">
                        <i class="fa-solid fa-map-location-dot mr-1.5 text-xs"></i>Destinasi
                    </a>
                    <a href="{{ route('trip-planner') }}"
                        class="nav-underline text-sm font-medium text-gray-600 hover:text-blue-600 transition">
                        <i class="fa-solid fa-route mr-1.5 text-xs"></i>Trip Planner
                    </a>
                    <a href="{{ route('submit') }}"
                        class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-2 rounded-full text-sm font-semibold hover:opacity-90 transition shadow-md shadow-blue-500/20">
                        <i class="fa-solid fa-plus text-xs"></i> Submit Wisata
                    </a>

                    {{-- User menu --}}
                    @auth
                        @if (!auth()->user()->isAdmin())
                            <div class="relative" id="userMenuWrapper">
                                <button onclick="toggleUserMenu()"
                                    class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-full text-sm font-medium text-gray-700 transition">
                                    <div
                                        class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    {{ Str::limit(auth()->user()->name, 12) }}
                                    <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                                </button>
                                <div id="userDropdown" style="display:none;"
                                    class="absolute right-0 top-full mt-2 w-44 bg-white border border-gray-100 rounded-2xl shadow-lg py-1 z-50">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-xs font-semibold text-gray-800 truncate">{{ auth()->user()->name }}
                                        </p>
                                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <form action="{{ route('auth.logout') }}" method="POST">
                                        @csrf
                                        <button
                                            class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2 transition">
                                            <i class="fa-solid fa-right-from-bracket text-xs"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
                {{-- <div class="hidden md:flex items-center gap-7">
                    <a href="{{ route('home') }}"
                        class="nav-underline text-sm font-medium text-gray-600 hover:text-blue-600 transition">
                        <i class="fa-solid fa-house mr-1.5 text-xs"></i>Beranda
                    </a>
                    <a href="{{ route('destinations.index') }}"
                        class="nav-underline text-sm font-medium text-gray-600 hover:text-blue-600 transition">
                        <i class="fa-solid fa-map-location-dot mr-1.5 text-xs"></i>Destinasi
                    </a>
                    <a href="{{ route('trip-planner') }}"
                        class="nav-underline text-sm font-medium text-gray-600 hover:text-blue-600 transition">
                        <i class="fa-solid fa-route mr-1.5 text-xs"></i>Trip Planner
                    </a>
                    <a href="{{ route('submit') }}"
                        class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-2 rounded-full text-sm font-semibold hover:opacity-90 transition shadow-md shadow-blue-500/20">
                        <i class="fa-solid fa-plus text-xs"></i> Ajukan Wisata
                    </a>
                </div> --}}
                <button id="hamburger"
                    class="md:hidden p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                    onclick="toggleMenu()">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
            </div>
        </div>
        <div id="mobile-menu" class="md:hidden bg-white border-t border-gray-100 px-4 pb-4">
            <div class="space-y-1 pt-3">
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fa-solid fa-house text-blue-500 w-4"></i> Beranda
                </a>
                <a href="{{ route('destinations.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fa-solid fa-map-location-dot text-blue-500 w-4"></i> Destinasi
                </a>
                <a href="{{ route('trip-planner') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fa-solid fa-route text-blue-500 w-4"></i> Trip Planner
                </a>
                <a href="{{ route('submit') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-blue-600 bg-blue-50">
                    <i class="fa-solid fa-plus text-blue-500 w-4"></i> Ajukan Wisata
                </a>
            </div>
        </div>
    </nav>

    <main>@yield('content')</main>

    <footer class="bg-gray-950 text-gray-400 mt-20">
        <div class="max-w-6xl mx-auto px-4 pt-12 pb-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div
                        class="w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-map text-white text-sm"></i>
                    </div>
                    <p class="text-white font-bold text-lg">JelajahJogja</p>
                </div>
                <p class="text-sm leading-relaxed text-gray-500">Platform direktori wisata Yogyakarta yang dikurasi
                    oleh
                    komunitas. Temukan permata tersembunyi Kota Gudeg.</p>
            </div>
            <div>
                <p class="text-white font-semibold mb-4 flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-compass text-blue-500"></i> Jelajahi
                </p>
                <div class="space-y-2.5 text-sm">
                    <a href="{{ route('destinations.index') }}"
                        class="flex items-center gap-2 text-gray-500 hover:text-white transition">
                        <i class="fa-solid fa-chevron-right text-xs text-blue-500"></i> Semua Destinasi
                    </a>
                    <a href="{{ route('submit') }}"
                        class="flex items-center gap-2 text-gray-500 hover:text-white transition">
                        <i class="fa-solid fa-chevron-right text-xs text-blue-500"></i> Ajukan Wisata Baru
                    </a>
                </div>
            </div>
            <div>
                <p class="text-white font-semibold mb-4 flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-circle-info text-blue-500"></i> Tentang
                </p>
                <p class="text-sm leading-relaxed text-gray-500">Konten dikurasi dan diverifikasi sebelum tayang. Bantu
                    kami mempromosikan wisata Yogyakarta ke seluruh Indonesia.</p>
            </div>
        </div>
        <div class="border-t border-gray-800 px-4 py-4">
            <div
                class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-2 text-xs text-gray-600">
                <p>&copy; {{ date('Y') }} JelajahJogja</p>
                <p>Developed by <a href="https://trcodes.id" target="_blank"
                        class="text-blue-500 hover:text-blue-400 font-semibold transition">trcodes.id</a></p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.0/js/glightbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            once: true,
            offset: 60
        });
        const lightbox = GLightbox({
            touchNavigation: true,
            loop: true
        });

        function toggleMenu() {
            document.getElementById('mobile-menu').classList.toggle('open');
        }

        function toggleUserMenu() {
            const dd = document.getElementById('userDropdown');
            dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
        }
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('userMenuWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                const dd = document.getElementById('userDropdown');
                if (dd) dd.style.display = 'none';
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
