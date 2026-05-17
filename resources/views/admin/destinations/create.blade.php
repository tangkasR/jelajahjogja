@extends('layouts.admin')
@section('title', 'Tambah Destinasi')
@section('subtitle', 'Tambah destinasi wisata baru langsung dari admin')
@section('content')

    <div class="max-w-3xl">
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-6">
            <a href="{{ route('admin.destinations.index') }}" class="hover:text-indigo-600">Destinasi</a>
            <i class="fa-solid fa-chevron-right text-gray-300"></i>
            <span class="text-gray-600">Tambah Baru</span>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-map-pin text-indigo-500"></i>
                <h2 class="font-semibold text-gray-800">Informasi Destinasi</h2>
            </div>

            <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            <i class="fa-solid fa-heading mr-1 text-gray-400"></i> Nama Destinasi <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            placeholder="Contoh: Pantai Parangtritis"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title') border-red-400 @enderror">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1"><i
                                    class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                <i class="fa-solid fa-tag mr-1 text-gray-400"></i> Kategori <span
                                    class="text-red-500">*</span>
                            </label>
                            <select name="category_id"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Pilih kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                <i class="fa-solid fa-map mr-1 text-gray-400"></i> Wilayah <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="text" name="district" value="{{ old('district') }}" placeholder="Contoh: Bantul"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @error('district')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            <i class="fa-solid fa-location-dot mr-1 text-gray-400"></i> Alamat Lengkap <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" name="address" value="{{ old('address') }}"
                            placeholder="Jl. Parangtritis Km. 27, Bantul"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            <i class="fa-solid fa-align-left mr-1 text-gray-400"></i> Deskripsi <span
                                class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="5" placeholder="Ceritakan keunikan dan daya tarik destinasi ini..."
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                <i class="fa-solid fa-compass mr-1 text-gray-400"></i> Latitude
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <input type="text" name="lat" value="{{ old('lat') }}" placeholder="-7.797068"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                <i class="fa-solid fa-compass mr-1 text-gray-400"></i> Longitude
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <input type="text" name="lng" value="{{ old('lng') }}" placeholder="110.370529"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-yellow-50 border border-yellow-200 rounded-xl">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1"
                            @checked(old('is_featured')) class="w-4 h-4 accent-indigo-600">
                        <label for="is_featured" class="text-sm text-yellow-800 font-medium cursor-pointer">
                            <i class="fa-solid fa-star mr-1 text-yellow-500"></i> Tampilkan sebagai Destinasi Unggulan
                        </label>
                    </div>
                </div>

                <div class="px-6 pb-6 border-t border-gray-100 pt-5 space-y-5">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fa-solid fa-images text-indigo-500"></i>
                        <h3 class="font-semibold text-gray-800 text-sm">Upload Foto</h3>
                    </div>

                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Foto Hero <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-3">Foto utama yang tampil di halaman detail dan kartu destinasi.
                            Maks. 3MB.</p>
                        <input type="file" name="hero" accept="image/*" required id="hero-input"
                            class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                        <div id="hero-preview" class="mt-3 hidden">
                            <img id="hero-preview-img" class="h-32 rounded-lg object-cover border border-gray-200">
                        </div>
                        @error('hero')
                            <p class="text-red-500 text-xs mt-1"><i
                                    class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Foto Galeri <span class="text-gray-400 font-normal">(opsional, maks. 8 foto)</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-3">Foto-foto tambahan yang tampil di galeri halaman detail.
                            Maks. 2MB per foto.</p>
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
                        class="flex items-center gap-2 bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Destinasi
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
