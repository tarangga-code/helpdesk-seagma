<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi OTP - Portal Helpdesk PT Seagma</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .font-tegas {
            font-family: 'Poppins', sans-serif;
        }
        .bg-grid-pattern {
            background-image: linear-gradient(to right, #f1f5f9 1px, transparent 1px), linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
            background-size: 3rem 3rem;
        }
    </style>
</head>
<body class="min-h-[100dvh] w-full overflow-y-auto antialiased text-gray-800 bg-gray-50 flex items-center justify-center selection:bg-red-600 selection:text-white relative p-4 py-8">

    <div class="absolute inset-0 z-[-1] bg-grid-pattern">
        <div class="absolute inset-0 bg-gradient-to-b from-white/40 via-white/80 to-white"></div>
        
        <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-bl from-red-200/50 to-orange-100/30 blur-[90px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-tr from-gray-200/60 to-blue-50/40 blur-[90px] pointer-events-none"></div>
    </div>

    <div class="w-full max-w-[420px] relative z-10">
        
        <div class="bg-white/70 backdrop-blur-2xl p-8 sm:p-10 shadow-2xl shadow-gray-200/50 rounded-[2rem] border border-white/80 ring-1 ring-gray-100/50 flex flex-col">
            
            <div class="text-center mb-6">
                <img class="mx-auto h-12 w-auto object-contain drop-shadow-sm mb-4" src="{{ asset('images/logo.png') }}" alt="Logo PT Seagma">
                <h2 class="text-xl font-bold tracking-tight text-gray-900 font-tegas leading-tight">
                    Verifikasi Kode OTP
                </h2>
                <p class="mt-2 text-xs text-gray-500 leading-relaxed font-light">
                    Masukkan 6 digit kode OTP yang telah dikirimkan ke Gmail atau nomor HP terdaftar Anda.
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200/60 rounded-xl p-3.5 shadow-sm text-xs font-medium text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Errors -->
            @if ($errors->any())
                <div class="mb-5 bg-red-50/80 border border-red-100 rounded-xl p-3.5 flex items-start gap-2.5 shadow-sm text-xs font-medium text-red-600">
                    <svg class="h-4 w-4 text-red-600 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        {{ $errors->first('otp') }}
                    </div>
                </div>
            @endif

            <form class="space-y-5" method="POST" action="{{ route('password.otp.verify') }}">
                @csrf

                <!-- OTP Input -->
                <div>
                    <label for="otp" class="sr-only">Kode OTP</label>
                    <input id="otp" name="otp" type="text" maxlength="6" required placeholder="Masukkan 6 Digit OTP" autofocus autocomplete="off"
                        class="block w-full text-center tracking-widest font-mono font-bold text-lg rounded-xl border-0 py-3.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 placeholder:font-sans placeholder:tracking-normal focus:ring-2 focus:ring-inset focus:ring-red-600 focus:bg-white bg-gray-50/50 transition-all duration-300 outline-none">
                </div>

                <div class="pt-2 flex flex-col gap-3">
                    <button type="submit" class="flex w-full justify-center rounded-xl bg-gray-900 px-4 py-3.5 text-sm font-bold tracking-wide text-white shadow-md hover:bg-red-600 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        VERIFIKASI OTP
                    </button>

                    <a href="{{ route('password.request') }}" class="w-full text-center py-2.5 text-xs font-semibold text-gray-500 hover:text-gray-800 hover:underline transition-all duration-200">
                        Kirim Ulang Kode OTP
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="absolute bottom-6 w-full text-center z-10">
        <p class="text-[10px] font-medium tracking-widest text-gray-400 uppercase">
            &copy; {{ date('Y') }} Sistem Informasi Manajemen
        </p>
    </div>

</body>
</html>
