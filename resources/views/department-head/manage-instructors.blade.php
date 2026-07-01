<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IOT-Based Marksmanship - Manage Instructors</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/Marksmanship innovatech.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{font-family:'Inter',sans-serif;margin:0;background:#f8fafc;overflow:hidden;height:100vh}
        .sidebar-link{display:flex;align-items:center;gap:12px;padding:10px 16px;border-radius:8px;color:rgba(255,255,255,0.6);transition:all .2s;font-size:14px;border-left:4px solid transparent;margin-bottom:2px;white-space:nowrap}
        .sidebar-link:hover{background:rgba(255,255,255,0.1);color:#fff;border-left-color:#8B5CF6}
        .sidebar-link.active{background:rgba(255,255,255,0.15);color:#fff;border-left-color:#A78BFA;font-weight:600}
        .dash-card{background:#fff;border:1px solid #f1f5f9;border-radius:16px;transition:all .3s}
        .dash-card:hover{box-shadow:0 10px 25px -5px rgba(0,0,0,.05);transform:translateY(-2px)}
        .glass-card{background:#fff;border:1px solid #e5e7eb;border-radius:20px;box-shadow:0 4px 14px -8px rgba(30,5,82,.1)}
        .status-pill{display:inline-flex;align-items:center;gap:6px;padding:3px 10px;border-radius:9999px;font-size:11px;font-weight:700;white-space:nowrap}
        .portal-card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;box-shadow:0 4px 14px -8px rgba(30,5,82,.1)}
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
    @include('shared.back-button-prevention')
</head>
<body class="flex h-screen">
    @php
        if (!isset($name) || $name === null || $name === '') {
            $name = auth()->user()->name ?? 'Admin User';
        }
        $name = $name ?? 'Admin User';
        $profilePhoto = auth()->user()->profile_photo_path ?? null;
    @endphp
    <aside id="sidebar" class="bg-violet-950 text-white flex flex-col border-r border-violet-800/30 flex-shrink-0 h-full overflow-hidden">
        <div class="p-6 border-b border-violet-800/30 flex items-center gap-3">
            <img src="{{ asset('images/assets/Marksmanship innovatech.png') }}" alt="SPC" class="h-10 w-auto flex-shrink-0">
            <div class="sidebar-header-text whitespace-nowrap overflow-hidden"><span class="font-display font-bold text-sm">IOT-Based Marksmanship</span><span class="block text-[9px] text-violet-300 uppercase tracking-widest">Admin Panel</span></div>
        </div>
        @include('department-head.partials.nav-links', ['activeNav' => 'instructors'])
        <div class="p-4 border-t border-violet-800/30">
            <div class="sidebar-profile flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-full bg-violet-700 flex items-center justify-center text-sm font-bold flex-shrink-0 overflow-hidden">
                    @if($profilePhoto)
                        <img src="{{ asset('storage/' . $profilePhoto) }}" alt="Photo" class="w-full h-full object-cover">
                    @else
                        {{ !empty($name) ? implode('', array_map(fn($word) => substr($word, 0, 1), explode(' ', $name))) : 'DH' }}
                    @endif
                </div>
                <div class="sidebar-profile-text flex-1 min-w-0">
                    <div class="text-sm font-medium truncate">{{ $name }}</div>
                    <div class="text-xs text-violet-300">Dept. Head</div>
                </div>
                <button type="button" id="sidebar-settings-btn" class="w-9 h-9 rounded-full bg-violet-700 flex items-center justify-center text-violet-300 hover:text-white hover:bg-violet-600 transition-colors flex-shrink-0">
                    <i class="fas fa-cog text-sm"></i>
                </button>
            </div>
            <button type="button" onclick="showLogoutAlert()" class="w-full text-left sidebar-link text-red-300 hover:text-red-200 hover:bg-red-900/30 hover:border-red-400"><i class="fas fa-sign-out-alt text-sm"></i> <span class="sidebar-text">Logout</span></button>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto bg-gray-50">
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors text-gray-500 focus:outline-none"><i class="fas fa-bars text-lg"></i></button>
                <div><h1 class="font-display font-bold text-xl text-black">List of Instructors</h1><p class="text-xs text-gray-400">Create faculty accounts and manage instructor records</p></div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" id="open-add-instructor" class="inline-flex items-center gap-2 px-4 py-2 bg-violet-700 text-white text-xs font-bold uppercase rounded-lg hover:bg-violet-800 transition-all">
                    <i class="fas fa-user"></i> Add Instructor
                </button>
        </header>

        <div class="p-8 space-y-6">
            @if($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <div><i class="fas fa-triangle-exclamation mr-2"></i>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <section class="glass-card rounded-3xl p-5 sm:p-6 mb-6">
                <form method="GET" action="{{ route('department-head.manage-instructors') }}" id="filter-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="relative">
                        <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Live Search</label>
                        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" id="search-input" placeholder="Search name or email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                        <i class="fas fa-magnifying-glass absolute right-4 top-10 text-gray-400"></i>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                            <option value="">All Statuses</option>
                            <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                            <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Date Added</label>
                        <select name="date_range" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                            <option value="">All Time</option>
                            <option value="today" @selected(($filters['date_range'] ?? '') === 'today')>Today</option>
                            <option value="this_week" @selected(($filters['date_range'] ?? '') === 'this_week')>This Week</option>
                            <option value="this_month" @selected(($filters['date_range'] ?? '') === 'this_month')>This Month</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('department-head.manage-instructors') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-600 text-sm font-bold hover:bg-gray-50 transition-colors text-center">
                            <i class="fas fa-rotate-left mr-1"></i> Clear
                        </a>
                    </div>
                </form>
            </section>

            <section class="glass-card rounded-3xl overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h2 class="font-display font-bold text-xl text-gray-900">Instructor Accounts Table</h2>
                        <p class="text-sm text-gray-500">Faculty instructor accounts registered in the system.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[700px]">
                        <thead class="bg-violet-950 text-xs text-violet-100 uppercase tracking-wider">
                            <tr>
                                <th class="px-5 sm:px-6 py-4 font-semibold">Name</th>
                                <th class="px-5 sm:px-6 py-4 font-semibold">Email</th>
                                <th class="px-5 sm:px-6 py-4 font-semibold">Status</th>
                                <th class="px-5 sm:px-6 py-4 font-semibold">Students</th>
                                <th class="px-5 sm:px-6 py-4 font-semibold">Date Added</th>
                                <th class="px-5 sm:px-6 py-4 text-right font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($instructors as $instructor)
                                @php
                                    $isActive = !$instructor->deleted_at;
                                    $instructorPayload = [
                                        'name' => $instructor->name,
                                        'email' => $instructor->email,
                                        'status' => $isActive ? 'Active' : 'Inactive',
                                        'date_added' => $instructor->created_at ? $instructor->created_at->format('M d, Y') : '—',
                                    ];
                                @endphp
                                <tr class="even:bg-gray-50/60 hover:bg-violet-50/50 transition-colors">
                                    <td class="px-5 sm:px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $instructor->name }}</div>
                                    </td>
                                    <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">{{ $instructor->email }}</td>
                                    <td class="px-5 sm:px-6 py-4">
                                        <span class="status-pill {{ $isActive ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                            <i class="fas {{ $isActive ? 'fa-circle-check' : 'fa-circle' }} text-[8px]"></i>
                                            {{ $isActive ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-5 sm:px-6 py-4">
                                        @php $sectionCounts = $sectionCountsByInstructor[$instructor->id] ?? collect(); @endphp
                                        @if($sectionCounts->isEmpty())
                                            <span class="text-gray-400 text-sm">—</span>
                                        @else
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($sectionCounts as $sec => $cnt)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-violet-100 text-violet-700 text-xs font-semibold">
                                                        Sec {{ $sec }} <span class="text-violet-500 font-bold">{{ $cnt }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">{{ $instructor->created_at ? $instructor->created_at->format('M d, Y') : '—' }}</td>
                                    <td class="px-5 sm:px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" class="view-instructor inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-colors" data-instructor='@json($instructorPayload)'>
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <form method="POST" action="{{ route('department-head.manage-instructors.toggle-status', $instructor->id) }}" class="inline toggle-status-form" data-instructor-name="{{ $instructor->name }}" data-action="{{ $isActive ? 'deactivate' : 'activate' }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="toggle-status-btn inline-flex items-center gap-2 px-4 py-2 text-xs font-bold uppercase rounded-lg transition-all {{ $isActive ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }} hover:bg-slate-200">
                                                    <i class="fas {{ $isActive ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                                    {{ $isActive ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                        <div class="max-w-md mx-auto">
                                            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-2xl">
                                                <i class="fas fa-chalkboard-teacher"></i>
                                            </div>
                                            <p class="font-semibold text-gray-900 mb-2">No instructor accounts found</p>
                                            <p class="text-sm">No faculty instructor accounts have been created yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-5 sm:px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">Showing {{ $instructors->firstItem() ?? 0 }}–{{ $instructors->lastItem() ?? 0 }} of {{ $instructors->total() }} record(s)</div>
                    @if($instructors->hasPages())
                        <div class="flex items-center gap-1">
                            {{ $instructors->onEachSide(1)->links('vendor.pagination.violet') }}
                        </div>
                    @endif
                </div>
            </section>
        </div>

        <!-- Add Instructor Modal -->
        <div id="add-modal" class="fixed inset-0 z-50 hidden opacity-0 pointer-events-none items-center justify-center p-4 modal-backdrop transition-opacity duration-200 ease-out backdrop-blur-sm bg-slate-950/35">
            <div class="modal-panel w-full max-w-xl rounded-3xl bg-white shadow-2xl overflow-hidden transform scale-95 translate-y-3 opacity-0 transition-all duration-200 ease-out">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-display font-bold text-2xl text-gray-900">Add Instructor</h3>
                        <p class="text-sm text-gray-500">Create a new faculty instructor account.</p>
                    </div>
                    <button type="button" class="close-modal w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors"><i class="fas fa-xmark"></i></button>
                </div>
                <form method="POST" action="{{ route('department-head.manage-instructors.store') }}" class="p-6 space-y-5">
                    @csrf
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">First Name</label>
                                <input name="first_name" type="text" value="{{ old('first_name') }}" placeholder="e.g. Juan" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Middle Name <span class="text-gray-400 font-normal normal-case tracking-normal">(optional)</span></label>
                                <input name="middle_name" type="text" value="{{ old('middle_name') }}" placeholder="e.g. Santos" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Last Name</label>
                                <input name="last_name" type="text" value="{{ old('last_name') }}" placeholder="e.g. Dela Cruz" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Email</label>
                                <input name="email" type="email" value="{{ old('email') }}" placeholder="e.g. instructor@school.edu.ph" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Password</label>
                            <input name="password" type="text" value="instructor123" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Confirm Password</label>
                            <input name="password_confirmation" type="text" value="instructor123" placeholder="Re-type password to confirm" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" class="close-modal px-4 py-3 rounded-xl bg-gray-100 text-gray-700 text-sm font-bold hover:bg-gray-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-3 rounded-xl bg-violet-700 text-white text-sm font-bold hover:bg-violet-800 transition-colors">Create Instructor Account</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- View Modal -->
        <div id="view-modal" class="fixed inset-0 z-50 hidden opacity-0 pointer-events-none items-center justify-center p-4 modal-backdrop transition-opacity duration-200 ease-out backdrop-blur-sm bg-slate-950/35">
            <div class="modal-panel w-full max-w-xl rounded-3xl bg-white shadow-2xl overflow-hidden transform scale-95 translate-y-3 opacity-0 transition-all duration-200 ease-out">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-display font-bold text-2xl text-gray-900">Instructor Details</h3>
                        <p class="text-sm text-gray-500">Faculty instructor record</p>
                    </div>
                    <button type="button" class="close-modal w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors"><i class="fas fa-xmark"></i></button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Name</p><p id="view-name" class="text-lg font-semibold text-gray-900"></p></div>
                        <div><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Email</p><p id="view-email" class="text-gray-700"></p></div>
                        <div><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Status</p><p id="view-status" class="text-gray-700"></p></div>
                        <div><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Date Added</p><p id="view-date" class="text-gray-700"></p></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Modal -->
        <div id="settings-modal" class="fixed inset-0 z-50 hidden opacity-0 pointer-events-none items-center justify-center p-4 modal-backdrop transition-opacity duration-200 ease-out backdrop-blur-sm bg-slate-950/35">
            <div class="modal-panel w-full max-w-4xl rounded-3xl bg-white shadow-2xl overflow-hidden transform scale-95 translate-y-3 opacity-0 transition-all duration-200 ease-out">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-display font-bold text-xl text-gray-900">Profile Settings</h3>
                        <p class="text-sm text-gray-500">Update your name or change your password.</p>
                    </div>
                    <button type="button" class="close-settings w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors"><i class="fas fa-xmark"></i></button>
                </div>
                <div class="p-6">
                    @if(session('success'))
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 mb-6">
                            <i class="fas fa-circle-check mr-2"></i>{{ session('success') }}
                        </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div>
                                <h4 class="font-display font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-camera text-violet-600"></i> Change Profile Photo
                                </h4>
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-full bg-violet-100 flex items-center justify-center text-xl font-bold text-violet-700 flex-shrink-0 overflow-hidden">
                                        @if($profilePhoto)
                                            <img src="{{ asset('storage/' . $profilePhoto) }}" alt="Photo" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('department-head.profile.photo') }}" enctype="multipart/form-data" class="flex-1">
                                        @csrf
                                        <div class="flex items-center gap-2">
                                            <input type="file" name="photo" id="profile-photo-input" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden" required>
                                            <label for="profile-photo-input" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200 transition-colors cursor-pointer whitespace-nowrap">
                                                <i class="fas fa-folder-open"></i> Choose File
                                            </label>
                                            <span id="file-name-display" class="text-xs text-gray-400 truncate max-w-[140px]">No file chosen</span>
                                            <button type="submit" class="px-4 py-3 rounded-xl bg-violet-700 text-white text-xs font-bold hover:bg-violet-800 transition-colors whitespace-nowrap">
                                                <i class="fas fa-upload"></i> Upload
                                            </button>
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-2">JPEG, PNG, GIF, WebP up to 2MB.</p>
                                        @error('photo')
                                            <p class="mt-1 text-xs text-red-500"><i class="fas fa-triangle-exclamation mr-1"></i>{{ $message }}</p>
                                        @enderror
                                    </form>
                                    <script>
                                        document.getElementById('profile-photo-input')?.addEventListener('change', function() {
                                            const display = document.getElementById('file-name-display');
                                            display.textContent = this.files[0] ? this.files[0].name : 'No file chosen';
                                        });
                                    </script>
                                </div>
                            </div>
                            <div class="pt-6 border-t border-gray-100">
                                <h4 class="font-display font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-user-edit text-violet-600"></i> Edit Name
                                </h4>
                                <form method="POST" action="{{ route('department-head.profile.name') }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <input type="text" name="name" value="{{ $name }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                                        </div>
                                        <button type="submit" class="px-5 py-3 rounded-xl bg-violet-700 text-white text-sm font-bold hover:bg-violet-800 transition-colors whitespace-nowrap">
                                            <i class="fas fa-check"></i> Save
                                        </button>
                                    </div>
                                    @error('name')
                                        <p class="mt-2 text-xs text-red-500"><i class="fas fa-triangle-exclamation mr-1"></i>{{ $message }}</p>
                                    @enderror
                                </form>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-display font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-lock text-violet-600"></i> Change Password
                            </h4>
                            <form method="POST" action="{{ route('department-head.profile.password') }}">
                                @csrf
                                @method('PATCH')
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Current Password</label>
                                        <input type="password" name="current_password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">New Password</label>
                                        <input type="password" name="new_password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required minlength="8">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Confirm New Password</label>
                                        <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required minlength="8">
                                    </div>
                                </div>
                                @error('current_password')
                                    <p class="mt-2 text-xs text-red-500"><i class="fas fa-triangle-exclamation mr-1"></i>{{ $message }}</p>
                                @enderror
                                @error('new_password')
                                    <p class="mt-2 text-xs text-red-500"><i class="fas fa-triangle-exclamation mr-1"></i>{{ $message }}</p>
                                @enderror
                                <div class="mt-4">
                                    <button type="submit" class="px-5 py-3 rounded-xl bg-violet-700 text-white text-sm font-bold hover:bg-violet-800 transition-colors">
                                        <i class="fas fa-check"></i> Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('shared.sweet-alerts.logout', ['logoutLabel' => $name, 'logoutSubtext' => 'Department Head session active', 'logoutDescription' => 'You are about to end your department head session. Review your faculty account list before leaving.', 'redirectUrl' => url('/login')])

    <!-- Toggle Status Confirmation -->
    <div id="toggle-overlay" class="swal-overlay" role="dialog" aria-modal="true">
        <div class="swal-modal">
            <svg class="swal-bg-shape" style="top:-20px;right:-20px;width:120px;height:120px" viewBox="0 0 120 120"><circle cx="60" cy="60" r="60" fill="#7C3AED"></circle></svg>
            <svg class="swal-bg-shape" style="bottom:-30px;left:-30px;width:150px;height:150px" viewBox="0 0 150 150"><circle cx="75" cy="75" r="75" fill="#7C3AED"></circle></svg>
            <div class="pt-8 pb-2"><div class="swal-icon-wrap"><div class="swal-icon-ring"><i class="fas fa-exchange-alt text-sm"></i></div><span class="swal-dot"></span><span class="swal-dot"></span><span class="swal-dot"></span><span class="swal-dot"></span></div></div>
            <div class="px-8 pt-4 pb-3 text-center">
                <h3 id="toggle-title" class="swal-title">Confirm Action</h3>
                <p id="toggle-desc" class="swal-text mt-2">Are you sure you want to change the status of this instructor account?</p>
            </div>
            <div class="mx-8 mb-5 p-3 bg-violet-50/70 rounded-xl border border-violet-100/80">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-violet-200/60 flex items-center justify-center"><i class="fas fa-chalkboard-teacher text-violet-600 text-xs"></i></div>
                    <div class="flex-1 min-w-0">
                        <p id="toggle-instructor-name" class="text-xs font-semibold text-violet-900 truncate">Instructor</p>
                        <p id="toggle-action-label" class="text-[10px] text-violet-500">Status change</p>
                    </div>
                </div>
            </div>
            <div class="px-8 pb-8 flex items-center gap-3">
                <button id="toggle-cancel" class="swal-btn swal-btn-cancel flex-1" type="button"><i class="fas fa-times text-xs"></i> Cancel</button>
                <button id="toggle-confirm" class="swal-btn swal-btn-logout btn-shine flex-1" type="button"><span class="swal-btn-text"><i class="fas fa-check text-sm"></i> Confirm</span><span class="swal-spinner"></span></button>
            </div>
        </div>
    </div>

    <!-- Created Credentials SweetAlert -->
    @if(session('created_email'))
        <div id="cred-overlay" class="swal-overlay active" role="dialog" aria-modal="true">
            <div class="swal-modal" style="max-width:460px">
                <svg class="swal-bg-shape" style="top:-20px;right:-20px;width:120px;height:120px" viewBox="0 0 120 120"><circle cx="60" cy="60" r="60" fill="#7C3AED"></circle></svg>
                <svg class="swal-bg-shape" style="bottom:-30px;left:-30px;width:150px;height:150px" viewBox="0 0 150 150"><circle cx="75" cy="75" r="75" fill="#7C3AED"></circle></svg>
                <div class="pt-8 pb-2"><div class="swal-icon-wrap"><div class="swal-icon-ring"><i class="fas fa-check text-sm"></i></div><span class="swal-dot"></span><span class="swal-dot"></span><span class="swal-dot"></span><span class="swal-dot"></span></div></div>
                <div class="px-8 pt-4 pb-3 text-center">
                    <h3 class="swal-title">Instructor Created</h3>
                    <p class="swal-text mt-2">Account has been created successfully. Please share these credentials with the instructor.</p>
                </div>
                <div class="mx-8 mb-4 p-4 bg-violet-50/70 rounded-xl border border-violet-100/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-violet-900">Email</span>
                        <span id="cred-email" class="text-sm font-mono font-bold text-violet-700">{{ session('created_email') }}</span>
                    </div>
                    <div class="border-t border-violet-200/60"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-violet-900">Password</span>
                        <span id="cred-password" class="text-sm font-mono font-bold text-violet-700">{{ session('created_password') }}</span>
                    </div>
                </div>
                <div class="px-8 pb-8 flex items-center gap-3">
                    <button id="cred-copy" class="swal-btn swal-btn-cancel flex-1" type="button"><i class="fas fa-copy text-xs"></i> Copy Credentials</button>
                    <button id="cred-ok" class="swal-btn swal-btn-logout btn-shine flex-1" type="button"><i class="fas fa-check text-sm"></i> Okay</button>
                </div>
                <div id="cred-toast" style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);background:#065f46;color:#fff;padding:8px 16px;border-radius:10px;font-size:12px;font-weight:600;opacity:0;transition:opacity .3s;pointer-events:none">Copied!</div>
            </div>
        </div>
        <script>
            (function(){
                var overlay = document.getElementById('cred-overlay');
                var okBtn = document.getElementById('cred-ok');
                var copyBtn = document.getElementById('cred-copy');
                var toast = document.getElementById('cred-toast');
                okBtn.addEventListener('click', function(){
                    overlay.classList.add('closing');
                    overlay.classList.remove('active');
                });
                copyBtn.addEventListener('click', function(){
                    var email = document.getElementById('cred-email').textContent;
                    var pass = document.getElementById('cred-password').textContent;
                    navigator.clipboard.writeText('Email: ' + email + '\nPassword: ' + pass).then(function(){
                        toast.style.opacity = '1';
                        setTimeout(function(){ toast.style.opacity = '0'; }, 2000);
                    });
                });
            })();
        </script>
    @endif

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        toggleBtn?.addEventListener('click', () => { sidebar.classList.toggle('collapsed'); });

        const addModal = document.getElementById('add-modal');
        const viewModal = document.getElementById('view-modal');
        const openAddBtn = document.getElementById('open-add-instructor');
        const modalAnimationDelay = 200;

        const openModal = (modal) => {
            const panel = modal.querySelector('.modal-panel');
            modal.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                modal.classList.add('opacity-100');
                if (panel) {
                    panel.classList.remove('scale-95', 'translate-y-3', 'opacity-0');
                    panel.classList.add('scale-100', 'translate-y-0', 'opacity-100');
                }
            });
        };

        const closeModal = (modal) => {
            const panel = modal.querySelector('.modal-panel');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0', 'pointer-events-none');
            if (panel) {
                panel.classList.remove('scale-100', 'translate-y-0', 'opacity-100');
                panel.classList.add('scale-95', 'translate-y-3', 'opacity-0');
            }
            window.setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, modalAnimationDelay);
        };

        openAddBtn?.addEventListener('click', () => openModal(addModal));

        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', () => {
                closeModal(addModal);
                closeModal(viewModal);
            });
        });

        document.querySelectorAll('.view-instructor').forEach(button => {
            button.addEventListener('click', () => {
                const instructor = JSON.parse(button.dataset.instructor);
                document.getElementById('view-name').textContent = instructor.name;
                document.getElementById('view-email').textContent = instructor.email;
                document.getElementById('view-status').textContent = instructor.status;
                document.getElementById('view-date').textContent = instructor.date_added;
                openModal(viewModal);
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal(addModal);
                closeModal(viewModal);
                closeToggleAlert();
            }
        });

        const toggleOverlay = document.getElementById('toggle-overlay');
        const toggleTitle = document.getElementById('toggle-title');
        const toggleDesc = document.getElementById('toggle-desc');
        const toggleName = document.getElementById('toggle-instructor-name');
        const toggleActionLabel = document.getElementById('toggle-action-label');
        const toggleConfirm = document.getElementById('toggle-confirm');
        const toggleCancel = document.getElementById('toggle-cancel');
        let activeToggleForm = null;

        function openToggleAlert(form) {
            activeToggleForm = form;
            const name = form.dataset.instructorName;
            const action = form.dataset.action;
            const isActivate = action === 'activate';

            toggleName.textContent = name;
            toggleTitle.textContent = isActivate ? 'Activate Instructor' : 'Deactivate Instructor';
            toggleDesc.textContent = isActivate
                ? 'This will restore access for this instructor account. They will be able to log in and manage students.'
                : 'This will disable access for this instructor account. They will not be able to log in until re-activated.';
            toggleActionLabel.textContent = isActivate ? 'Will be activated' : 'Will be deactivated';
            toggleConfirm.querySelector('.swal-btn-text').innerHTML = isActivate
                ? '<i class="fas fa-check-circle text-sm"></i> Yes, Activate'
                : '<i class="fas fa-ban text-sm"></i> Yes, Deactivate';

            toggleOverlay.classList.remove('closing');
            toggleOverlay.classList.add('active');
            setTimeout(() => toggleCancel.focus(), 350);
        }

        window.closeToggleAlert = function () {
            if (!toggleOverlay.classList.contains('active')) return;
            toggleOverlay.classList.add('closing');
            toggleOverlay.classList.remove('active');
            setTimeout(() => {
                toggleOverlay.classList.remove('closing');
                activeToggleForm = null;
                toggleConfirm.classList.remove('loading');
                toggleConfirm.disabled = false;
                toggleCancel.disabled = false;
            }, 280);
        };

        toggleCancel.addEventListener('click', window.closeToggleAlert);
        toggleConfirm.addEventListener('click', function () {
            if (!activeToggleForm) return;
            toggleConfirm.classList.add('loading');
            toggleConfirm.disabled = true;
            toggleCancel.disabled = true;
            activeToggleForm.submit();
        });
        toggleOverlay.addEventListener('click', function (event) {
            if (event.target === toggleOverlay) window.closeToggleAlert();
        });

        document.querySelectorAll('.toggle-status-btn').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('.toggle-status-form');
                openToggleAlert(form);
            });
        });

        const settingsModal = document.getElementById('settings-modal');
        const settingsBtn = document.getElementById('sidebar-settings-btn');

        settingsBtn?.addEventListener('click', () => openModal(settingsModal));

        document.querySelectorAll('.close-settings').forEach(button => {
            button.addEventListener('click', () => closeModal(settingsModal));
        });
    </script>
</body>
</html>
