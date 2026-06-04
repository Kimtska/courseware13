<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VirtualArm - Manage Instructors</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{font-family:'Inter',sans-serif;margin:0;background:#f8fafc;overflow:hidden;height:100vh}
        .sidebar-link{display:flex;align-items:center;gap:12px;padding:10px 16px;border-radius:8px;color:rgba(255,255,255,0.6);transition:all .2s;font-size:14px;border-left:4px solid transparent;margin-bottom:2px;white-space:nowrap}
        .sidebar-link:hover{background:rgba(255,255,255,0.1);color:#fff;border-left-color:#8B5CF6}
        .sidebar-link.active{background:rgba(255,255,255,0.15);color:#fff;border-left-color:#A78BFA;font-weight:600}
        .dash-card{background:#fff;border:1px solid #f1f5f9;border-radius:16px;transition:all .3s}
        .dash-card:hover{box-shadow:0 10px 25px -5px rgba(0,0,0,.05);transform:translateY(-2px)}
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
        @include('Department-head.partials.nav-links', ['activeNav' => 'instructors'])
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
                <div><h1 class="font-display font-bold text-xl text-black">Manage Instructors</h1><p class="text-xs text-gray-400">Create faculty accounts and review the staff list</p></div>
            </div>
            <div class="w-8 h-8 rounded-full bg-violet-950 flex items-center justify-center text-white text-xs font-bold">{{ isset($name) ? implode('', array_map(fn($word) => substr($word, 0, 1), explode(' ', $name))) : 'DH' }}</div>
        </header>

        <div class="p-8 space-y-6">
            @if(session('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1 dash-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-display font-bold text-black">Add Instructor</h3>
                            <p class="text-xs text-gray-400">Create a faculty account</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-violet-50 text-violet-700 flex items-center justify-center"><i class="fas fa-user-plus"></i></div>
                    </div>
                    <form method="POST" action="{{ route('department-head.manage-instructors.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2" for="name">Full Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-violet-400">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2" for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-violet-400">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2" for="password">Password</label>
                            <input id="password" name="password" type="password" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-violet-400">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2" for="password_confirmation">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-violet-400">
                        </div>
                        <button type="submit" class="w-full rounded-xl bg-violet-700 px-4 py-3 text-sm font-bold text-white hover:bg-violet-600 transition-colors">Create Instructor Account</button>
                    </form>
                </div>

                <div class="lg:col-span-2 dash-card overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-display font-bold text-black">Faculty Accounts</h3>
                            <p class="text-xs text-gray-400">Current instructor users in the system</p>
                        </div>
                        <span class="text-xs font-semibold text-violet-600 bg-violet-50 px-3 py-1 rounded-full border border-violet-100">{{ $instructors->count() }} accounts</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wider">
                                <tr>
                                    <th class="p-4">Name</th>
                                    <th class="p-4">Email</th>
                                    <th class="p-4">Role</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($instructors as $instructor)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-4 font-medium text-black">{{ $instructor->name }}</td>
                                        <td class="p-4 text-gray-500">{{ $instructor->email }}</td>
                                        <td class="p-4"><span class="px-2 py-0.5 bg-violet-100 text-violet-700 text-[10px] font-bold rounded">Instructor</span></td>
                                    </tr>
                                @empty
                                    <tr><td class="p-4 text-gray-500" colspan="3">No instructor accounts found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('shared.sweet-alerts.logout', ['logoutLabel' => $name ?? 'Admin User', 'logoutSubtext' => 'Department Head session active', 'logoutDescription' => 'You are about to end your department head session. Review your faculty account list before leaving.', 'redirectUrl' => url('/')])

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        toggleBtn?.addEventListener('click', () => { sidebar.classList.toggle('collapsed'); });
    </script>
</body>
</html>