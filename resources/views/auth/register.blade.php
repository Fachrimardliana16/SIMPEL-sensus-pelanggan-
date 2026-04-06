<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased font-sans">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Surveyor - SIMPEL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Outfit', sans-serif; } </style>
</head>
<body class="h-full flex bg-white text-slate-900">

    <!-- Left Side: Premium Minimalist Graphic Background -->
    <div class="hidden lg:block relative w-0 flex-1 bg-gradient-to-tr from-blue-900 via-blue-600 to-white overflow-hidden">
        
        <div class="flex items-end justify-start h-full relative z-10 pb-24 px-12 xl:px-20">
            <div class="text-left w-full">
                <div class="mb-8 w-16 h-1 bg-white rounded-full opacity-80"></div>
                <h1 class="text-5xl lg:text-7xl font-light text-white tracking-tight mb-4 drop-shadow-md">
                    <span class="font-extrabold">DAFTAR</span>
                </h1>
                <p class="text-xl lg:text-2xl text-blue-50 font-medium max-w-lg leading-relaxed drop-shadow-sm">
                    Bergabung sebagai tim Surveyor SIMPEL
                </p>
                
                <div class="mt-16 flex items-center space-x-4 border-t border-white/20 pt-8 w-full max-w-sm">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                    </span>
                    <span class="text-sm font-medium tracking-wider text-blue-200 uppercase">
                        Sistem Informasi Terpadu
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side: Register Form -->
    <div class="flex-1 flex flex-col justify-center bg-slate-50 lg:bg-white px-6 sm:px-10 md:px-16 lg:flex-none lg:w-1/2 lg:px-24 xl:px-32 overflow-y-auto">
        <div class="mx-auto w-full max-w-[440px] lg:max-w-sm lg:w-96 bg-white lg:bg-transparent p-10 sm:p-14 lg:p-0 rounded-[3rem] lg:rounded-none shadow-2xl shadow-slate-200/60 lg:shadow-none">
            <div class="mb-12 text-center lg:text-left">
                <h2 class="text-3xl md:text-4xl font-black tracking-tight text-slate-900 leading-tight">
                    Registrasi Baru
                </h2>
            </div>

            <div class="mt-8">
                <form action="{{ route('register') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 mb-6">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-red-800">Registrasi Gagal</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700 ml-1">Nama Lengkap</label>
                        <div class="mt-2">
                            <input id="name" name="name" type="text" required value="{{ old('name') }}"
                                class="appearance-none block w-full px-5 py-4 border border-slate-200 rounded-2xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-600 sm:text-sm transition-all duration-300" 
                                placeholder="Masukkan nama lengkap">
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 ml-1">Email</label>
                        <div class="mt-2 text-left">
                            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                class="appearance-none block w-full px-5 py-4 border @error('email') border-red-500 @else border-slate-200 @enderror rounded-2xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-600 sm:text-sm transition-all duration-300" 
                                placeholder="nama@instansi.com">
                            @error('email')
                                <p class="mt-2 text-xs font-bold text-red-600 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-bold text-slate-700 ml-1">Password</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" required 
                                class="appearance-none block w-full px-5 py-4 border border-slate-200 rounded-2xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-600 sm:text-sm transition-all duration-300" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Password Confirmation Field -->
                    <div class="space-y-1">
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 ml-1">Konfirmasi Password</label>
                        <div class="mt-2">
                            <input id="password_confirmation" name="password_confirmation" type="password" required 
                                class="appearance-none block w-full px-5 py-4 border border-slate-200 rounded-2xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-600 sm:text-sm transition-all duration-300" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-xl text-base font-black text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-600 transition-all duration-300 transform hover:scale-[1.02] active:scale-95">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <p class="text-sm text-center text-slate-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 transition-colors">
                            Masuk ke Sistem
                        </a>
                    </p>
                </div>
            </div>

            <p class="mt-10 text-center text-sm text-slate-400">
                &copy; {{ date('Y') }} SIMPEL (Sensus Informasi & Menajemen Pelanggan). All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
