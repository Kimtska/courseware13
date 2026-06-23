<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VirtualArm - Student Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{font-family:'Inter',sans-serif;background:linear-gradient(180deg,#f8fafc 0%,#eef2ff 100%);min-height:100vh}
        .nav-link{position:relative;padding:8px 16px;color:rgba(255,255,255,0.65);transition:all .2s;font-size:13px;font-weight:500;border-radius:6px;white-space:nowrap}
        .nav-link:hover{color:#fff;background:rgba(255,255,255,0.1)}
        .nav-link.active{color:#fff;background:rgba(255,255,255,0.15);font-weight:600}
        .nav-link.active::after{content:'';position:absolute;bottom:-14px;left:50%;transform:translateX(-50%);width:20px;height:3px;background:#A78BFA;border-radius:3px}
        .mobile-menu{transform:translateY(-100%);opacity:0;transition:all .3s ease;pointer-events:none}
        .mobile-menu.open{transform:translateY(0);opacity:1;pointer-events:auto}
        .card{background:#fff;border:1px solid #e5e7eb;border-radius:20px;box-shadow:0 18px 45px -20px rgba(30,5,82,.18)}
        .module-card{transition:all .25s ease}
        .module-card:hover{transform:translateY(-3px);border-color:#c4b5fd;box-shadow:0 16px 30px -20px rgba(91,33,182,.35)}
    </style>
</head>
<body>
    @php
        $__studentUser = Auth::guard('student')->user() ?? (Auth::guard('web')->user() && Auth::guard('web')->user()->role === 'student' ? Auth::guard('web')->user() : null);
        if (!isset($name) || $name === null || $name === '') {
            $name = $__studentUser->full_name ?? $__studentUser->name ?? 'Student';
        }
        if (!isset($firstName) || $firstName === null || $firstName === '') {
            $firstName = $__studentUser->first_name ?? (preg_split('/\s+/', trim($name))[0] ?? $name);
        }
        if (!isset($lastName) || $lastName === null) {
            $lastName = $__studentUser->last_name ?? (preg_split('/\s+/', trim($name))[1] ?? '');
        }
        $__studentIdNumber = $__studentUser->student_id_number ?? $__studentUser->email ?? '';
        $__yearLevel = $__studentUser->year_level ?? 'N/A';
        $__section = $__studentUser->section ?? 'Student Portal';
        $selectedStudent = $selectedStudent ?? [
            'full_name' => $name,
            'student_id_number' => $__studentIdNumber,
            'year_level' => $__yearLevel,
            'section' => $__section,
        ];
        $name = $name ?? 'Student';
        $firstName = $firstName ?? $name;
        $lastName = $lastName ?? '';
    @endphp
    <header id="main-header" data-turbo-permanent class="bg-violet-950 text-white sticky top-0 z-50 shadow-lg shadow-violet-950/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3 flex-shrink-0">
                    <img src="{{ asset('images/assets/logo.png') }}" alt="SPC" class="h-9 w-auto">
                    <div class="hidden sm:block"><span class="font-display font-bold text-sm">VirtualArm</span><span class="block text-[8px] text-violet-300 uppercase tracking-widest leading-none">Student Portal</span></div>
                </div>
                <div class="flex-1 flex justify-center">
                    @include('Students.partials.nav-links', ['type' => 'desktop', 'activeNav' => 'dashboard'])
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-2 pl-3 border-l border-violet-800/50">
                        <div class="w-8 h-8 rounded-full bg-violet-700 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($firstName ?: $name, 0, 1)) }}{{ strtoupper(substr($lastName ?: $name, 0, 1)) }}</div>
                        <span class="text-sm font-medium">{{ $name }}</span>
                    </div>
                    <button type="button" class="student-settings-btn p-2 rounded-lg hover:bg-violet-800/50 transition-colors text-violet-300 hover:text-white" title="Settings" aria-label="Settings">
                        <i class="fas fa-cog text-sm"></i>
                    </button>
                    <button onclick="showLogoutAlert()" class="p-2 rounded-lg hover:bg-violet-800/50 transition-colors text-violet-300 hover:text-white" title="Logout" aria-label="Logout">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                    <button id="mobile-toggle" class="md:hidden p-2 rounded-lg hover:bg-violet-800/50 transition-colors">
                        <svg class="inline-block align-middle w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z"/></svg>
                    </button>
                </div>
            </div>
        </div>
        @include('Students.partials.nav-links', ['type' => 'mobile', 'activeNav' => 'dashboard'])
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        @php
            $moduleStates = \App\Models\ModuleAccessControl::whereIn('module_key', ['module-1','module-3','module-4'])->get()->keyBy('module_key');
        @endphp
        <div class="card p-6 sm:p-8 mb-6">
            <div class="flex flex-wrap items-start justify-between gap-5">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.28em] text-violet-500 font-bold">Student dashboard</p>
                    <h1 class="font-display font-bold text-3xl text-gray-900 mt-2">Welcome, {{ $name }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Student ID: {{ $selectedStudent['student_id_number'] ?? '' }} • {{ $selectedStudent['year_level'] ?? 'N/A' }} • {{ $selectedStudent['section'] ?? 'Student Portal' }}</p>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider"><i class="fas fa-circle-check"></i> Verified Enrolled</span>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-bold uppercase tracking-wider"><i class="fas fa-bolt"></i> Ready For Training</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
            <div class="card p-5">
                <p class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Modules Completed</p>
                <p class="text-3xl font-bold text-black mt-2">2 / 4</p>
            </div>
            <div class="card p-5">
                <p class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Overall Score</p>
                <p class="text-3xl font-bold text-black mt-2">78%</p>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="card p-6">
                <h3 class="font-display font-bold text-xl text-gray-900 mb-4">Recent Activity</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="mt-0.5 text-violet-600"><i class="fas fa-book-open"></i></span>
                        <div>
                            <p class="font-medium text-gray-900">Completed Gun Parts Review</p>
                            <p class="text-gray-500 text-xs">Today • Instruction Module</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="mt-0.5 text-violet-600"><i class="fas fa-screwdriver-wrench"></i></span>
                        <div>
                            <p class="font-medium text-gray-900">Assembly Training Started</p>
                            <p class="text-gray-500 text-xs">Yesterday • Hybrid Assembly</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('shared.sweet-alerts.logout', ['logoutLabel' => 'Student — ' . $name, 'logoutSubtext' => 'Student Dashboard', 'logoutDescription' => 'You are about to end your session.', 'redirectUrl' => url('/')])

    <script>
        const mobileToggle = document.getElementById('mobile-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('open');
                mobileToggle.querySelector('i').classList.toggle('fa-bars');
                mobileToggle.querySelector('i').classList.toggle('fa-times');
            });
        }
    </script>
</body>
</html>
