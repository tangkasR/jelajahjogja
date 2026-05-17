@extends('layouts.public')
@section('title', 'Submit Destinasi — Jelajah Jogja')
@section('content')

    <div class="bg-gradient-to-br from-blue-700 to-indigo-800 py-12 md:py-16 px-4">
        <div class="max-w-2xl mx-auto text-center text-white">
            <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i class="fa-solid fa-location-dot text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold mb-2">Ajukan Destinasi Wisata</h1>
            <p class="text-blue-200 text-sm md:text-base">Punya rekomendasi tempat wisata di Jogja? Kirim ke kami dan tunggu
                review dari tim.</p>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-8 md:py-12">

        @if (session('success'))
            <div
                class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-6">
                <i class="fa-solid fa-circle-check text-green-500 mt-0.5 text-lg"></i>
                <div>
                    <p class="font-semibold">Berhasil dikirim!</p>
                    <p class="text-sm mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-blue-500"></i>
                <h2 class="font-semibold text-gray-800 text-sm">Formulir Pengajuan</h2>
            </div>

            <form action="{{ route('submit.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-1.5">
                            <i class="fa-solid fa-user mr-1 text-gray-400"></i> Nama Kamu <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" name="submitter_name" value="{{ old('submitter_name') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('submitter_name') border-red-400 @enderror">
                        @error('submitter_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-1.5">
                            <i class="fa-solid fa-envelope mr-1 text-gray-400"></i> Email <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="email" name="submitter_email" value="{{ old('submitter_email') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('submitter_email') border-red-400 @enderror">
                        @error('submitter_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-1.5">
                        <i class="fa-solid fa-heading mr-1 text-gray-400"></i> Nama Destinasi <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-400 @enderror">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-1.5">
                            <i class="fa-solid fa-tag mr-1 text-gray-400"></i> Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        <label class="text-sm font-semibold text-gray-700 block mb-1.5">
                            <i class="fa-solid fa-map mr-1 text-gray-400"></i> Kecamatan / Wilayah <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" name="district" value="{{ old('district') }}" placeholder="contoh: Bantul"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('district')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-1.5">
                        <i class="fa-solid fa-location-dot mr-1 text-gray-400"></i> Alamat Lengkap <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="text" name="address" value="{{ old('address') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-1.5">
                        <i class="fa-solid fa-align-left mr-1 text-gray-400"></i> Deskripsi <span
                            class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="4"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-400 @enderror"
                        placeholder="Ceritakan keunikan tempat ini minimal 50 karakter...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-1.5">
                            <i class="fa-solid fa-compass mr-1 text-gray-400"></i> Latitude <span
                                class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="text" name="lat" value="{{ old('lat') }}" placeholder="-7.797068"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-1.5">
                            <i class="fa-solid fa-compass mr-1 text-gray-400"></i> Longitude <span
                                class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="text" name="lng" value="{{ old('lng') }}" placeholder="110.370529"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-5 space-y-4">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-images text-blue-500"></i>
                        <h3 class="font-semibold text-gray-800 text-sm">Upload Foto</h3>
                    </div>

                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Foto Hero / Utama <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-3">Foto utama yang tampil di kartu dan halaman detail (maks.
                            3MB)</p>
                        <input type="file" name="hero" accept="image/*" required id="hero-input"
                            class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <div id="hero-preview" class="mt-3 hidden">
                            <img id="hero-preview-img" class="h-28 rounded-lg object-cover">
                        </div>
                        @error('hero')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Foto Galeri <span class="text-gray-400 font-normal">(opsional, maks. 8 foto)</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-3">Foto-foto tambahan yang tampil di galeri destinasi (maks.
                            2MB/foto)</p>
                        <input type="file" name="gallery[]" accept="image/*" multiple id="gallery-input"
                            class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                        <div id="gallery-preview" class="mt-3 flex flex-wrap gap-2 hidden"></div>
                        @error('gallery')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('gallery.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition text-sm shadow-sm">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Destinasi untuk Direview
                </button>
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
                        img.className = 'h-16 w-16 object-cover rounded-lg border border-gray-200';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });
        </script>
    @endpush
@endsection
