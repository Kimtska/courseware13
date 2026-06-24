<aside id="sidebar" class="bg-violet-950 text-white flex flex-col border-r border-violet-800/30 flex-shrink-0 h-full overflow-hidden">
    @php
        if (!isset($name) || $name === null || $name === '') {
            $name = auth()->user()->name ?? 'Instructor';
        }
        $name = $name ?? 'Instructor';
        $profilePhoto = auth()->user()->profile_photo_path ?? null;
    @endphp
    <div class="p-6 border-b border-violet-800/30 flex items-center gap-3">
        <img src="{{ asset('images/assets/Marksmanship innovatech.png') }}" alt="SPC" class="h-10 w-auto flex-shrink-0">
        <div class="sidebar-header-text whitespace-nowrap overflow-hidden"><span class="font-display font-bold text-sm">IOT-Based Marksmanship</span><span class="block text-[9px] text-violet-300 uppercase tracking-widest">Instructor Panel</span></div>
    </div>
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto overflow-x-hidden">
        <a href="{{ route('instructor.dashboard') }}" class="sidebar-link {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}"><i class="fas fa-home w-5 text-center flex-shrink-0"></i> <span class="sidebar-text">Dashboard</span></a>
        <a href="{{ route('instructor.manage-students') }}" class="sidebar-link {{ request()->routeIs('instructor.manage-students') ? 'active' : '' }}"><i class="fas fa-user-graduate w-5 text-center flex-shrink-0"></i> <span class="sidebar-text">Manage Students</span></a>
        <a href="{{ route('instructor.manage-module') }}" class="sidebar-link {{ request()->routeIs('instructor.manage-module*') ? 'active' : '' }}"><i class="fas fa-door-open w-5 text-center flex-shrink-0"></i> <span class="sidebar-text">Manage Modules</span></a>
        <a href="{{ route('instructor.manage-marksmanship') }}" class="sidebar-link {{ request()->routeIs('instructor.manage-marksmanship') ? 'active' : '' }}"><i class="fas fa-bullseye w-5 text-center flex-shrink-0"></i> <span class="sidebar-text">Manage Marksmanship</span></a>
        <a href="{{ route('instructor.reports') }}" class="sidebar-link {{ request()->routeIs('instructor.reports*') ? 'active' : '' }}"><i class="fas fa-chart-simple w-5 text-center flex-shrink-0"></i> <span class="sidebar-text">Reports</span></a>
    </nav>
    <div class="p-4 border-t border-violet-800/30">
        <div class="sidebar-profile flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-full bg-violet-700 flex items-center justify-center text-sm font-bold flex-shrink-0 overflow-hidden" id="userAvatar">
                @if($profilePhoto)
                    <img src="{{ asset('storage/' . $profilePhoto) }}" alt="Photo" class="w-full h-full object-cover">
                @elseif(!empty($name))
                    {{ implode('', array_map(fn($word) => substr($word, 0, 1), explode(' ', $name))) }}
                @else
                    IN
                @endif
            </div>
            <div class="sidebar-profile-text flex-1 min-w-0">
                <div class="text-sm font-medium truncate" id="userName">{{ $name }}</div>
                <div class="text-xs text-violet-300">Instructor</div>
            </div>
            <button type="button" id="sidebar-settings-btn" class="w-9 h-9 rounded-full bg-violet-700 flex items-center justify-center text-violet-300 hover:text-white hover:bg-violet-600 transition-colors flex-shrink-0">
                <i class="fas fa-cog text-sm"></i>
            </button>
        </div>
        <button type="button" onclick="showLogoutAlert()" class="w-full text-left sidebar-link text-red-300 hover:text-red-200 hover:bg-red-900/30 hover:border-red-400"><i class="fas fa-sign-out-alt text-sm"></i> <span class="sidebar-text">Logout</span></button>
    </div>
</aside>
