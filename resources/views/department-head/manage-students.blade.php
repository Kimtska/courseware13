<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VirtualArm - Manage Students</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{font-family:'Inter',sans-serif;margin:0;background:#f8fafc;overflow:hidden;height:100vh}
        .sidebar-link{display:flex;align-items:center;gap:12px;padding:10px 16px;border-radius:8px;color:rgba(255,255,255,0.6);transition:all .2s;font-size:14px;border-left:4px solid transparent;margin-bottom:2px;white-space:nowrap}
        .sidebar-link:hover{background:rgba(255,255,255,0.1);color:#fff;border-left-color:#8B5CF6}
        .sidebar-link.active{background:rgba(255,255,255,0.15);color:#fff;border-left-color:#A78BFA;font-weight:600}
        .dash-card{background:#fff;border:1px solid #f1f5f9;border-radius:16px;transition:all .3s}
        .dash-card:hover{box-shadow:0 10px 25px -5px rgba(0,0,0,.05);transform:translateY(-2px)}
        .progress-bar{height:8px;border-radius:4px;background:#e2e8f0;overflow:hidden}.progress-fill{height:100%;border-radius:4px;transition:width 1s ease-out}
        #sidebar { width: 256px; transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        #sidebar.collapsed { width: 80px; }
        #sidebar.collapsed .sidebar-text { display: none; }
        #sidebar.collapsed .sidebar-header-text { display: none; }
        #sidebar.collapsed .sidebar-profile-text { display: none; }
        #sidebar.collapsed .sidebar-link { justify-content: center; padding-left: 0; padding-right: 0; border-left: none; }
        #sidebar.collapsed .sidebar-link.active { border-left: none; background: rgba(255,255,255,0.15); }
        #sidebar.collapsed .sidebar-link:hover { border-left: none; background: rgba(255,255,255,0.1); }
        #sidebar.collapsed .sidebar-profile { justify-content: center; padding: 16px 0; }
    </style>
</head>
<body class="flex h-screen">
    <aside id="sidebar" class="bg-violet-950 text-white flex flex-col border-r border-violet-800/30 flex-shrink-0 h-full overflow-hidden">
        <div class="p-6 border-b border-violet-800/30 flex items-center gap-3">
            <img src="{{ asset('images/assets/logo.png') }}" alt="SPC" class="h-10 w-auto flex-shrink-0">
            <div class="sidebar-header-text whitespace-nowrap overflow-hidden"><span class="font-display font-bold text-sm">VirtualArm</span><span class="block text-[9px] text-violet-300 uppercase tracking-widest">Admin Panel</span></div>
        </div>
        @include('Department-head.partials.nav-links', ['activeNav' => 'students'])
        <div class="p-4 border-t border-violet-800/30">
            <div class="sidebar-profile flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-full bg-violet-700 flex items-center justify-center text-sm font-bold flex-shrink-0">{{ isset($name) ? implode('', array_map(fn($word) => substr($word, 0, 1), explode(' ', $name))) : 'DH' }}</div>
                <div class="sidebar-profile-text">
                    <div class="text-sm font-medium">{{ $name ?? 'Admin User' }}</div>
                    <div class="text-xs text-violet-300">Dept. Head</div>
                </div>
            </div>
            <button type="button" onclick="showLogoutAlert()" class="w-full text-left sidebar-link text-red-300 hover:text-red-200 hover:bg-red-900/30 hover:border-red-400"><i class="fas fa-sign-out-alt text-sm"></i> <span class="sidebar-text">Logout</span></button>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto bg-gray-50">
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors text-gray-500 focus:outline-none"><i class="fas fa-bars text-lg"></i></button>
                <div><h1 class="font-display font-bold text-xl text-black">Manage Students</h1><p class="text-xs text-gray-400">View student performance and progress</p></div>
            </div>
            <div class="w-8 h-8 rounded-full bg-violet-950 flex items-center justify-center text-white text-xs font-bold">{{ isset($name) ? implode('', array_map(fn($word) => substr($word, 0, 1), explode(' ', $name))) : 'DH' }}</div>
        </header>

        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="dash-card p-5 border-l-4 border-violet-600"><p class="text-xs text-gray-400 uppercase tracking-wider">Students</p><p class="text-2xl font-bold text-black mt-1">{{ $students->count() }}</p></div>
                <div class="dash-card p-5 border-l-4 border-emerald-600"><p class="text-xs text-gray-400 uppercase tracking-wider">Verified</p><p class="text-2xl font-bold text-black mt-1">{{ $students->where('verification_status', 'verified')->count() }}</p></div>
                <div class="dash-card p-5 border-l-4 border-amber-600"><p class="text-xs text-gray-400 uppercase tracking-wider">Pending</p><p class="text-2xl font-bold text-black mt-1">{{ $students->where('verification_status', 'pending')->count() }}</p></div>
                <div class="dash-card p-5 border-l-4 border-blue-600"><p class="text-xs text-gray-400 uppercase tracking-wider">Avg. Score</p><p class="text-2xl font-bold text-black mt-1">{{ number_format($students->map(function ($student) { $scores = $student->scores ?? collect(); $maxScore = $scores->sum('max_score'); return $maxScore > 0 ? ($scores->sum('score') / $maxScore) * 100 : 0; })->avg() ?? 0, 1) }}%</p></div>
            </div>

            <div class="dash-card overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-display font-bold text-black">Student Performance</h3>
                        <p class="text-xs text-gray-400">Read-only view for departmental oversight</p>
                    </div>
                    <span class="text-xs font-semibold text-violet-600 bg-violet-50 px-3 py-1 rounded-full border border-violet-100">View only</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th class="p-4">Student</th>
                                <th class="p-4">Section</th>
                                <th class="p-4">Verification</th>
                                <th class="p-4">Attendance</th>
                                <th class="p-4">Average Score</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($students as $student)
                                @php
                                    $scores = $student->scores ?? collect();
                                    $attendance = $student->attendanceRecords ?? collect();
                                    $scorePercent = ($scores->sum('max_score') > 0) ? round(($scores->sum('score') / $scores->sum('max_score')) * 100, 1) : 0;
                                    $presentCount = $attendance->where('status', 'present')->count();
                                    $attendanceRate = $attendance->count() > 0 ? round(($presentCount / $attendance->count()) * 100, 1) : 0;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-4 font-medium text-black">{{ $student->full_name ?? trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) }}</td>
                                    <td class="p-4 text-gray-500">{{ $student->section ?? 'N/A' }}</td>
                                    <td class="p-4">
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ ($student->verification_status ?? '') === 'verified' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ ucfirst($student->verification_status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-gray-500">{{ $attendanceRate }}%</td>
                                    <td class="p-4 text-gray-500">{{ $scorePercent }}%</td>
                                </tr>
                            @empty
                                <tr><td class="p-4 text-gray-500" colspan="5">No student records available yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    @include('shared.sweet-alerts.logout', ['logoutLabel' => $name ?? 'Admin User', 'logoutSubtext' => 'Department Head session active', 'logoutDescription' => 'You are about to end your department head session. Review your student performance records before leaving.', 'redirectUrl' => url('/')])

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        toggleBtn?.addEventListener('click', () => { sidebar.classList.toggle('collapsed'); });
    </script>
</body>
</html>