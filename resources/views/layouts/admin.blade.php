<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin — @yield('title', 'Jelajah Jogja')</title>
    <meta name="description" content="Admin Panel JelajahJogja — Kelola destinasi wisata Yogyakarta">
    <meta name="robots" content="noindex, nofollow">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('imglogocolapse.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('imglogocolapse.png') }}">
    <link rel="shortcut icon" href="{{ asset('imglogocolapse.png') }}">

    {{-- Performance --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @verbatim
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .sidebar-link {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 9px 12px;
                border-radius: 10px;
                font-size: 13.5px;
                color: #9ca3af;
                transition: all 0.15s;
                text-decoration: none;
            }

            .sidebar-link:hover {
                background: rgba(255, 255, 255, 0.07);
                color: #e5e7eb;
            }

            .sidebar-link.active {
                background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), rgba(139, 92, 246, 0.2));
                color: #a5b4fc;
                font-weight: 600;
                border: 1px solid rgba(99, 102, 241, 0.2);
            }

            .sidebar-link i {
                width: 16px;
                text-align: center;
                font-size: 13px;
            }

            #sidebar {
                transition: transform 0.3s ease;
            }

            @media (max-width: 768px) {
                #sidebar {
                    transform: translateX(-100%);
                    position: fixed;
                    z-index: 50;
                }

                #sidebar.open {
                    transform: translateX(0);
                }

                #main-content {
                    margin-left: 0 !important;
                }

                #overlay {
                    display: none;
                    position: fixed;
                    inset: 0;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 40;
                }

                #overlay.show {
                    display: block;
                }
            }
        </style>
    @endverbatim
</head>

<body class="bg-slate-50 text-gray-800">

    <div id="overlay" onclick="closeSidebar()"></div>

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside id="sidebar"
            class="sticky left-0 max-h-screen w-64 bg-gray-950 flex flex-col inset-y-0 left-0 shadow-2xl"
            style="min-height:100vh">
            <div class="px-5 py-4 border-b border-gray-800/60">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3">
                    <div
                        class="p-1 w-fit h-full bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl flex items-center justify-center shadow-lg">
                        {{-- <i class="fa-solid fa-map text-white text-sm"></i> --}}
                        <img src="{{ asset('imglogocolapse.png') }}" alt="Logo" class="w-10 h-full">
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm leading-tight mb-0.5">JelajahJogja</p>
                        <p class="text-gray-500 text-xs m-0">Admin Panel</p>
                    </div>
                </a>
            </div>

            <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">
                <p class="text-gray-600 text-xs font-semibold uppercase tracking-widest px-3 mb-3">Utama</p>
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard
                </a>
                <a href="{{ route('admin.destinations.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.destinations.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-map-location-dot"></i> Destinasi
                    @php $pendingDest = \App\Models\Destination::pending()->count(); @endphp
                    @if ($pendingDest > 0)
                        <span
                            class="ml-auto bg-yellow-500 text-yellow-900 text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $pendingDest }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.reviews.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-star"></i> Ulasan
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i> Kategori
                </a>

                <div class="pt-5 mt-3 border-t border-gray-800/60">
                    <p class="text-gray-600 text-xs font-semibold uppercase tracking-widest px-3 mb-3">Lainnya</p>
                    <a href="{{ route('admin.destinations.create') }}" class="sidebar-link">
                        <i class="fa-solid fa-plus-circle"></i> Tambah Destinasi
                    </a>
                    <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Website
                    </a>
                    <a href="{{ route('admin.password.index') }}"
                        class="sidebar-link {{ request()->routeIs('admin.password.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-key"></i> Ubah Password
                    </a>
                </div>
            </nav>

            <div class="px-3 py-4 border-t border-gray-800/60">
                <div class="flex items-center gap-3 px-2 mb-3">
                    <div
                        class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold shadow">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-white text-sm font-semibold leading-tight truncate">
                            {{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-gray-500 text-xs">Administrator</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="sidebar-link w-full text-left text-red-400 hover:text-red-300 hover:bg-red-500/10">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <div id="main-content" class="flex-1 md:flex flex-col min-w-0">
            {{-- Top header --}}
            <header
                class="bg-white border-b border-gray-200 px-4 md:px-6 py-3.5 flex items-center justify-between sticky top-0 z-20 shadow-sm">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()"
                        class="md:hidden p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div>
                        <h1 class="text-sm font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                        <p class="text-xs text-gray-400">@yield('subtitle', 'Kelola konten JelajahJogja')</p>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 md:p-6">
                @if (session('success'))
                    <div
                        class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                        <i class="fa-solid fa-circle-check text-green-500"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div
                        class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i> {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('show');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('overlay').classList.remove('show');
        }
    </script>
    @stack('scripts')
</body>

</html>
