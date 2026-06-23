@php
    $activeNav = $activeNav ?? 'dashboard';
@endphp

<nav class="flex-1 p-4 space-y-1 overflow-y-auto overflow-x-hidden">
    <a href="{{ route('department-head.dashboard') }}" class="sidebar-link {{ $activeNav === 'dashboard' ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt w-5 text-center flex-shrink-0"></i>
        <span class="sidebar-text">Dashboard</span>
    </a>
    <a href="{{ route('department-head.manage-instructors') }}" class="sidebar-link {{ $activeNav === 'instructors' ? 'active' : '' }}">
        <i class="fas fa-chalkboard-teacher w-5 text-center flex-shrink-0"></i>
        <span class="sidebar-text">Manage Instructors</span>
    </a>   
    <a href="{{ route('department-head.manage-students') }}" class="sidebar-link {{ $activeNav === 'students' ? 'active' : '' }}">
        <i class="fas fa-user-graduate w-5 text-center flex-shrink-0"></i>
        <span class="sidebar-text">View list of Students</span>
    </a>
</nav>

