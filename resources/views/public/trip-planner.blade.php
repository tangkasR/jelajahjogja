@extends('layouts.public')
@section('title', 'AI Trip Planner — Jelajah Jogja')
@section('content')

    {{-- Hero --}}
    <section
        class="relative bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900 py-16 md:py-24 px-4 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-3xl mx-auto text-center text-white">
            <div
                class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 px-4 py-2 rounded-full text-xs font-semibold mb-6">
                <i class="fa-solid fa-wand-magic-sparkles text-yellow-400"></i>
                Powered by Groq AI
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold mb-4 leading-tight">
                Rencanakan Liburan Jogja<br>
                <span class="bg-gradient-to-r from-yellow-300 to-orange-400 bg-clip-text text-transparent">
                    dengan AI
                </span>
            </h1>
            <p class="text-blue-200 text-base md:text-lg max-w-xl mx-auto">
                Ceritakan preferensimu, AI akan merancang rencana perjalanan lengkap hari per hari — gratis!
            </p>
        </div>
    </section>

    {{-- Form --}}
    <div class="max-w-2xl mx-auto px-4 py-10 md:py-14">

        @if (session('ai_error'))
            <div
                class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-6 text-sm">
                <i class="fa-solid fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
                <p>{{ session('ai_error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-blue-500"></i>
                    Sesuaikan Perjalananmu
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Isi form di bawah, AI akan buat perjalanan terbaik untukmu</p>
            </div>

            <form action="{{ route('trip-planner.generate') }}" method="POST" class="p-6 space-y-6" id="plannerForm">
                @csrf

                {{-- Durasi --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fa-solid fa-calendar-days mr-1.5 text-blue-500"></i>
                        Berapa hari di Jogja?
                    </label>
                    <div class="flex gap-2 flex-wrap">
                        @foreach ([1, 2, 3, 4, 5, 6, 7] as $day)
                            <label class="cursor-pointer">
                                <input type="radio" name="duration" value="{{ $day }}" class="sr-only peer"
                                    {{ old('duration', 3) == $day ? 'checked' : '' }}>
                                <div
                                    class="w-12 h-12 rounded-2xl border-2 border-gray-200 flex flex-col items-center justify-center text-center
                                    peer-checked:border-blue-500 peer-checked:bg-blue-500 peer-checked:text-white
                                    text-gray-600 font-bold text-sm hover:border-blue-300 transition">
                                    {{ $day }}
                                    <span class="text-xs font-normal leading-tight">hari</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Tipe traveler --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fa-solid fa-users mr-1.5 text-blue-500"></i>
                        Pergi dengan siapa?
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach (['solo' => ['🧍', 'Solo'], 'pasangan' => ['👫', 'Pasangan'], 'keluarga' => ['👨‍👩‍👧', 'Keluarga'], 'rombongan' => ['👥', 'Rombongan']] as $val => [$emoji, $label])
                            <label class="cursor-pointer">
                                <input type="radio" name="travelers" value="{{ $val }}" class="sr-only peer"
                                    {{ old('travelers', 'pasangan') == $val ? 'checked' : '' }}>
                                <div
                                    class="border-2 border-gray-200 rounded-2xl p-3 text-center
                                    peer-checked:border-blue-500 peer-checked:bg-blue-50
                                    hover:border-blue-200 transition">
                                    <p class="text-2xl mb-1">{{ $emoji }}</p>
                                    <p class="text-xs font-semibold text-gray-700">{{ $label }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Budget --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fa-solid fa-wallet mr-1.5 text-blue-500"></i>
                        Budget perjalanan?
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach (['hemat' => ['💰', 'Hemat', 'Backpacker friendly'], 'sedang' => ['💳', 'Menengah', 'Rp 300-700rb/hari'], 'mewah' => ['💎', 'Premium', 'No limit!']] as $val => [$emoji, $label, $desc])
                            <label class="cursor-pointer">
                                <input type="radio" name="budget" value="{{ $val }}" class="sr-only peer"
                                    {{ old('budget', 'sedang') == $val ? 'checked' : '' }}>
                                <div
                                    class="border-2 border-gray-200 rounded-2xl p-3 text-center
                                    peer-checked:border-blue-500 peer-checked:bg-blue-50
                                    hover:border-blue-200 transition">
                                    <p class="text-2xl mb-1">{{ $emoji }}</p>
                                    <p class="text-xs font-bold text-gray-700">{{ $label }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $desc }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Minat --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fa-solid fa-heart mr-1.5 text-blue-500"></i>
                        Apa yang kamu suka? <span class="text-gray-400 font-normal">(pilih semua yang relevan)</span>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach ([['alam', '🏞️', 'Alam & Outdoor'], ['budaya', '🎭', 'Budaya & Seni'], ['sejarah', '🏛️', 'Sejarah & Candi'], ['kuliner', '🍜', 'Kuliner Lokal'], ['belanja', '🛍️', 'Belanja & Oleh-oleh'], ['foto', '📸', 'Photography Spot'], ['religi', '🕌', 'Wisata Religi'], ['petualangan', '🧗', 'Petualangan']] as [$val, $emoji, $label])
                            <label class="cursor-pointer">
                                <input type="checkbox" name="interests[]" value="{{ $val }}" class="sr-only peer"
                                    {{ in_array($val, old('interests', [])) ? 'checked' : '' }}>
                                <div
                                    class="flex items-center gap-1.5 px-3 py-2 border-2 border-gray-200 rounded-full text-sm
                                    peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700
                                    text-gray-600 hover:border-blue-200 transition cursor-pointer">
                                    <span>{{ $emoji }}</span> {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('interests')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan tambahan --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">
                        <i class="fa-solid fa-comment mr-1.5 text-blue-500"></i>
                        Catatan tambahan <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="extra_note" rows="2"
                        placeholder="Contoh: ada anak kecil umur 5 tahun, tidak bisa jalan jauh, alergi makanan laut..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('extra_note') }}</textarea>
                </div>

                {{-- Submit --}}
                {{-- Submit --}}
                @auth
                    @if (!auth()->user()->isAdmin())
                        <button type="submit" id="submitBtn"
                            class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-blue-600 to-indigo-600
                                text-white py-4 rounded-2xl font-bold text-base hover:opacity-90 active:scale-98
                                transition shadow-lg shadow-blue-500/25">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            Buat rencana perjalanan dengan AI
                        </button>
                    @else
                        <button type="button" onclick="document.getElementById('authModal').classList.remove('hidden')"
                            class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-blue-600 to-indigo-600
                                text-white py-4 rounded-2xl font-bold text-base hover:opacity-90 transition shadow-lg shadow-blue-500/25">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            Buat rencana perjalanan dengan AI
                        </button>
                    @endif
                @else
                    <button type="button" onclick="document.getElementById('authModal').classList.remove('hidden')"
                        class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-blue-600 to-indigo-600
                            text-white py-4 rounded-2xl font-bold text-base hover:opacity-90 transition shadow-lg shadow-blue-500/25">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        Buat rencana perjalanan dengan AI
                    </button>
                @endauth
                {{-- <button type="submit" id="submitBtn"
                    class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-blue-600 to-indigo-600
                           text-white py-4 rounded-2xl font-bold text-base hover:opacity-90 active:scale-98
                           transition shadow-lg shadow-blue-500/25">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    Buat rencana perjalanan dengan AI
                </button> --}}
                <p class="text-center text-xs text-gray-400">
                    <i class="fa-solid fa-clock mr-1"></i> Proses sekitar 10-20 detik
                </p>
            </form>
        </div>

        {{-- Info --}}
        <div class="mt-8 grid grid-cols-3 gap-4 text-center">
            @foreach ([['fa-database', 'Dari Data Real', 'Destinasi dari database JelajahJogja'], ['fa-route', 'Efisien', 'Rute dioptimalkan per wilayah'], ['fa-bolt', 'Instan', 'Hasil dalam hitungan detik']] as [$icon, $title, $desc])
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <i class="fa-solid {{ $icon }} text-blue-500 text-lg mb-2 block"></i>
                    <p class="text-xs font-bold text-gray-800">{{ $title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </div>


    {{-- Loading Overlay --}}
    <div id="loadingOverlay" class="fixed inset-0 z-50 hidden" style="opacity:0">

        {{-- Background --}}
        <div class="absolute inset-0 bg-[#0a0f1e]"></div>

        {{-- Animated grid background --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none"
            style="background-image: linear-gradient(rgba(99,102,241,0.07) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(99,102,241,0.07) 1px, transparent 1px);
                    background-size: 40px 40px;">
        </div>

        {{-- Glow orbs --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div id="orb1" class="absolute w-[500px] h-[500px] rounded-full opacity-10"
                style="background: radial-gradient(circle, hsl(239, 100%, 81%), transparent);
                    top: -300px; left: -300px;
                    animation: orbMove1 8s ease-in-out infinite;">
            </div>
            <div id="orb2" class="absolute w-[400px] h-[400px] rounded-full opacity-10"
                style="background: radial-gradient(circle, hsl(239, 100%, 81%), transparent);
                    bottom: -200px; right: -200px;
                    animation: orbMove2 7s ease-in-out infinite;">
            </div>
            <div id="orb3" class="absolute w-[300px] h-[300px] rounded-full opacity-10"
                style="background: radial-gradient(circle, #ffd489, transparent);
                    top: 60%; left: 50%; transform: translate(-50%,-50%);
                    animation: orbMove3 6s ease-in-out infinite;">
            </div>
        </div>

        {{-- Main content --}}
        <div class="relative h-full flex flex-col items-center justify-center px-4">

            {{-- Top badge --}}
            <div class="mb-8" id="loadingBadge" style="animation: fadeSlideDown 0.6s ease forwards;">
                <div
                    class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-sm border border-white/10 px-4 py-2 rounded-full">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-white/70 text-xs font-semibold tracking-wider uppercase">AI Sedang Bekerja</span>
                </div>
            </div>

            {{-- Central animation --}}
            <div class="relative mb-10">
                {{-- Outer rings --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-40 h-40 rounded-full border border-indigo-500/20 animate-spin"
                        style="animation-duration:8s"></div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-32 h-32 rounded-full border border-purple-500/30 animate-spin"
                        style="animation-duration:5s; animation-direction:reverse"></div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-24 h-24 rounded-full border border-indigo-400/40 animate-spin"
                        style="animation-duration:3s"></div>
                </div>

                {{-- Orbiting dot --}}
                <div class="absolute inset-0 flex items-center justify-center"
                    style="animation: spin 3s linear infinite;">
                    <div style="transform: translateY(-60px);">
                        <div class="w-3 h-3 bg-yellow-400 rounded-full shadow-lg"
                            style="box-shadow: 0 0 12px #fbbf24, 0 0 24px #fbbf2440;"></div>
                    </div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center"
                    style="animation: spin 5s linear infinite reverse;">
                    <div style="transform: translateY(-40px);">
                        <div class="w-2 h-2 bg-blue-400 rounded-full" style="box-shadow: 0 0 8px #60a5fa;"></div>
                    </div>
                </div>

                {{-- Center icon --}}
                <div class="relative w-20 h-20 rounded-2xl flex items-center justify-center"
                    style="background: linear-gradient(135deg, #4f46e5, #7c3aed);
                        box-shadow: 0 0 40px rgba(99,102,241,0.5), 0 0 80px rgba(99,102,241,0.2);">
                    <i id="centerIcon"
                        class="fa-solid fa-map-location-dot text-3xl text-white transition-all duration-500"></i>
                </div>
            </div>

            {{-- Title & subtitle --}}
            <div class="text-center mb-6 min-h-[70px]">
                <h2 id="loadingTitle"
                    class="text-2xl md:text-3xl font-extrabold text-white mb-2 transition-all duration-400"
                    style="text-shadow: 0 0 40px rgba(99,102,241,0.8);">
                    Memulai AI...
                </h2>
                <p id="loadingSubtitle" class="text-indigo-300 text-sm md:text-base transition-all duration-400">
                    Menyiapkan mesin perencanaan perjalanan
                </p>
            </div>

            {{-- Progress bar --}}
            <div class="w-72 md:w-96 mb-8">
                <div class="flex justify-between text-xs text-white/30 mb-2">
                    <span>Progress</span>
                    <span id="progressPercent">0%</span>
                </div>
                <div class="w-full bg-white/5 rounded-full h-1.5 overflow-hidden border border-white/10">
                    <div id="progressBar"
                        class="h-full rounded-full transition-all duration-1000 relative overflow-hidden"
                        style="width:0%; background: linear-gradient(90deg, #6366f1, #8b5cf6, #a78bfa);">
                        {{-- Shimmer --}}
                        <div class="absolute inset-0 opacity-60"
                            style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
                                animation: shimmer 1.5s infinite;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step indicators --}}
            <div class="flex items-center gap-1 mb-10">
                @php $stepLabels = ['Analisa', 'Pilih Destinasi', 'Susun Rute', 'Finalisasi']; @endphp
                @foreach ($stepLabels as $i => $stepLabel)
                    <div class="flex items-center">
                        <div class="flex flex-col items-center gap-1.5">
                            <div id="stepDot{{ $i }}"
                                class="w-7 h-7 rounded-full border border-white/20 flex items-center justify-center text-xs font-bold text-white/30 transition-all duration-500">
                                {{ $i + 1 }}
                            </div>
                            <span class="text-white/30 text-xs hidden md:block transition-all duration-500"
                                id="stepLabel{{ $i }}">
                                {{ $stepLabel }}
                            </span>
                        </div>
                        @if ($i < count($stepLabels) - 1)
                            <div id="stepLine{{ $i }}"
                                class="w-10 md:w-16 h-px bg-white/10 mx-1 mb-5 md:mb-0 transition-all duration-700"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Fun fact card --}}
            <div class="w-full max-w-md">
                <div class="relative bg-white/3 backdrop-blur-sm border border-white/8 rounded-2xl p-5 overflow-hidden"
                    style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
                    {{-- Card glow --}}
                    <div class="absolute top-0 left-0 right-0 h-px"
                        style="background: linear-gradient(90deg, transparent, rgba(99,102,241,0.5), transparent);"></div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                            style="background: linear-gradient(135deg, #fbbf24, #f59e0b);">
                            <i class="fa-solid fa-lightbulb text-white text-xs"></i>
                        </div>
                        <div>
                            <p class="text-white/40 text-xs font-semibold uppercase tracking-wider mb-1.5">
                                Fakta Menarik Jogja
                            </p>
                            <p id="funFact" class="text-white/80 text-sm leading-relaxed"
                                style="transition: opacity 0.4s, transform 0.4s;">
                                Yogyakarta memiliki lebih dari 500 destinasi wisata yang menakjubkan!
                            </p>
                        </div>
                    </div>

                    {{-- Fact progress dots --}}
                    <div class="flex justify-center gap-1.5 mt-4" id="factDots"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- ===== AUTH MODAL ===== --}}
    <div id="authModal" class="fixed inset-0 z-50 hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeAuthModal()"></div>

        {{-- Modal box --}}
        <div class="relative flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden"
                style="animation: modalSlideUp 0.3s ease;">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5 relative">
                    <button onclick="closeAuthModal()"
                        class="absolute top-4 right-4 text-white/60 hover:text-white transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-wand-magic-sparkles text-yellow-300"></i>
                        </div>
                        <div>
                            <h2 id="modalTitle" class="text-white font-bold text-lg">Mulai Trip Planner AI</h2>
                            <p id="modalSubtitle" class="text-white/70 text-xs">Daftarkan akun untuk membuat itinerary
                                gratis</p>
                        </div>
                    </div>

                    {{-- Tab switcher --}}
                    <div id="authTabs" class="flex mt-4 bg-white/10 rounded-xl p-1">
                        <button onclick="switchTab('register')" id="tabRegister"
                            class="auth-tab flex-1 py-1.5 text-xs font-semibold rounded-lg text-white bg-white/20 transition">
                            Daftar
                        </button>
                        <button onclick="switchTab('login')" id="tabLogin"
                            class="auth-tab flex-1 py-1.5 text-xs font-semibold rounded-lg text-white/70 transition">
                            Masuk
                        </button>
                    </div>
                </div>

                {{-- Alert --}}
                <div id="authAlert" class="hidden mx-6 mt-4 px-4 py-3 rounded-xl text-sm flex items-start gap-2"></div>

                {{-- ===== FORM REGISTER ===== --}}
                <div id="formRegister" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            <i class="fa-solid fa-user mr-1 text-gray-400"></i> Nama Lengkap
                        </label>
                        <input type="text" id="reg_name" placeholder="Nama kamu"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            <i class="fa-solid fa-envelope mr-1 text-gray-400"></i> Email
                        </label>
                        <input type="email" id="reg_email" placeholder="email@kamu.com"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            <i class="fa-solid fa-lock mr-1 text-gray-400"></i> Password
                        </label>
                        <input type="password" id="reg_password" placeholder="Minimal 8 karakter"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            <i class="fa-solid fa-shield-check mr-1 text-gray-400"></i> Konfirmasi Password
                        </label>
                        <input type="password" id="reg_password_confirmation" placeholder="Ulangi password"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button onclick="doRegister()" id="btnRegister"
                        class="w-full bg-blue-600 text-white py-3 rounded-xl text-sm font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-user-plus"></i> Buat Akun Gratis
                    </button>
                    <p class="text-center text-xs text-gray-400">
                        Sudah punya akun?
                        <button onclick="switchTab('login')" class="text-blue-600 font-semibold hover:underline">Masuk di
                            sini</button>
                    </p>
                </div>

                {{-- ===== FORM LOGIN ===== --}}
                <div id="formLogin" class="p-6 space-y-4 hidden">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            <i class="fa-solid fa-envelope mr-1 text-gray-400"></i> Email
                        </label>
                        <input type="email" id="login_email" placeholder="email@kamu.com"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            <i class="fa-solid fa-lock mr-1 text-gray-400"></i> Password
                        </label>
                        <input type="password" id="login_password" placeholder="Password kamu"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button onclick="doLogin()" id="btnLogin"
                        class="w-full bg-blue-600 text-white py-3 rounded-xl text-sm font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk
                    </button>
                    <p class="text-center text-xs text-gray-400">
                        Belum punya akun?
                        <button onclick="switchTab('register')" class="text-blue-600 font-semibold hover:underline">Daftar
                            gratis</button>
                    </p>
                </div>

                {{-- ===== FORM OTP ===== --}}
                <div id="formOtp" class="p-6 space-y-4 hidden">
                    <div class="text-center mb-2">
                        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-envelope-open-text text-blue-500 text-2xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Kode OTP telah dikirim ke<br>
                            <strong id="otpEmailDisplay" class="text-gray-800"></strong>
                        </p>
                    </div>

                    <input type="hidden" id="otp_email">

                    {{-- OTP Input 6 digit --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2 text-center">Masukkan 6 digit kode
                            OTP</label>
                        <div class="flex gap-2 justify-center">
                            @for ($i = 0; $i < 6; $i++)
                                <input type="text" maxlength="1"
                                    class="otp-input w-11 h-12 border-2 border-gray-200 rounded-xl text-center text-lg font-bold
                                      focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                                    onkeyup="otpKeyup(this, {{ $i }})"
                                    onkeydown="otpKeydown(event, {{ $i }})"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            @endfor
                        </div>
                    </div>

                    {{-- Countdown --}}
                    <p class="text-center text-xs text-gray-400">
                        Kode berlaku <span id="otpCountdown" class="font-semibold text-blue-600">10:00</span>
                    </p>

                    <button onclick="doVerifyOtp()" id="btnVerifyOtp"
                        class="w-full bg-blue-600 text-white py-3 rounded-xl text-sm font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-check"></i> Verifikasi Akun
                    </button>

                    <div class="text-center">
                        <p class="text-xs text-gray-400 mb-1">Tidak dapat kode?</p>
                        <button id="btnResend" onclick="doResendOtp()" disabled
                            class="text-xs text-blue-600 font-semibold hover:underline disabled:text-gray-400 disabled:no-underline disabled:cursor-not-allowed">
                            Kirim ulang OTP (<span id="resendCountdown">60</span>s)
                        </button>
                    </div>
                </div>

                {{-- ===== SUCCESS ===== --}}
                <div id="formSuccess" class="p-8 text-center hidden">
                    <div class="w-20 h-20 bg-green-50 rounded-3xl flex items-center justify-center mx-auto mb-4"
                        style="animation: bounceIn 0.5s ease;">
                        <i class="fa-solid fa-circle-check text-green-500 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Berhasil! 🎉</h3>
                    <p id="successMessage" class="text-sm text-gray-500 mb-6"></p>
                    <button onclick="closeAuthModal(); window.location.reload();"
                        class="bg-blue-600 text-white px-8 py-3 rounded-xl text-sm font-bold hover:bg-blue-700 transition">
                        Mulai Trip Planner
                    </button>
                </div>

            </div>
        </div>
    </div>


    <style>
        @keyframes modalSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            70% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .auth-tab {
            cursor: pointer;
        }

        .otp-input::-webkit-inner-spin-button,
        .otp-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }

        @keyframes orbMove1 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(60px, 40px) scale(1.1);
            }
        }

        @keyframes orbMove2 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(-40px, -60px) scale(1.15);
            }
        }

        @keyframes orbMove3 {

            0%,
            100% {
                transform: translate(-50%, -50%) scale(1);
            }

            50% {
                transform: translate(-50%, -50%) scale(1.3);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(200%);
            }
        }

        @keyframes fadeSlideDown {
            from {
                opacity: 0;
                transform: translateY(-16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    {{-- End Loading Overlay --}}

    @push('scripts')
        <script>
            const CSRF = '{{ csrf_token() }}';
            let otpEmail = '';
            let countdownTimer = null;
            let resendTimer = null;

            // ===== Modal =====
            function closeAuthModal() {
                document.getElementById('authModal').classList.add('hidden');
                clearTimers();
            }

            function showAlert(msg, type = 'error') {
                const el = document.getElementById('authAlert');
                el.classList.remove('hidden', 'bg-red-50', 'text-red-700', 'border-red-200',
                    'bg-green-50', 'text-green-700', 'border-green-200',
                    'bg-blue-50', 'text-blue-700', 'border-blue-200');
                const map = {
                    error: ['bg-red-50', 'text-red-700', 'border', 'border-red-200'],
                    success: ['bg-green-50', 'text-green-700', 'border', 'border-green-200'],
                    info: ['bg-blue-50', 'text-blue-700', 'border', 'border-blue-200'],
                };
                el.classList.add(...(map[type] || map.error));
                el.innerHTML =
                    `<i class="fa-solid fa-${type === 'error' ? 'circle-exclamation' : type === 'success' ? 'circle-check' : 'circle-info'} flex-shrink-0 mt-0.5"></i> ${msg}`;
                el.classList.remove('hidden');
            }

            function hideAlert() {
                document.getElementById('authAlert').classList.add('hidden');
            }

            function setLoading(btnId, loading) {
                const btn = document.getElementById(btnId);
                if (loading) {
                    btn.disabled = true;
                    btn.innerHTML = `<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg> Memproses...`;
                } else {
                    btn.disabled = false;
                }
            }

            // ===== Tab =====
            function switchTab(tab) {
                hideAlert();
                document.getElementById('formRegister').classList.toggle('hidden', tab !== 'register');
                document.getElementById('formLogin').classList.toggle('hidden', tab !== 'login');
                document.getElementById('formOtp').classList.add('hidden');
                document.getElementById('formSuccess').classList.add('hidden');
                document.getElementById('authTabs').classList.remove('hidden');

                document.getElementById('tabRegister').className =
                    'auth-tab flex-1 py-1.5 text-xs font-semibold rounded-lg transition ' +
                    (tab === 'register' ? 'text-white bg-white/20' : 'text-white/70');
                document.getElementById('tabLogin').className =
                    'auth-tab flex-1 py-1.5 text-xs font-semibold rounded-lg transition ' +
                    (tab === 'login' ? 'text-white bg-white/20' : 'text-white/70');

                document.getElementById('modalTitle').textContent = tab === 'register' ? 'Mulai Trip Planner AI' :
                    'Selamat Datang Kembali';
                document.getElementById('modalSubtitle').textContent = tab === 'register' ?
                    'Daftarkan akun untuk membuat itinerary gratis' : 'Masuk untuk melanjutkan trip planning';
            }

            // ===== Register =====
            async function doRegister() {
                hideAlert();
                setLoading('btnRegister', true);

                const data = {
                    name: document.getElementById('reg_name').value,
                    email: document.getElementById('reg_email').value,
                    password: document.getElementById('reg_password').value,
                    password_confirmation: document.getElementById('reg_password_confirmation').value,
                    _token: CSRF,
                };

                try {
                    const res = await fetch('{{ route('auth.register') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        },
                        body: JSON.stringify(data),
                    });
                    const json = await res.json();

                    if (json.success) {
                        showOtpForm(json.email);
                    } else {
                        showAlert(json.message || json.errors ? Object.values(json.errors || {}).flat().join('<br>') :
                            'Terjadi kesalahan.');
                    }
                } catch (e) {
                    showAlert('Terjadi kesalahan. Coba lagi.');
                }

                document.getElementById('btnRegister').disabled = false;
                document.getElementById('btnRegister').innerHTML = '<i class="fa-solid fa-user-plus"></i> Buat Akun Gratis';
            }

            // ===== Login =====
            async function doLogin() {
                hideAlert();
                setLoading('btnLogin', true);

                const data = {
                    email: document.getElementById('login_email').value,
                    password: document.getElementById('login_password').value,
                    _token: CSRF,
                };

                try {
                    const res = await fetch('{{ route('auth.login') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        },
                        body: JSON.stringify(data),
                    });
                    const json = await res.json();

                    if (json.success) {
                        showSuccess(json.message);
                    } else if (json.step === 'otp') {
                        showOtpForm(json.email);
                        showAlert('Akun belum diverifikasi. Kami kirim ulang kode OTP ke email kamu.', 'info');
                    } else {
                        showAlert(json.message);
                    }
                } catch (e) {
                    showAlert('Terjadi kesalahan. Coba lagi.');
                }

                document.getElementById('btnLogin').disabled = false;
                document.getElementById('btnLogin').innerHTML = '<i class="fa-solid fa-right-to-bracket"></i> Masuk';
            }

            // ===== OTP Form =====
            function showOtpForm(email) {
                otpEmail = email;
                document.getElementById('otp_email').value = email;
                document.getElementById('otpEmailDisplay').textContent = email;
                document.getElementById('authTabs').classList.add('hidden');
                document.getElementById('formRegister').classList.add('hidden');
                document.getElementById('formLogin').classList.add('hidden');
                document.getElementById('formOtp').classList.remove('hidden');
                document.getElementById('modalTitle').textContent = 'Verifikasi Email';
                document.getElementById('modalSubtitle').textContent = 'Masukkan kode OTP yang dikirim ke email kamu';
                hideAlert();
                startCountdown();
                startResendCountdown();
                document.querySelectorAll('.otp-input')[0]?.focus();
            }

            // ===== OTP Input handling =====
            function otpKeyup(input, idx) {
                const inputs = document.querySelectorAll('.otp-input');
                if (input.value && idx < 5) {
                    inputs[idx + 1].focus();
                }
            }

            function otpKeydown(e, idx) {
                const inputs = document.querySelectorAll('.otp-input');
                if (e.key === 'Backspace' && !inputs[idx].value && idx > 0) {
                    inputs[idx - 1].focus();
                }
            }

            function getOtpValue() {
                return Array.from(document.querySelectorAll('.otp-input')).map(i => i.value).join('');
            }

            // ===== Verify OTP =====
            async function doVerifyOtp() {
                hideAlert();
                const otp = getOtpValue();
                if (otp.length !== 6) {
                    showAlert('Masukkan 6 digit kode OTP.');
                    return;
                }
                setLoading('btnVerifyOtp', true);

                try {
                    const res = await fetch('{{ route('auth.verify-otp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        },
                        body: JSON.stringify({
                            email: otpEmail,
                            otp,
                            _token: CSRF
                        }),
                    });
                    const json = await res.json();

                    if (json.success) {
                        clearTimers();
                        showSuccess(json.message);
                    } else {
                        showAlert(json.message);
                    }
                } catch (e) {
                    showAlert('Terjadi kesalahan. Coba lagi.');
                }

                document.getElementById('btnVerifyOtp').disabled = false;
                document.getElementById('btnVerifyOtp').innerHTML =
                    '<i class="fa-solid fa-circle-check"></i> Verifikasi Akun';
            }

            // ===== Resend OTP =====
            async function doResendOtp() {
                hideAlert();
                document.getElementById('btnResend').disabled = true;

                try {
                    const res = await fetch('{{ route('auth.resend-otp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        },
                        body: JSON.stringify({
                            email: otpEmail,
                            _token: CSRF
                        }),
                    });
                    const json = await res.json();
                    showAlert(json.message, json.success ? 'success' : 'error');
                    if (json.success) {
                        startCountdown();
                        startResendCountdown();
                        document.querySelectorAll('.otp-input').forEach(i => i.value = '');
                        document.querySelectorAll('.otp-input')[0]?.focus();
                    }
                } catch (e) {
                    showAlert('Gagal kirim OTP. Coba lagi.');
                }
            }

            // ===== Success =====
            function showSuccess(msg) {
                document.getElementById('authTabs').classList.add('hidden');
                document.getElementById('formRegister').classList.add('hidden');
                document.getElementById('formLogin').classList.add('hidden');
                document.getElementById('formOtp').classList.add('hidden');
                document.getElementById('formSuccess').classList.remove('hidden');
                document.getElementById('successMessage').textContent = msg;
                hideAlert();
            }

            // ===== Countdown 10 menit =====
            function startCountdown() {
                if (countdownTimer) clearInterval(countdownTimer);
                let seconds = 600;
                const el = document.getElementById('otpCountdown');

                countdownTimer = setInterval(() => {
                    seconds--;
                    const m = String(Math.floor(seconds / 60)).padStart(2, '0');
                    const s = String(seconds % 60).padStart(2, '0');
                    if (el) el.textContent = `${m}:${s}`;
                    if (seconds <= 0) {
                        clearInterval(countdownTimer);
                        if (el) {
                            el.textContent = 'Kadaluarsa';
                            el.classList.add('text-red-500');
                        }
                    }
                }, 1000);
            }

            // ===== Resend countdown 60 detik =====
            function startResendCountdown() {
                if (resendTimer) clearInterval(resendTimer);
                let sec = 60;
                const btn = document.getElementById('btnResend');
                const el = document.getElementById('resendCountdown');
                if (btn) btn.disabled = true;

                resendTimer = setInterval(() => {
                    sec--;
                    if (el) el.textContent = sec;
                    if (sec <= 0) {
                        clearInterval(resendTimer);
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = 'Kirim ulang OTP';
                        }
                    }
                }, 1000);
            }

            function clearTimers() {
                if (countdownTimer) clearInterval(countdownTimer);
                if (resendTimer) clearInterval(resendTimer);
            }

            // Close modal dengan ESC
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeAuthModal();
            });

            const funFacts = [{
                    icon: "🏛️",
                    text: "Candi Borobudur adalah candi Buddha terbesar di dunia, dibangun pada abad ke-8 Masehi."
                },
                {
                    icon: "🍜",
                    text: "Gudeg Yogyakarta butuh 4-8 jam memasak nangka muda agar mendapat cita rasa yang sempurna."
                },
                {
                    icon: "👑",
                    text: "Keraton Yogyakarta masih ditempati Sultan dan keluarganya — satu-satunya di Indonesia!"
                },
                {
                    icon: "🌋",
                    text: "Gunung Merapi meletus rata-rata setiap 4-5 tahun dan menjadi salah satu gunung berapi teraktif di dunia."
                },
                {
                    icon: "🎨",
                    text: "Batik Yogyakarta diakui UNESCO sebagai Warisan Budaya Tak Benda Dunia sejak 2009."
                },
                {
                    icon: "🌊",
                    text: "Pantai Parangtritis dipercaya sebagai singgasana Nyi Roro Kidul dalam kepercayaan Jawa."
                },
                {
                    icon: "🛍️",
                    text: "Malioboro berasal dari kata 'Marlborough', nama seorang bangsawan Inggris abad ke-17."
                },
                {
                    icon: "📚",
                    text: "Yogyakarta dijuluki 'Kota Pelajar' karena memiliki lebih dari 100 perguruan tinggi!"
                },
                {
                    icon: "🎭",
                    text: "Sendratari Ramayana di Prambanan adalah pertunjukan seni terbesar di Asia Tenggara."
                },
                {
                    icon: "🌿",
                    text: "Hutan Pinus Mangunan Jogja punya suhu 10°C lebih sejuk dari pusat kota Yogyakarta."
                },
                {
                    icon: "⏰",
                    text: "Upacara Sekaten di alun-alun Yogyakarta sudah berlangsung selama lebih dari 500 tahun!"
                },
                {
                    icon: "🗺️",
                    text: "Daerah Istimewa Yogyakarta adalah satu dari dua provinsi dengan status keistimewaan di Indonesia."
                },
            ];

            const loadingSteps = [{
                    icon: "fa-brain",
                    title: "Membaca preferensimu...",
                    subtitle: "AI menganalisa minat, budget & durasi perjalananmu",
                    progress: 12
                },
                {
                    icon: "fa-magnifying-glass",
                    title: "Mencari destinasi terbaik...",
                    subtitle: "Mencocokkan dengan ratusan wisata di database Jogja",
                    progress: 35
                },
                {
                    icon: "fa-route",
                    title: "Merancang rute optimal...",
                    subtitle: "Mengelompokkan destinasi per wilayah agar efisien",
                    progress: 58
                },
                {
                    icon: "fa-utensils",
                    title: "Menambahkan kuliner & tips...",
                    subtitle: "Menyiapkan rekomendasi makanan lokal terbaik",
                    progress: 78
                },
                {
                    icon: "fa-wand-magic-sparkles",
                    title: "Menyempurnakan rencana perjalanan...",
                    subtitle: "Menggabungkan destinasi, kuliner & tips — sebentar lagi selesai!",
                    progress: 92
                },
            ];

            let currentStep = 0;
            let currentFact = 0;
            let stepTimer, factTimer;

            // ---- Build fact dots ----
            function buildFactDots() {
                const container = document.getElementById('factDots');
                container.innerHTML = funFacts.map((_, i) =>
                    `<div id="dot${i}" class="w-1.5 h-1.5 rounded-full transition-all duration-300"
              style="background: ${i === 0 ? 'rgba(99,102,241,0.9)' : 'rgba(255,255,255,0.15)'};
                     width: ${i === 0 ? '20px' : '6px'};"></div>`
                ).join('');
            }

            // ---- Update fact ----
            function rotateFact() {
                const el = document.getElementById('funFact');
                el.style.opacity = '0';
                el.style.transform = 'translateY(8px)';

                setTimeout(() => {
                    currentFact = (currentFact + 1) % funFacts.length;
                    const f = funFacts[currentFact];
                    el.textContent = f.text;
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';

                    // Update dots
                    funFacts.forEach((_, i) => {
                        const dot = document.getElementById('dot' + i);
                        if (!dot) return;
                        if (i === currentFact) {
                            dot.style.background = 'rgba(99,102,241,0.9)';
                            dot.style.width = '20px';
                        } else {
                            dot.style.background = 'rgba(255,255,255,0.15)';
                            dot.style.width = '6px';
                        }
                    });
                }, 400);
            }

            // ---- Update step ----
            function updateStep(idx) {
                if (idx >= loadingSteps.length) return;
                const s = loadingSteps[idx];

                // Animate title out
                const title = document.getElementById('loadingTitle');
                const subtitle = document.getElementById('loadingSubtitle');
                const icon = document.getElementById('centerIcon');

                title.style.opacity = '0';
                title.style.transform = 'translateY(-8px)';
                subtitle.style.opacity = '0';
                icon.style.transform = 'scale(0.8)';
                icon.style.opacity = '0';

                setTimeout(() => {
                    title.textContent = s.title;
                    subtitle.textContent = s.subtitle;
                    icon.className = `fa-solid ${s.icon} text-3xl text-white transition-all duration-500`;

                    title.style.opacity = '1';
                    title.style.transform = 'translateY(0)';
                    subtitle.style.opacity = '1';
                    icon.style.transform = 'scale(1)';
                    icon.style.opacity = '1';
                }, 300);

                // Progress
                document.getElementById('progressBar').style.width = s.progress + '%';
                document.getElementById('progressPercent').textContent = s.progress + '%';

                // Step dots
                for (let i = 0; i < 4; i++) {
                    const dot = document.getElementById('stepDot' + i);
                    const label = document.getElementById('stepLabel' + i);
                    const line = document.getElementById('stepLine' + i);

                    if (i < idx) {
                        // Completed
                        dot.style.background = '#22c55e';
                        dot.style.borderColor = '#22c55e';
                        dot.style.color = 'white';
                        dot.innerHTML = '<i class="fa-solid fa-check" style="font-size:10px"></i>';
                        if (label) {
                            label.style.color = 'rgba(255,255,255,0.6)';
                        }
                        if (line) {
                            line.style.background = '#22c55e';
                            line.style.opacity = '1';
                        }
                    } else if (i === idx) {
                        // Active
                        dot.style.background = 'linear-gradient(135deg, #6366f1, #8b5cf6)';
                        dot.style.borderColor = '#818cf8';
                        dot.style.color = 'white';
                        dot.style.boxShadow = '0 0 12px rgba(99,102,241,0.6)';
                        dot.textContent = i + 1;
                        if (label) {
                            label.style.color = 'rgba(255,255,255,0.9)';
                            label.style.fontWeight = '600';
                        }
                    } else {
                        // Pending
                        dot.style.background = 'transparent';
                        dot.style.borderColor = 'rgba(255,255,255,0.15)';
                        dot.style.color = 'rgba(255,255,255,0.25)';
                        dot.style.boxShadow = 'none';
                        dot.textContent = i + 1;
                        if (label) {
                            label.style.color = 'rgba(255,255,255,0.25)';
                        }
                    }
                }
            }

            // ---- Show overlay ----
            function showLoading() {
                const overlay = document.getElementById('loadingOverlay');
                overlay.classList.remove('hidden');

                requestAnimationFrame(() => {
                    overlay.style.transition = 'opacity 0.5s ease';
                    overlay.style.opacity = '1';
                });

                buildFactDots();
                updateStep(0);

                // Randomize first fact
                currentFact = Math.floor(Math.random() * funFacts.length);
                document.getElementById('funFact').textContent = funFacts[currentFact].text;

                // Step progression — setiap 6 detik
                stepTimer = setInterval(() => {
                    currentStep++;
                    if (currentStep < loadingSteps.length) {
                        updateStep(currentStep);
                    } else {
                        clearInterval(stepTimer);
                        // Final state
                        document.getElementById('progressBar').style.width = '99%';
                        document.getElementById('progressPercent').textContent = '99%';
                        document.getElementById('loadingTitle').textContent =
                            'Rencana perjalanan Anda berhasil dibuat! 🎉';
                        document.getElementById('loadingSubtitle').textContent = 'Mengalihkan ke halaman hasil...';
                    }
                }, 6000);

                // Fun fact rotation — setiap 5 detik
                factTimer = setInterval(rotateFact, 5000);
            }

            // ---- Form submit ----
            document.getElementById('plannerForm').addEventListener('submit', function() {
                const interests = document.querySelectorAll('input[name="interests[]"]:checked');
                if (interests.length === 0) return;

                const btn = document.getElementById('submitBtn');
                btn.disabled = true;
                btn.innerHTML = `
                                <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Memproses...
                            `;

                setTimeout(showLoading, 300);
            });
        </script>
    @endpush
@endsection
