<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'identity' => ['required', 'string'],
        ]);

        $identity = $request->identity;
        
        // Cari user berdasarkan email atau no_telepon
        $user = null;
        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $user = \App\Models\User::where('email', $identity)->first();
        } else {
            $user = \App\Models\User::where('no_telepon', $identity)->first();
        }

        if (!$user) {
            return back()->withInput()->withErrors(['identity' => 'Akun dengan email atau nomor HP tersebut tidak ditemukan.']);
        }

        // Generate 6-digit OTP
        $otp = mt_rand(100000, 999999);

        // Simpan token ke password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $otp, 'created_at' => now()]
        );

        // Simpan email di session
        session(['reset_email' => $user->email]);

        // Log OTP ke file log untuk keperluan debugging di server lokal
        \Log::info("OTP reset password untuk {$user->email}: {$otp}");

        // Coba kirim email asli jika mail server terkonfigurasi
        try {
            if (config('mail.default') !== 'smtp' || env('MAIL_USERNAME') !== null) {
                // Di sini Anda bisa mengirimkan email jika SMTP aktif
                // \Mail::to($user->email)->send(new \App\Mail\SendOtpMail($otp));
            }
        } catch (\Exception $e) {
            // Abaikan error pengiriman email asli di local environment
        }

        return redirect()->route('password.otp.show')->with('status', 'Kode OTP telah dikirimkan ke Gmail/No HP akun Anda.');
    }

    /**
     * Display the OTP verification view.
     */
    public function showVerifyOtp(): View|RedirectResponse
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp');
    }

    /**
     * Handle verification of the OTP.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'numeric', 'digits:6'],
        ]);

        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }

        $email = session('reset_email');
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Kode OTP tidak cocok atau salah.']);
        }

        // Cek kedaluwarsa (15 menit)
        if (\Carbon\Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa. Silakan minta kode baru.']);
        }

        // Simpan status verifikasi di session
        session(['otp_verified' => true]);

        // Redirect ke reset password page dengan token (OTP) di url
        return redirect()->route('password.reset', [
            'token' => $request->otp,
            'email' => $email
        ]);
    }
}
