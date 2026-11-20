<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In | thrumble.opticals</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-full flex flex-col font-sans text-slate-800 antialiased bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">

    <main class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <!-- Brand Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-teal-400 text-white shadow-xl shadow-blue-500/30 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight">thrumble.opticals</h1>
                <p class="text-xs uppercase tracking-widest text-teal-400 font-bold mt-1">Optical Store Management ERP</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-2xl border border-slate-200/80 p-8">
                <div class="mb-6">
                    <h2 class="text-lg font-extrabold text-slate-900">Sign in to your account</h2>
                    <p class="text-xs text-slate-500 mt-1">Enter your credentials or pick a role below to auto-fill.</p>
                </div>

                @if(session('status'))
                    <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Email Address</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="admin@thrumble.opticals"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••••••"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        >
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span>Remember me</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        id="login-submit-btn"
                        class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-600/30 transition cursor-pointer"
                    >
                        Sign In
                    </button>
                </form>

                <!-- Quick-Fill Role Links -->
                <div class="mt-6 pt-5 border-t border-slate-100">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-3">Quick Fill Credentials</p>
                    <div class="grid grid-cols-1 gap-2">
                        <a href="#"
                           onclick="fillCredentials('admin@thrumble.opticals', 'Password@123'); return false;"
                           class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 text-xs transition group">
                            <span class="font-bold text-slate-800 group-hover:text-blue-700">👑 Super Admin</span>
                            <span class="font-mono text-[11px] text-slate-500 group-hover:text-blue-600">admin@thrumble.opticals</span>
                        </a>

                        <a href="#"
                           onclick="fillCredentials('manager@thrumble.opticals', 'Password@123'); return false;"
                           class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 text-xs transition group">
                            <span class="font-bold text-slate-800 group-hover:text-indigo-700">🏬 Store Manager</span>
                            <span class="font-mono text-[11px] text-slate-500 group-hover:text-indigo-600">manager@thrumble.opticals</span>
                        </a>

                        <a href="#"
                           onclick="fillCredentials('optom@thrumble.opticals', 'Password@123'); return false;"
                           class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-50 hover:bg-sky-50 border border-slate-200 hover:border-sky-300 text-xs transition group">
                            <span class="font-bold text-slate-800 group-hover:text-sky-700">🩺 Optometrist</span>
                            <span class="font-mono text-[11px] text-slate-500 group-hover:text-sky-600">optom@thrumble.opticals</span>
                        </a>

                        <a href="#"
                           onclick="fillCredentials('lab@thrumble.opticals', 'Password@123'); return false;"
                           class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-50 hover:bg-amber-50 border border-slate-200 hover:border-amber-300 text-xs transition group">
                            <span class="font-bold text-slate-800 group-hover:text-amber-700">🔬 Lab Technician</span>
                            <span class="font-mono text-[11px] text-slate-500 group-hover:text-amber-600">lab@thrumble.opticals</span>
                        </a>

                        <a href="#"
                           onclick="fillCredentials('cashier@thrumble.opticals', 'Password@123'); return false;"
                           class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-50 hover:bg-teal-50 border border-slate-200 hover:border-teal-300 text-xs transition group">
                            <span class="font-bold text-slate-800 group-hover:text-teal-700">💳 POS Cashier</span>
                            <span class="font-mono text-[11px] text-slate-500 group-hover:text-teal-600">cashier@thrumble.opticals</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Credits -->
    <footer class="px-6 py-4 text-center text-xs text-slate-400">
        <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1">
            <span class="font-semibold text-slate-300">&copy; {{ date('Y') }} thrumble.opticals</span>
            <span class="text-slate-600">|</span>
            <span>Developed by <a href="https://www.attrixtech.com" target="_blank" rel="noopener noreferrer" class="font-semibold text-blue-400 hover:text-blue-300 hover:underline">Attrix Technologies</a></span>
            <span class="text-slate-600">|</span>
            <span>Marketing by <a href="https://www.infusionn.in" target="_blank" rel="noopener noreferrer" class="font-semibold text-teal-400 hover:text-teal-300 hover:underline">Infusionn Pvt. Ltd.</a></span>
        </div>
    </footer>

    <script>
        function fillCredentials(email, password) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            emailInput.value = email;
            passwordInput.value = password;
            document.getElementById('login-submit-btn').focus();
        }
    </script>
</body>
</html>
