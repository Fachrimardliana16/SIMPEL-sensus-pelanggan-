<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased font-sans">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMPEL - Sensus Informasi dan Manajemen PELanggan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Outfit', sans-serif; } </style>
</head>
<body class="h-full flex bg-white text-slate-900">

    <!-- Left Side: Login Form -->
    <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:flex-none lg:w-1/2 xl:px-24">
        <div class="mx-auto w-full max-w-sm lg:w-96">
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">
                    Welcome back
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Masuk ke workspace **SIMPEL** Anda untuk melanjutkan.
                </p>
            </div>

            <div class="mt-8">
                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-semibold text-red-800">Authentication failed</h3>
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

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700">Email address</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                class="appearance-none block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm transition duration-200 ease-in-out" 
                                placeholder="nama@instansi.com">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="appearance-none block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm transition duration-200 ease-in-out" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-600 border-slate-300 rounded cursor-pointer">
                            <label for="remember" class="ml-2 block text-sm text-slate-600 cursor-pointer">
                                Remember me for 30 days
                            </label>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition duration-200 ease-in-out transform hover:-translate-y-0.5">
                            Masuk ke Dashboard
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Side: Graphic/Gradient -->
    <div class="hidden lg:block relative w-0 flex-1 bg-blue-900 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-indigo-900 to-slate-900 opacity-90"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
        
        <div class="flex items-center justify-center h-full relative z-10 px-12">
            <div class="text-center">
                <h1 class="text-5xl lg:text-7xl font-extrabold text-white tracking-tight mb-8 drop-shadow-lg">
                    SIMPEL
                </h1>
                <p class="mt-4 text-xl text-blue-100 font-light max-w-lg mx-auto leading-relaxed">
                    Sensus Informasi dan Manajemen PELanggan. Kumpulkan data lapangan dengan akurasi tinggi dan manajemen terpusat.
                </p>
                <div class="mt-12 grid grid-cols-2 gap-8 text-left text-sm text-slate-400 max-w-sm mx-auto">
                    <div>
                        <span class="block text-white font-bold text-2xl mb-1">99.9%</span>
                        Uptime Service Level
                    </div>
                    <div>
                        <span class="block text-white font-bold text-2xl mb-1">PDP 2022</span>
                        Regulatory Compliance
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
