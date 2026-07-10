<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeknisiController extends Controller
{
    // Menampilkan halaman dashboard teknisi
    public function index()
    {
        $teknisiId = Auth::id();

        // 1. HANYA mengambil tiket yang SEDANG DIKERJAKAN (yang sudah ditunjuk oleh Admin)
        $tiketSaya = Tiket::with('pelanggan')
            ->where('teknisi_id', $teknisiId)
            ->where('status', 'diproses')
            ->orderBy('updated_at', 'desc')
            ->get();

        // 2. Menghitung total tiket yang SUDAH SELESAI dikerjakan oleh teknisi ini (untuk statistik dashboard)
        $totalSelesai = Tiket::where('teknisi_id', $teknisiId)
            ->where('status', 'selesai')
            ->count();

        return view('teknisi-dashboard', compact('tiketSaya', 'totalSelesai'));
    }

    // Fungsi utama untuk menyelesaikan tugas wajib dengan foto bukti lapangan & koordinat geotagging
    public function selesaikanTugas(Request $request, $id)
    {
        // Menambahkan validasi wajib kirim koordinat dari perangkat teknisi
        $request->validate([
            // Validasi file maksimal 5MB aman untuk jepretan kamera HP resolusi tinggi
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg|max:5120', 
            'latitude_teknisi' => 'required|numeric',
            'longitude_teknisi' => 'required|numeric',
        ], [
            // Pesan kustom jika teknisi belum mengaktifkan GPS / izin lokasi di HP-nya
            'latitude_teknisi.required' => 'Gagal membaca lokasi. Pastikan GPS HP aktif dan izin lokasi browser diizinkan.',
            'longitude_teknisi.required' => 'Gagal membaca lokasi. Pastikan GPS HP aktif dan izin lokasi browser diizinkan.',
        ]);

        // Cari tiket dan pastikan tiket ini memang milik teknisi yang sedang login
        $tiket = Tiket::where('id', $id)
                      ->where('teknisi_id', Auth::id())
                      ->firstOrFail();

        // Proses upload foto bukti ke dalam storage
        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/bukti_selesai', $filename);
            
            $tiket->foto_bukti = $filename;
        }

        // Menyimpan koordinat lokasi terkini tempat teknisi menekan tombol selesai
        $tiket->latitude_teknisi = $request->latitude_teknisi;
        $tiket->longitude_teknisi = $request->longitude_teknisi;

        // Ubah status tiket menjadi selesai
        $tiket->status = 'selesai';
        $tiket->save();

        // Kirim notifikasi ke pelanggan
        \App\Models\Notification::create([
            'user_id' => $tiket->pelanggan_id,
            'title' => 'Tiket Selesai Dikerjakan',
            'message' => 'Status tiket Anda "' . $tiket->judul . '" telah diubah menjadi: Selesai.',
        ]);

        return redirect()->route('teknisi.dashboard')->with('success', 'Luar biasa! Tugas lapangan telah diselesaikan dan bukti berhasil diunggah.');
    }

    public function toggleStatus()
    {
        $user = auth()->user();
        $user->status = ($user->status === 'libur') ? 'aktif' : 'libur';
        $user->save();

        return back()->with('success', 'Status ketersediaan Anda berhasil diperbarui.');
    }
}