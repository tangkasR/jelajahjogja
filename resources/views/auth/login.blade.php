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

<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">

    <!-- subtle background -->
    <div class="absolute inset-0 overflow-hidden">
        <div
            class="absolute top-[-150px] left-[-150px] w-[400px] h-[400px] bg-blue-100 rounded-full blur-3xl opacity-70">
        </div>
        <div
            class="absolute bottom-[-150px] right-[-150px] w-[400px] h-[400px] bg-indigo-100 rounded-full blur-3xl opacity-70">
        </div>
    </div>

    <div class="relative w-full max-w-md">

        <!-- logo -->
        <div class="text-center mb-7">
            <div
                class="w-14 h-14 mx-auto bg-white border border-gray-200 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-shield-halved text-blue-600 text-lg"></i>
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mt-4">Welcome Back</h1>
            <p class="text-sm text-gray-500 mt-1">Masuk ke Admin Jelajah Jogja</p>
        </div>

        <!-- card -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-lg p-6">

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>

                    <div class="relative mt-1">
                        <i class="fa-solid fa-envelope absolute left-3 top-3 text-gray-400 text-sm"></i>
                        <input type="email" name="email"
                            class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm
                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="admin@example.com" required>
                    </div>
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Password</label>

                    <div class="relative mt-1">
                        <i class="fa-solid fa-lock absolute left-3 top-3 text-gray-400 text-sm"></i>
                        <input type="password" name="password"
                            class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm
                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="••••••••" required>
                    </div>
                </div>

                <!-- ERROR -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 text-xs rounded-xl p-3">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-semibold
                    shadow-sm transition active:scale-[0.99]">
                    Sign In
                </button>
            </form>

            <!-- footer hint -->
            <p class="text-center text-xs text-gray-400 mt-5">
                Protected admin area • Jelajah Jogja
            </p>

        </div>
    </div>

</body>

</html>
