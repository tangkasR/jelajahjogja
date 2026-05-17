@extends('layouts.public')
@section('title', 'Semua Destinasi — Jelajah Jogja')
@section('content')

    <div class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 py-8 md:py-10">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <i class="fa-solid fa-map-location-dot text-blue-600"></i> Semua Destinasi Wisata
            </h1>
            <form method="GET" class="grid grid-cols-2 md:flex gap-2 md:gap-3 flex-wrap">
                <div
                    class="col-span-2 flex items-center gap-2 border border-gray-200 rounded-xl px-3 md:px-4 bg-white focus-within:ring-2 focus-within:ring-blue-500">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari destinasi..."
                        class="flex-1 py-2.5 text-sm focus:outline-none">
                </div>
                <select name="category"
                    class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value=""><i class="fa-solid fa-tag"></i> Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="district"
                    class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Semua Wilayah</option>
                    @foreach ($districts as $d)
                        <option value="{{ $d }}" @selected(request('district') === $d)>{{ $d }}</option>
                    @endforeach
                </select>
                <button
                    class="flex items-center justify-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if (request()->hasAny(['q', 'category', 'district']))
                    <a href="{{ route('destinations.index') }}"
                        class="flex items-center justify-center gap-2 border border-gray-200 px-4 py-2.5 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition">
                        <i class="fa-solid fa-xmark"></i> Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8 md:py-10">
        @if ($destinations->count())
            <p class="text-sm text-gray-500 mb-5 flex items-center gap-2">
                <i class="fa-solid fa-map-pin text-blue-500"></i>
                Menampilkan <strong>{{ $destinations->total() }}</strong> destinasi
            </p>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-5">
                @foreach ($destinations as $dest)
                    <a href="{{ route('destinations.show', $dest->slug) }}"
                        class="card-hover bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 flex flex-col h-full">
                        <div class="relative h-36 md:h-44 overflow-hidden">
                            @if ($dest->hero)
                                <img src="{{ asset('storage/' . $dest->hero->path) }}" class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
                                    <i class="fa-solid fa-mountain-sun text-3xl text-blue-200"></i>
                                </div>
                            @endif
                            <div class="absolute top-2 left-2">
                                <span class="bg-white/90 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    {{ $dest->category->name }}
                                </span>
                            </div>
                        </div>
                        <div class="p-3 md:p-4 min-w-0">
                            <h3 class="font-bold text-gray-900 text-xs md:text-sm leading-snug mb-1">
                                {{ $dest->title }}
                            </h3>

                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-blue-500 text-xs"></i>
                                {{ $dest->district }}
                            </p>

                            <p class="text-xs text-gray-600 mt-1.5 line-clamp-2">
                                {{ $dest->description }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8 md:mt-10">{{ $destinations->links() }}</div>
        @else
            <div class="text-center py-20 md:py-28">
                <i class="fa-solid fa-magnifying-glass text-4xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-500 font-medium">Tidak ada destinasi yang ditemukan.</p>
                <a href="{{ route('destinations.index') }}"
                    class="text-blue-600 text-sm hover:underline mt-2 inline-flex items-center gap-1">
                    <i class="fa-solid fa-xmark text-xs"></i> Reset filter
                </a>
            </div>
        @endif
    </div>
@endsection
