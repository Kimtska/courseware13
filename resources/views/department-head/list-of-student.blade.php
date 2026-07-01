<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IOT-Based Marksmanship - List of Students</title>
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
                    <div class="flex items-end">
                        <a href="{{ route('department-head.manage-students') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-600 text-sm font-bold hover:bg-gray-50 transition-colors text-center">
                            <i class="fas fa-rotate-left mr-1"></i> Clear
                        </a>
                    </div>
                </form>
            </section>

            <section class="glass-card rounded-3xl overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="flex items-center gap-6">
                        <div>
                            <h2 class="font-display font-bold text-xl text-gray-900">Student Management Table</h2>
                            <p class="text-sm text-gray-500">Read-only view of all verified enrolled SPC student records.</p>
                        </div>
                        <div class="flex items-center gap-1 bg-gray-100 rounded-xl p-1">
                            <button type="button" class="tab-btn px-4 py-2 text-xs font-bold rounded-lg transition-all bg-white text-violet-700 shadow-sm" data-tab="all">Recent</button>
                            <button type="button" class="tab-btn px-4 py-2 text-xs font-bold rounded-lg transition-all text-gray-600 hover:text-violet-700" data-tab="aging">
                                5 Months Old
                                @if ($fiveMonthOld->isNotEmpty())
                                    <span class="ml-1.5 inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold">{{ $fiveMonthOld->count() }}</span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>

                <div id="tab-all" class="tab-content active">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[950px]">
                            <thead class="bg-violet-950 text-xs text-violet-100 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Student ID</th>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Full Name</th>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Section</th>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Current Progress</th>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Date Added</th>
                                    <th class="px-5 sm:px-6 py-4 text-right font-semibold">Action Buttons</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($students as $student)
                                    @php
                                        $studentId = $student->student_id_number ?? '';
                                        $fullName = $student->full_name;
                                        $section = $student->section ?? '—';
                                        $dateAdded = $student->created_at ?? null;
                                        $progress = $student->current_progress;
                                        $currentModule = $progress['module_key'] ?? $student->latestModule?->module_key;
                                        $currentLesson = $progress['lesson_key'] ?? null;
                                        $currentPage = $progress['page_index'] ?? null;
                                        $totalPages = $progress['total_pages'] ?? null;
                                        if ($currentModule) {
                                            $progressTrack = ucwords(str_replace(['module-', '_'], ['Module ', ' '], $currentModule));
                                            if ($currentLesson) {
                                                $progressTrack .= ' - ' . str_replace('_', ' ', $currentLesson);
                                                if ($currentPage !== null) {
                                                    $progressTrack .= ' (Page ' . ($currentPage + 1) . ($totalPages ? ' of ' . $totalPages : '') . ')';
                                                }
                                            }
                                        } else {
                                            $progressTrack = '—';
                                        }

                                        $progressClass = $currentModule ? 'bg-violet-100 text-violet-700' : 'bg-slate-100 text-slate-700';
                                        $studentPayload = [
                                            'id' => $student->id,
                                            'student_id_number' => $studentId,
                                            'full_name' => $fullName,
                                            'section' => $section,
                                            'progress' => $progressTrack,
                                            'date_added' => $dateAdded ? $dateAdded->format('Y-m-d H:i') : null,
                                        ];
                                    @endphp
                                    <tr class="even:bg-gray-50/60 hover:bg-violet-50/50 transition-colors">
                                        <td class="px-5 sm:px-6 py-4 font-semibold text-gray-900">{{ $studentId }}</td>
                                        <td class="px-5 sm:px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $fullName }}</div>
                                        </td>
                                        <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">{{ $section }}</td>
                                        <td class="px-5 sm:px-6 py-4"><span class="status-pill {{ $progressClass }}">{{ $progressTrack }}</span></td>
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
                                        <td colspan="6" class="px-6 py-16 text-center text-gray-500">
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
                </div>

                <div id="tab-aging" class="tab-content hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[950px]">
                            <thead class="bg-amber-900 text-xs text-amber-100 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Student ID</th>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Full Name</th>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Section</th>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Current Progress</th>
                                    <th class="px-5 sm:px-6 py-4 font-semibold">Archived At</th>
                                    <th class="px-5 sm:px-6 py-4 text-right font-semibold">Action Buttons</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($fiveMonthOld as $student)
                                    @php
                                        $sid = $student->student_id_number ?? '';
                                        $name = $student->full_name ?? '';
                                        $sec = $student->section ?? '—';
                                        $moved = $student->archived_at;
                                        $progress = $student->current_progress;
                                        $currentModule = $progress['module_key'] ?? $student->latestModule?->module_key;
                                        $currentLesson = $progress['lesson_key'] ?? null;
                                        $currentPage = $progress['page_index'] ?? null;
                                        $totalPages = $progress['total_pages'] ?? null;
                                        if ($currentModule) {
                                            $progressTrack = ucwords(str_replace(['module-', '_'], ['Module ', ' '], $currentModule));
                                            if ($currentLesson) {
                                                $progressTrack .= ' - ' . str_replace('_', ' ', $currentLesson);
                                                if ($currentPage !== null) {
                                                    $progressTrack .= ' (Page ' . ($currentPage + 1) . ($totalPages ? ' of ' . $totalPages : '') . ')';
                                                }
                                            }
                                        } else {
                                            $progressTrack = '—';
                                        }
                                        $progressClass = $currentModule ? 'bg-violet-100 text-violet-700' : 'bg-gray-200 text-gray-700';

                                        $studentPayload = [
                                            'id' => $student->id,
                                            'student_id_number' => $sid,
                                            'full_name' => $name,
                                            'section' => $sec,
                                            'status' => 'Archived',
                                            'progress' => $progressTrack,
                                            'date_added' => $moved ? $moved->format('Y-m-d H:i') : null,
                                        ];
                                    @endphp
                                    <tr class="hover:bg-amber-50/50 transition-colors">
                                        <td class="px-5 sm:px-6 py-4 font-semibold text-gray-900">{{ $sid }}</td>
                                        <td class="px-5 sm:px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $name }}</div>
                                        </td>
                                        <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">{{ $sec }}</td>
                                        <td class="px-5 sm:px-6 py-4"><span class="status-pill {{ $progressClass }}">{{ $progressTrack }}</span></td>
                                        <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">{{ $moved ? $moved->format('M d, Y') : '—' }}</td>
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
                                        <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                            <div class="max-w-md mx-auto">
                                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <p class="font-semibold text-gray-900 mb-2">No old student records</p>
                                                <p class="text-sm">Students are automatically moved here once their account reaches 5 months old.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 sm:px-6 py-4 border-t border-gray-100">
                        <div class="text-sm text-gray-500">{{ $fiveMonthOld->count() }} student(s) with accounts around the 5-month mark</div>
                    </div>
                </div>
            </section>
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
                        <div><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Section</p><p id="view-section" class="text-gray-700"></p></div>
                        <div><p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Current Progress</p><p id="view-progress" class="text-gray-700"></p></div>
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

    @include('shared.sweet-alerts.logout', ['logoutLabel' => $name, 'logoutSubtext' => 'Department Head session active', 'logoutDescription' => 'You are about to end your department head session. Review student records before leaving.', 'redirectUrl' => url('/login')])

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

        document.querySelectorAll('select[name="activity_status"], select[name="section"]').forEach(select => {
            select.addEventListener('change', () => filterForm.submit());
        });

        const tabBtns = document.querySelectorAll('.tab-btn');
        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                tabBtns.forEach(b => {
                    b.classList.remove('bg-white', 'text-violet-700', 'shadow-sm');
                    b.classList.add('text-gray-600', 'hover:text-violet-700');
                });
                btn.classList.remove('text-gray-600', 'hover:text-violet-700');
                btn.classList.add('bg-white', 'text-violet-700', 'shadow-sm');
                document.querySelectorAll('.tab-content').forEach(tc => tc.classList.add('hidden'));
                const tabId = btn.dataset.tab;
                document.getElementById('tab-' + tabId).classList.remove('hidden');
            });
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
                document.getElementById('view-section').textContent = student.section || '—';
                document.getElementById('view-progress').textContent = student.progress || '—';
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