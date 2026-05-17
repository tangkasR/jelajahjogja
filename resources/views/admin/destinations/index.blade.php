@extends('layouts.admin')
@section('title', 'Kelola Destinasi')
@section('subtitle', 'Daftar semua destinasi wisata')
@section('content')

    <div class="flex flex-wrap items-center gap-2 mb-5">
        @foreach (['' => 'Semua', 'pending' => 'Pending', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $val => $label)
            <a href="{{ route('admin.destinations.index', $val ? ['status' => $val] : []) }}"
                class="flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold border transition
       {{ request('status') === $val || (request('status') === null && $val === '')
           ? 'bg-indigo-600 text-white border-indigo-600'
           : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300' }}">
                @if ($val === 'pending')
                    <i class="fa-solid fa-clock"></i>
                @elseif($val === 'approved')
                    <i class="fa-solid fa-circle-check"></i>
                @elseif($val === 'rejected')
                    <i class="fa-solid fa-circle-xmark"></i>
                @else
                    <i class="fa-solid fa-list"></i>
                @endif
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm w-full overflow-x-auto">
        <table class="text-sm w-full min-w-max">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Destinasi
                    </th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Kategori
                    </th>
                    <th
                        class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide hidden md:table-cell">
                        Pengirim</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($destinations as $dest)
                    <tr class="hover:bg-gray-50 transition">
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
                                    <p class="font-semibold text-gray-800">{{ $dest->title }}</p>
                                    <p class="text-xs text-gray-400 flex items-center gap-1">
                                        <i class="fa-solid fa-location-dot"></i> {{ $dest->district }}
                                        @if ($dest->is_featured)
                                            <span class="ml-1 bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded text-xs">⭐
                                                Unggulan</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $dest->category->name }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs hidden md:table-cell">
                            <p>{{ $dest->submitter_name }}</p>
                            <p class="text-gray-400">{{ $dest->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @php $s = $dest->status->value; @endphp
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $s === 'approved' ? 'bg-green-50 text-green-700' : ($s === 'pending' ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700') }}">
                                <i
                                    class="fa-solid {{ $s === 'approved' ? 'fa-circle-check' : ($s === 'pending' ? 'fa-clock' : 'fa-circle-xmark') }} text-xs"></i>
                                {{ $dest->status->label() }}
                            </span>
                        </td>
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
                                    @csrf @method('DELETE')
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
        <div class="px-4 py-3 border-t border-gray-100">{{ $destinations->links() }}</div>
    </div>
@endsection
