@extends('layouts.public')
@section('title', 'Hasil Itinerary AI — Jelajah Jogja')
@section('content')

    @php
        $budgetLabel = ['hemat' => '💰 Hemat', 'sedang' => '💳 Menengah', 'mewah' => '💎 Premium'];
        $travelerLabel = [
            'solo' => '🧍 Solo',
            'pasangan' => '👫 Pasangan',
            'keluarga' => '👨‍👩‍👧 Keluarga',
            'rombongan' => '👥 Rombongan',
        ];
        $hariCount = count($itinerary['hari']);

        // Kumpulkan semua koordinat untuk map
        $allCoords = [];
        foreach ($itinerary['hari'] as $hi => $hari) {
            foreach ($hari['destinasi'] as $di => $dest) {
                if (!empty($dest['lat']) && !empty($dest['lng'])) {
                    $allCoords[] = [
                        'lat' => $dest['lat'],
                        'lng' => $dest['lng'],
                        'nama' => $dest['nama'],
                        'hari' => $hi + 1,
                        'urutan' => $di + 1,
                        'waktu' => $dest['waktu_mulai'] . ' – ' . $dest['waktu_selesai'],
                        'type' => 'destination',
                    ];
                }
            }
            if (!empty($hari['kuliner_rekomendasi']['lat'])) {
                $k = $hari['kuliner_rekomendasi'];
                $allCoords[] = [
                    'lat' => $k['lat'],
                    'lng' => $k['lng'],
                    'nama' => $k['nama'],
                    'hari' => $hi + 1,
                    'urutan' => 99,
                    'type' => 'kuliner',
                ];
            }
        }
        $penginapan = $itinerary['penginapan'] ?? [];
    @endphp

    {{-- Header --}}
    <div class="bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900 px-4 py-10 md:py-14">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center gap-2 text-white/60 text-xs mb-4">
                <a href="{{ route('trip-planner') }}" class="hover:text-white flex items-center gap-1">
                    <i class="fa-solid fa-wand-magic-sparkles text-yellow-400"></i> Trip Planner
                </a>
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <span>Hasil Itinerary</span>
            </div>
            <h1 class="text-2xl md:text-4xl font-extrabold text-white mb-3">
                Itinerary {{ $hariCount }} Hari di Yogyakarta
            </h1>
            <div class="flex flex-wrap gap-2 mb-6">
                <span
                    class="bg-white/10 text-white text-xs px-3 py-1 rounded-full">{{ $travelerLabel[$tripInput['travelers']] ?? '' }}</span>
                <span
                    class="bg-white/10 text-white text-xs px-3 py-1 rounded-full">{{ $budgetLabel[$tripInput['budget']] ?? '' }}</span>
                @foreach ($tripInput['interests'] ?? [] as $interest)
                    <span
                        class="bg-white/10 text-white text-xs px-3 py-1 rounded-full capitalize">{{ $interest }}</span>
                @endforeach
            </div>

            {{-- Summary + estimasi total --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-5">
                    <p class="text-white/90 text-sm leading-relaxed">{{ $itinerary['summary'] }}</p>
                </div>
                @if (!empty($itinerary['estimasi_total']))
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-5">
                        <p class="text-white/60 text-xs uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-wallet text-yellow-400"></i> Estimasi Total
                        </p>
                        <p class="text-white font-extrabold text-lg">{{ $itinerary['estimasi_total']['biaya_min'] }}</p>
                        <p class="text-white/60 text-xs">s/d {{ $itinerary['estimasi_total']['biaya_max'] }}</p>
                        <p class="text-white/50 text-xs mt-2">{{ $itinerary['estimasi_total']['catatan'] ?? '' }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8 md:py-12 space-y-8">

        {{-- ===== PETA RUTE ===== --}}
        @if (count($allCoords) > 0)
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" data-aos="fade-up">
                <div
                    class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-map text-blue-500"></i> Peta Rute Perjalanan
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">{{ count($allCoords) }} titik lokasi · Klik marker untuk
                            detail</p>
                    </div>
                    {{-- Legend --}}
                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded-full bg-blue-500 inline-block"></span> Destinasi
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded-full bg-orange-400 inline-block"></span> Kuliner
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded-full bg-purple-500 inline-block"></span> Penginapan
                        </span>
                    </div>
                </div>

                {{-- Tab hari untuk filter rute --}}
                <div class="px-6 pt-4 flex gap-2 flex-wrap">
                    <button onclick="filterRoute('all')" id="tabAll"
                        class="route-tab active px-4 py-1.5 rounded-full text-xs font-semibold border transition">
                        Semua Hari
                    </button>
                    @foreach ($itinerary['hari'] as $i => $hari)
                        <button onclick="filterRoute({{ $i + 1 }})" id="tab{{ $i + 1 }}"
                            class="route-tab px-4 py-1.5 rounded-full text-xs font-semibold border transition">
                            Hari {{ $i + 1 }}
                        </button>
                    @endforeach
                </div>

                {{-- Loading indicator --}}
                <div id="mapLoadingIndicator" style="display:none;"
                    class="px-6 py-3 flex items-center gap-2 text-xs text-gray-500 bg-blue-50 border-t border-blue-100">
                    <svg class="animate-spin w-4 h-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Memuat jalur jalan asli via OpenStreetMap...
                </div>

                <div id="routeMap" class="w-full" style="height: 450px;"></div>

                {{-- Tombol animasi --}}
                {{-- <div
                    class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-xs text-gray-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info text-blue-400"></i>
                            Rute mengikuti jalan asli · Jalur kuning = trail animasi
                        </p>
                    </div>
                    <button id="animBtn" onclick="toggleAnimation()" style="display:none;"
                        class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600
              text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:opacity-90 transition shadow-sm">
                        <i class="fa-solid fa-play"></i> Mulai Animasi Perjalanan
                    </button>
                </div> --}}

                {{-- Kontrol animasi --}}
                <div class="px-6 py-4 border-t border-gray-100 space-y-3">
                    {{-- Progress bar --}}
                    <div id="animProgressContainer" style="display:none;">
                        <div class="flex items-center justify-between text-xs text-gray-400 mb-1.5">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-route text-blue-400"></i> Progress perjalanan
                            </span>
                            <span id="animProgressText" class="font-semibold text-blue-600">0%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div id="animProgress" class="h-full rounded-full transition-all"
                                style="width:0%; background: linear-gradient(90deg, #fbbf24, #f59e0b);"></div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-4 flex-wrap">
                            <p class="text-xs text-gray-400 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-info text-blue-400"></i>
                                Rute mengikuti jalan asli
                            </p>
                            <div id="speedControl" style="display:none;" class="flex items-center gap-2">
                                <i class="fa-solid fa-gauge text-gray-400 text-xs"></i>
                                <span class="text-xs text-gray-500">Lambat</span>
                                <input type="range" id="speedSlider" min="1" max="5" value="1"
                                    class="w-20 h-1.5 accent-blue-600 cursor-pointer">
                                <span class="text-xs text-gray-500">Cepat</span>
                            </div>
                        </div>
                        <button id="animBtn" onclick="toggleAnimation()" style="display:none;"
                            class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600
                       text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:opacity-90 transition shadow-sm whitespace-nowrap">
                            <i class="fa-solid fa-play"></i> Mulai Animasi Perjalanan
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- ===== MAIN CONTENT GRID ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Itinerary per hari --}}
            <div class="lg:col-span-2 space-y-6">
                @foreach ($itinerary['hari'] as $hari)
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" data-aos="fade-up">

                        {{-- Header hari --}}
                        <div class="px-6 py-4 flex items-center justify-between"
                            style="background: linear-gradient(135deg, #1e40af, #4338ca);">
                            <div>
                                <p class="text-white/60 text-xs font-semibold uppercase tracking-wider">Hari
                                    ke-{{ $hari['hari_ke'] }}</p>
                                <h2 class="text-white font-bold text-lg">{{ $hari['tema'] }}</h2>
                            </div>
                            <div class="text-right">
                                @if (!empty($hari['estimasi_biaya_hari']))
                                    <p class="text-white/60 text-xs">Estimasi Biaya</p>
                                    <p class="text-yellow-300 font-bold text-sm">{{ $hari['estimasi_biaya_hari']['min'] }}
                                        – {{ $hari['estimasi_biaya_hari']['max'] }}</p>
                                    <p class="text-white/40 text-xs mt-0.5">
                                        {{ $hari['estimasi_biaya_hari']['rincian'] ?? '' }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Timeline --}}
                        <div class="p-6 space-y-0">
                            @foreach ($hari['destinasi'] as $i => $dest)
                                {{-- Destinasi item --}}
                                <div class="flex gap-4">
                                    {{-- Timeline --}}
                                    <div class="flex flex-col items-center flex-shrink-0">
                                        <div
                                            class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                            {{ $i + 1 }}
                                        </div>
                                        @if (!$loop->last || !empty($dest['transportasi_ke_berikutnya']))
                                            <div class="w-0.5 bg-blue-100 flex-1 mt-1" style="min-height: 20px;"></div>
                                        @endif
                                    </div>

                                    <div class="flex-1 pb-4">
                                        <div class="flex gap-3 items-start">
                                            @if (!empty($dest['hero_url']))
                                                <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0">
                                                    <img src="{{ $dest['hero_url'] }}"
                                                        class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div
                                                    class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center flex-shrink-0">
                                                    <i class="fa-solid fa-mountain-sun text-blue-300 text-xl"></i>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2 flex-wrap">
                                                    <div>
                                                        @if (!empty($dest['url']) && ($dest['found'] ?? false))
                                                            <a href="{{ $dest['url'] }}"
                                                                class="font-bold text-gray-900 hover:text-blue-600 transition text-sm">
                                                                {{ $dest['nama'] }}
                                                                <i
                                                                    class="fa-solid fa-arrow-up-right-from-square text-xs ml-1 text-gray-400"></i>
                                                            </a>
                                                        @else
                                                            <p class="font-bold text-gray-900 text-sm">{{ $dest['nama'] }}
                                                            </p>
                                                        @endif
                                                        @if (!empty($dest['wilayah']))
                                                            <p
                                                                class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                                                <i
                                                                    class="fa-solid fa-location-dot text-blue-400"></i>{{ $dest['wilayah'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right flex-shrink-0">
                                                        <span
                                                            class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full whitespace-nowrap">
                                                            <i
                                                                class="fa-solid fa-clock mr-1"></i>{{ $dest['waktu_mulai'] }}
                                                            – {{ $dest['waktu_selesai'] }}
                                                        </span>
                                                        <p class="text-xs text-gray-400 mt-1">{{ $dest['durasi'] }}</p>
                                                    </div>
                                                </div>
                                                @if (!empty($dest['tips']))
                                                    <p
                                                        class="text-xs text-gray-500 mt-2 leading-relaxed flex items-start gap-1.5">
                                                        <i
                                                            class="fa-solid fa-lightbulb text-yellow-400 mt-0.5 flex-shrink-0"></i>{{ $dest['tips'] }}
                                                    </p>
                                                @endif
                                                @if (!empty($dest['estimasi_biaya']))
                                                    <p
                                                        class="text-xs text-green-600 font-semibold mt-1.5 flex items-center gap-1">
                                                        <i
                                                            class="fa-solid fa-ticket text-green-500"></i>{{ $dest['estimasi_biaya'] }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Transportasi ke berikutnya --}}
                                @if (!empty($dest['transportasi_ke_berikutnya']) && !$loop->last)
                                    @php $t = $dest['transportasi_ke_berikutnya']; @endphp
                                    <div class="flex gap-4">
                                        <div class="flex flex-col items-center flex-shrink-0">
                                            <div class="w-9 flex justify-center">
                                                <div class="w-0.5 bg-blue-100 flex-1"></div>
                                            </div>
                                        </div>
                                        <div class="flex-1 pb-4">
                                            <div
                                                class="flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 text-xs text-gray-500 w-fit">
                                                <i class="fa-solid fa-motorcycle text-blue-400"></i>
                                                <span class="font-semibold text-gray-700">{{ $t['moda'] ?? '' }}</span>
                                                <span class="text-gray-300">·</span>
                                                <span>{{ $t['jarak'] ?? '' }}</span>
                                                <span class="text-gray-300">·</span>
                                                <span>{{ $t['waktu_tempuh'] ?? '' }}</span>
                                                <span class="text-gray-300">·</span>
                                                <span
                                                    class="text-green-600 font-semibold">{{ $t['estimasi_biaya'] ?? '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- Kuliner --}}
                        @if (!empty($hari['kuliner_rekomendasi']))
                            @php $k = $hari['kuliner_rekomendasi']; @endphp
                            <div class="mx-6 mb-5 bg-orange-50 border border-orange-100 rounded-2xl p-4">
                                <p class="text-xs font-bold text-orange-600 mb-1.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-utensils"></i> Rekomendasi Kuliner Hari Ini
                                </p>
                                <p class="text-sm font-bold text-gray-800">{{ $k['nama'] }}</p>
                                @if (!empty($k['menu']))
                                    <p class="text-xs text-gray-500 mt-0.5">Menu: {{ $k['menu'] }}</p>
                                @endif
                                @if (!empty($k['kisaran_harga']))
                                    <p class="text-xs text-orange-600 font-semibold mt-1 flex items-center gap-1"><i
                                            class="fa-solid fa-tag"></i>{{ $k['kisaran_harga'] }}</p>
                                @endif
                            </div>
                        @endif

                        {{-- Catatan hari --}}
                        @if (!empty($hari['catatan_hari']))
                            <div class="mx-6 mb-5 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                                <p class="text-xs text-blue-600 leading-relaxed flex items-start gap-1.5">
                                    <i
                                        class="fa-solid fa-circle-info mt-0.5 flex-shrink-0"></i>{{ $hari['catatan_hari'] }}
                                </p>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>

            {{-- Sidebar --}}
            <div>
                <div class="space-y-5 lg:sticky lg:top-20">
                    {{-- Tips --}}
                    @if (!empty($itinerary['tips_umum']))
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 " data-aos="fade-left">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 text-sm">
                                <i class="fa-solid fa-lightbulb text-yellow-400"></i> Tips Perjalanan
                            </h3>
                            <div class="space-y-3">
                                @foreach ($itinerary['tips_umum'] as $i => $tip)
                                    <div class="flex items-start gap-2.5">
                                        <div
                                            class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <span class="text-blue-600 font-bold text-xs">{{ $i + 1 }}</span>
                                        </div>
                                        <p class="text-xs text-gray-600 leading-relaxed">{{ $tip }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-5 pt-4 border-t border-gray-100 space-y-2">
                                <a href="{{ route('trip-planner') }}"
                                    class="flex items-center justify-center gap-2 w-full bg-blue-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i> Buat Itinerary Baru
                                </a>
                                <button onclick="window.print()"
                                    class="flex items-center justify-center gap-2 w-full bg-gray-50 border border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-100 transition">
                                    <i class="fa-solid fa-print"></i> Print / PDF
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Semua destinasi --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5" data-aos="fade-left"
                        data-aos-delay="100">
                        <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2 text-sm">
                            <i class="fa-solid fa-map-pin text-blue-500"></i> Ringkasan Destinasi
                        </h3>
                        <div class="space-y-1.5">
                            @foreach ($itinerary['hari'] as $hi => $hari)
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-3 first:mt-0">Hari
                                    {{ $hi + 1 }}</p>
                                @foreach ($hari['destinasi'] as $dest)
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></div>
                                        @if (!empty($dest['url']) && ($dest['found'] ?? false))
                                            <a href="{{ $dest['url'] }}"
                                                class="text-xs text-gray-600 hover:text-blue-600 transition truncate">{{ $dest['nama'] }}</a>
                                        @else
                                            <p class="text-xs text-gray-500 truncate">{{ $dest['nama'] }}</p>
                                        @endif
                                        <span
                                            class="text-xs text-gray-300 ml-auto whitespace-nowrap">{{ $dest['waktu_mulai'] }}</span>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===== PENGINAPAN ===== --}}
        @if (!empty($penginapan))
            <div data-aos="fade-up">
                <div class="flex items-center gap-2 mb-5">
                    <i class="fa-solid fa-bed text-purple-500 text-lg"></i>
                    <h2 class="text-xl font-extrabold text-gray-900">Rekomendasi Penginapan</h2>
                    <span class="text-xs text-gray-400 font-normal ml-1">Sesuai budget
                        {{ $budgetLabel[$tripInput['budget']] ?? '' }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($penginapan as $i => $hotel)
                        <div
                            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition card-hover">
                            {{-- Header card --}}
                            <div class="p-5 border-b border-gray-100">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-900 text-sm leading-snug">{{ $hotel['nama'] }}</p>
                                        <p class="text-xs text-purple-600 font-semibold mt-0.5">{{ $hotel['tipe'] ?? '' }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        @if (!empty($hotel['bintang']))
                                            <div class="flex items-center gap-0.5 justify-end">
                                                @for ($s = 1; $s <= 5; $s++)
                                                    <i
                                                        class="fa-solid fa-star text-xs {{ $s <= $hotel['bintang'] ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                                                @endfor
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-xs text-gray-500 flex items-center gap-1 mb-2">
                                    <i class="fa-solid fa-location-dot text-purple-400"></i>{{ $hotel['lokasi'] ?? '' }}
                                </p>

                                <p class="text-sm font-extrabold text-purple-600">{{ $hotel['kisaran_harga'] ?? '' }}</p>
                            </div>

                            <div class="p-5 space-y-3">
                                {{-- Fasilitas --}}
                                @if (!empty($hotel['fasilitas']))
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($hotel['fasilitas'] as $fas)
                                            <span
                                                class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $fas }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Cocok untuk --}}
                                @if (!empty($hotel['cocok_untuk']))
                                    <p class="text-xs text-gray-500 flex items-start gap-1.5">
                                        <i
                                            class="fa-solid fa-users text-purple-400 mt-0.5 flex-shrink-0"></i>{{ $hotel['cocok_untuk'] }}
                                    </p>
                                @endif

                                {{-- Alasan --}}
                                @if (!empty($hotel['alasan']))
                                    <p class="text-xs text-gray-600 leading-relaxed flex items-start gap-1.5">
                                        <i
                                            class="fa-solid fa-thumbs-up text-green-400 mt-0.5 flex-shrink-0"></i>{{ $hotel['alasan'] }}
                                    </p>
                                @endif

                                {{-- CTA --}}
                                @if (!empty($hotel['lat']) && !empty($hotel['lng']))
                                    <a href="https://maps.google.com/?q={{ $hotel['lat'] }},{{ $hotel['lng'] }}"
                                        target="_blank"
                                        class="flex items-center justify-center gap-2 w-full bg-purple-50 text-purple-600 border border-purple-100 py-2 rounded-xl text-xs font-semibold hover:bg-purple-100 transition mt-2">
                                        <i class="fa-solid fa-map-location-dot"></i> Lihat di Maps
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- CTA --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-8 text-center text-white"
            data-aos="fade-up">
            <h3 class="font-extrabold text-xl mb-2">Suka itinerarynya?</h3>
            <p class="text-blue-200 text-sm mb-6">Jelajahi lebih banyak destinasi seru di Yogyakarta</p>
            <a href="{{ route('destinations.index') }}"
                class="inline-flex items-center gap-2 bg-white text-blue-600 font-bold px-6 py-3 rounded-xl hover:bg-blue-50 transition shadow-lg text-sm">
                <i class="fa-solid fa-map-location-dot"></i> Lihat Semua Destinasi
            </a>
        </div>
    </div>

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        .pointer-icon {
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.3);
            transition: transform 0.1s linear;
            object-fit: cover;
        }

        .route-tab {
            background: white;
            color: #6b7280;
            border-color: #e5e7eb;
        }

        .route-tab.active {
            background: #eff6ff;
            color: #2563eb;
            border-color: #bfdbfe;
        }

        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            padding: 0;
            border: none;
        }

        .custom-popup .leaflet-popup-content {
            margin: 0;
        }

        @media print {

            nav,
            footer,
            #routeMap,
            button,
            .lg\:sticky {
                display: none !important;
            }

            .shadow-sm {
                box-shadow: none;
            }
        }
    </style>

    @push('scripts')
        <script>
            const allPoints = @json($allCoords);
            const hotels = @json($penginapan);
            const totalDays = {{ $hariCount }};
            const strategi = @json($itinerary['strategi_rute'] ?? '');

            const dayColors = ['#2563eb', '#7c3aed', '#059669', '#dc2626', '#d97706', '#0891b2', '#be185d'];

            // ===== Init map =====
            const map = L.map('routeMap', {
                zoom: 13,
                zoomControl: true
            });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 19,
            }).addTo(map);

            let allMarkers = [];
            let allPolylines = [];

            // ===== Animasi state =====
            let animationTimer = null;
            let animationRunning = false;
            let pointerMarker = null;
            let fullRouteCoords = []; // semua koordinat rute real (dari OSRM) hari 1 → hari terakhir

            // ===== Custom pointer icon dari pointer.jpg =====
            // const pointerIcon = L.icon({
            //     iconUrl: '{{ asset('pointer.png') }}',
            //     iconSize: [40, 40],
            //     iconAnchor: [20, 20],
            //     className: 'pointer-icon',
            // });

            // ===== Custom pointer icon pakai divIcon agar rotasi bisa dikontrol =====
            function makePointerIcon(angle = 0) {
                return L.divIcon({
                    className: '',
                    html: `<div id="pointerWrapper" style="
                            width: 44px;
                            height: 44px;
                            transform: rotate(${angle}deg);
                            transition: transform 0.15s linear;
                        ">
                            <img src="{{ asset('pointer.png') }}"
                                style="width:44px;height:44px;border-radius:50%;
                                        border:3px solid white;
                                        box-shadow:0 3px 12px rgba(0,0,0,0.35);
                                        object-fit:cover;display:block;">
                        </div>`,
                    iconSize: [44, 44],
                    iconAnchor: [22, 22],
                    popupAnchor: [0, -24],
                });
            }

            // ===== Icon builder =====
            function makeDestIcon(color, label) {
                return L.divIcon({
                    className: '',
                    html: `<div style="background:${color};color:white;width:34px;height:34px;
            border-radius:50% 50% 50% 0;transform:rotate(-45deg);
            display:flex;align-items:center;justify-content:center;
            box-shadow:0 3px 12px rgba(0,0,0,0.25);border:2.5px solid white;
            font-weight:800;font-size:12px;">
            <span style="transform:rotate(45deg)">${label}</span>
        </div>`,
                    iconSize: [34, 34],
                    iconAnchor: [17, 34],
                    popupAnchor: [0, -36],
                });
            }

            function makeKulinerIcon(color) {
                return L.divIcon({
                    className: '',
                    html: `<div style="background:${color};color:white;width:28px;height:28px;
            border-radius:50%;display:flex;align-items:center;justify-content:center;
            font-size:13px;box-shadow:0 2px 8px rgba(0,0,0,0.2);border:2px solid white;">🍜</div>`,
                    iconSize: [28, 28],
                    iconAnchor: [14, 14],
                    popupAnchor: [0, -16],
                });
            }

            function makeHotelIcon() {
                return L.divIcon({
                    className: '',
                    html: `<div style="background:#7c3aed;color:white;width:34px;height:34px;
            border-radius:50%;display:flex;align-items:center;justify-content:center;
            font-size:16px;box-shadow:0 3px 12px rgba(124,58,237,0.4);border:2.5px solid white;">🏨</div>`,
                    iconSize: [34, 34],
                    iconAnchor: [17, 17],
                    popupAnchor: [0, -20],
                });
            }

            // ===== Fetch real road route dari OSRM =====
            async function fetchRoadRoute(coords) {
                if (coords.length < 2) return coords.map(c => [c[0], c[1]]);
                try {
                    const coordStr = coords.map(c => `${c[1]},${c[0]}`).join(';');
                    const url =
                        `https://router.project-osrm.org/route/v1/driving/${coordStr}?overview=full&geometries=geojson`;
                    const res = await fetch(url);
                    const data = await res.json();
                    if (data.code === 'Ok' && data.routes[0]) {
                        return data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
                    }
                } catch (e) {
                    console.warn('OSRM error, pakai garis lurus:', e);
                }
                return coords;
            }

            // ===== Render map + rute real =====
            async function renderMap(filterDay) {
                // Stop animasi dulu
                stopAnimation();

                allMarkers.forEach(m => map.removeLayer(m));
                allPolylines.forEach(p => map.removeLayer(p));
                allMarkers = [];
                allPolylines = [];
                fullRouteCoords = [];

                const bounds = [];

                // Group destinations by day
                const routeByDay = {};
                for (let d = 1; d <= totalDays; d++) {
                    routeByDay[d] = allPoints
                        .filter(p => p.hari === d && p.type === 'destination')
                        .sort((a, b) => a.urutan - b.urutan);
                }

                // Loading indicator
                const loadingEl = document.getElementById('mapLoadingIndicator');
                if (loadingEl) loadingEl.style.display = 'flex';

                // Kumpulkan semua waypoint untuk animasi full route
                const allWaypoints = [];
                for (let day = 1; day <= totalDays; day++) {
                    routeByDay[day]?.forEach(p => allWaypoints.push([p.lat, p.lng]));
                }

                // Fetch rute per hari & render
                for (let day = 1; day <= totalDays; day++) {
                    if (filterDay !== 'all' && filterDay !== day) continue;

                    const color = dayColors[(day - 1) % dayColors.length];
                    const dayPoints = routeByDay[day];
                    if (!dayPoints?.length) continue;

                    const coords = dayPoints.map(p => [p.lat, p.lng]);

                    // Render marker per destinasi
                    dayPoints.forEach(p => {
                        const marker = L.marker([p.lat, p.lng], {
                                icon: makeDestIcon(color, p.urutan)
                            })
                            .addTo(map)
                            .bindPopup(`
                    <div style="padding:12px 14px;min-width:190px;">
                        <div style="display:flex;align-items:center;gap:7px;margin-bottom:8px;">
                            <div style="background:${color};color:white;width:24px;height:24px;border-radius:50%;
                                display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;">${p.urutan}</div>
                            <p style="font-weight:700;font-size:13px;color:#0f172a;margin:0;">${p.nama}</p>
                        </div>
                        <span style="background:${color}18;color:${color};font-size:11px;font-weight:600;
                            padding:3px 8px;border-radius:6px;">📅 Hari ${p.hari} · 🕐 ${p.waktu}</span>
                    </div>
                `, {
                                className: 'custom-popup',
                                maxWidth: 230
                            });
                        allMarkers.push(marker);
                        bounds.push([p.lat, p.lng]);
                    });

                    // Fetch road route
                    const roadCoords = await fetchRoadRoute(coords);

                    // Shadow
                    const shadow = L.polyline(roadCoords, {
                        color: '#000',
                        weight: 6,
                        opacity: 0.07
                    }).addTo(map);
                    allPolylines.push(shadow);

                    // Main line
                    const poly = L.polyline(roadCoords, {
                        color,
                        weight: 4,
                        opacity: 0.85,
                        lineJoin: 'round',
                        lineCap: 'round',
                    }).addTo(map);
                    allPolylines.push(poly);

                    // Titik tengah segmen (arah marker)
                    for (let i = 0; i < roadCoords.length - 1; i += Math.max(1, Math.floor(roadCoords.length / 8))) {
                        const midLat = (roadCoords[i][0] + roadCoords[i + 1][0]) / 2;
                        const midLng = (roadCoords[i][1] + roadCoords[i + 1][1]) / 2;
                        const dot = L.circleMarker([midLat, midLng], {
                            radius: 3,
                            fillColor: color,
                            fillOpacity: 0.9,
                            color: 'white',
                            weight: 1.5,
                        }).addTo(map);
                        allPolylines.push(dot);
                    }

                    // Kuliner markers
                    allPoints.filter(p => p.hari === day && p.type === 'kuliner').forEach(k => {
                        const m = L.marker([k.lat, k.lng], {
                                icon: makeKulinerIcon(color)
                            })
                            .addTo(map)
                            .bindPopup(`<div style="padding:12px 14px;min-width:160px;">
                    <p style="font-weight:700;font-size:12px;color:#0f172a;margin:0 0 4px;">🍜 ${k.nama}</p>
                    <span style="font-size:10px;color:#f97316;font-weight:600;">Kuliner Hari ${k.hari}</span>
                </div>`, {
                                className: 'custom-popup'
                            });
                        allMarkers.push(m);
                        bounds.push([k.lat, k.lng]);
                    });
                }

                // Connector antar hari (dashed)
                if (filterDay === 'all' && totalDays > 1) {
                    for (let day = 1; day < totalDays; day++) {
                        const thisDay = routeByDay[day];
                        const nextDay = routeByDay[day + 1];
                        if (!thisDay?.length || !nextDay?.length) continue;

                        const last = thisDay[thisDay.length - 1];
                        const first = nextDay[0];

                        const connCoords = await fetchRoadRoute([
                            [last.lat, last.lng],
                            [first.lat, first.lng]
                        ]);

                        const conn = L.polyline(connCoords, {
                            color: '#94a3b8',
                            weight: 2,
                            opacity: 0.5,
                            dashArray: '10,8',
                        }).addTo(map);
                        allPolylines.push(conn);

                        // Label H→H
                        const midLat = (last.lat + first.lat) / 2;
                        const midLng = (last.lng + first.lng) / 2;
                        const labelIcon = L.divIcon({
                            className: '',
                            html: `<div style="background:white;border:1px solid #cbd5e1;color:#64748b;
                    font-size:9px;font-weight:600;padding:2px 7px;border-radius:10px;
                    white-space:nowrap;box-shadow:0 1px 4px rgba(0,0,0,0.1);">H${day}→H${day+1}</div>`,
                            iconSize: [60, 20],
                            iconAnchor: [30, 10],
                        });
                        const lm = L.marker([midLat, midLng], {
                            icon: labelIcon
                        }).addTo(map);
                        allMarkers.push(lm);
                    }
                }

                // Hotel markers
                hotels.forEach(h => {
                    if (!h.lat || !h.lng) return;
                    const m = L.marker([h.lat, h.lng], {
                            icon: makeHotelIcon()
                        })
                        .addTo(map)
                        .bindPopup(`<div style="padding:12px 14px;min-width:190px;">
                <p style="font-weight:700;font-size:12px;color:#0f172a;margin:0 0 4px;">🏨 ${h.nama}</p>
                <p style="font-size:11px;color:#7c3aed;margin:0 0 4px;font-weight:600;">${h.tipe} ${'⭐'.repeat(h.bintang||0)}</p>
                <p style="font-size:11px;color:#64748b;margin:0 0 6px;">${h.lokasi||''}</p>
                <p style="font-size:13px;font-weight:800;color:#7c3aed;margin:0;">${h.kisaran_harga||''}</p>
            </div>`, {
                            className: 'custom-popup'
                        });
                    allMarkers.push(m);
                    bounds.push([h.lat, h.lng]);
                });

                if (loadingEl) loadingEl.style.display = 'none';

                if (bounds.length > 0) map.fitBounds(bounds, {
                    padding: [50, 50]
                });

                // Bangun full route untuk animasi (hanya saat "Semua Hari")
                if (filterDay === 'all' && allWaypoints.length >= 2) {
                    try {
                        fullRouteCoords = await fetchRoadRoute(allWaypoints);
                    } catch (e) {
                        fullRouteCoords = allWaypoints;
                    }
                    // Tampilkan tombol animasi
                    document.getElementById('animBtn').style.display = 'flex';
                } else {
                    document.getElementById('animBtn').style.display = 'none';
                }

                // Strategi
                if (strategi) {
                    const mapEl = document.getElementById('routeMap');
                    let infoEl = document.getElementById('strategiInfo');
                    if (!infoEl) {
                        infoEl = document.createElement('div');
                        infoEl.id = 'strategiInfo';
                        infoEl.style.cssText =
                            'padding:10px 16px;background:#f8fafc;border-top:1px solid #e2e8f0;font-size:11px;color:#64748b;display:flex;align-items:start;gap:8px;';
                        mapEl.parentElement.appendChild(infoEl);
                    }
                    infoEl.innerHTML = `<i class="fa-solid fa-route" style="color:#2563eb;flex-shrink:0;margin-top:1px;"></i>
            <span><strong style="color:#334155;">Strategi rute:</strong> ${strategi}</span>`;
                }
            }

            // ===== ANIMASI POINTER =====
            function startAnimation() {
                if (!fullRouteCoords.length || animationRunning) return;

                animationRunning = true;
                document.getElementById('animBtn').innerHTML = `
        <i class="fa-solid fa-stop"></i> Stop Animasi
    `;

                // Tampilkan progress & speed
                document.getElementById('animProgressContainer').style.display = 'block';
                document.getElementById('speedControl').style.display = 'flex';

                // Buat pointer di titik awal
                if (pointerMarker) map.removeLayer(pointerMarker);
                pointerMarker = L.marker(fullRouteCoords[0], {
                    icon: makePointerIcon(0),
                    zIndexOffset: 1000,
                }).addTo(map);

                // Zoom awal — tidak terlalu dekat
                map.setView(fullRouteCoords[0], 14, {
                    animate: true
                });

                let idx = 0;
                const total = fullRouteCoords.length;
                let lastAngle = 0;

                function getSpeed() {
                    const slider = document.getElementById('speedSlider');
                    return slider ? parseInt(slider.value) : 1;
                }

                // Trail kuning di belakang pointer
                let trailCoords = [fullRouteCoords[0]];
                const trailLine = L.polyline(trailCoords, {
                    color: '#fbbf24',
                    weight: 3.5,
                    opacity: 0.9,
                    dashArray: '8,5',
                }).addTo(map);
                allPolylines.push(trailLine);

                function getAngle(from, to) {
                    const dy = to[0] - from[0];
                    const dx = to[1] - from[1];
                    return Math.atan2(dx, dy) * 180 / Math.PI;
                }

                let lastTime = null;
                const msPerStep = 40;

                function step(timestamp) {
                    if (!animationRunning) return;

                    if (lastTime && timestamp - lastTime < msPerStep) {
                        animationTimer = requestAnimationFrame(step);
                        return;
                    }
                    lastTime = timestamp;

                    if (idx >= total - 1) {
                        animationRunning = false;
                        document.getElementById('animBtn').innerHTML = `
                <i class="fa-solid fa-rotate-left"></i> Ulangi Animasi
            `;
                        // Flash selesai
                        let flash = 0;
                        const flashTimer = setInterval(() => {
                            const wrapper = document.getElementById('pointerWrapper');
                            if (wrapper) wrapper.style.opacity = flash % 2 === 0 ? '0.2' : '1';
                            flash++;
                            if (flash > 8) {
                                clearInterval(flashTimer);
                                const w = document.getElementById('pointerWrapper');
                                if (w) w.style.opacity = '1';
                            }
                        }, 200);
                        return;
                    }

                    const speed = getSpeed();
                    idx = Math.min(idx + speed, total - 1);

                    const pos = fullRouteCoords[idx];
                    const prev = fullRouteCoords[Math.max(0, idx - speed)];

                    // Hitung sudut rotasi
                    if (idx > 0) {
                        lastAngle = getAngle(prev, pos);
                    }

                    // Update posisi marker dengan icon baru (rotasi terupdate)
                    if (pointerMarker) {
                        pointerMarker.setLatLng(pos);
                        // Update rotasi langsung via DOM — lebih smooth dari ganti icon
                        const wrapper = document.getElementById('pointerWrapper');
                        if (wrapper) {
                            wrapper.style.transform = `rotate(${lastAngle}deg)`;
                        }
                    }

                    // Trail
                    trailCoords.push(pos);
                    if (trailCoords.length > 100) trailCoords.shift();
                    trailLine.setLatLngs(trailCoords);

                    // Pan mengikuti pointer — zoom tetap di 14 agar jalan terrender
                    map.panTo(pos, {
                        animate: true,
                        duration: 0.6,
                        easeLinearity: 0.4,
                    });

                    // Jaga zoom tetap di level 14 (tidak terlalu dekat)
                    if (map.getZoom() > 14) {
                        map.setZoom(14, {
                            animate: false
                        });
                    }

                    // Progress bar
                    const pct = Math.round((idx / (total - 1)) * 100);
                    const progressEl = document.getElementById('animProgress');
                    if (progressEl) {
                        progressEl.style.width = pct + '%';
                        const txt = document.getElementById('animProgressText');
                        if (txt) txt.textContent = pct + '%';
                    }

                    animationTimer = requestAnimationFrame(step);
                }

                animationTimer = requestAnimationFrame(step);
            }

            //         function startAnimation() {
            //             if (!fullRouteCoords.length || animationRunning) return;
            //             animationRunning = true;
            //             document.getElementById('animBtn').innerHTML = `
    //     <i class="fa-solid fa-stop"></i> Stop Animasi
    // `;
            //             if (pointerMarker) map.removeLayer(pointerMarker);
            //             pointerMarker = L.marker(fullRouteCoords[0], {
            //                 icon: pointerIcon,
            //                 zIndexOffset: 1000,
            //             }).addTo(map);
            //             map.setView(fullRouteCoords[0], 15, {
            //                 animate: true
            //             });
            //             let idx = 0;
            //             const total = fullRouteCoords.length;
            //             // Ambil nilai speed dari slider
            //             function getSpeed() {
            //                 const slider = document.getElementById('speedSlider');
            //                 return slider ? parseInt(slider.value) : 1;
            //             }
            //             // Trail
            //             let trailCoords = [fullRouteCoords[0]];
            //             const trailLine = L.polyline(trailCoords, {
            //                 color: '#fbbf24',
            //                 weight: 3,
            //                 opacity: 0.85,
            //                 dashArray: '6,4',
            //             }).addTo(map);
            //             allPolylines.push(trailLine);
            //             function getAngle(from, to) {
            //                 const dy = to[0] - from[0];
            //                 const dx = to[1] - from[1];
            //                 return Math.atan2(dx, dy) * 180 / Math.PI;
            //             }
            //             function applyRotation(angle) {
            //                 const el = document.querySelector('.pointer-icon');
            //                 if (el) el.style.transform = `rotate(${angle}deg)`;
            //             }
            //             let lastTime = null;
            //             const msPerStep = 40; // update setiap 40ms
            //             function step(timestamp) {
            //                 if (!animationRunning) return;
            //                 // Throttle — hanya update setiap msPerStep ms
            //                 if (lastTime && timestamp - lastTime < msPerStep) {
            //                     animationTimer = requestAnimationFrame(step);
            //                     return;
            //                 }
            //                 lastTime = timestamp;
            //                 if (idx >= total - 1) {
            //                     // Selesai
            //                     animationRunning = false;
            //                     document.getElementById('animBtn').innerHTML = `
    //             <i class="fa-solid fa-rotate-left"></i> Ulangi Animasi
    //         `;
            //                     // Flash effect selesai
            //                     let flash = 0;
            //                     const flashTimer = setInterval(() => {
            //                         const el = document.querySelector('.pointer-icon');
            //                         if (el) el.style.opacity = flash % 2 === 0 ? '0.3' : '1';
            //                         flash++;
            //                         if (flash > 8) {
            //                             clearInterval(flashTimer);
            //                             if (el) el.style.opacity = '1';
            //                         }
            //                     }, 200);
            //                     return;
            //                 }
            //                 // Gerak maju sesuai speed slider (1 = pelan, 5 = cepat)
            //                 const speed = getSpeed();
            //                 idx = Math.min(idx + speed, total - 1);
            //                 const pos = fullRouteCoords[idx];
            //                 if (pointerMarker) pointerMarker.setLatLng(pos);
            //                 // Trail
            //                 trailCoords.push(pos);
            //                 if (trailCoords.length > 120) trailCoords.shift();
            //                 trailLine.setLatLngs(trailCoords);
            //                 // Rotasi
            //                 if (idx > 0) {
            //                     const angle = getAngle(fullRouteCoords[idx - 1], pos);
            //                     applyRotation(angle);
            //                 }
            //                 // Pan mengikuti pointer dengan smooth
            //                 map.panTo(pos, {
            //                     animate: true,
            //                     duration: 0.5,
            //                     easeLinearity: 0.5
            //                 });
            //                 // Update progress bar
            //                 const pct = Math.round((idx / (total - 1)) * 100);
            //                 const progressEl = document.getElementById('animProgress');
            //                 if (progressEl) {
            //                     progressEl.style.width = pct + '%';
            //                     document.getElementById('animProgressText').textContent = pct + '%';
            //                 }
            //                 animationTimer = requestAnimationFrame(step);
            //             }
            //             animationTimer = requestAnimationFrame(step);
            //         }

            // function stopAnimation() {
            //     if (animationTimer) {
            //         cancelAnimationFrame(animationTimer);
            //         animationTimer = null;
            //     }
            //     animationRunning = false;

            //     if (pointerMarker) {
            //         map.removeLayer(pointerMarker);
            //         pointerMarker = null;
            //     }

            //     const btn = document.getElementById('animBtn');
            //     if (btn) btn.innerHTML = `<i class="fa-solid fa-play"></i> Mulai Animasi Perjalanan`;
            // }

            function stopAnimation() {
                if (animationTimer) {
                    cancelAnimationFrame(animationTimer);
                    animationTimer = null;
                }
                animationRunning = false;

                if (pointerMarker) {
                    map.removeLayer(pointerMarker);
                    pointerMarker = null;
                }

                // Reset progress UI
                const progressEl = document.getElementById('animProgress');
                if (progressEl) progressEl.style.width = '0%';
                const progressText = document.getElementById('animProgressText');
                if (progressText) progressText.textContent = '0%';

                const container = document.getElementById('animProgressContainer');
                if (container) container.style.display = 'none';
                const speedCtrl = document.getElementById('speedControl');
                if (speedCtrl) speedCtrl.style.display = 'none';

                const btn = document.getElementById('animBtn');
                if (btn) btn.innerHTML = `<i class="fa-solid fa-play"></i> Mulai Animasi Perjalanan`;
            }

            function toggleAnimation() {
                if (animationRunning) {
                    stopAnimation();
                } else {
                    startAnimation();
                }
            }

            // ===== Filter tab =====
            function filterRoute(day) {
                stopAnimation();
                document.querySelectorAll('.route-tab').forEach(t => t.classList.remove('active'));
                const id = day === 'all' ? 'tabAll' : 'tab' + day;
                document.getElementById(id)?.classList.add('active');
                renderMap(day);
            }

            // ===== Warna dot di tab =====
            (function() {
                document.querySelectorAll('.route-tab').forEach((tab, i) => {
                    if (i === 0) return;
                    const color = dayColors[(i - 1) % dayColors.length];
                    const dot = document.createElement('span');
                    dot.style.cssText =
                        `display:inline-block;width:8px;height:8px;border-radius:50%;background:${color};margin-right:5px;vertical-align:middle;`;
                    tab.prepend(dot);
                });
            })();

            renderMap('all');
        </script>
    @endpush

    <style>
        @media print {

            nav,
            footer,
            #routeMap,
            button {
                display: none !important;
            }
        }
    </style>

@endsection
