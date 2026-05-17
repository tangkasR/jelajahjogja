@extends('layouts.public')
@section('title', $destination->title . ' — Jelajah Jogja')
@section('content')

    {{-- Hero --}}
    <div class="w-full h-[55vh] md:h-[65vh] min-h-[350px] relative bg-gray-900 overflow-hidden">
        @if ($destination->hero)
            <img src="{{ asset('storage/' . $destination->hero->path) }}" class="w-full h-full object-cover opacity-85"
                style="animation: slowZoom 20s ease-in-out infinite alternate;">
        @else
            <div class="w-full h-full bg-gradient-to-br from-blue-800 to-indigo-900"></div>
        @endif
        <div class="hero-gradient absolute inset-0"></div>
        <div class="absolute bottom-6 md:bottom-10 left-4 md:left-8 text-white max-w-3xl">
            <div class="flex items-center gap-2 mb-3 flex-wrap text-xs">
                <a href="{{ route('destinations.index') }}" class="text-white/60 hover:text-white flex items-center gap-1">
                    <i class="fa-solid fa-map-location-dot"></i> Destinasi
                </a>
                <i class="fa-solid fa-chevron-right text-white/30 text-xs"></i>
                <span class="text-white/60">{{ $destination->category->name }}</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold drop-shadow-xl mb-3 leading-tight">{{ $destination->title }}</h1>
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-white/80 text-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-location-dot text-yellow-400"></i>
                    {{ $destination->district }}, Yogyakarta
                </p>
                @php
                    $avg = $destination->averageRating();
                    $cnt = $destination->reviewCount();
                @endphp
                @if ($avg > 0)
                    <div class="flex items-center gap-1.5 bg-black/30 backdrop-blur-sm px-3 py-1 rounded-full text-sm">
                        <i class="fa-solid fa-star text-yellow-400"></i>
                        <span class="font-bold">{{ $avg }}</span>
                        <span class="text-white/60 text-xs">({{ $cnt }} ulasan)</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @keyframes slowZoom {
            from {
                transform: scale(1.05)
            }

            to {
                transform: scale(1.13)
            }
        }
    </style>

    <div class="max-w-5xl mx-auto px-4 py-10 md:py-14">
        <div class="flex flex-col lg:grid lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- Konten Kiri --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Deskripsi --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-500"></i> Tentang Destinasi
                    </h2>
                    <p class="text-gray-600 leading-relaxed">{{ $destination->description }}</p>
                </div>

                {{-- Galeri --}}
                @if ($destination->gallery->count())
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-images text-blue-500"></i>
                            Galeri Foto
                            <span class="text-sm font-normal text-gray-400">({{ $destination->gallery->count() }})</span>
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-3">
                            @foreach ($destination->gallery as $photo)
                                <a href="{{ asset('storage/' . $photo->path) }}"
                                    class="glightbox block overflow-hidden rounded-xl aspect-square relative group"
                                    data-gallery="dest-{{ $destination->id }}"
                                    data-description="{{ $destination->title }}">
                                    <img src="{{ asset('storage/' . $photo->path) }}"
                                        class="w-full h-full object-cover transition duration-300 group-hover:scale-110">
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition flex items-center justify-center">
                                        <i
                                            class="fa-solid fa-magnifying-glass-plus text-white opacity-0 group-hover:opacity-100 transition text-xl"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400 mt-3 flex items-center gap-1">
                            <i class="fa-solid fa-hand-pointer"></i> Klik foto untuk memperbesar
                        </p>
                    </div>
                @endif

                {{-- Peta --}}
                @if ($destination->lat && $destination->lng)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100" data-aos="fade-up">

                        {{-- Header peta --}}
                        <div class="px-6 pt-5 pb-4 flex flex-col gap-3">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <i class="fa-solid fa-map text-blue-500"></i> Lokasi & Sekitar
                                </h2>
                            </div>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $destination->address }}</p>
                            <a href="https://maps.google.com/?q={{ $destination->lat }},{{ $destination->lng }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 border border-blue-100 px-4 py-2 rounded-xl text-xs font-semibold hover:bg-blue-100 transition self-start sm:self-auto">
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i> Buka Google Maps
                            </a>
                        </div>

                        {{-- Filter POI --}}
                        <div class="px-6 pb-3 flex flex-wrap gap-2" id="poiFilters">
                            <button onclick="togglePOI('restaurant')"
                                class="poi-btn active flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border transition"
                                data-type="restaurant">
                                <span>🍜</span> Kuliner
                            </button>
                            <button onclick="togglePOI('hotel')"
                                class="poi-btn active flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border transition"
                                data-type="hotel">
                                <span>🏨</span> Penginapan
                            </button>
                            <button onclick="togglePOI('tourism')"
                                class="poi-btn active flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border transition"
                                data-type="tourism">
                                <span>📍</span> Wisata
                            </button>
                            <button onclick="togglePOI('mosque')"
                                class="poi-btn active flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border transition"
                                data-type="mosque">
                                <span>🕌</span> Ibadah
                            </button>
                            <button onclick="togglePOI('convenience')"
                                class="poi-btn active flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border transition"
                                data-type="convenience">
                                <span>🏪</span> Toko
                            </button>
                        </div>

                        {{-- Map container --}}
                        <div id="map" class="w-full" style="height: 420px;"></div>

                        {{-- POI List --}}
                        <div id="poiList" class="px-6 py-4 border-t border-gray-100">
                            <p class="text-xs text-gray-400 flex items-center gap-2">
                                <i class="fa-solid fa-circle-notch fa-spin text-blue-400"></i>
                                Memuat data sekitar lokasi...
                            </p>
                        </div>
                    </div>

                    {{-- Leaflet CSS & JS --}}
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

                    <style>
                        .poi-btn {
                            background: white;
                            color: #6b7280;
                            border-color: #e5e7eb;
                        }

                        .poi-btn.active {
                            background: #eff6ff;
                            color: #2563eb;
                            border-color: #bfdbfe;
                        }

                        .poi-popup .leaflet-popup-content-wrapper {
                            border-radius: 12px;
                            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
                            border: none;
                            padding: 0;
                        }

                        .poi-popup .leaflet-popup-content {
                            margin: 0;
                            line-height: 1;
                        }

                        .poi-popup .leaflet-popup-tip-container {
                            margin-top: -1px;
                        }
                    </style>

                    @push('scripts')
                        <script>
                            const LAT = {{ $destination->lat }};
                            const LNG = {{ $destination->lng }};
                            const NAME = @json($destination->title);

                            // ---- Inisialisasi peta ----
                            const map = L.map('map', {
                                center: [LAT, LNG],
                                zoom: 15,
                                zoomControl: true,
                            });

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                                maxZoom: 19,
                            }).addTo(map);

                            // ---- Marker utama (destinasi) ----
                            const mainIcon = L.divIcon({
                                className: '',
                                html: `<div style="
                                    background: linear-gradient(135deg, #2563eb, #4f46e5);
                                    color: white;
                                    width: 40px;
                                    height: 40px;
                                    border-radius: 50% 50% 50% 0;
                                    transform: rotate(-45deg);
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    box-shadow: 0 4px 15px rgba(37,99,235,0.4);
                                    border: 3px solid white;
                                ">
                                    <span style="transform: rotate(45deg); font-size: 16px;">📍</span>
                                </div>`,
                                iconSize: [40, 40],
                                iconAnchor: [20, 40],
                                popupAnchor: [0, -42],
                            });

                            L.marker([LAT, LNG], {
                                    icon: mainIcon
                                })
                                .addTo(map)
                                .bindPopup(`
                                    <div style="padding:12px 14px; min-width:160px;">
                                        <p style="font-weight:700; font-size:13px; color:#1e293b; margin:0 0 4px;">${NAME}</p>
                                        <p style="font-size:11px; color:#64748b; margin:0;">📍 Lokasi destinasi ini</p>
                                    </div>
                                `, {
                                    className: 'poi-popup'
                                })
                                .openPopup();

                            // ---- Radius circle ----
                            L.circle([LAT, LNG], {
                                radius: 800,
                                color: '#2563eb',
                                fillColor: '#2563eb',
                                fillOpacity: 0.04,
                                weight: 1.5,
                                dashArray: '6, 4',
                            }).addTo(map);

                            // ---- Konfigurasi POI ----
                            const POI_CONFIG = {
                                restaurant: {
                                    query: `node["amenity"~"restaurant|cafe|food_court|fast_food"](around:800,${LAT},${LNG});`,
                                    icon: '🍜',
                                    color: '#f97316',
                                    label: 'Kuliner',
                                    bgColor: '#fff7ed',
                                },
                                hotel: {
                                    query: `node["tourism"~"hotel|hostel|guest_house|motel|lodging"](around:800,${LAT},${LNG});`,
                                    icon: '🏨',
                                    color: '#8b5cf6',
                                    label: 'Penginapan',
                                    bgColor: '#f5f3ff',
                                },
                                tourism: {
                                    query: `node["tourism"~"attraction|viewpoint|museum|artwork|theme_park"](around:1000,${LAT},${LNG});`,
                                    icon: '🗺️',
                                    color: '#10b981',
                                    label: 'Wisata',
                                    bgColor: '#ecfdf5',
                                },
                                mosque: {
                                    query: `node["amenity"~"place_of_worship"](around:800,${LAT},${LNG});`,
                                    icon: '🕌',
                                    color: '#6366f1',
                                    label: 'Ibadah',
                                    bgColor: '#eef2ff',
                                },
                                convenience: {
                                    query: `node["shop"~"convenience|supermarket|minimarket"](around:600,${LAT},${LNG});`,
                                    icon: '🏪',
                                    color: '#0ea5e9',
                                    label: 'Toko',
                                    bgColor: '#f0f9ff',
                                },
                            };

                            // ---- State markers ----
                            const poiMarkers = {};
                            const poiData = {};
                            let activeTypes = new Set(Object.keys(POI_CONFIG));

                            // ---- Buat icon POI ----
                            function createPOIIcon(cfg) {
                                return L.divIcon({
                                    className: '',
                                    html: `<div style="
                                            background: white;
                                            width: 30px;
                                            height: 30px;
                                            border-radius: 50%;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                                            border: 2px solid ${cfg.color};
                                            font-size: 13px;
                                        ">${cfg.icon}</div>`,
                                    iconSize: [30, 30],
                                    iconAnchor: [15, 15],
                                    popupAnchor: [0, -18],
                                });
                            }

                            // ---- Fetch POI dari Overpass API ----
                            async function fetchPOI(type) {
                                const cfg = POI_CONFIG[type];
                                const query = `[out:json][timeout:15];(${cfg.query});out body;`;
                                const url = `https://overpass-api.de/api/interpreter?data=${encodeURIComponent(query)}`;

                                try {
                                    const res = await fetch(url);
                                    const data = await res.json();
                                    poiData[type] = data.elements || [];
                                    renderMarkers(type);
                                    updatePOIList();
                                } catch (e) {
                                    console.warn('Overpass error:', type, e);
                                    poiData[type] = [];
                                }
                            }

                            // ---- Render marker ke peta ----
                            function renderMarkers(type) {
                                // Hapus markers lama
                                if (poiMarkers[type]) {
                                    poiMarkers[type].forEach(m => map.removeLayer(m));
                                }
                                poiMarkers[type] = [];

                                if (!activeTypes.has(type)) return;

                                const cfg = POI_CONFIG[type];
                                const icon = createPOIIcon(cfg);
                                const items = poiData[type] || [];

                                items.slice(0, 15).forEach(node => {
                                    if (!node.lat || !node.lon) return;

                                    const name = node.tags?.name || cfg.label;
                                    const addr = [
                                        node.tags?.['addr:street'],
                                        node.tags?.['addr:housenumber'],
                                    ].filter(Boolean).join(' ') || '';

                                    const phone = node.tags?.phone || node.tags?.['contact:phone'] || '';
                                    const opening = node.tags?.opening_hours || '';

                                    const dist = getDistance(LAT, LNG, node.lat, node.lon);

                                    const marker = L.marker([node.lat, node.lon], {
                                            icon
                                        })
                                        .addTo(map)
                                        .bindPopup(`
                                            <div style="padding:12px 14px; min-width:170px; max-width:220px;">
                                                <div style="display:flex; align-items:center; gap:6px; margin-bottom:6px;">
                                                    <span style="font-size:16px;">${cfg.icon}</span>
                                                    <p style="font-weight:700; font-size:12px; color:#1e293b; margin:0; line-height:1.3;">${name}</p>
                                                </div>
                                                <div style="display:flex; flex-direction:column; gap:3px;">
                                                    <span style="display:inline-block; background:${cfg.bgColor}; color:${cfg.color}; font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px; width:fit-content;">${cfg.label}</span>
                                                    ${addr ? `<p style="font-size:10px; color:#64748b; margin:0;">📍 ${addr}</p>` : ''}
                                                    ${dist ? `<p style="font-size:10px; color:#64748b; margin:0;">🚶 ${dist}m dari sini</p>` : ''}
                                                    ${opening ? `<p style="font-size:10px; color:#64748b; margin:0;">🕐 ${opening.length > 25 ? opening.substring(0,25)+'...' : opening}</p>` : ''}
                                                    ${phone ? `<p style="font-size:10px; color:#64748b; margin:0;">📞 ${phone}</p>` : ''}
                                                </div>
                                            </div>
                                        `, {
                                            className: 'poi-popup',
                                            maxWidth: 240
                                        });

                                    poiMarkers[type].push(marker);
                                });
                            }

                            // ---- Hitung jarak meter ----
                            function getDistance(lat1, lng1, lat2, lng2) {
                                const R = 6371000;
                                const dL = (lat2 - lat1) * Math.PI / 180;
                                const dl = (lng2 - lng1) * Math.PI / 180;
                                const a = Math.sin(dL / 2) ** 2 + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(
                                    dl / 2) ** 2;
                                return Math.round(R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
                            }

                            // ---- Toggle filter ----
                            function togglePOI(type) {
                                const btn = document.querySelector(`.poi-btn[data-type="${type}"]`);
                                if (activeTypes.has(type)) {
                                    activeTypes.delete(type);
                                    btn.classList.remove('active');
                                    if (poiMarkers[type]) {
                                        poiMarkers[type].forEach(m => map.removeLayer(m));
                                    }
                                } else {
                                    activeTypes.add(type);
                                    btn.classList.add('active');
                                    renderMarkers(type);
                                }
                                updatePOIList();
                            }

                            // ---- Update daftar POI di bawah peta ----
                            function updatePOIList() {
                                const list = document.getElementById('poiList');
                                let html = '';
                                let total = 0;

                                Object.keys(POI_CONFIG).forEach(type => {
                                    const items = (poiData[type] || []).slice(0, 5);
                                    if (!items.length || !activeTypes.has(type)) return;
                                    const cfg = POI_CONFIG[type];

                                    html += `
                                            <div class="mb-3">
                                                <p style="font-size:11px; font-weight:700; color:${cfg.color}; text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px;">
                                                    ${cfg.icon} ${cfg.label} Terdekat
                                                </p>
                                                <div style="display:flex; flex-wrap:wrap; gap:6px;">
                                        `;

                                    items.forEach(node => {
                                        if (!node.lat) return;
                                        const name = node.tags?.name || cfg.label;
                                        const dist = getDistance(LAT, LNG, node.lat, node.lon);
                                        total++;
                                        html += `
                                                <button onclick="map.setView([${node.lat},${node.lon}], 17)"
                                                        style="
                                                            background:${cfg.bgColor};
                                                            border:1px solid ${cfg.color}22;
                                                            color:#374151;
                                                            font-size:11px;
                                                            padding:4px 10px;
                                                            border-radius:20px;
                                                            cursor:pointer;
                                                            display:flex;
                                                            align-items:center;
                                                            gap:4px;
                                                            transition:all 0.15s;
                                                        "
                                                        onmouseover="this.style.background='${cfg.color}22'"
                                                        onmouseout="this.style.background='${cfg.bgColor}'">
                                                    ${name.length > 20 ? name.substring(0,20)+'…' : name}
                                                    <span style="color:#9ca3af; font-size:10px;">${dist}m</span>
                                                </button>
                                            `;
                                    });

                                    html += `</div></div>`;
                                });

                                if (total === 0) {
                                    const loaded = Object.keys(poiData).length;
                                    if (loaded < Object.keys(POI_CONFIG).length) {
                                        html = `<p style="font-size:12px; color:#9ca3af;" class="flex items-center gap-2">
                                                <i class="fa-solid fa-circle-notch fa-spin text-blue-400"></i> Memuat data sekitar lokasi...
                                            </p>`;
                                    } else {
                                        html = `<p style="font-size:12px; color:#9ca3af;">Tidak ditemukan POI di sekitar lokasi ini.</p>`;
                                    }
                                }

                                list.innerHTML = html;
                            }

                            // ---- Load semua POI ----
                            Object.keys(POI_CONFIG).forEach(type => fetchPOI(type));
                        </script>
                    @endpush
                @endif

                {{-- ===== SECTION ULASAN ===== --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                    {{-- Header rating summary --}}
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                            {{-- Angka besar --}}
                            <div class="text-center sm:border-r sm:border-gray-100 sm:pr-6">
                                <p class="text-5xl font-extrabold text-gray-900">{{ $avg > 0 ? $avg : '–' }}</p>
                                <div class="flex items-center justify-center gap-0.5 my-1.5">
                                    @php
                                        $fullStars = floor($avg);
                                        $hasHalf = $avg - $fullStars >= 0.5;
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $fullStars)
                                            <i class="fa-solid fa-star text-yellow-400 text-base"></i>
                                        @elseif($i == $fullStars + 1 && $hasHalf)
                                            <i class="fa-solid fa-star-half-stroke text-yellow-400 text-base"></i>
                                        @else
                                            <i class="fa-regular fa-star text-gray-200 text-base"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-400">{{ $cnt }} ulasan</p>
                            </div>
                            {{-- Bar distribusi --}}
                            @if ($cnt > 0)
                                <div class="flex-1 space-y-1.5">
                                    @foreach ([5, 4, 3, 2, 1] as $star)
                                        @php
                                            $count = $reviews->filter(fn($r) => round($r->rating) == $star)->count();
                                            $pct = $cnt > 0 ? ($count / $cnt) * 100 : 0;
                                        @endphp
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="text-gray-500 w-4 text-right">{{ $star }}</span>
                                            <i class="fa-solid fa-star text-yellow-400 text-xs"></i>
                                            <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-yellow-400 h-1.5 rounded-full transition-all duration-500"
                                                    style="width: {{ $pct }}%"></div>
                                            </div>
                                            <span class="text-gray-400 w-4">{{ $count }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Success message --}}
                    @if (session('review_success'))
                        <div
                            class="mx-6 mt-5 flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                            <i class="fa-solid fa-circle-check text-green-500 mt-0.5 flex-shrink-0"></i>
                            {{ session('review_success') }}
                        </div>
                    @endif

                    {{-- Daftar ulasan --}}
                    @if ($reviews->count())
                        <div class="divide-y divide-gray-50 px-6">
                            @foreach ($reviews as $review)
                                <div class="py-5">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                            {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between flex-wrap gap-1 mb-1">
                                                <p class="font-bold text-gray-800">{{ $review->reviewer_name }}</p>
                                                <span
                                                    class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            {{-- Bintang half star --}}
                                            <div class="flex items-center gap-0.5 mb-2">
                                                @php
                                                    $full = floor($review->rating);
                                                    $half = $review->rating - $full >= 0.5;
                                                @endphp
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $full)
                                                        <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                                                    @elseif($i == $full + 1 && $half)
                                                        <i
                                                            class="fa-solid fa-star-half-stroke text-yellow-400 text-sm"></i>
                                                    @else
                                                        <i class="fa-regular fa-star text-gray-200 text-sm"></i>
                                                    @endif
                                                @endfor
                                                <span class="text-xs text-gray-400 ml-1">{{ $review->rating }}/5</span>
                                            </div>
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 px-6">
                            <i class="fa-regular fa-star text-4xl text-gray-200 mb-3 block"></i>
                            <p class="text-gray-500 font-medium">Belum ada ulasan</p>
                            <p class="text-gray-400 text-sm mt-1">Jadilah yang pertama memberikan ulasan!</p>
                        </div>
                    @endif

                    {{-- ===== FORM ULASAN ===== --}}
                    <div class="p-6 bg-gray-50 border-t border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-5 flex items-center gap-2 text-base">
                            <i class="fa-solid fa-pen-to-square text-blue-500"></i> Tulis Ulasanmu
                        </h3>

                        @if ($errors->any())
                            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                                <p class="font-semibold mb-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> Ada
                                    kesalahan:</p>
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('destinations.review', $destination->slug) }}" method="POST"
                            class="space-y-4">
                            @csrf

                            {{-- Nama --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Nama <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="reviewer_name" value="{{ old('reviewer_name') }}" required
                                    placeholder="Nama kamu"
                                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('reviewer_name') border-red-400 @enderror">
                            </div>

                            {{-- Rating — half star interaktif --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Rating <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-0.5" id="starContainer">
                                        @for ($i = 1; $i <= 5; $i++)
                                            {{-- Setiap bintang dibagi dua (kiri = half, kanan = full) --}}
                                            <div class="relative w-8 h-8 cursor-pointer" data-star="{{ $i }}">
                                                {{-- Background bintang (kosong) --}}
                                                <i
                                                    class="fa-regular fa-star text-gray-200 text-2xl absolute inset-0 flex items-center justify-center star-bg pointer-events-none"></i>
                                                {{-- Overlay bintang (isi) --}}
                                                <i class="fa-solid fa-star text-yellow-400 text-2xl absolute inset-0 flex items-center justify-center star-fill pointer-events-none"
                                                    style="opacity:0; clip-path: none;"></i>
                                                {{-- Area klik kiri (half) --}}
                                                <div class="absolute left-0 top-0 w-1/2 h-full"
                                                    data-value="{{ $i - 0.5 }}"
                                                    onclick="setRating({{ $i - 0.5 }})"></div>
                                                {{-- Area klik kanan (full) --}}
                                                <div class="absolute right-0 top-0 w-1/2 h-full"
                                                    data-value="{{ $i }}"
                                                    onclick="setRating({{ $i }})"></div>
                                            </div>
                                        @endfor
                                    </div>
                                    <span id="ratingLabel" class="text-sm text-gray-400 font-medium">Pilih rating</span>
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating') }}">
                                @error('rating')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Komentar --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Ulasan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="comment" rows="4" required
                                    placeholder="Bagikan pengalamanmu mengunjungi {{ $destination->title }}..."
                                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none @error('comment') border-red-400 @enderror">{{ old('comment') }}</textarea>
                                <p class="text-xs text-gray-400 mt-1">Minimal 10 karakter</p>
                            </div>

                            <button type="submit"
                                class="flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-blue-700 active:scale-95 transition shadow-sm">
                                <i class="fa-solid fa-paper-plane"></i> Kirim Ulasan
                            </button>
                        </form>
                    </div>
                </div>
                {{-- ===== END SECTION ULASAN ===== --}}

            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="space-y-4 lg:sticky lg:top-20">
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-indigo-500"></i> Info Destinasi
                        </h3>
                        <div class="space-y-3.5 text-sm">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-tag text-blue-500 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-xs">Kategori</p>
                                    <p class="font-semibold text-gray-800">{{ $destination->category->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-location-dot text-blue-500 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-xs">Wilayah</p>
                                    <p class="font-semibold text-gray-800">{{ $destination->district }}, Yogyakarta</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-house text-blue-500 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-xs">Alamat</p>
                                    <p class="font-semibold text-gray-800 leading-snug">{{ $destination->address }}</p>
                                </div>
                            </div>
                            @if ($avg > 0)
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-8 h-8 bg-yellow-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="fa-solid fa-star text-yellow-400 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-xs">Rating</p>
                                        <p class="font-semibold text-gray-800">{{ $avg }} / 5
                                            <span class="font-normal text-gray-400 text-xs">({{ $cnt }}
                                                ulasan)</span>
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        {{-- @if ($destination->lat && $destination->lng)
                            <a href="https://maps.google.com/?q={{ $destination->lat }},{{ $destination->lng }}"
                                target="_blank"
                                class="mt-5 flex items-center justify-center gap-2 bg-blue-600 text-white text-sm font-semibold py-3 rounded-xl hover:bg-blue-700 transition shadow-sm">
                                <i class="fa-solid fa-diamond-turn-right"></i> Petunjuk Arah
                            </a>
                        @endif --}}
                        {{-- Peta --}}
                    </div>

                    @if ($related->count())
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-map-pin text-indigo-500"></i> Destinasi Serupa
                            </h3>
                            <div class="space-y-3">
                                @foreach ($related as $r)
                                    <a href="{{ route('destinations.show', $r->slug) }}"
                                        class="flex gap-3 items-center hover:bg-gray-50 rounded-xl p-2 -mx-2 transition group">
                                        <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0">
                                            @if ($r->hero)
                                                <img src="{{ asset('storage/' . $r->hero->path) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                                    <i class="fa-solid fa-mountain-sun text-gray-300"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p
                                                class="text-sm font-semibold text-gray-800 leading-snug group-hover:text-blue-600 transition">
                                                {{ $r->title }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                                <i class="fa-solid fa-location-dot text-xs"></i>{{ $r->district }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // ===== HALF STAR RATING =====
            const ratingLabels = {
                0.5: '😐 Sangat Buruk',
                1: '😕 Buruk',
                1.5: '😐 Kurang',
                2: '🙂 Cukup Kurang',
                2.5: '😊 Cukup',
                3: '😊 Lumayan',
                3.5: '👍 Bagus',
                4: '👍 Sangat Bagus',
                4.5: '🌟 Luar Biasa',
                5: '🌟 Sempurna!'
            };

            let currentRating = parseFloat(document.getElementById('ratingInput').value) || 0;

            function setRating(val) {
                currentRating = val;
                document.getElementById('ratingInput').value = val;
                updateStars(val);
                document.getElementById('ratingLabel').textContent = ratingLabels[val] || '';
                document.getElementById('ratingLabel').className = 'text-sm font-semibold text-yellow-600';
            }

            function updateStars(rating) {
                const stars = document.querySelectorAll('#starContainer [data-star]');
                stars.forEach((star, idx) => {
                    const starNum = idx + 1;
                    const fill = star.querySelector('.star-fill');
                    const bg = star.querySelector('.star-bg');

                    if (rating >= starNum) {
                        // full star
                        fill.style.opacity = '1';
                        fill.className = fill.className.replace('fa-star-half-stroke', 'fa-star').replace('fa-regular',
                            'fa-solid');
                        fill.classList.add('fa-solid', 'fa-star');
                    } else if (rating >= starNum - 0.5) {
                        // half star
                        fill.style.opacity = '1';
                        fill.classList.remove('fa-star');
                        fill.classList.add('fa-star-half-stroke');
                    } else {
                        // empty
                        fill.style.opacity = '0';
                    }
                });
            }

            // Hover effect
            document.querySelectorAll('#starContainer [data-value]').forEach(area => {
                area.addEventListener('mouseenter', () => {
                    updateStars(parseFloat(area.dataset.value));
                });
                area.addEventListener('mouseleave', () => {
                    updateStars(currentRating);
                });
            });

            // Init jika ada old value
            if (currentRating > 0) {
                updateStars(currentRating);
                document.getElementById('ratingLabel').textContent = ratingLabels[currentRating] || '';
                document.getElementById('ratingLabel').className = 'text-sm font-semibold text-yellow-600';
            }
        </script>
    @endpush
@endsection
