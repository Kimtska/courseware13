<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VirtualArm - List of Students</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/logo.png') }}">
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
            <img src="{{ asset('images/assets/logo.png') }}" alt="SPC" class="h-10 w-auto flex-shrink-0">
            <div class="sidebar-header-text whitespace-nowrap overflow-hidden"><span class="font-display font-bold text-sm">VirtualArm</span><span class="block text-[9px] text-violet-300 uppercase tracking-widest">Admin Panel</span></div>
        </div>
        @include('department-head.partials.nav-links', ['activeNav' => 'students'])
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
                <div><h1 class="font-display font-bold text-xl text-black">List of Students</h1><p class="text-xs text-gray-400">View all managed student records across the system</p></div>
            </div>
        </header>

        <div class="p-8 space-y-6">
            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    <i class="fas fa-circle-check mr-2"></i>{{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <div><i class="fas fa-triangle-exclamation mr-2"></i>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <section class="portal-card p-4 mb-6 max-w-md">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">Student Total</p>
                        <p class="text-xl font-display font-bold text-gray-900 leading-tight">{{ $totalStudents ?? 0 }}</p>
                    </div>
                </div>
            </section>

            <section class="glass-card rounded-3xl p-5 sm:p-6 mb-6">
                <form method="GET" action="{{ route('department-head.manage-students') }}" id="filter-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="relative">
                        <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Live Search</label>
                        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" id="search-input" placeholder="Search student ID or full name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                        <i class="fas fa-magnifying-glass absolute right-4 top-10 text-gray-400"></i>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Section</label>
                        <select name="section" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                            <option value="">All Sections</option>
                            @foreach(($sections ?? []) as $section)
                                <option value="{{ $section }}" @selected(($filters['section'] ?? '') === $section)>{{ $section }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Enrollment Status</label>
                        <select name="enrollment_status" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                            <option value="">All Statuses</option>
                            <option value="verified_enrolled" @selected(($filters['enrollment_status'] ?? '') === 'verified_enrolled')>Verified Enrolled</option>
                            <option value="ready_for_training" @selected(($filters['enrollment_status'] ?? '') === 'ready_for_training')>Ready for Training</option>
                            <option value="pending" @selected(($filters['enrollment_status'] ?? '') === 'pending')>Pending</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Session Activity</label>
                        <select name="activity_status" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                            <option value="">All Activity</option>
                            <option value="inactive" @selected(($filters['activity_status'] ?? '') === 'inactive')>Inactive</option>
                            <option value="active_in_firing_range" @selected(($filters['activity_status'] ?? '') === 'active_in_firing_range')>Active in Firing Range</option>
                            <option value="active_in_assembly" @selected(($filters['activity_status'] ?? '') === 'active_in_assembly')>Active in Assembly</option>
                            <option value="completed_session" @selected(($filters['activity_status'] ?? '') === 'completed_session')>Completed Session</option>
                        </select>
                    </div>
                </form>
            </section>

            <section class="glass-card rounded-3xl overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h2 class="font-display font-bold text-xl text-gray-900">Student Management Table</h2>
                        <p class="text-sm text-gray-500">Read-only view of all verified enrolled SPC student records.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[950px]">
                        <thead class="bg-violet-950 text-xs text-violet-100 uppercase tracking-wider">
                            <tr>
                                <th class="px-5 sm:px-6 py-4 font-semibold">Student ID</th>
                                <th class="px-5 sm:px-6 py-4 font-semibold">Full Name</th>
                                <th class="px-5 sm:px-6 py-4 font-semibold">Course/Year/Section</th>
                                <th class="px-5 sm:px-6 py-4 font-semibold">Module Access Status</th>
                                <th class="px-5 sm:px-6 py-4 font-semibold">Current Activity Status</th>
                                <th class="px-5 sm:px-6 py-4 font-semibold">Date Added</th>
                                <th class="px-5 sm:px-6 py-4 text-right font-semibold">Action Buttons</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($students as $student)
                                @php
                                    $studentId = $student->student_id_number ?? $student->student_number ?? '';
                                    $fullName = $student->full_name ?? trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? ''));
                                    $course = $student->course ?? '—';
                                    $yearLevel = $student->year_level ?? '—';
                                    $section = $student->section ?? '—';
                                    $enrollmentStatus = $student->enrollment_status ?? ($student->verification_status ?? 'pending');
                                    $moduleAccessStatus = $student->module_access_status ?? (($enrollmentStatus === 'verified_enrolled' || $enrollmentStatus === 'verified') ? 'ready_for_training' : 'locked');
                                    $activityStatus = $student->current_activity_status ?? 'inactive';
                                    $dateAdded = $student->created_at ?? null;
                                    $studentPayload = [
                                        'id' => $student->id,
                                        'student_id_number' => $studentId,
                                        'full_name' => $fullName,
                                        'course' => $course,
                                        'year_level' => $yearLevel,
                                        'section' => $section,
                                        'enrollment_status' => $enrollmentStatus,
                                        'module_access_status' => $moduleAccessStatus,
                                        'current_activity_status' => $activityStatus,
                                        'date_added' => $dateAdded ? $dateAdded->format('Y-m-d H:i') : null,
                                    ];

                                    $enrollmentClass = match ($enrollmentStatus) {
                                        'verified_enrolled', 'verified' => 'bg-emerald-100 text-emerald-700',
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'archived' => 'bg-gray-200 text-gray-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                    $moduleClass = match ($moduleAccessStatus) {
                                        'ready_for_training' => 'bg-violet-100 text-violet-700',
                                        'locked' => 'bg-slate-100 text-slate-700',
                                        'active_in_firing_range' => 'bg-cyan-100 text-cyan-700',
                                        'completed_session' => 'bg-emerald-100 text-emerald-700',
                                        'archived' => 'bg-gray-200 text-gray-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                    $activityClass = match ($activityStatus) {
                                        'inactive' => 'bg-slate-100 text-slate-700',
                                        'active_in_firing_range' => 'bg-cyan-100 text-cyan-700',
                                        'active_in_assembly' => 'bg-violet-100 text-violet-700',
                                        'completed_session' => 'bg-emerald-100 text-emerald-700',
                                        'archived' => 'bg-gray-200 text-gray-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <tr class="even:bg-gray-50/60 hover:bg-violet-50/50 transition-colors">
                                    <td class="px-5 sm:px-6 py-4 font-semibold text-gray-900">{{ $studentId }}</td>
                                    <td class="px-5 sm:px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $fullName }}</div>
                                        <div class="text-xs text-gray-500">{{ $course }}</div>
                                    </td>
                                    <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">{{ $course }} / {{ $yearLevel }} / {{ $section }}</td>
                                    <td class="px-5 sm:px-6 py-4"><span class="status-pill {{ $moduleClass }}">{{ str_replace('_', ' ', $moduleAccessStatus) }}</span></td>
                                    <td class="px-5 sm:px-6 py-4"><span class="status-pill {{ $activityClass }}">{{ str_replace('_', ' ', $activityStatus) }}</span></td>
                                    <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">{{ $dateAdded ? $dateAdded->format('M d, Y') : '—' }}</td>
                                    <td class="px-5 sm:px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" class="view-student inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-colors" data-student='@json($studentPayload)'>
                                                <i class="fas fa-eye"></i> View
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center text-gray-500">
                                        <div class="max-w-md mx-auto">
                                            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-2xl">
                                                <i class="fas fa-user-graduate"></i>
                                            </div>
                                            <p class="font-semibold text-gray-900 mb-2">No student records found</p>
                                            <p class="text-sm">No managed student records are available in the system yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-5 sm:px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">Showing {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} records</div>
                    <div>{{ $students->links('vendor.pagination.violet') }}</div>
                </div>
            </section>

            @if (!empty($showArchivedStudents) && $archivedStudents->isNotEmpty())
                <section class="glass-card rounded-3xl overflow-hidden mt-6">
                    <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="font-display font-bold text-xl text-gray-900">Archived Students</h2>
                            <p class="text-sm text-gray-500">Stored records for completed or ineligible students.</p>
                        </div>
                        <span class="chip bg-gray-200 text-gray-700"><i class="fas fa-box-archive"></i> Admin Only</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[900px]">
                            <thead class="bg-gray-100 text-xs text-gray-600 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Student ID</th>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Full Name</th>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Course/Year/Section</th>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Status</th>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Archived At</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($archivedStudents as $student)
                                    <tr class="even:bg-gray-100/80 bg-gray-50/80">
                                        <td class="px-5 sm:px-6 py-4 font-semibold text-gray-900">{{ $student->student_id_number }}</td>
                                        <td class="px-5 sm:px-6 py-4 text-gray-900">{{ $student->full_name }}</td>
                                        <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">{{ $student->course ?? '—' }} / {{ $student->year_level ?? '—' }} / {{ $student->section ?? '—' }}</td>
                                        <td class="px-5 sm:px-6 py-4"><span class="status-pill bg-gray-200 text-gray-700">archived</span></td>
                                        <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">{{ $student->archived_at ? $student->archived_at->format('M d, Y') : '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif
        </div>

        <!-- View Modal -->
        <div id="view-modal" class="fixed inset-0 z-50 hidden opacity-0 pointer-events-none items-center justify-center p-4 modal-backdrop transition-opacity duration-200 ease-out backdrop-blur-sm bg-slate-950/35">
            <div class="modal-panel w-full max-w-xl rounded-3xl bg-white shadow-2xl overflow-hidden transform scale-95 translate-y-3 opacity-0 transition-all duration-200 ease-out">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-display font-bold text-2xl text-gray-900">Student Details</h3>
                        <p class="text-sm text-gray-500">Managed SPC student record</p>
                    </div>
                    <button type="button" class="close-modal w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors"><i class="fas fa-xmark"></i></button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Student ID</p><p id="view-id" class="text-lg font-semibold text-gray-900"></p></div>
                        <div><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Full Name</p><p id="view-name" class="text-lg font-semibold text-gray-900"></p></div>
                        <div><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Course</p><p id="view-course" class="text-gray-700"></p></div>
                        <div><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Year/Section</p><p id="view-year-section" class="text-gray-700"></p></div>
                        <div><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Enrollment Status</p><p id="view-enrollment" class="text-gray-700"></p></div>
                        <div><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Module Access</p><p id="view-access" class="text-gray-700"></p></div>
                        <div class="sm:col-span-2"><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Current Activity</p><p id="view-activity" class="text-gray-700"></p></div>
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

    @include('shared.sweet-alerts.logout', ['logoutLabel' => $name, 'logoutSubtext' => 'Department Head session active', 'logoutDescription' => 'You are about to end your department head session. Review student records before leaving.', 'redirectUrl' => url('/')])

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        toggleBtn?.addEventListener('click', () => { sidebar.classList.toggle('collapsed'); });

        const searchInput = document.getElementById('search-input');
        const filterForm = document.getElementById('filter-form');

        let searchTimer = null;
        searchInput?.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => filterForm.submit(), 250);
        });

        document.querySelectorAll('select[name="enrollment_status"], select[name="activity_status"], select[name="section"]').forEach(select => {
            select.addEventListener('change', () => filterForm.submit());
        });

        const viewModal = document.getElementById('view-modal');
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

        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', () => closeModal(viewModal));
        });

        document.querySelectorAll('.view-student').forEach(button => {
            button.addEventListener('click', () => {
                const student = JSON.parse(button.dataset.student);
                document.getElementById('view-id').textContent = student.student_id_number;
                document.getElementById('view-name').textContent = student.full_name;
                document.getElementById('view-course').textContent = student.course || '—';
                document.getElementById('view-year-section').textContent = `${student.year_level || '—'} / ${student.section || '—'}`;
                document.getElementById('view-enrollment').textContent = student.enrollment_status;
                document.getElementById('view-access').textContent = student.module_access_status;
                document.getElementById('view-activity').textContent = student.current_activity_status;
                openModal(viewModal);
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeModal(viewModal);
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