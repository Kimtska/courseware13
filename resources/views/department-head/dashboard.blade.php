<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IOT-Based Marksmanship - Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/Marksmanship innovatech.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{font-family:'Inter',sans-serif;margin:0;background:#f8fafc;overflow:hidden;height:100vh}
        .sidebar-link{display:flex;align-items:center;gap:12px;padding:10px 16px;border-radius:8px;color:rgba(255,255,255,0.6);transition:all .2s;font-size:14px;border-left:4px solid transparent;margin-bottom:2px;white-space:nowrap}
        .sidebar-link:hover{background:rgba(255,255,255,0.1);color:#fff;border-left-color:#8B5CF6}
        .sidebar-link.active{background:rgba(255,255,255,0.15);color:#fff;border-left-color:#A78BFA;font-weight:600}
        .dash-card{background:#fff;border:1px solid #f1f5f9;border-radius:12px;transition:all .3s}.dash-card:hover{box-shadow:0 10px 25px -5px rgba(0,0,0,.05);transform:translateY(-2px)}
        .progress-bar{height:8px;border-radius:4px;background:#e2e8f0;overflow:hidden}.progress-fill{height:100%;border-radius:4px;transition:width 1s ease-out}

        /* Sidebar Collapse CSS */
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
    @php
        if (!isset($name) || $name === null || $name === '') {
            $name = auth()->user()->name ?? 'Admin User';
        }
        $name = $name ?? 'Admin User';
    @endphp
    <!-- Sidebar -->
    <aside id="sidebar" class="bg-violet-950 text-white flex flex-col border-r border-violet-800/30 flex-shrink-0 h-full overflow-hidden">
        <div class="p-6 border-b border-violet-800/30 flex items-center gap-3">
            <img src="{{ asset('images/assets/Marksmanship innovatech.png') }}" alt="SPC" class="h-10 w-auto flex-shrink-0">
            <div class="sidebar-header-text whitespace-nowrap overflow-hidden"><span class="font-display font-bold text-sm">IOT-Based Marksmanship</span><span class="block text-[9px] text-violet-300 uppercase tracking-widest">Admin Panel</span></div>
        </div>
        @include('department-head.partials.nav-links', ['activeNav' => 'dashboard'])
        <div class="p-4 border-t border-violet-800/30">
            <div class="sidebar-profile flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-full bg-violet-700 flex items-center justify-center text-sm font-bold flex-shrink-0" id="userAvatar">
                    @if(!empty($name))
                        {{ implode('', array_map(fn($word) => substr($word, 0, 1), explode(' ', $name))) }}
                    @else
                        DH
                    @endif
                </div>
                <div class="sidebar-profile-text">
                    <div class="text-sm font-medium" id="userName">{{ $name }}</div>
                    <div class="text-xs text-violet-300">Dept. Head</div>
                </div>
            </div>
            <button type="button" onclick="showLogoutAlert()" class="w-full text-left sidebar-link text-red-300 hover:text-red-200 hover:bg-red-900/30 hover:border-red-400"><i class="fas fa-sign-out-alt text-sm"></i> <span class="sidebar-text">Logout</span></button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto bg-gray-50">
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors text-gray-500 focus:outline-none">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div><h1 class="font-display font-bold text-xl text-black">Admin Dashboard</h1><p class="text-xs text-gray-400">System overview and user management</p></div>
            </div>
        </header>
        <div class="p-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="dash-card p-5 border-l-4 border-violet-600"><p class="text-xs text-gray-400 uppercase tracking-wider">Total Students</p><p class="text-2xl font-bold text-black mt-1">{{ isset($stats['total_students']) ? $stats['total_students'] : 0 }}</p><p class="text-[10px] text-green-500 mt-1 font-medium">+{{ isset($stats['students_change']) ? $stats['students_change'] : 0 }} this semester</p></div>
                <div class="dash-card p-5 border-l-4 border-indigo-600"><p class="text-xs text-gray-400 uppercase tracking-wider">Instructors</p><p class="text-2xl font-bold text-black mt-1">{{ isset($stats['total_instructors']) ? $stats['total_instructors'] : 0 }}</p><p class="text-[10px] text-gray-400 mt-1">Active this term</p></div>
                <div class="dash-card p-5 border-l-4 border-blue-600"><p class="text-xs text-gray-400 uppercase tracking-wider">Active Users (Now)</p><p class="text-2xl font-bold text-black mt-1">{{ isset($stats['active_now']) ? $stats['active_now'] : 0 }}</p><p class="text-[10px] text-violet-500 mt-1">{{ isset($stats['in_range']) ? $stats['in_range'] : 0 }} in Firing Range</p></div>
            </div>
        </div>
    </main>

    @include('shared.sweet-alerts.logout', ['logoutLabel' => $name, 'logoutSubtext' => 'Department Head session active', 'logoutDescription' => 'You are about to end your department head session. Review your system updates and user management tasks before leaving.', 'redirectUrl' => url('/')])

    <script>

        // Sidebar Toggle Logic
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        toggleBtn.addEventListener('click', () => { sidebar.classList.toggle('collapsed'); });


    </script>
</body>
</html>