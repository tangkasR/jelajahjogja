@extends('layouts.public')
@section('title', 'Jelajah Jogja — Temukan Wisata Yogyakarta')
@section('content')

    {{-- Hero --}}
    <section
        class="relative bg-gray-900 h-screen min-h-[600px] max-h-[900px] flex items-center justify-center overflow-hidden">
        @if ($featured->first()?->hero)
            <img src="{{ asset('storage/hero.jpg') }}"
                class="absolute inset-0 w-full h-full object-cover opacity-50 scale-105"
                style="animation: slowZoom 20s ease-in-out infinite alternate;">
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900"></div>
        @endif
        <div class="hero-gradient absolute inset-0"></div>

        {{-- Floating particles --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/3 right-1/4 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl animate-pulse"
                style="animation-delay:1s"></div>
        </div>

        <div class="relative z-10 text-center text-white px-4 max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white/90 text-xs font-semibold px-4 py-2 rounded-full mb-7"
                data-aos="fade-down">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                Yogyakarta Menanti Kamu
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold leading-tight mb-6" data-aos="fade-up"
                data-aos-delay="100">
                Jelajahi Keindahan<br>
                <span class="bg-gradient-to-r from-yellow-300 to-orange-400 bg-clip-text text-transparent">
                    Yogyakarta
                </span>
            </h1>
            <p class="text-gray-300 text-base md:text-xl mb-10 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up"
                data-aos-delay="200">
                Temukan ratusan destinasi wisata terbaik — dari candi bersejarah, pantai eksotis, hingga kuliner autentik
                Kota Gudeg.
            </p>
            <div data-aos="fade-up" data-aos-delay="300">
                <form action="{{ route('destinations.index') }}" method="GET"
                    class="flex max-w-xl mx-auto bg-white rounded-2xl overflow-hidden shadow-2xl shadow-black/30">
                    <div class="flex-1 flex items-center gap-2 px-4 md:px-5">
                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                        <input type="text" name="q" placeholder="Cari pantai, candi, kuliner..."
                            class="flex-1 py-4 text-gray-800 text-sm focus:outline-none">
                    </div>
                    <button
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 md:px-8 font-bold text-sm hover:opacity-90 transition">
                        Cari
                    </button>
                </form>
                <p class="text-gray-400 text-xs mt-4">
                    Tersedia <span
                        class="text-white font-semibold">{{ \App\Models\Destination::approved()->count() }}+</span>
                    destinasi terkurasi
                </p>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div
            class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 text-white/40 animate-bounce">
            <span class="text-xs">Scroll</span>
            <i class="fa-solid fa-chevron-down text-sm"></i>
        </div>
    </section>

    <style>
        @keyframes slowZoom {
            from {
                transform: scale(1.05);
            }

            to {
                transform: scale(1.12);
            }
        }
    </style>

    {{-- Statistik --}}
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 py-6 grid grid-cols-3 divide-x divide-gray-100 text-center">
            <div class="px-4 md:px-8" data-aos="fade-up">
                <p class="text-2xl md:text-3xl font-extrabold text-blue-600">
                    {{ \App\Models\Destination::approved()->count() }}+</p>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Destinasi Terkurasi</p>
            </div>
            <div class="px-4 md:px-8" data-aos="fade-up" data-aos-delay="100">
                <p class="text-2xl md:text-3xl font-extrabold text-blue-600">{{ \App\Models\Category::count() }}</p>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Kategori Wisata</p>
            </div>
            <div class="px-4 md:px-8" data-aos="fade-up" data-aos-delay="200">
                <p class="text-2xl md:text-3xl font-extrabold text-blue-600">{{ \App\Models\Review::count() }}+</p>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Ulasan Traveler</p>
            </div>
        </div>
    </section>

    {{-- Kategori --}}
    <section class="max-w-6xl mx-auto px-4 py-14 md:py-20">
        <div class="text-center mb-10" data-aos="fade-up">
            <span
                class="inline-flex items-center gap-1.5 text-blue-600 font-semibold text-xs md:text-sm bg-blue-50 px-3 py-1.5 rounded-full mb-3">
                <i class="fa-solid fa-compass"></i> Eksplor Berdasarkan Kategori
            </span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Apa yang Ingin Kamu Jelajahi?</h2>
        </div>
        @php $icons = ['Alam'=>'fa-mountain-sun','Budaya'=>'fa-masks-theater','Kuliner'=>'fa-utensils','Belanja'=>'fa-bag-shopping','Sejarah'=>'fa-landmark']; @endphp
        @php $colors = ['Alam'=>'from-green-400 to-emerald-500','Budaya'=>'from-purple-400 to-violet-500','Kuliner'=>'from-orange-400 to-red-500','Belanja'=>'from-pink-400 to-rose-500','Sejarah'=>'from-amber-400 to-yellow-500']; @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 md:gap-4">
            @foreach ($categories as $i => $cat)
                <a href="{{ route('destinations.index', ['category' => $cat->slug]) }}"
                    class="card-hover bg-white border border-gray-100 rounded-2xl p-5 text-center group overflow-hidden relative"
                    data-aos="fade-up" data-aos-delay="{{ $i * 60 }}">
                    <div
                        class="absolute inset-0 bg-gradient-to-br {{ $colors[$cat->name] ?? 'from-blue-400 to-indigo-500' }} opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-gray-100 group-hover:bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-3 transition-colors duration-300">
                            <i
                                class="fa-solid {{ $icons[$cat->name] ?? 'fa-map-pin' }} text-gray-600 group-hover:text-white transition-colors duration-300 text-lg"></i>
                        </div>
                        <p class="font-bold text-gray-800 group-hover:text-white text-sm transition-colors duration-300">
                            {{ $cat->name }}</p>
                        <p class="text-xs text-gray-400 group-hover:text-white/80 mt-0.5 transition-colors duration-300">
                            {{ $cat->destinations_count }} tempat</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Destinasi Unggulan --}}
    @if ($featured->count())
        <section class="bg-gradient-to-b from-gray-50 to-white py-14 md:py-20">
            <div class="max-w-6xl mx-auto px-4">
                <div class="flex items-end justify-between mb-10">
                    <div data-aos="fade-right">
                        <span
                            class="inline-flex items-center gap-1.5 text-yellow-600 font-semibold text-xs md:text-sm bg-yellow-50 px-3 py-1.5 rounded-full mb-3">
                            <i class="fa-solid fa-star"></i> Pilihan Editor
                        </span>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Destinasi Unggulan</h2>
                    </div>
                    <a href="{{ route('destinations.index') }}"
                        class="hidden md:flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-700 font-semibold"
                        data-aos="fade-left">
                        Lihat semua <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-6">
                    @foreach ($featured as $i => $dest)
                        <a href="{{ route('destinations.show', $dest->slug) }}"
                            class="card-hover bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 block group"
                            data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                            <div class="relative overflow-hidden {{ $i === 0 ? 'h-60 md:h-72' : 'h-48 md:h-56' }}">
                                @if ($dest->hero)
                                    <img src="{{ asset('storage/' . $dest->hero->path) }}"
                                        class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center">
                                        <i class="fa-solid fa-mountain-sun text-5xl text-blue-200"></i>
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
                                <div class="absolute top-3 left-3 flex gap-2">
                                    <span
                                        class="bg-white/90 backdrop-blur-sm text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                                        {{ $dest->category->name }}
                                    </span>
                                </div>
                                @if ($dest->is_featured)
                                    <div class="absolute top-3 right-3">
                                        <span
                                            class="bg-gradient-to-r from-yellow-400 to-orange-400 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                                            <i class="fa-solid fa-star mr-1 text-xs"></i>Unggulan
                                        </span>
                                    </div>
                                @endif
                                @php $avg = $dest->averageRating(); @endphp
                                @if ($avg > 0)
                                    <div class="absolute bottom-3 right-3">
                                        <span
                                            class="bg-black/60 backdrop-blur-sm text-white text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                                            <i class="fa-solid fa-star text-yellow-400 text-xs"></i> {{ $avg }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-gray-900 text-lg mb-1.5 group-hover:text-blue-600 transition">
                                    {{ $dest->title }}</h3>
                                <p class="text-sm text-gray-500 flex items-center gap-1.5 mb-2">
                                    <i class="fa-solid fa-location-dot text-blue-500 text-xs"></i>
                                    {{ $dest->district }}, Yogyakarta
                                </p>
                                <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">{{ $dest->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-6 md:hidden">
                    <a href="{{ route('destinations.index') }}"
                        class="inline-flex items-center gap-2 text-sm text-blue-600 font-semibold">
                        Lihat semua <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Destinasi Terbaru --}}
    <section class="py-14 md:py-20">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-end justify-between mb-10">
                <div data-aos="fade-right">
                    <span
                        class="inline-flex items-center gap-1.5 text-indigo-600 font-semibold text-xs md:text-sm bg-indigo-50 px-3 py-1.5 rounded-full mb-3">
                        <i class="fa-solid fa-clock-rotate-left"></i> Baru Ditambahkan
                    </span>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Destinasi Terbaru</h2>
                </div>
                <a href="{{ route('destinations.index') }}"
                    class="hidden md:flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-700 font-semibold"
                    data-aos="fade-left">
                    Lihat semua <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 md:gap-4">
                @foreach ($latest as $i => $dest)
                    <a href="{{ route('destinations.show', $dest->slug) }}"
                        class="card-hover bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 block group"
                        data-aos="fade-up" data-aos-delay="{{ ($i % 6) * 60 }}">
                        <div class="h-28 md:h-36 overflow-hidden relative">
                            @if ($dest->hero)
                                <img src="{{ asset('storage/' . $dest->hero->path) }}"
                                    class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <i class="fa-solid fa-mountain-sun text-2xl text-gray-300"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <p
                                class="font-bold text-gray-800 text-xs leading-snug line-clamp-2 group-hover:text-blue-600 transition">
                                {{ $dest->title }}</p>
                            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-blue-400 text-xs"></i>
                                {{ $dest->district }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="max-w-6xl mx-auto px-4 pb-16 md:pb-20" data-aos="fade-up">
        <div
            class="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-3xl p-8 md:p-14 text-white text-center overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/5 rounded-full"></div>
                <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-white/5 rounded-full"></div>
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white/3 rounded-full blur-3xl">
                </div>
            </div>
            <div class="relative z-10">
                <div
                    class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-6 border border-white/20">
                    <i class="fa-solid fa-camera text-2xl"></i>
                </div>
                <h2 class="text-2xl md:text-4xl font-extrabold mb-4 leading-tight">
                    Tahu tempat wisata tersembunyi<br class="hidden md:block"> di Jogja?
                </h2>
                <p class="text-blue-200 mb-8 text-sm md:text-lg max-w-xl mx-auto leading-relaxed">
                    Bagikan rekomendasimu dan bantu ribuan traveler menemukan permata tersembunyi Yogyakarta.
                </p>
                <a href="{{ route('submit') }}"
                    class="inline-flex items-center gap-2.5 bg-white text-blue-600 font-bold px-8 py-4 rounded-2xl hover:bg-blue-50 transition shadow-2xl shadow-black/20 text-sm md:text-base">
                    <i class="fa-solid fa-plus"></i> Ajukan Destinasi Wisata
                </a>
            </div>
        </div>
    </section>

@endsection
