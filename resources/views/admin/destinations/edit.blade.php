@extends('layouts.admin')
@section('title', 'Edit Destinasi')
@section('subtitle', 'Ubah informasi destinasi wisata')
@section('content')

    <div class="max-w-3xl">
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-6">
            <a href="{{ route('admin.destinations.index') }}" class="hover:text-indigo-600">Destinasi</a>
            <i class="fa-solid fa-chevron-right text-gray-300"></i>
            <span class="text-gray-600 truncate max-w-xs">{{ $destination->title }}</span>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-indigo-500"></i>
                <h2 class="font-semibold text-gray-800">Edit: {{ $destination->title }}</h2>
            </div>

            <form action="{{ route('admin.destinations.update', $destination) }}" method="POST"
                enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            <i class="fa-solid fa-heading mr-1 text-gray-400"></i> Nama Destinasi <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $destination->title) }}" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                <i class="fa-solid fa-tag mr-1 text-gray-400"></i> Kategori
                            </label>
                            <select name="category_id"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected($destination->category_id == $cat->id)>{{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                <i class="fa-solid fa-map mr-1 text-gray-400"></i> Wilayah
                            </label>
                            <input type="text" name="district" value="{{ old('district', $destination->district) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            <i class="fa-solid fa-location-dot mr-1 text-gray-400"></i> Alamat
                        </label>
                        <input type="text" name="address" value="{{ old('address', $destination->address) }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            <i class="fa-solid fa-align-left mr-1 text-gray-400"></i> Deskripsi
                        </label>
                        <textarea name="description" rows="5"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $destination->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                <i class="fa-solid fa-compass mr-1 text-gray-400"></i> Latitude
                            </label>
                            <input type="text" name="lat" value="{{ old('lat', $destination->lat) }}"
                                placeholder="-7.797068"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                <i class="fa-solid fa-compass mr-1 text-gray-400"></i> Longitude
                            </label>
                            <input type="text" name="lng" value="{{ old('lng', $destination->lng) }}"
                                placeholder="110.370529"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-yellow-50 border border-yellow-200 rounded-xl">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1"
                            @checked(old('is_featured', $destination->is_featured)) class="w-4 h-4 accent-indigo-600">
                        <label for="is_featured" class="text-sm text-yellow-800 font-medium cursor-pointer">
                            <i class="fa-solid fa-star mr-1 text-yellow-500"></i> Tampilkan sebagai Destinasi Unggulan
                        </label>
                    </div>
                </div>

                <div class="px-6 pb-6 border-t border-gray-100 pt-5 space-y-5">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fa-solid fa-images text-indigo-500"></i>
                        <h3 class="font-semibold text-gray-800 text-sm">Foto Destinasi</h3>
                    </div>

                    {{-- Hero photo --}}
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Ganti Foto Hero <span class="text-gray-400 font-normal">(biarkan kosong jika tidak ingin
                                mengganti)</span>
                        </label>
                        @if ($destination->hero)
                            <div class="flex items-center gap-3 mb-3">
                                <img src="{{ asset('storage/' . $destination->hero->path) }}"
                                    class="h-20 w-28 object-cover rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-400">Foto hero saat ini</p>
                            </div>
                        @endif
                        <input type="file" name="hero" accept="image/*" id="hero-input"
                            class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <div id="hero-preview" class="mt-3 hidden">
                            <img id="hero-preview-img" class="h-24 rounded-lg object-cover border border-gray-200">
                            <p class="text-xs text-gray-400 mt-1">Preview foto baru</p>
                        </div>
                        @error('hero')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gallery photos --}}
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Tambah Foto Galeri <span class="text-gray-400 font-normal">(akan ditambahkan ke galeri yang
                                ada)</span>
                        </label>
                        @if ($destination->gallery->count())
                            <div class="mb-3">
                                <p class="text-xs text-gray-400 mb-2">Galeri saat ini ({{ $destination->gallery->count() }}
                                    foto):</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($destination->gallery as $photo)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $photo->path) }}"
                                                class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <input type="file" name="gallery[]" accept="image/*" multiple id="gallery-input"
                            class="w-full text-sm text-gray-500
                                    file:mr-3 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-gray-50 file:text-gray-700
                                    hover:file:bg-gray-100
                                    @error('gallery') border border-red-400 rounded-lg @enderror
                                    @error('gallery.*') border border-red-400 rounded-lg @enderror">
                        <div id="gallery-preview" class="mt-3 flex flex-wrap gap-2 hidden"></div>
                        @error('gallery')
                            <p class="text-red-500 text-xs mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                        @error('gallery.*')
                            <p class="text-red-500 text-xs mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <a href="{{ route('admin.destinations.index') }}"
                        class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('hero-input').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (ev) => {
                    document.getElementById('hero-preview-img').src = ev.target.result;
                    document.getElementById('hero-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            });
            document.getElementById('gallery-input').addEventListener('change', function(e) {
                const preview = document.getElementById('gallery-preview');
                preview.innerHTML = '';
                preview.classList.remove('hidden');
                Array.from(e.target.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        const img = document.createElement('img');
                        img.src = ev.target.result;
                        img.className = 'h-20 w-20 object-cover rounded-lg border border-gray-200';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });
        </script>
    @endpush
@endsection
