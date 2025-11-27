<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | {{ config('app.name', 'USB App') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        slate: {
                            950: '#020617',
                        }
                    }
                }
            }
        };
    </script>
</head>
<body class="min-h-screen bg-linear-to-br from-slate-950 via-slate-900 to-slate-800 text-gray-100">
    @php($appName = config('app.name', 'USB App'))

    <div class="relative min-h-screen flex flex-col items-center justify-center px-4 py-10">
        <div class="absolute inset-0 opacity-40">
            <div class="bg-[radial-gradient(circle_at_top,rgba(37,99,235,0.35),transparent_60%)] w-full h-full"></div>
        </div>

        <div class="relative z-10 w-full max-w-5xl">
            <div class="rounded-3xl border border-white/10 bg-white text-gray-800 shadow-2xl shadow-primary-900/20 overflow-hidden flex flex-col lg:flex-row">
                <div class="w-full px-8 py-10 sm:px-12 lg:w-1/2">
                    <div class="mb-8">
                        <p class="text-sm font-medium text-primary-600">Masuk ke akun Anda</p>
                        <h2 class="text-3xl font-semibold text-slate-900">Dashboard Operasional</h2>
                        <p class="mt-2 text-sm text-slate-500">Gunakan email internal dan kata sandi yang sudah terdaftar.</p>
                    </div>

                    @if (session('status'))
                        <div class="mb-6 rounded-2xl border border-primary-100 bg-primary-50 px-4 py-3 text-sm text-primary-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="space-y-6" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-slate-600">Email</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8" />
                                    </svg>
                                </span>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" autofocus @class([
                                    'w-full rounded-2xl border bg-white/90 px-12 py-3 text-sm font-medium text-slate-900 shadow-sm outline-none transition ring-2 focus:border-primary-200 focus:ring-primary-200',
                                    'border-red-500 ring-red-100' => $errors->has('email'),
                                    'border-slate-200 ring-transparent' => ! $errors->has('email'),
                                ])>
                            </div>
                            @error('email')
                                <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-slate-600">Kata Sandi</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 21a7 7 0 0114 0" />
                                    </svg>
                                </span>
                                <input id="password" name="password" type="password" required autocomplete="current-password" @class([
                                    'w-full rounded-2xl border bg-white/90 px-12 py-3 text-sm font-medium text-slate-900 shadow-sm outline-none transition ring-2 focus:border-primary-200 focus:ring-primary-200',
                                    'border-red-500 ring-red-100' => $errors->has('password'),
                                    'border-slate-200 ring-transparent' => ! $errors->has('password'),
                                ])>
                            </div>
                            @error('password')
                                <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <label class="inline-flex cursor-pointer items-center gap-2 text-slate-500">
                                <input type="checkbox" id="remember" name="remember" class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500" {{ old('remember') ? 'checked' : '' }}>
                                <span>Ingatkan saya</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="font-semibold text-primary-600 hover:text-primary-700">Lupa kata sandi?</a>
                            @endif
                        </div>

                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-primary-600 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-primary-600/30 transition hover:bg-primary-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0l4-4m-4 4l4 4m2-8h6a6 6 0 016 6 6 6 0 01-6 6h-6" />
                            </svg>
                            Masuk Sekarang
                        </button>
                    </form>

                    <p class="mt-8 text-center text-xs text-slate-400">
                        Hubungi administrator apabila akun Anda belum diaktifkan.
                    </p>
                </div>
                <div class="relative w-full lg:w-1/2">
                    <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1200&q=80" alt="Dashboard illustration" class="h-full w-full object-cover object-center">
                    <div class="absolute inset-0 bg-linear-to-t from-slate-950/70 via-slate-900/30 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                        <p class="text-sm uppercase tracking-[0.4em] text-white/70">Terhubung</p>
                        <h3 class="text-2xl font-semibold">Operasional Lebih Cepat</h3>
                        <p class="mt-2 text-sm text-white/80">Monitoring produksi dan finansial kini bisa diakses kapan saja dari perangkat Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
