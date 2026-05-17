@extends('layouts.admin')
@section('title', 'Kelola Kategori')
@section('subtitle', 'Atur kategori destinasi wisata')
@section('content')

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-5">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-plus text-indigo-500"></i>
                <h2 class="font-semibold text-gray-800 text-sm">Tambah Kategori Baru</h2>
            </div>
            <div class="p-5">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="flex gap-3">
                    @csrf
                    <input type="text" name="name" placeholder="Nama kategori baru..." required
                        class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button
                        class="flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-tags text-indigo-500"></i>
                <h2 class="font-semibold text-gray-800 text-sm">Daftar Kategori</h2>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Nama
                        </th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Slug
                        </th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">
                            Destinasi</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($categories as $cat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 font-semibold text-gray-800">
                                <i class="fa-solid fa-tag mr-2 text-indigo-300"></i>{{ $cat->name }}
                            </td>
                            <td class="px-5 py-3.5 text-gray-400 font-mono text-xs">{{ $cat->slug }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    {{ $cat->destinations_count }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                    onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus">
                                        <i class="fa-solid fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
