@extends('layouts.admin')
@section('title', 'Ubah Password')
@section('subtitle', 'Ganti password akun admin kamu')
@section('content')

    <div class="max-w-md">



        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-key text-indigo-500"></i>
                <h2 class="font-semibold text-gray-800 text-sm">Ubah Password</h2>
            </div>

            <form action="{{ route('admin.password.update') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fa-solid fa-lock mr-1.5 text-gray-400"></i>
                        Password Lama <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="current_password" id="current_password"
                            placeholder="Masukkan password lama"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 transition
                                  @error('current_password') border-red-400 @enderror">
                        <button type="button" onclick="togglePassword('current_password', 'eye1')"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i id="eye1" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fa-solid fa-lock-open mr-1.5 text-gray-400"></i>
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="new_password" id="new_password" placeholder="Minimal 8 karakter"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 transition
                                  @error('new_password') border-red-400 @enderror">
                        <button type="button" onclick="togglePassword('new_password', 'eye2')"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i id="eye2" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('new_password')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror

                    {{-- Password strength indicator --}}
                    <div class="mt-2">
                        <div class="flex gap-1 mb-1">
                            <div id="str1" class="h-1 flex-1 rounded-full bg-gray-100 transition-all duration-300">
                            </div>
                            <div id="str2" class="h-1 flex-1 rounded-full bg-gray-100 transition-all duration-300">
                            </div>
                            <div id="str3" class="h-1 flex-1 rounded-full bg-gray-100 transition-all duration-300">
                            </div>
                            <div id="str4" class="h-1 flex-1 rounded-full bg-gray-100 transition-all duration-300">
                            </div>
                        </div>
                        <p id="strengthText" class="text-xs text-gray-400"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fa-solid fa-shield-check mr-1.5 text-gray-400"></i>
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="new_password_confirmation" id="confirm_password"
                            placeholder="Ulangi password baru"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <button type="button" onclick="togglePassword('confirm_password', 'eye3')"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i id="eye3" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    {{-- Match indicator --}}
                    <p id="matchText" class="text-xs mt-1 hidden"></p>
                </div>

                {{-- Tips --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <p class="text-xs font-semibold text-blue-700 mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-lightbulb text-blue-500"></i> Tips Password Aman
                    </p>
                    <ul class="space-y-1 text-xs text-blue-600">
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-xs text-blue-400"></i>
                            Minimal 8 karakter</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-xs text-blue-400"></i>
                            Kombinasi huruf besar & kecil</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-xs text-blue-400"></i>
                            Tambahkan angka atau simbol</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-xs text-blue-400"></i> Jangan
                            gunakan password yang sama di tempat lain</li>
                    </ul>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="flex items-center gap-2 bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Password Baru
                    </button>
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Toggle show/hide password
            function togglePassword(inputId, eyeId) {
                const input = document.getElementById(inputId);
                const eye = document.getElementById(eyeId);
                if (input.type === 'password') {
                    input.type = 'text';
                    eye.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    eye.classList.replace('fa-eye-slash', 'fa-eye');
                }
            }

            // Password strength
            document.getElementById('new_password').addEventListener('input', function() {
                const val = this.value;
                const bars = [document.getElementById('str1'), document.getElementById('str2'),
                    document.getElementById('str3'), document.getElementById('str4')
                ];
                const textEl = document.getElementById('strengthText');

                let score = 0;
                if (val.length >= 8) score++;
                if (/[A-Z]/.test(val)) score++;
                if (/[0-9]/.test(val)) score++;
                if (/[^A-Za-z0-9]/.test(val)) score++;

                const colors = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
                const labels = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat'];
                const texts = ['text-red-500', 'text-orange-500', 'text-yellow-600', 'text-green-600'];

                bars.forEach((bar, i) => {
                    bar.className = 'h-1 flex-1 rounded-full transition-all duration-300 ' +
                        (i < score ? colors[score - 1] : 'bg-gray-100');
                });

                if (val.length === 0) {
                    textEl.textContent = '';
                } else {
                    textEl.textContent = labels[score - 1] || 'Sangat Lemah';
                    textEl.className = 'text-xs ' + (texts[score - 1] || 'text-red-500');
                }

                checkMatch();
            });

            // Confirm password match
            document.getElementById('confirm_password').addEventListener('input', checkMatch);

            function checkMatch() {
                const pw = document.getElementById('new_password').value;
                const confirm = document.getElementById('confirm_password').value;
                const el = document.getElementById('matchText');

                if (!confirm) {
                    el.classList.add('hidden');
                    return;
                }
                el.classList.remove('hidden');

                if (pw === confirm) {
                    el.textContent = '✓ Password cocok';
                    el.className = 'text-xs mt-1 text-green-600';
                } else {
                    el.textContent = '✗ Password tidak cocok';
                    el.className = 'text-xs mt-1 text-red-500';
                }
            }
        </script>
    @endpush

@endsection
