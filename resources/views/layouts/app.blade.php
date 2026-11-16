<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'WebotApp OpticalERP' }} | Optical Store Management ERP</title>
    
    <!-- Google Fonts & Tailwind CDN -->
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
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        },
                        optical: {
                            teal: '#0d9488',
                            cyan: '#06b6d4',
                            navy: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-blue-500 selection:text-white">
    @php
        $currentUser = auth()->user() ?? \App\Models\User::first();
    @endphp

    <div class="flex h-full overflow-hidden">
        
        <!-- Sidebar Navigation (Role-Based Visibility) -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col flex-shrink-0 z-30 shadow-xl transition-all duration-300">
            <!-- Brand Logo -->
            <div class="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800/80 gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-teal-400 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <div>
                    <span class="font-extrabold text-white text-sm tracking-tight">WebotApp VisionCraft</span>
                    <span class="block text-[10px] uppercase tracking-wider text-teal-400 font-bold">OpticalERP</span>
                </div>
            </div>

            <!-- Current User Active Role Badge -->
            <div class="px-5 py-3 bg-slate-800/40 border-b border-slate-800 text-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ $currentUser->isLabTechnician() ? 'bg-amber-400' : ($currentUser->isOptometrist() ? 'bg-sky-400' : ($currentUser->isCashier() ? 'bg-teal-400' : 'bg-emerald-400')) }} animate-pulse"></span>
                    <span class="truncate font-bold text-white capitalize">{{ str_replace('_', ' ', $currentUser->role) }}</span>
                </div>
                <span class="px-1.5 py-0.5 rounded bg-blue-900/60 text-blue-300 text-[10px] font-mono">MUM-01</span>
            </div>

            <!-- Role-Specific Navigation Links -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 text-sm">
                <!-- Dashboard (Tailored for each role) -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/30' : 'hover:bg-slate-800 hover:text-white transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>
                        @if($currentUser->isOptometrist())
                            Clinical Dashboard
                        @elseif($currentUser->isLabTechnician())
                            Lab Dashboard
                        @elseif($currentUser->isCashier())
                            POS Dashboard
                        @elseif($currentUser->isManager())
                            Manager Dashboard
                        @else
                            Executive Dashboard
                        @endif
                    </span>
                </a>

                <!-- POS Section (Admin, Manager, Cashier/Sales) -->
                @if($currentUser->canAccess('pos'))
                    <a href="{{ route('pos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('pos.*') ? 'bg-teal-600 text-white font-semibold shadow-md shadow-teal-600/30' : 'hover:bg-slate-800 hover:text-white transition' }}">
                        <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <span class="font-medium text-white">Optical POS & Sales</span>
                        <span class="ml-auto bg-teal-500/20 text-teal-300 text-[10px] font-bold px-1.5 py-0.5 rounded">Fast</span>
                    </a>
                @endif

                <!-- Clinical Optometry Section (Admin, Manager, Optometrist) -->
                @if($currentUser->canAccess('prescriptions') || $currentUser->isOptometrist())
                    <div class="pt-3 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3">Clinical Optometry</div>

                    <a href="{{ route('prescriptions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('prescriptions.*') ? 'bg-slate-800 text-white font-medium' : 'hover:bg-slate-800/60 hover:text-white transition' }}">
                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Prescriptions (OD/OS)</span>
                    </a>
                @endif

                <!-- Customer CRM (Admin, Manager, Optometrist, Cashier) -->
                @if($currentUser->canAccess('customers'))
                    <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('customers.*') ? 'bg-slate-800 text-white font-medium' : 'hover:bg-slate-800/60 hover:text-white transition' }}">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Customer CRM & Patients</span>
                    </a>
                @endif

                <!-- Orders & Laboratory (Admin, Manager, Lab Tech, Cashier) -->
                @if($currentUser->canAccess('orders') || $currentUser->canAccess('laboratory'))
                    <div class="pt-3 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3">Orders & Processing</div>

                    @if($currentUser->canAccess('orders'))
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('orders.*') ? 'bg-slate-800 text-white font-medium' : 'hover:bg-slate-800/60 hover:text-white transition' }}">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span>Sales Orders</span>
                        </a>
                    @endif

                    @if($currentUser->canAccess('laboratory'))
                        <a href="{{ route('laboratory.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('laboratory.*') ? 'bg-slate-800 text-white font-medium' : 'hover:bg-slate-800/60 hover:text-white transition' }}">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            <span>Lens Lab & 8-Pt QC</span>
                            <span class="ml-auto bg-amber-500/20 text-amber-300 text-[10px] font-bold px-1.5 py-0.5 rounded">Active</span>
                        </a>
                    @endif

                    @if($currentUser->canAccess('repairs'))
                        <a href="{{ route('repairs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('repairs.*') ? 'bg-slate-800 text-white font-medium' : 'hover:bg-slate-800/60 hover:text-white transition' }}">
                            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Repairs & Warranty</span>
                        </a>
                    @endif
                @endif

                <!-- Inventory & Catalog (Admin, Manager) -->
                @if($currentUser->canAccess('inventory'))
                    <div class="pt-3 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3">Inventory & Catalog</div>

                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('products.*') ? 'bg-slate-800 text-white font-medium' : 'hover:bg-slate-800/60 hover:text-white transition' }}">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                        <span>Frames & Lenses Master</span>
                    </a>

                    <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('inventory.*') ? 'bg-slate-800 text-white font-medium' : 'hover:bg-slate-800/60 hover:text-white transition' }}">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span>Stock & Barcodes</span>
                    </a>
                @endif

                <!-- Finance & Invoicing (Admin, Manager, Cashier) -->
                @if($currentUser->canAccess('invoices'))
                    <div class="pt-3 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3">Finance & Compliance</div>

                    <a href="{{ route('invoices.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('invoices.*') ? 'bg-slate-800 text-white font-medium' : 'hover:bg-slate-800/60 hover:text-white transition' }}">
                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                        <span>GST Invoices & Cash</span>
                    </a>
                @endif
            </nav>

            <!-- User Status Footer -->
            <div class="p-3 bg-slate-950/80 border-t border-slate-800/80 flex items-center justify-between">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/30 flex items-center justify-center font-bold text-xs flex-shrink-0">
                        {{ substr($currentUser->name ?? 'User', 0, 1) }}
                    </div>
                    <div class="truncate text-xs">
                        <div class="font-semibold text-white truncate">{{ $currentUser->name }}</div>
                        <div class="text-[11px] text-teal-400 font-medium capitalize">{{ str_replace('_', ' ', $currentUser->role) }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">
            <!-- Top Header with 1-Click Role Switcher -->
            <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-8 z-20 flex-shrink-0">
                <div class="flex items-center gap-4">
                    <h1 class="text-xl font-black text-slate-900 tracking-tight">{{ $header ?? 'Dashboard' }}</h1>
                    @if(isset($badge))
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $badgeClass ?? 'bg-blue-100 text-blue-700' }}">{{ $badge }}</span>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <!-- 1-Click Role Switcher Dropdown (Allows testing each role's view instantly) -->
                    <div class="flex items-center gap-2 p-1 bg-slate-100 border border-slate-200 rounded-xl">
                        <span class="text-[11px] font-bold text-slate-500 uppercase px-2 hidden md:inline">Role View:</span>
                        
                        <a href="{{ route('auth.switch', 'admin') }}" 
                           class="px-2.5 py-1 text-xs font-bold rounded-lg transition {{ $currentUser->isAdmin() ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200' }}">
                            👑 Admin
                        </a>

                        <a href="{{ route('auth.switch', 'manager') }}" 
                           class="px-2.5 py-1 text-xs font-bold rounded-lg transition {{ $currentUser->isManager() ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200' }}">
                            🏬 Manager
                        </a>

                        <a href="{{ route('auth.switch', 'optometrist') }}" 
                           class="px-2.5 py-1 text-xs font-bold rounded-lg transition {{ $currentUser->isOptometrist() ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200' }}">
                            🩺 Optometrist
                        </a>

                        <a href="{{ route('auth.switch', 'lab_technician') }}" 
                           class="px-2.5 py-1 text-xs font-bold rounded-lg transition {{ $currentUser->isLabTechnician() ? 'bg-amber-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200' }}">
                            🔬 Lab Tech
                        </a>

                        <a href="{{ route('auth.switch', 'cashier') }}" 
                           class="px-2.5 py-1 text-xs font-bold rounded-lg transition {{ $currentUser->isCashier() ? 'bg-teal-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200' }}">
                            💳 Cashier
                        </a>
                    </div>

                    <!-- Direct POS Action Button if permitted -->
                    @if($currentUser->canAccess('pos'))
                        <a href="{{ route('pos.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-sm shadow-teal-600/30 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span>New Sale / POS</span>
                        </a>
                    @endif
                </div>
            </header>

            <!-- Dynamic Body Content -->
            <main class="flex-1 overflow-y-auto p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-sm">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-sm">
                        <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
