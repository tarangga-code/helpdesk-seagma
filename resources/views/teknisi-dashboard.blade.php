<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@400;500;600;700;800&display=swap');

        .font-tegas { font-family: 'Poppins', sans-serif; }
        body { font-family: 'Inter', sans-serif; }

        .bg-grid-pattern {
            background-image: linear-gradient(to right, #f1f5f9 1px, transparent 1px), linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
            background-size: 3rem 3rem;
        }

        .stat-glow::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            box-shadow: 0 30px 60px -20px rgba(0,0,0,0.25);
            opacity: 0;
            transition: opacity .3s ease;
            pointer-events: none;
        }
        .stat-glow:hover::after { opacity: 1; }

        /* Mencegah peta meluap dan mengatur UI rute agar rapi di HP */
        .leaflet-container { z-index: 1 !important; font-family: 'Inter', sans-serif;}
        .leaflet-routing-container {
            background-color: rgba(255, 255, 255, 0.95) !important;
            padding: 10px !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
            max-height: 150px !important;
            overflow-y: auto !important;
            font-size: 11px !important;
            z-index: 1000 !important;
        }
    </style>

    {{-- ================= HEADER BAR ================= --}}
    <div class="relative min-h-[calc(100vh-64px)] sm:min-h-[calc(100vh-80px)] bg-white bg-gradient-to-b from-white via-white to-slate-100/50 pb-16">
        <div class="fixed inset-0 z-0 bg-grid-pattern opacity-50"></div>
        <div class="fixed top-[-5%] right-[-5%] w-[600px] h-[600px] bg-red-50/30 rounded-full blur-[130px] z-0 pointer-events-none"></div>

        <div class="relative z-10">

            {{-- ================= TITLE ================= --}}
            <div class="bg-white/70 backdrop-blur-md border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-red-600 flex items-center justify-center shadow-lg shadow-red-200 shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a1 1 0 01-1-1V9a1 1 0 011-1h1a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1V4z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-red-600 uppercase tracking-[0.2em] block mb-1 font-tegas">
                                Workspace Lapangan
                            </span>
                            <h2 class="text-2xl font-bold text-gray-950 font-tegas leading-none">
                                Ruang Kerja Teknisi
                            </h2>
                            <p class="text-xs text-gray-500 mt-1.5 font-light">
                                Kelola tugas lapangan dan navigasi rute ke lokasi pelanggan.
                            </p>
                        </div>
                    </div>

                    <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 text-red-600 text-xs font-bold rounded-full border border-red-100 self-start lg:self-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-8">

                @if(session('success'))
                <div class="bg-green-50/80 border border-green-100 rounded-2xl p-4 flex items-center gap-3">
                    <div class="bg-emerald-500 p-1.5 rounded-xl text-white shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-900">{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50/80 border border-red-100 rounded-2xl p-4 flex items-center gap-3">
                    <div class="bg-red-500 p-1.5 rounded-xl text-white shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-900">{{ session('error') }}</p>
                </div>
                @endif

                {{-- ================= STAT CARDS ================= --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 relative z-20">

                    <div class="stat-glow relative overflow-hidden bg-gray-950 rounded-[2rem] p-6 shadow-xl flex items-center justify-between">
                        <div class="absolute -right-6 -bottom-8 w-28 h-28 rounded-full bg-white/5"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Tugas Lapangan Aktif
                            </p>
                            <p class="text-4xl font-extrabold text-white font-tegas mt-1">
                                {{ $tiketSaya->count() }}
                            </p>
                            <p class="text-[10px] text-gray-400 mt-2 font-medium">
                                Sedang Anda kerjakan saat ini
                            </p>
                        </div>
                        <div class="relative z-10 w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>
                        </div>
                    </div>

                    <div class="stat-glow relative overflow-hidden bg-red-600 rounded-[2rem] p-6 shadow-xl shadow-red-200/40 flex items-center justify-between">
                        <div class="absolute -right-6 -bottom-8 w-28 h-28 rounded-full bg-white/10"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold text-white/80 uppercase tracking-widest">
                                Perbaikan Selesai
                            </p>
                            <p class="text-4xl font-extrabold text-white font-tegas mt-1">
                                {{ $totalSelesai ?? 0 }}
                            </p>
                            <p class="text-[10px] text-white/80 mt-2 font-medium">
                                Total diselesaikan oleh Anda
                            </p>
                        </div>
                        <div class="relative z-10 w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center text-white shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                </div>
                
                {{-- ================= INDIKATOR STATUS ================= --}}
                <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-gray-200/20 border border-gray-100/50 flex flex-col sm:flex-row items-center justify-between gap-4 relative overflow-hidden">
                    <div class="flex items-center gap-3.5 text-center sm:text-left">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ auth()->user()->status === 'libur' ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }}">
                            <span class="w-3 h-3 rounded-full {{ auth()->user()->status === 'libur' ? 'bg-red-500' : 'bg-emerald-500 animate-pulse' }}"></span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900 font-tegas uppercase tracking-wide">Status Operasional Anda</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                Status Anda saat ini ditentukan oleh Admin: 
                                <span class="font-bold {{ auth()->user()->status === 'libur' ? 'text-red-600' : 'text-emerald-600' }}">
                                    {{ auth()->user()->status === 'libur' ? 'ðŸ”´ Sedang Libur / Off Shift' : 'ðŸŸ¢ Masuk Kerja / Siaga (Standby)' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="text-[10px] text-slate-400 italic text-center sm:text-right bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                        *Perubahan jadwal shift & libur dikendalikan terpusat oleh Admin.
                    </div>
                </div>

                {{-- ================= TUGAS SAYA SAAT INI ================= --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/20 border border-gray-100/50 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-gray-900"></div>
                    <div class="px-6 py-5 border-b border-gray-50 flex items-center bg-gray-50/20">
                        <span class="w-2 h-2 rounded-full bg-red-500 inline-block mr-2 animate-pulse"></span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800 font-tegas">Tugas Saya Saat Ini</h3>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($tiketSaya as $tiket)
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

                                <div class="lg:col-span-5 flex flex-col justify-between space-y-4">
                                    <div>
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Pelanggan</span>
                                        <h4 class="text-base font-bold text-gray-900 uppercase tracking-wide font-tegas">
                                            {{ $tiket->pelanggan->name ?? 'User Tidak Ditemukan' }}
                                        </h4>
                                        <span class="inline-block text-[10px] text-gray-500 font-mono bg-gray-100 px-2.5 py-1 rounded-md mt-1 border border-gray-200/60">
                                            #{{ $tiket->nomor_tiket }}
                                        </span>
                                    </div>

                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex-1 flex flex-col justify-start">
                                        <span class="text-[9px] font-bold text-red-500 uppercase tracking-wider block mb-1">Detail Keluhan</span>
                                        <p class="text-xs font-semibold text-gray-800 mb-1">{{ $tiket->judul }}</p>
                                        <p class="text-[11px] text-gray-500 font-light leading-relaxed">{{ $tiket->deskripsi }}</p>
                                    </div>

                                    <form action="{{ route('teknisi.pengaduan.selesai', $tiket->id) }}" method="POST" enctype="multipart/form-data" class="pt-2 space-y-3" onsubmit="return validasiSelesai('{{ $tiket->id }}')">
                                        @csrf @method('PATCH')
                                        
                                        {{-- NAMA INPUT DISESUAIKAN DENGAN NAMA KOLOM BARU DI DATABASE --}}
                                        <input type="hidden" name="latitude_teknisi" id="lat_teknisi_{{ $tiket->id }}" value="">
                                        <input type="hidden" name="longitude_teknisi" id="lng_teknisi_{{ $tiket->id }}" value="">
                                        
                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-300 border-dashed">
                                            
                                            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                                                <label for="foto_bukti_{{ $tiket->id }}" class="text-[10px] font-bold text-gray-700 uppercase tracking-wider mb-0">
                                                    Ambil Foto Bukti <span class="text-red-500">*</span>
                                                </label>
                                                
                                                <div id="status_gps_{{ $tiket->id }}" class="flex items-center gap-1.5 text-[9px] font-bold text-red-600 bg-red-100 px-2 py-1.5 rounded-md border border-red-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span>
                                                    Mencari Sinyal GPS...
                                                </div>
                                            </div>
                                            
                                            <input type="file" 
                                                id="foto_bukti_{{ $tiket->id }}" 
                                                name="foto_bukti" 
                                                accept="image/*" 
                                                capture="environment"
                                                required
                                                class="block w-full text-xs text-black file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-900 file:text-white hover:file:bg-red-600 transition-all cursor-pointer">
                                                
                                            <p class="text-[9px] text-gray-700 mt-3 font-medium bg-amber-50 p-2.5 rounded-lg border border-amber-200">
                                                âš ï¸ <b>Peringatan:</b> Lokasi GPS Anda saat ini akan dilampirkan otomatis dan dicocokkan dengan koordinat rumah pelanggan oleh sistem.
                                            </p>
                                        </div>

                                        <button type="submit" class="w-full flex justify-center items-center py-3 bg-gray-950 hover:bg-red-600 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg transition-all duration-300">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Selesaikan Tugas Lapangan
                                        </button>
                                    </form>

                                </div>

                                <div class="lg:col-span-7 flex flex-col">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block mb-2">Peta Navigasi & Rute Jalan Otomatis</span>
                                    <div class="relative w-full h-72 lg:h-full min-h-[320px] rounded-2xl overflow-hidden border border-gray-200/80 shadow-inner bg-gray-100">

                                        <div id="map_{{ $tiket->id }}" class="absolute inset-0 w-full h-full z-10"></div>

                                        <div id="gps-error_{{ $tiket->id }}" class="hidden absolute top-3 inset-x-3 z-50 bg-red-500/90 backdrop-blur-sm text-white text-[10px] p-2.5 rounded-xl text-center font-semibold tracking-wide shadow-md">
                                            âš ï¸ GPS Gagal terdeteksi. Pastikan izin lokasi browser Anda aktif.
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-16 text-center">
                            <h4 class="text-sm font-bold text-gray-900 font-tegas">Belum Ada Penugasan Aktif</h4>
                            <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Anda saat ini sedang dalam status <span class="text-emerald-600 font-bold">Siaga (Standby)</span>. Silakan tunggu instruksi kerja (*work order*) yang didistribusikan langsung oleh Admin.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <script>
        // Validasi Form
        window.validasiSelesai = function(tiketId) {
            const latInput = document.getElementById('lat_teknisi_' + tiketId);
            if (!latInput || !latInput.value) {
                alert("Sinyal GPS belum terkunci! Pastikan GPS HP Anda aktif dan tunggu indikator menjadi hijau (GPS Terkunci) sebelum menyelesaikan tugas.");
                return false; 
            }
            return confirm('Apakah Anda yakin foto sudah benar dan Anda saat ini berada di lokasi pelanggan?');
        };

        document.addEventListener('DOMContentLoaded', function () {
            
            const dataTiket = [
                @foreach($tiketSaya as $tiket)
                {
                    id: "{{ $tiket->id }}",
                    lat: parseFloat("{{ $tiket->latitude }}") || -8.1331,
                    lng: parseFloat("{{ $tiket->longitude }}") || 113.2241
                },
                @endforeach
            ];

            if(dataTiket.length === 0) return; 

            const ikonTeknisi = L.divIcon({
                html: `
                    <div class="relative flex items-center justify-center">
                        <span class="animate-ping absolute inline-flex h-8 w-8 rounded-full bg-red-500 opacity-60"></span>
                        <div class="relative w-7 h-7 bg-red-600 rounded-full border-2 border-white flex items-center justify-center shadow-lg">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </div>`,
                className: '', iconSize: [32, 32], iconAnchor: [16, 16]
            });

            const ikonPelanggan = L.divIcon({
                html: `
                    <div class="w-8 h-8 bg-emerald-600 rounded-full border-2 border-white flex items-center justify-center shadow-lg text-white">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>`,
                className: '', iconSize: [32, 32], iconAnchor: [16, 16]
            });

            const maps = {};
            const routingControls = {};

            dataTiket.forEach(tiket => {
                maps[tiket.id] = L.map('map_' + tiket.id).setView([tiket.lat, tiket.lng], 14);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(maps[tiket.id]);
            });

            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    function (position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        const userLatLng = L.latLng(userLat, userLng);

                        dataTiket.forEach(tiket => {
                            const targetLatLng = L.latLng(tiket.lat, tiket.lng);

                            // MENGISI ELEMENT HIDDEN INPUT TEKNISI YANG BARU
                            const inputLat = document.getElementById('lat_teknisi_' + tiket.id);
                            const inputLng = document.getElementById('lng_teknisi_' + tiket.id);
                            const statusGps = document.getElementById('status_gps_' + tiket.id);

                            if(inputLat) inputLat.value = userLat;
                            if(inputLng) inputLng.value = userLng;

                            if(statusGps) {
                                statusGps.className = "flex items-center gap-1.5 text-[9px] font-bold text-emerald-700 bg-emerald-100 px-2 py-1.5 rounded-md border border-emerald-200 transition-all";
                                statusGps.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> GPS Terkunci`;
                            }

                            if (!routingControls[tiket.id]) {
                                routingControls[tiket.id] = L.Routing.control({
                                    waypoints: [userLatLng, targetLatLng],
                                    lineOptions: { styles: [{ color: '#dc2626', weight: 6, opacity: 0.85 }] },
                                    createMarker: function(i, wp) {
                                        return L.marker(wp.latLng, { icon: i === 0 ? ikonTeknisi : ikonPelanggan })
                                                .bindPopup(i === 0 ? "<b>Lokasi Anda</b>" : "<b>Pelanggan</b>");
                                    },
                                    routeWhileDragging: false,
                                    addWaypoints: false,
                                    fitSelectedRoutes: false
                                }).addTo(maps[tiket.id]);
                                
                                maps[tiket.id].setView(userLatLng, 15);
                            } else {
                                routingControls[tiket.id].spliceWaypoints(0, 1, userLatLng);
                                maps[tiket.id].panTo(userLatLng);
                            }
                        });
                    },
                    function (error) {
                        console.warn("Akses GPS ditolak.");
                        dataTiket.forEach(tiket => {
                            document.getElementById('gps-error_' + tiket.id)?.classList.remove('hidden');
                            if(!routingControls[tiket.id]) {
                                L.marker([tiket.lat, tiket.lng], { icon: ikonPelanggan }).addTo(maps[tiket.id]);
                            }
                        });
                    },
                    { enableHighAccuracy: true, maximumAge: 0, timeout: 10000 }
                );
            } else {
                dataTiket.forEach(tiket => {
                    L.marker([tiket.lat, tiket.lng], { icon: ikonPelanggan }).addTo(maps[tiket.id]);
                });
            }
        });
    </script>
</x-app-layout>
