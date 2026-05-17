@extends('layouts.admin')
@section('title', 'Kelola Ulasan')
@section('subtitle', 'Semua ulasan dari pengunjung destinasi')
@section('content')

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-3 md:gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm text-center">
            <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-400 mt-1 flex items-center justify-center gap-1">
                <i class="fa-solid fa-star text-yellow-400"></i> Total Ulasan
            </p>
        </div>
        <div class="bg-white rounded-2xl p-4 md:p-5 border border-yellow-100 shadow-sm text-center">
            <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['avg'] }}</p>
            <p class="text-xs text-gray-400 mt-1 flex items-center justify-center gap-1">
                <i class="fa-solid fa-star-half-stroke text-yellow-400"></i> Rata-rata Rating
            </p>
        </div>
        <div class="bg-white rounded-2xl p-4 md:p-5 border border-blue-100 shadow-sm text-center">
            <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $stats['this_month'] }}</p>
            <p class="text-xs text-gray-400 mt-1 flex items-center justify-center gap-1">
                <i class="fa-solid fa-calendar text-blue-400"></i> Bulan Ini
            </p>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" class="flex gap-2 mb-5">
        <div
            class="flex-1 flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 focus-within:ring-2 focus-within:ring-indigo-500">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau isi ulasan..."
                class="flex-1 py-2.5 text-sm focus:outline-none">
        </div>
        <button
            class="flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
            <i class="fa-solid fa-search"></i> Cari
        </button>
        @if (request('q'))
            <a href="{{ route('admin.reviews.index') }}"
                class="flex items-center gap-2 border border-gray-200 px-4 py-2.5 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition">
                <i class="fa-solid fa-xmark"></i> Reset
            </a>
        @endif
    </form>

    {{-- Daftar ulasan --}}
    <div class="space-y-3">
        @forelse($reviews as $review)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 md:p-5 hover:border-gray-200 transition">
                <div class="flex flex-col md:flex-row md:items-start gap-4">
                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        {{-- Avatar --}}
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            {{-- Header --}}
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <p class="font-bold text-gray-800">{{ $review->reviewer_name }}</p>
                                {{-- Bintang dengan half star --}}
                                <div class="flex items-center gap-0.5">
                                    @php
                                        $full = floor($review->rating);
                                        $half = $review->rating - $full >= 0.5;
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $full)
                                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                                        @elseif($i == $full + 1 && $half)
                                            <i class="fa-solid fa-star-half-stroke text-yellow-400 text-sm"></i>
                                        @else
                                            <i class="fa-regular fa-star text-gray-200 text-sm"></i>
                                        @endif
                                    @endfor
                                    <span class="text-xs text-gray-500 ml-1 font-semibold">{{ $review->rating }}</span>
                                </div>
                            </div>
                            {{-- Destinasi --}}
                            <p class="text-xs text-indigo-500 mb-2 flex items-center gap-1">
                                <i class="fa-solid fa-map-pin text-xs"></i>
                                <a href="{{ route('admin.destinations.show', $review->destination) }}"
                                    class="hover:underline">
                                    {{ $review->destination->title }}
                                </a>
                                <span class="text-gray-300 mx-1">·</span>
                                <span class="text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                            </p>
                            {{-- Komentar --}}
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                        </div>
                    </div>

                    {{-- Hapus --}}
                    <div class="flex-shrink-0 self-start">
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus ulasan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="flex items-center gap-1.5 bg-red-50 text-red-600 border border-red-100 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-red-100 transition">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
                <i class="fa-solid fa-star text-5xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-500 font-medium">Belum ada ulasan masuk.</p>
                <p class="text-gray-400 text-sm mt-1">Ulasan dari pengunjung akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $reviews->links() }}</div>
@endsection
