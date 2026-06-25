<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IOT-Based Marksmanship - Reports</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/Marksmanship innovatech.png') }}">
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
    @include('shared.back-button-prevention')
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
        $name = $name ?? 'Student';
        $firstName = $firstName ?? $name;
        $lastName = $lastName ?? '';
    @endphp
    <header class="bg-violet-950 text-white sticky top-0 z-50 shadow-lg shadow-violet-950/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3 flex-shrink-0">
                    <img src="{{ asset('images/assets/Marksmanship innovatech.png') }}" alt="SPC" class="h-9 w-auto">
                    <div class="hidden sm:block"><span class="font-display font-bold text-sm">IOT-Based Marksmanship</span><span class="block text-[8px] text-violet-300 uppercase tracking-widest leading-none">Student Portal</span></div>
                </div>
                <div class="flex-1 flex justify-center">
                    @include('Students.partials.nav-links', ['type' => 'desktop', 'activeNav' => 'reports'])
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
        @include('Students.partials.nav-links', ['type' => 'mobile', 'activeNav' => 'reports'])
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="card p-6 sm:p-8 mb-6">
            <h1 class="font-display font-bold text-2xl">Assessment Reports</h1>
            <p class="text-sm text-gray-500 mt-1">Summaries of your assessments from course modules.</p>
        </div>

        @php
            $moduleScoresTotal = 0;
            $moduleScoresMax = 0;
            $marksmanshipTotal = 0;
            $marksmanshipMax = 0;
            foreach ($scores as $s) {
                if (($s->module_key ?? '') === 'final') {
                    $marksmanshipTotal += $s->score;
                    $marksmanshipMax += $s->max_score;
                } else {
                    $moduleScoresTotal += $s->score;
                    $moduleScoresMax += $s->max_score;
                }
            }
            $modulePercent = $moduleScoresMax > 0 ? round(($moduleScoresTotal / $moduleScoresMax) * 100, 1) : 0;
            $marksmanshipPercent = $marksmanshipMax > 0 ? round(($marksmanshipTotal / $marksmanshipMax) * 100, 1) : 0;
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="card p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                        <i class="fas fa-book-open text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">Module Score</p>
                        <p class="text-xl font-display font-bold text-gray-900 leading-tight">{{ $moduleScoresTotal }} / {{ $moduleScoresMax }}</p>
                        <p class="text-xs text-gray-500">{{ $modulePercent }}% overall</p>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <i class="fas fa-bullseye text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">Marksmanship Assessment</p>
                        <p class="text-xl font-display font-bold text-gray-900 leading-tight">{{ $marksmanshipTotal }} / {{ $marksmanshipMax }}</p>
                        <p class="text-xs text-gray-500">{{ $marksmanshipPercent }}% accuracy</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
                @php $byModule = $scores->groupBy('module_key'); @endphp

                @if ($byModule->isEmpty())
                    <div class="p-4 bg-gray-50 border border-gray-100 rounded">No assessment records available yet.</div>
                @else
                    @foreach ($byModule as $moduleKey => $moduleScores)
                        <div class="p-4 bg-white border border-gray-100 rounded shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-semibold">{{ strtoupper($moduleKey) }}</div>
                                <div class="text-sm text-gray-500">Entries: {{ $moduleScores->count() }}</div>
                            </div>

                            @php
                                $total = $moduleScores->sum('score');
                                $maxTotal = $moduleScores->sum('max_score');
                                $percent = $maxTotal > 0 ? round(($total / $maxTotal) * 100, 1) : 0;
                            @endphp

                            <div class="mb-3">
                                <div class="text-sm text-gray-500">Total Score</div>
                                <div class="font-bold text-lg">{{ $total }} / {{ $maxTotal }} <span class="text-xs text-gray-500">({{ $percent }}%)</span></div>
                            </div>

                            <table class="w-full text-sm border-collapse">
                                <thead>
                                    <tr class="text-left text-xs text-gray-500 border-t border-b">
                                        <th class="py-2">Date</th>
                                        <th class="py-2">Score</th>
                                        <th class="py-2">Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($moduleScores as $s)
                                        <tr class="border-b">
                                            <td class="py-2 text-xs text-gray-500">{{ optional($s->recorded_at)->format('Y-m-d H:i') }}</td>
                                            <td class="py-2">{{ $s->score }} / {{ $s->max_score }}</td>
                                            <td class="py-2 text-xs text-gray-500">{{ $s->metadata['notes'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @endif
        </div>
    </main>
    </main>

        @include('shared.sweet-alerts.logout', ['logoutLabel' => 'Student — ' . $name, 'logoutSubtext' => 'Student Reports', 'logoutDescription' => 'You are about to end your session.', 'redirectUrl' => url('/login')])

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
