<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller {

    // ===== REGISTER =====
    public function register(Request $request) {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $otp     = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires = now()->addMinutes(10);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'user',
            'is_active'      => false,
            'otp_code'       => $otp,
            'otp_expires_at' => $expires,
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        } catch (\Exception $e) {
            $user->delete();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email. Coba lagi.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Cek email kamu untuk kode OTP.',
            'step'    => 'otp',
            'email'   => $user->email,
        ]);
    }

    // ===== VERIFY OTP =====
    public function verifyOtp(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email tidak ditemukan.'], 404);
        }

        if ($user->is_active) {
            return response()->json(['success' => false, 'message' => 'Akun sudah aktif, silakan login.'], 400);
        }

        if ($user->otp_code !== $request->otp) {
            return response()->json(['success' => false, 'message' => 'Kode OTP salah.'], 422);
        }

        if (now()->isAfter($user->otp_expires_at)) {
            return response()->json(['success' => false, 'message' => 'Kode OTP sudah kadaluarsa. Daftar ulang.'], 422);
        }

        $user->update([
            'is_active'      => true,
            'otp_code'       => null,
            'otp_expires_at' => null,
        ]);

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil diverifikasi! Selamat datang di JelajahJogja 🎉',
        ]);
    }

    // ===== RESEND OTP =====
    public function resendOtp(Request $request) {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->where('is_active', false)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email tidak ditemukan atau sudah aktif.'], 404);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal kirim email.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Kode OTP baru telah dikirim ke email kamu.']);
    }

    // ===== LOGIN =====
    public function login(Request $request) {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Email atau password salah.'], 422);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun belum diverifikasi. Cek email kamu untuk kode OTP.',
                'step'    => 'otp',
                'email'   => $user->email,
            ], 403);
        }

        if ($user->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Gunakan halaman login admin.'], 403);
        }

        Auth::login($user, $request->boolean('remember'));

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil! Selamat datang kembali 👋',
        ]);
    }

    // ===== LOGOUT =====
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
