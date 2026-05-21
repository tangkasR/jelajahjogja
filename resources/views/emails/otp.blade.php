<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP JelajahJogja</title>
</head>

<body style="margin:0;padding:0;background:#f8fafc;font-family:'Segoe UI',sans-serif;">
    <div
        style="max-width:480px;margin:40px auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#1e40af,#4338ca);padding:32px;text-align:center;">
            <div
                style="width:48px;height:48px;background:rgba(255,255,255,0.15);border-radius:12px;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;">
                <span style="font-size:24px;">🗺️</span>
            </div>
            <h1 style="color:white;margin:0;font-size:20px;font-weight:700;">JelajahJogja</h1>
            <p style="color:rgba(255,255,255,0.7);margin:4px 0 0;font-size:13px;">Verifikasi Akun Kamu</p>
        </div>

        {{-- Body --}}
        <div style="padding:32px;">
            <p style="color:#374151;font-size:15px;margin:0 0 8px;">Halo, <strong>{{ $userName }}</strong>!</p>
            <p style="color:#6b7280;font-size:14px;margin:0 0 24px;line-height:1.6;">
                Terima kasih sudah mendaftar di JelajahJogja. Gunakan kode OTP berikut untuk verifikasi akunmu:
            </p>

            {{-- OTP Box --}}
            <div
                style="background:#f0f4ff;border:2px dashed #6366f1;border-radius:12px;padding:24px;text-align:center;margin-bottom:24px;">
                <p style="color:#6b7280;font-size:12px;margin:0 0 8px;text-transform:uppercase;letter-spacing:.08em;">
                    Kode OTP</p>
                <p
                    style="color:#1e40af;font-size:40px;font-weight:800;letter-spacing:12px;margin:0;font-family:monospace;">
                    {{ $otpCode }}</p>
                <p style="color:#9ca3af;font-size:12px;margin:12px 0 0;">Berlaku selama <strong>10 menit</strong></p>
            </div>

            <p style="color:#9ca3af;font-size:12px;margin:0;line-height:1.6;">
                Jika kamu tidak mendaftar di JelajahJogja, abaikan email ini.
                Jangan bagikan kode OTP ini kepada siapapun.
            </p>
        </div>

        {{-- Footer --}}
        <div style="background:#f8fafc;padding:16px 32px;border-top:1px solid #f1f5f9;text-align:center;">
            <p style="color:#9ca3af;font-size:11px;margin:0;">
                &copy; {{ date('Y') }} JelajahJogja · Dibuat dengan ❤️ untuk Yogyakarta
            </p>
        </div>
    </div>
</body>

</html>
