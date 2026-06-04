@extends('Instructor.layout')

@section('title', 'Manage Students')
@section('pageTitle', 'Manage Students')
@section('pageSubtitle', 'Import and manage verified student records')
@section('headerActions')
    <div class="ml-auto flex items-center gap-3">
        <button type="button" id="open-add-student" class="inline-flex items-center gap-2 px-4 py-2 bg-violet-700 text-white text-xs font-bold uppercase rounded-lg hover:bg-violet-800 transition-all">
            <i class="fas fa-user-plus"></i> Add Student
        </button>
        <button type="button" id="open-bulk-upload" class="inline-flex items-center gap-2 px-4 py-2 bg-violet-700 text-white text-xs font-bold uppercase rounded-lg hover:bg-violet-800 transition-all">
            <i class="fas fa-upload"></i> Bulk Add Students
        </button>
    </div>
@endsection

@section('content')
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

        @if (!empty($summary))
            <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                <div class="glass-card rounded-2xl p-5">
                    <p class="text-[10px] uppercase tracking-[0.28em] text-gray-500 font-bold">Total Uploaded</p>
                    <p class="text-3xl font-display font-bold text-gray-900 mt-2">{{ $summary['total_uploaded'] ?? 0 }}</p>
                </div>
                <div class="glass-card rounded-2xl p-5">
                    <p class="text-[10px] uppercase tracking-[0.28em] text-gray-500 font-bold">Successfully Imported</p>
                    <p class="text-3xl font-display font-bold text-emerald-600 mt-2">{{ $summary['successfully_imported'] ?? 0 }}</p>
                </div>
                <div class="glass-card rounded-2xl p-5">
                    <p class="text-[10px] uppercase tracking-[0.28em] text-gray-500 font-bold">Duplicate Records</p>
                    <p class="text-3xl font-display font-bold text-amber-600 mt-2">{{ $summary['duplicate_records'] ?? 0 }}</p>
                </div>
                <div class="glass-card rounded-2xl p-5">
                    <p class="text-[10px] uppercase tracking-[0.28em] text-gray-500 font-bold">Invalid Entries</p>
                    <p class="text-3xl font-display font-bold text-red-600 mt-2">{{ $summary['invalid_entries'] ?? 0 }}</p>
                </div>
            </section>
        @endif

        <section class="glass-card rounded-3xl p-5 sm:p-6 mb-6">
            <form method="GET" action="{{ route('instructor.manage-students') }}" id="filter-form" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Live Search</label>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" id="search-input" placeholder="Search student ID or full name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                    <i class="fas fa-magnifying-glass absolute right-4 top-10 text-gray-400"></i>
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
                    <p class="text-sm text-gray-500">Manage verified enrolled SPC students and their training access.</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs">
                    <span class="chip bg-emerald-100 text-emerald-700"><i class="fas fa-circle-check"></i> Verified Enrolled</span>
                    <span class="chip bg-violet-100 text-violet-700"><i class="fas fa-bolt"></i> Ready for Training</span>
                    <span class="chip bg-cyan-100 text-cyan-700"><i class="fas fa-crosshairs"></i> Active in Firing Range</span>
                    <span class="chip bg-amber-100 text-amber-700"><i class="fas fa-flag-checkered"></i> Completed Session</span>
                    <span class="chip bg-slate-100 text-slate-700"><i class="fas fa-circle"></i> Inactive</span>
                    <span class="chip bg-gray-200 text-gray-700"><i class="fas fa-box-archive"></i> Archived</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[1100px]">
                    <thead class="bg-violet-950 text-xs text-violet-100 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 sm:px-6 py-4 font-semibold">Student ID</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold">Full Name</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold">Course/Year/Section</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold">Status</th>
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
                            <tr class="hover:bg-violet-50/50 transition-colors">
                                <td class="px-5 sm:px-6 py-4 font-semibold text-gray-900">{{ $studentId }}</td>
                                <td class="px-5 sm:px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $fullName }}</div>
                                    <div class="text-xs text-gray-500">{{ $course }}</div>
                                </td>
                                <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">{{ $course }} / {{ $yearLevel }} / {{ $section }}</td>
                                <td class="px-5 sm:px-6 py-4"><span class="status-pill {{ $enrollmentClass }}">{{ str_replace('_', ' ', $enrollmentStatus) }}</span></td>
                                <td class="px-5 sm:px-6 py-4"><span class="status-pill {{ $moduleClass }}">{{ str_replace('_', ' ', $moduleAccessStatus) }}</span></td>
                                <td class="px-5 sm:px-6 py-4"><span class="status-pill {{ $activityClass }}">{{ str_replace('_', ' ', $activityStatus) }}</span></td>
                                <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">{{ $dateAdded ? $dateAdded->format('M d, Y') : '—' }}</td>
                                <td class="px-5 sm:px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" class="view-student inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-colors" data-student='@json($studentPayload)'>
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <button type="button" class="edit-student inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-violet-100 text-violet-700 text-xs font-bold hover:bg-violet-200 transition-colors" data-student='@json($studentPayload)'>
                                            <i class="fas fa-pen-to-square"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('instructor.manage-students.archive', $student->id) }}" onsubmit="return confirm('Archive this student record?');">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-gray-200 text-gray-700 text-xs font-bold hover:bg-gray-300 transition-colors">
                                                <i class="fas fa-box-archive"></i> Archive
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center text-gray-500">
                                    <div class="max-w-md mx-auto">
                                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-2xl">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <p class="font-semibold text-gray-900 mb-2">No student records found</p>
                                        <p class="text-sm">Use Bulk Add Students to upload the official SPC enrolled student list.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 sm:px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-500">Showing {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} records</div>
                <div>{{ $students->links() }}</div>
            </div>
        </section>

        @if (!empty($showArchivedStudents) && $archivedStudents->isNotEmpty())
            <section class="glass-card rounded-3xl overflow-hidden mt-6">
                <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="font-display font-bold text-xl text-gray-900">Archived Students</h2>
                        <p class="text-sm text-gray-500">Stored records for completed or ineligible students. These rows remain in the database.</p>
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
                                <tr class="bg-gray-50/80">
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

    <!-- Bulk Upload Modal -->
    <div id="bulk-modal" class="fixed inset-0 z-50 hidden opacity-0 pointer-events-none items-center justify-center p-4 modal-backdrop transition-opacity duration-200 ease-out backdrop-blur-sm bg-slate-950/35">
        <div class="modal-panel w-full max-w-2xl rounded-3xl bg-white shadow-2xl overflow-hidden transform scale-95 translate-y-3 opacity-0 transition-all duration-200 ease-out">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-4">
                <div>
                    <h3 class="font-display font-bold text-2xl text-gray-900">Bulk Add Students</h3>
                    <p class="text-sm text-gray-500">Upload official SPC enrolled student lists in CSV or XLSX format.</p>
                </div>
                <button type="button" class="close-modal w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors"><i class="fas fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('instructor.manage-students.import') }}" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Upload file</label>
                        <input type="file" name="import_file" accept=".csv,.xlsx" class="block w-full text-sm border border-gray-200 rounded-2xl px-4 py-3 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400">
                        <p class="text-xs text-gray-500 mt-2">The system will skip invalid records and duplicate student IDs automatically.</p>
                    </div>
                    <div class="md:col-span-1 flex flex-col items-end gap-2">
                        <a href="{{ route('instructor.manage-students.template') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-white text-gray-700 border border-gray-200 text-sm font-bold hover:border-violet-300 hover:text-violet-700 transition-colors">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                        <p class="text-[10px] text-gray-400 text-right">Download a simplified CSV template. Course is fixed to BSCRIM and year level is fixed to 2nd year, so leave those out.</p>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button" class="close-modal px-4 py-3 rounded-xl bg-gray-100 text-gray-700 text-sm font-bold hover:bg-gray-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-3 rounded-xl bg-violet-700 text-white text-sm font-bold hover:bg-violet-800 transition-colors">Import Students</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div id="add-modal" class="fixed inset-0 z-50 hidden opacity-0 pointer-events-none items-center justify-center p-4 modal-backdrop transition-opacity duration-200 ease-out backdrop-blur-sm bg-slate-950/35">
        <div class="modal-panel w-full max-w-3xl rounded-3xl bg-white shadow-2xl overflow-hidden transform scale-95 translate-y-3 opacity-0 transition-all duration-200 ease-out">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-4">
                <div>
                    <h3 class="font-display font-bold text-2xl text-gray-900">Add Student</h3>
                    <p class="text-sm text-gray-500">Create a managed student account with a default password.</p>
                </div>
                <button type="button" class="close-modal w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors"><i class="fas fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('instructor.manage-students.store') }}" class="p-6 space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Student ID</label>
                        <input type="text" name="student_id_number" value="{{ old('student_id_number') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Password</label>
                        <input type="text" name="password" value="{{ old('password', 'Password123!') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                        <p class="text-xs text-gray-500 mt-2">Default password is prefilled, but you can change it before saving.</p>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button" class="close-modal px-4 py-3 rounded-xl bg-gray-100 text-gray-700 text-sm font-bold hover:bg-gray-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-3 rounded-xl bg-violet-700 text-white text-sm font-bold hover:bg-violet-800 transition-colors">Save Student</button>
                </div>
            </form>
        </div>
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

    <!-- Edit Modal -->
    <div id="edit-modal" class="fixed inset-0 z-50 hidden opacity-0 pointer-events-none items-center justify-center p-4 modal-backdrop transition-opacity duration-200 ease-out backdrop-blur-sm bg-slate-950/35">
        <div class="modal-panel w-full max-w-2xl rounded-3xl bg-white shadow-2xl overflow-hidden transform scale-95 translate-y-3 opacity-0 transition-all duration-200 ease-out">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-4">
                <div>
                    <h3 class="font-display font-bold text-2xl text-gray-900">Edit Student</h3>
                    <p class="text-sm text-gray-500">Update the managed student record</p>
                </div>
                <button type="button" class="close-modal w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors"><i class="fas fa-xmark"></i></button>
            </div>
            <form id="edit-form" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" name="student_id" id="edit-id">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Full Name</label>
                        <input type="text" name="full_name" id="edit-full-name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Course</label>
                        <input type="text" name="course" id="edit-course" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Year Level</label>
                        <input type="text" name="year_level" id="edit-year-level" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Section</label>
                        <input type="text" name="section" id="edit-section" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Enrollment Status</label>
                        <select name="enrollment_status" id="edit-enrollment-status" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                            <option value="verified_enrolled">Verified Enrolled</option>
                            <option value="ready_for_training">Ready for Training</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Module Access Status</label>
                        <select name="module_access_status" id="edit-module-access-status" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                            <option value="ready_for_training">Ready for Training</option>
                            <option value="locked">Locked</option>
                            <option value="active_in_firing_range">Active in Firing Range</option>
                            <option value="completed_session">Completed Session</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Current Activity Status</label>
                        <select name="current_activity_status" id="edit-activity-status" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                            <option value="inactive">Inactive</option>
                            <option value="active_in_firing_range">Active in Firing Range</option>
                            <option value="active_in_assembly">Active in Assembly</option>
                            <option value="completed_session">Completed Session</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" class="close-modal px-4 py-3 rounded-xl bg-gray-100 text-gray-700 text-sm font-bold hover:bg-gray-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-3 rounded-xl bg-violet-700 text-white text-sm font-bold hover:bg-violet-800 transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const openAddStudent = document.getElementById('open-add-student');
        const addModal = document.getElementById('add-modal');
        const openBulkUpload = document.getElementById('open-bulk-upload');
        const bulkModal = document.getElementById('bulk-modal');
        const viewModal = document.getElementById('view-modal');
        const editModal = document.getElementById('edit-modal');
        const searchInput = document.getElementById('search-input');
        const filterForm = document.getElementById('filter-form');
        const editForm = document.getElementById('edit-form');
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

        openAddStudent?.addEventListener('click', () => openModal(addModal));
        openBulkUpload?.addEventListener('click', () => openModal(bulkModal));
        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', () => {
                closeModal(addModal);
                closeModal(bulkModal);
                closeModal(viewModal);
                closeModal(editModal);
            });
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

        document.querySelectorAll('.edit-student').forEach(button => {
            button.addEventListener('click', () => {
                const student = JSON.parse(button.dataset.student);
                editForm.action = `{{ url('/instructor/manage-students') }}/${student.id}`;
                document.getElementById('edit-id').value = student.id;
                document.getElementById('edit-full-name').value = student.full_name;
                document.getElementById('edit-course').value = student.course || '';
                document.getElementById('edit-year-level').value = student.year_level || '';
                document.getElementById('edit-section').value = student.section || '';
                document.getElementById('edit-enrollment-status').value = student.enrollment_status;
                document.getElementById('edit-module-access-status').value = student.module_access_status;
                document.getElementById('edit-activity-status').value = student.current_activity_status;
                openModal(editModal);
            });
        });

        let searchTimer = null;
        searchInput?.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => filterForm.submit(), 250);
        });

        document.querySelectorAll('select[name="enrollment_status"], select[name="activity_status"]').forEach(select => {
            select.addEventListener('change', () => filterForm.submit());
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal(addModal);
                closeModal(bulkModal);
                closeModal(viewModal);
                closeModal(editModal);
            }
        });
    </script>
@endsection
