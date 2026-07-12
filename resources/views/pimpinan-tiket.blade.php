<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap');

        .font-tegas {
            font-family: 'Poppins', sans-serif;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
        }

        .bg-grid-pattern {
            background-image: linear-gradient(to right, #f1f5f9 1px, transparent 1px), linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
            background-size: 3rem 3rem;
        }
    </style>

    <div class="relative min-h-[calc(100vh-64px)] sm:min-h-[calc(100vh-80px)] bg-gray-50/40 pb-20">
        <div class="fixed inset-0 z-0 bg-grid-pattern opacity-50"></div>
        <div
            class="fixed top-0 right-0 w-[600px] h-[600px] bg-red-50/20 rounded-full blur-[140px] z-0 pointer-events-none">
        </div>

        <div class="relative z-10">
            <div class="bg-white/60 backdrop-blur-md border-b border-gray-100">
                <div
                    class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-950 font-tegas leading-none">
                            Manajemen Pengaduan Global
                        </h2>
                        <p class="text-xs text-black mt-1.5 font-light">Pantau status, lakukan delegasi ulang regu
                            teknisi, dan kendalikan seluruh berkas laporan sistem.</p>
                    </div>

                    <a href="{{ route('pimpinan.dashboard') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-gray-900 text-xs font-semibold uppercase tracking-widest rounded-full shadow-sm hover:shadow-md transition-all duration-300 w-full sm:w-auto">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Dashboard Utama
                    </a>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

                @if (session('success'))
                    <div
                        class="mb-8 bg-green-50/80 border border-green-100 rounded-2xl p-4 flex items-center gap-3 animate-fade-in-down">
                        <div class="text-green-500 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-900">{{ session('success') }}</span>
                    </div>
                @endif

                <div
                    class="bg-white rounded-[2rem] shadow-2xl shadow-gray-200/30 border border-gray-100/60 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/20 flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800 font-tegas">Daftar  
                            Tiket</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-max">
                            <thead>
                                <tr class="border-b-2 border-gray-100 bg-white">
                                    <th class="py-5 px-6 font-extrabold text-gray-400 uppercase tracking-wider text-[11px]">Rincian Keluhan &amp; Waktu</th>
                                    <th class="py-5 px-6 font-extrabold text-gray-400 uppercase tracking-wider text-[11px]">Pelanggan</th>
                                    <th class="py-5 px-6 font-extrabold text-gray-400 uppercase tracking-wider text-[11px]">Status Berkas</th>
                                    <th class="py-5 px-6 font-extrabold text-gray-400 uppercase tracking-wider text-[11px] text-center">Teknisi Yang Bertugas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                                @foreach ($tikets as $tiket)
                                    @php
                                        $pelanggan = $users->where('id', $tiket->pelanggan_id)->first();
                                        $namaPelanggan = $pelanggan ? $pelanggan->name : 'Akun Terhapus';

                                        $teknisiAssign = $users->where('id', $tiket->teknisi_id)->first();
                                        $namaTeknisi = $teknisiAssign ? $teknisiAssign->name : 'Belum Ada';
                                    @endphp
                                    <tr class="border-b border-gray-50 hover:bg-[#f8f9fa] transition-colors group">
                                        <td class="py-4 px-6">
                                            <div class="text-[10px] font-mono font-semibold text-gray-400 mb-1">
                                                {{ \Carbon\Carbon::parse($tiket->created_at)->format('d F Y, H:i') }} WIB
                                            </div>
                                            <div class="font-bold text-gray-900 uppercase tracking-wide text-xs font-tegas">
                                                {{ $tiket->judul }}
                                            </div>
                                            <div class="text-xs text-gray-500 font-light mt-1 max-w-md line-clamp-2">
                                                {{ $tiket->deskripsi }}
                                            </div>
                                        </td>

                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white text-sm font-bold uppercase shrink-0">
                                                    {{ Str::substr($namaPelanggan, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-gray-900 uppercase tracking-wide font-tegas leading-none">
                                                        {{ $namaPelanggan }}
                                                    </p>
                                                    @if($pelanggan)
                                                    <p class="text-[10px] text-gray-500 font-mono mt-1 leading-none">
                                                        {{ $pelanggan->email }}
                                                    </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <td class="py-4 px-6">
                                            @php
                                                $normalizedStatus = ucwords(strtolower(trim($tiket->status)));
                                            @endphp
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider shadow-sm
                                                @if (in_array($normalizedStatus, ['Open', 'Pending', 'Menunggu', 'Menunggu Verifikasi'])) bg-red-50 text-red-600 border border-red-100
                                                @elseif(in_array($normalizedStatus, ['In Progress', 'Proses', 'Diproses', 'Dalam Proses'])) bg-slate-100 text-slate-600 border border-slate-200
                                                @elseif(in_array($normalizedStatus, ['Resolved', 'Selesai', 'Selesai Diperbaiki'])) bg-emerald-50 text-emerald-600 border border-emerald-100
                                                @else bg-gray-50 text-gray-600 border border-gray-200 @endif">
                                                
                                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 animate-pulse
                                                    @if (in_array($normalizedStatus, ['Open', 'Pending', 'Menunggu', 'Menunggu Verifikasi'])) bg-red-500
                                                    @elseif(in_array($normalizedStatus, ['In Progress', 'Proses', 'Diproses', 'Dalam Proses'])) bg-slate-500
                                                    @elseif(in_array($normalizedStatus, ['Resolved', 'Selesai', 'Selesai Diperbaiki'])) bg-emerald-500
                                                    @else bg-gray-400 @endif"></span>
                                                {{ $tiket->status }}
                                            </span>
                                        </td>

                                        <td class="py-4 px-6 text-center">
                                            <div class="max-w-xs mx-auto">
                                                <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-xl text-xs font-bold text-gray-700 bg-gray-50 border border-gray-200 shadow-sm min-h-[32px] min-w-[120px]">
                                                    {{ $namaTeknisi }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

