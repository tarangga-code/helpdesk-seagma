<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Cek record token (OTP) di database
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'Kode OTP reset sandi tidak valid atau telah kedaluwarsa.']);
        }

        // Cek kedaluwarsa (15 menit)
        if (\Carbon\Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'Kode OTP reset sandi telah kedaluwarsa. Silakan lakukan proses dari awal.']);
        }

        // Dapatkan user dan perbarui kata sandi
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user) {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));

            // Hapus token yang sudah terpakai
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Bersihkan data session OTP
            session()->forget(['reset_email', 'demo_otp', 'otp_verified']);

            return redirect()->route('login')->with('status', 'Kata sandi Anda berhasil diperbarui! Silakan masuk kembali.');
        }

        return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Gagal mengubah kata sandi. Pengguna tidak ditemukan.']);
    }
}
