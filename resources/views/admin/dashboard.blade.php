@extends('layouts.admin')
@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan data destinasi dan ulasan')
@section('content')

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total</span>
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-map-pin text-blue-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Semua destinasi</p>
        </div>
        <div class="bg-white rounded-2xl p-4 md:p-5 border border-yellow-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-yellow-500 uppercase tracking-wide">Pending</span>
                <div class="w-9 h-9 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-clock text-yellow-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Menunggu review</p>
        </div>
        <div class="bg-white rounded-2xl p-4 md:p-5 border border-green-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-green-500 uppercase tracking-wide">Disetujui</span>
                <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-green-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Tayang di publik</p>
        </div>
        <div class="bg-white rounded-2xl p-4 md:p-5 border border-purple-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-purple-500 uppercase tracking-wide">Ulasan</span>
                <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-star text-purple-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $reviewStats['total'] }}</p>
            <p class="text-xs text-gray-400 mt-1">
                ⭐ {{ $reviewStats['avg'] }} rata-rata · {{ $reviewStats['this_month'] }} bulan ini
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
        {{-- Pending Destinasi --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-clock text-yellow-500"></i>
                    <h2 class="font-semibold text-gray-800 text-sm">Destinasi Perlu Review</h2>
                    @if ($stats['pending'] > 0)
                        <span
                            class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full font-semibold">{{ $stats['pending'] }}</span>
                    @endif
                </div>
                <a href="{{ route('admin.destinations.index', ['status' => 'pending']) }}"
                    class="text-xs text-indigo-600 hover:underline font-medium">Lihat semua →</a>
            </div>
            @forelse($pending as $dest)
                <div
                    class="flex items-center gap-3 px-5 py-3 border-b border-gray-50 hover:bg-gray-50 transition last:border-0">
                    <div class="w-10 h-10 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                        @if ($dest->hero)
                            <img src="{{ asset('storage/' . $dest->hero->path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="fa-solid fa-image text-sm"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $dest->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $dest->submitter_name }} · {{ $dest->district }} ·
                            {{ $dest->created_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('admin.destinations.show', $dest) }}"
                        class="flex-shrink-0 bg-yellow-50 text-yellow-700 border border-yellow-200 px-2.5 py-1 rounded-lg text-xs font-semibold hover:bg-yellow-100 transition">
                        Review
                    </a>
                </div>
            @empty
                <div class="text-center py-10">
                    <i class="fa-solid fa-circle-check text-3xl text-green-200 mb-2 block"></i>
                    <p class="text-gray-400 text-sm">Semua sudah direview!</p>
                </div>
            @endforelse
        </div>

        {{-- Ulasan Terbaru --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-star text-yellow-400"></i>
                    <h2 class="font-semibold text-gray-800 text-sm">Ulasan Terbaru</h2>
                </div>
                <a href="{{ route('admin.reviews.index') }}"
                    class="text-xs text-indigo-600 hover:underline font-medium">Lihat semua →</a>
            </div>
            @forelse($recentReviews as $review)
                <div class="px-5 py-3 border-b border-gray-50 hover:bg-gray-50 transition last:border-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-start gap-2.5 min-w-0">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5">
                                {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <p class="font-semibold text-gray-800 text-xs">{{ $review->reviewer_name }}</p>
                                    <div class="flex items-center gap-0.5">
                                        @php
                                            $fullStars = floor($review->rating);
                                            $half = $review->rating - $fullStars >= 0.5;
                                        @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $fullStars)
                                                <i class="fa-solid fa-star text-yellow-400" style="font-size:10px"></i>
                                            @elseif($i == $fullStars + 1 && $half)
                                                <i class="fa-solid fa-star-half-stroke text-yellow-400"
                                                    style="font-size:10px"></i>
                                            @else
                                                <i class="fa-regular fa-star text-gray-200" style="font-size:10px"></i>
                                            @endif
                                        @endfor
                                        <span class="text-gray-400 text-xs ml-1">{{ $review->rating }}</span>
                                    </div>
                                </div>
                                <p class="text-xs text-indigo-500 truncate">{{ $review->destination->title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $review->comment }}</p>
                            </div>
                        </div>
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                            onsubmit="return confirm('Hapus ulasan ini?')" class="flex-shrink-0">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition"
                                title="Hapus">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <i class="fa-solid fa-star text-3xl text-gray-200 mb-2 block"></i>
                    <p class="text-gray-400 text-sm">Belum ada ulasan masuk.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Top Rated --}}
    @if ($topRated->count())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-trophy text-yellow-500"></i>
                <h2 class="font-semibold text-gray-800 text-sm">Destinasi Terpopuler — Berdasarkan Rating</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach ($topRated as $i => $dest)
                    <div class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                        <div
                            class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0
                {{ $i === 0 ? 'bg-yellow-400 text-yellow-900' : ($i === 1 ? 'bg-gray-200 text-gray-600' : ($i === 2 ? 'bg-orange-200 text-orange-700' : 'bg-gray-100 text-gray-500')) }}">
                            {{ $i + 1 }}
                        </div>
                        <div class="w-10 h-10 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                            @if ($dest->hero)
                                <img src="{{ asset('storage/' . $dest->hero->path) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $dest->title }}</p>
                            <p class="text-xs text-gray-400">{{ $dest->district }} · {{ $dest->review_count }} ulasan</p>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                            <span class="font-bold text-gray-800 text-sm">{{ number_format($dest->avg_rating, 1) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@endsection
