@extends('layouts.admin')

@section('title', 'Kelola Destinasi')
@section('subtitle', 'Daftar semua destinasi wisata')

@section('content')

    {{-- FILTER STATUS --}}
    <div class="flex flex-wrap items-center gap-2 mb-5">

        @foreach ([
            '' => 'Semua',
            'pending' => 'Pending',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ] as $val => $label)
            @php
                $isActive = request('status') === $val || (request('status') === null && $val === '');

                $btnClass = $isActive
                    ? 'bg-indigo-600 text-white border-indigo-600'
                    : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300';

                $icon = match ($val) {
                    'pending' => 'fa-clock',
                    'approved' => 'fa-circle-check',
                    'rejected' => 'fa-circle-xmark',
                    default => 'fa-list',
                };
            @endphp

            <a href="{{ route('admin.destinations.index', $val ? ['status' => $val] : []) }}"
                class="flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold border transition {{ $btnClass }}">

                <i class="fa-solid {{ $icon }}"></i>

                {{ $label }}

            </a>
        @endforeach

    </div>

    {{-- SEARCH --}}
    @php
        $advance = request()->get('advance_field', 'all');
    @endphp

    <form method="GET" class="flex flex-col lg:flex-row lg:items-center gap-3 mb-5">

        <div
            class="flex-1 flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 focus-within:ring-2 focus-within:ring-indigo-500">

            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>

            <input type="text" name="q" value="{{ request('q') }}"
                placeholder="Cari destinasi (nama, kabupaten/kota, atau pengirim)..."
                class="flex-1 py-2.5 text-sm focus:outline-none bg-transparent">

        </div>

        <div class="flex items-center gap-2">

            <select name="advance_field"
                class="bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

                <option value="all" @selected($advance == 'all')>
                    Semua field
                </option>

                <option value="title" @selected($advance == 'title')>
                    Destinasi
                </option>

                <option value="district" @selected($advance == 'district')>
                    Wilayah
                </option>

                <option value="submitter_name" @selected($advance == 'submitter_name')>
                    Pengirim
                </option>

            </select>

            <button type="submit"
                class="flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">

                <i class="fa-solid fa-search"></i>
                Cari

            </button>

            @if (request('q'))
                <a href="{{ route('admin.destinations.index') }}"
                    class="flex items-center gap-2 border border-gray-200 px-4 py-2.5 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition">

                    <i class="fa-solid fa-xmark"></i>
                    Reset

                </a>
            @endif

        </div>

        @if (request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif

    </form>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm w-full overflow-x-auto">

        <table class="text-sm w-full min-w-max">

            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">

                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">
                        Destinasi
                    </th>

                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">
                        Kategori
                    </th>

                    <th
                        class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide hidden md:table-cell">
                        Pengirim
                    </th>

                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">
                        Status
                    </th>

                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">
                        Aksi
                    </th>

                </tr>
            </thead>

            <tbody class="divide-y divide-gray-50">

                @forelse($destinations as $dest)
                    @php
                        $statusValue = $dest->status?->value ?? 'pending';

                        $statusClass = match ($statusValue) {
                            'approved' => 'bg-green-50 text-green-700',
                            'pending' => 'bg-yellow-50 text-yellow-700',
                            default => 'bg-red-50 text-red-700',
                        };

                        $statusIcon = match ($statusValue) {
                            'approved' => 'fa-circle-check',
                            'pending' => 'fa-clock',
                            default => 'fa-circle-xmark',
                        };
                    @endphp

                    <tr class="hover:bg-gray-50 transition">

                        {{-- DESTINASI --}}
                        <td class="px-4 py-3">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">

                                    @if ($dest->hero)
                                        <img src="{{ asset('storage/' . $dest->hero->path) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <i class="fa-solid fa-image text-xs"></i>
                                        </div>
                                    @endif

                                </div>

                                <div>

                                    <p class="font-semibold text-gray-800">
                                        {{ $dest->title }}
                                    </p>

                                    <p class="text-xs text-gray-400 flex items-center gap-1">

                                        <i class="fa-solid fa-location-dot"></i>

                                        {{ $dest->district }}

                                        @if ($dest->is_featured)
                                            <span class="ml-1 bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded text-xs">

                                                ⭐ Unggulan

                                            </span>
                                        @endif

                                    </p>

                                </div>

                            </div>

                        </td>

                        {{-- KATEGORI --}}
                        <td class="px-4 py-3">

                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">
                                {{ $dest->category->name }}
                            </span>

                        </td>

                        {{-- PENGIRIM --}}
                        <td class="px-4 py-3 text-gray-500 text-xs hidden md:table-cell">

                            <p>{{ $dest->submitter_name }}</p>

                            <p class="text-gray-400">
                                {{ $dest->created_at->format('d M Y') }}
                            </p>

                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 py-3">

                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">

                                <i class="fa-solid {{ $statusIcon }} text-xs"></i>

                                {{ method_exists($dest->status, 'label') ? $dest->status->label() : ucfirst($statusValue) }}

                            </span>

                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-3">

                            <div class="flex items-center justify-end gap-1.5">

                                <a href="{{ route('admin.destinations.show', $dest) }}"
                                    class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                    title="Review">

                                    <i class="fa-solid fa-eye text-sm"></i>

                                </a>

                                <a href="{{ route('admin.destinations.edit', $dest) }}"
                                    class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                    title="Edit">

                                    <i class="fa-solid fa-pen text-sm"></i>

                                </a>

                                <form action="{{ route('admin.destinations.destroy', $dest) }}" method="POST"
                                    onsubmit="return confirm('Hapus destinasi ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus">

                                        <i class="fa-solid fa-trash text-sm"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center py-16 text-gray-400">

                            <i class="fa-solid fa-map text-3xl mb-3 block text-gray-200"></i>

                            Tidak ada destinasi ditemukan.

                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>

        <div class="px-4 py-3 border-t border-gray-100">
            {{ $destinations->links() }}
        </div>

    </div>

@endsection
