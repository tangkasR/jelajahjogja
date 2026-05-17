@extends('layouts.admin')
@section('title', 'Review Destinasi')
@section('subtitle', 'Tinjau dan moderasi submission destinasi')
@section('content')

    <div class="max-w-4xl">
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-6">
            <a href="{{ route('admin.destinations.index') }}" class="hover:text-indigo-600">Destinasi</a>
            <i class="fa-solid fa-chevron-right text-gray-300"></i>
            <span class="text-gray-600">Review</span>
        </div>

        <div class="grid grid-cols-3 gap-5">
            {{-- Konten utama --}}
            <div class="col-span-2 space-y-5">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    @if ($destination->hero)
                        <img src="{{ asset('storage/' . $destination->hero->path) }}" class="w-full h-56 object-cover">
                    @endif
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            @php $s = $destination->status->value; @endphp
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $s === 'approved' ? 'bg-green-50 text-green-700' : ($s === 'pending' ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700') }}">
                                <i
                                    class="fa-solid {{ $s === 'approved' ? 'fa-circle-check' : ($s === 'pending' ? 'fa-clock' : 'fa-circle-xmark') }}"></i>
                                {{ $destination->status->label() }}
                            </span>
                            <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full text-xs">
                                <i class="fa-solid fa-tag mr-1"></i>{{ $destination->category->name }}
                            </span>
                        </div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">{{ $destination->title }}</h1>
                        <p class="text-sm text-gray-400 mb-4">
                            <i class="fa-solid fa-location-dot mr-1"></i>{{ $destination->district }}, Yogyakarta
                        </p>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $destination->description }}</p>
                    </div>
                </div>

                {{-- Galeri --}}
                @if ($destination->gallery->count())
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-images text-indigo-500"></i> Galeri Foto
                            ({{ $destination->gallery->count() }})
                        </h3>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach ($destination->gallery as $photo)
                                <img src="{{ asset('storage/' . $photo->path) }}"
                                    class="w-full h-20 object-cover rounded-lg border border-gray-100">
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Rejection note --}}
                @if ($destination->rejection_note)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-red-700">Catatan Penolakan</p>
                            <p class="text-sm text-red-600 mt-1">{{ $destination->rejection_note }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-indigo-500"></i> Info Submission
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5"><i class="fa-solid fa-user mr-1"></i> Pengirim</p>
                            <p class="font-medium text-gray-800">{{ $destination->submitter_name }}</p>
                            <p class="text-gray-400 text-xs">{{ $destination->submitter_email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5"><i class="fa-solid fa-location-dot mr-1"></i> Alamat</p>
                            <p class="font-medium text-gray-800">{{ $destination->address }}</p>
                        </div>
                        @if ($destination->lat && $destination->lng)
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5"><i class="fa-solid fa-compass mr-1"></i> Koordinat
                                </p>
                                <p class="font-medium text-gray-800 text-xs">{{ $destination->lat }},
                                    {{ $destination->lng }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5"><i class="fa-solid fa-calendar mr-1"></i> Dikirim</p>
                            <p class="font-medium text-gray-800">{{ $destination->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                @if ($destination->status->value === 'pending')
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-gavel text-indigo-500"></i> Moderasi
                        </h3>
                        <form action="{{ route('admin.destinations.approve', $destination) }}" method="POST"
                            class="mb-3">
                            @csrf @method('PATCH')
                            <button
                                class="w-full flex items-center justify-center gap-2 bg-green-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700 transition">
                                <i class="fa-solid fa-circle-check"></i> Setujui & Tayangkan
                            </button>
                        </form>
                        <form action="{{ route('admin.destinations.reject', $destination) }}" method="POST"
                            class="space-y-2">
                            @csrf @method('PATCH')
                            <textarea name="rejection_note" rows="3" required placeholder="Tulis alasan penolakan untuk pengirim..."
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-400"></textarea>
                            <button
                                class="w-full flex items-center justify-center gap-2 bg-red-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-red-700 transition">
                                <i class="fa-solid fa-circle-xmark"></i> Tolak Submission
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <a href="{{ route('admin.destinations.edit', $destination) }}"
                            class="w-full flex items-center justify-center gap-2 bg-indigo-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
                            <i class="fa-solid fa-pen"></i> Edit Destinasi
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
