@extends('Instructor.layout')

@section('title', 'Manage Marksmanship')
@section('pageTitle', 'Manage Marksmanship')
@section('pageSubtitle', 'Students ready to proceed to the firing range after completing the gun-parts lesson')

@section('headerActions')@endsection

@section('content')
    <div class="grid gap-6">

 <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-2xl bg-amber-50 border border-amber-200 px-4 py-3 relative">
                    <div class="flex items-start gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                            <i class="fas fa-triangle-exclamation text-sm"></i>
                        </span>
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-amber-700 font-bold">Instructor Action Required</p>
                            <p class="text-sm font-semibold text-amber-900 mt-0.5">You must set up the marksmanship page first</p>
                            <p class="text-xs text-amber-800 mt-0.5">Configure the timer, firearm, and target type before sending any student to the firing range. Changes apply to all Proceed links below.</p>
                        </div>
                    </div>
                    <div class="relative flex items-center">
                        <div class="glow-arrow hidden sm:block"></div>
                        <button type="button" onclick="openMarksmanshipSettings()" id="marksmanship-settings-btn" class="ms-setup-btn inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-violet-900/30 transition-all whitespace-nowrap relative z-10">
                            <span class="flex h-7 w-7 items-center justify-center rounded-xl bg-white/20 text-white">
                                <i class="fas fa-sliders text-xs"></i>
                            </span>
                            <span>Marksmanship Setup</span>
                            <span class="hidden md:inline text-[10px] font-semibold uppercase tracking-wider text-violet-100">Timer &middot; Firearm &middot; Target</span>
                            <i class="fas fa-chevron-right text-[10px] text-violet-200"></i>
                        </button>
                    </div>
                </div>

        <section class="glass-card rounded-3xl p-5 sm:p-6 mb-6">
            <form id="mm-filter-form" class="grid grid-cols-1 md:grid-cols-3 gap-4" onsubmit="return false;">
                <div class="relative">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Live Search</label>
                    <input type="text" name="q" id="mm-search" placeholder="Search student ID or full name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                    <i class="fas fa-magnifying-glass absolute right-4 top-10 text-gray-400"></i>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Section</label>
                    <select name="section" id="mm-filter-section" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                        <option value="">All Sections</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Course</label>
                    <select name="course" id="mm-filter-course" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                        <option value="">All Courses</option>
                    </select>
                </div>
            </form>
        </section>

        <section class="glass-card rounded-3xl overflow-hidden bg-white border border-gray-200">
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex flex-col gap-3">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h2 class="font-display font-bold text-xl text-gray-900">Ready to Proceed</h2>
                        <p class="text-sm text-gray-500">Review students who have completed the presentation before sending them to the firing range.</p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="chip bg-emerald-100 text-emerald-700"><i class="fas fa-circle-check"></i> Completed Gun Parts</span>
                        <span class="chip bg-violet-100 text-violet-700"><i class="fas fa-arrow-right"></i> Proceed to Firing Range</span>
                    </div>
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
                            <th class="px-5 sm:px-6 py-4 text-right font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($students as $student)
                            @php
                                $studentId = $student->student_id_number ?? $student->student_number ?? '';
                                $fullName = $student->full_name ?? trim((($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? '')));
                                $course = $student->course ?? '—';
                                $yearLevel = $student->year_level ?? '—';
                                $section = $student->section ?? '—';
                            @endphp
                            <tr class="hover:bg-violet-50/50 transition-colors" data-search="{{ strtolower($studentId . ' ' . $fullName) }}" data-section="{{ $section }}" data-course="{{ $course }}">
                                <td class="px-5 sm:px-6 py-4 font-semibold text-gray-900">{{ $studentId }}</td>
                                <td class="px-5 sm:px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $fullName }}</div>
                                    <div class="text-xs text-gray-500">{{ $course }}</div>
                                </td>
                                <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">{{ $course }} / {{ $yearLevel }} / {{ $section }}</td>
                                <td class="px-5 sm:px-6 py-4"><span class="status-pill bg-emerald-100 text-emerald-700">Completed Gun Parts</span></td>
                                <td class="px-5 sm:px-6 py-4 text-right">
                                    <a href="{{ route('instructor.manage-module.module-4') }}?weapon=9mm&time=60&mode=steady&student_id={{ urlencode($studentId) }}" target="_blank" rel="noopener" data-proceed-link data-student-id="{{ $studentId }}" class="proceed-btn inline-flex items-center justify-center gap-2 rounded-lg bg-violet-700 px-4 py-2 text-xs font-bold text-white hover:bg-violet-800 transition-colors">
                                        <i class="fas fa-arrow-right"></i>
                                        Proceed
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr class="hover:bg-violet-50/50 transition-colors" data-search="20260001 juan dela cruz" data-section="A" data-course="BSCRIM">
                                <td class="px-5 sm:px-6 py-4 font-semibold text-gray-900">20260001</td>
                                <td class="px-5 sm:px-6 py-4">
                                    <div class="font-medium text-gray-900">Juan Dela Cruz</div>
                                    <div class="text-xs text-gray-500">BSCRIM</div>
                                </td>
                                <td class="px-5 sm:px-6 py-4 text-sm text-gray-600">BSCRIM / 2 / A</td>
                                <td class="px-5 sm:px-6 py-4"><span class="status-pill bg-emerald-100 text-emerald-700">Completed Gun Parts</span></td>
                                <td class="px-5 sm:px-6 py-4 text-right">
                                    <a href="{{ route('instructor.manage-module.module-4') }}?weapon=9mm&time=60&mode=steady&student_id=20260001" target="_blank" rel="noopener" data-proceed-link data-student-id="20260001" class="proceed-btn inline-flex items-center justify-center gap-2 rounded-lg bg-violet-700 px-4 py-2 text-xs font-bold text-white hover:bg-violet-800 transition-colors">
                                        <i class="fas fa-arrow-right"></i>
                                        Proceed
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <div class="max-w-md mx-auto">
                                        <p class="font-semibold text-gray-900 mb-2">Sample student displayed for demo purposes</p>
                                        <p class="text-sm">This student represents a learner who has completed the gun-parts lesson and is ready to proceed to the firing range.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 sm:px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-500" id="mm-summary-text">{{ $students->isEmpty() ? 'Showing a sample student who has completed the lessons.' : sprintf('Showing %s - %s of %s completed students.', $students->firstItem() ?? 0, $students->lastItem() ?? 0, $students->total()) }}</div>
                <div>{{ $students->links('vendor.pagination.violet') }}</div>
            </div>
        </section>
    </div>

    <!-- ==================== MARKSMANSHIP SETTINGS MODAL ==================== -->
    <div id="marksmanship-settings-overlay" class="ms-overlay" role="dialog" aria-modal="true" aria-labelledby="ms-title" onclick="if (event.target === this) closeMarksmanshipSettings()">
        <div class="ms-modal w-full mx-4" style="max-width:800px">
            <div class="menu-hero px-6 sm:px-8 py-5 text-left flex items-start justify-between gap-4">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.28em] text-violet-200 font-bold">Marksmanship Place</p>
                    <h1 id="ms-title" class="font-display font-bold text-xl md:text-2xl mt-1">Firing Range Setup</h1>
                    <p class="text-violet-100 text-[11px] mt-1">Configure the timer, firearm, and target type for the marksmanship drill.</p>
                </div>
                <button type="button" onclick="closeMarksmanshipSettings()" class="text-violet-200 hover:text-white transition-colors p-2" aria-label="Close">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-5 sm:p-6 text-left">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Time Limit -->
                    <div>
                        <h3 class="text-[11px] text-violet-700 uppercase tracking-widest font-bold mb-2 flex items-center gap-2"><i class="fas fa-clock text-violet-500"></i> Time Limit</h3>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="ms-card" data-time="30" onclick="selectMarksmanshipTime(30, this)">
                                <div class="flex flex-col items-center gap-1 text-center">
                                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-clock text-xs"></i></div>
                                    <p class="font-bold text-gray-900 text-sm">30s</p>
                                </div>
                            </div>
                            <div class="ms-card" data-time="60" onclick="selectMarksmanshipTime(60, this)">
                                <div class="flex flex-col items-center gap-1 text-center">
                                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-clock text-xs"></i></div>
                                    <p class="font-bold text-gray-900 text-sm">60s</p>
                                </div>
                            </div>
                            <div class="ms-card" data-time="90" onclick="selectMarksmanshipTime(90, this)">
                                <div class="flex flex-col items-center gap-1 text-center">
                                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-clock text-xs"></i></div>
                                    <p class="font-bold text-gray-900 text-sm">90s</p>
                                </div>
                            </div>
                            <div class="ms-card" data-time="120" onclick="selectMarksmanshipTime(120, this)">
                                <div class="flex flex-col items-center gap-1 text-center">
                                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-clock text-xs"></i></div>
                                    <p class="font-bold text-gray-900 text-sm">120s</p>
                                </div>
                            </div>
                            <div class="ms-card" data-time="custom" onclick="focusMarksmanshipCustomTime(this)">
                                <div class="flex flex-col items-center gap-1 text-center">
                                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-pen text-xs"></i></div>
                                    <p class="font-bold text-gray-900 text-sm">Custom</p>
                                </div>
                            </div>
                        </div>
                        <div id="ms-custom-input-wrap" class="hidden mt-2">
                            <div class="flex items-center gap-2">
                                <p class="text-xs text-gray-500 font-semibold">Custom time:</p>
                                <input id="marksmanship-custom-time" type="number" min="5" max="999" placeholder="seconds" class="w-24 rounded-lg border border-violet-200 bg-white px-2 py-1.5 text-center text-xs font-bold text-gray-900 outline-none focus:border-violet-400">
                                <span class="text-xs text-gray-400">sec</span>
                            </div>
                        </div>
                    </div>

                    <!-- Firearm Selection -->
                    <div>
                        <h3 class="text-[11px] text-violet-700 uppercase tracking-widest font-bold mb-2 flex items-center gap-2"><i class="fas fa-gun text-violet-500"></i> Firearm</h3>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="ms-card" data-weapon="9mm" onclick="selectMarksmanshipWeapon('9mm', this)">
                                <div class="flex flex-col items-center gap-1 text-center">
                                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-gun text-xs"></i></div>
                                    <div>
                                        <h4 class="font-display font-bold text-gray-900 text-sm leading-tight">9mm Pistol</h4>
                                        <p class="text-[10px] text-gray-500 leading-tight">Mag 15 &middot; Reserve 45</p>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-card" data-weapon=".45" onclick="selectMarksmanshipWeapon('.45', this)">
                                <div class="flex flex-col items-center gap-1 text-center">
                                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-gun text-xs"></i></div>
                                    <div>
                                        <h4 class="font-display font-bold text-gray-900 text-sm leading-tight">.45 Caliber</h4>
                                        <p class="text-[10px] text-gray-500 leading-tight">Mag 7 &middot; Reserve 28</p>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-card" data-weapon="all" onclick="selectMarksmanshipWeapon('all', this)">
                                <div class="flex flex-col items-center gap-1 text-center">
                                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-layer-group text-xs"></i></div>
                                    <div>
                                        <h4 class="font-display font-bold text-gray-900 text-sm leading-tight">All</h4>
                                        <p class="text-[10px] text-gray-500 leading-tight">9mm &rarr; .45 (cycle)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Target Type -->
                    <div>
                        <h3 class="text-[11px] text-violet-700 uppercase tracking-widest font-bold mb-2 flex items-center gap-2"><i class="fas fa-bullseye text-violet-500"></i> Target Type</h3>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="ms-card" data-mode="steady" onclick="selectMarksmanshipMode('steady', this)">
                                <div class="flex flex-col items-center gap-1 text-center">
                                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-bullseye text-xs"></i></div>
                                    <div>
                                        <h4 class="font-display font-bold text-gray-900 text-sm leading-tight">Steady</h4>
                                        <p class="text-[10px] text-gray-500 leading-tight">Center target. Pure accuracy.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-card" data-mode="sideways" onclick="selectMarksmanshipMode('sideways', this)">
                                <div class="flex flex-col items-center gap-1 text-center">
                                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-arrows-alt-h text-xs"></i></div>
                                    <div>
                                        <h4 class="font-display font-bold text-gray-900 text-sm leading-tight">Sideways</h4>
                                        <p class="text-[10px] text-gray-500 leading-tight">Horizontal tracking.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-card" data-mode="all" onclick="selectMarksmanshipMode('all', this)">
                                <div class="flex flex-col items-center gap-1 text-center">
                                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-layer-group text-xs"></i></div>
                                    <div>
                                        <h4 class="font-display font-bold text-gray-900 text-sm leading-tight">All</h4>
                                        <p class="text-[10px] text-gray-500 leading-tight">Steady &rarr; Sideways (cycle)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4 pt-4 border-t border-gray-100">
                    <div class="text-xs text-gray-500">
                        Active: <span id="ms-summary-time" class="font-semibold text-gray-700">60s</span> &middot; <span id="ms-summary-weapon" class="font-semibold text-gray-700">9mm Pistol</span> &middot; <span id="ms-summary-mode" class="font-semibold text-gray-700">Steady</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="resetMarksmanshipSettings()" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-rotate-left"></i> Reset
                        </button>
                        <button type="button" onclick="saveMarksmanshipSettings()" class="ms-save-btn inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2 text-xs font-bold text-white shadow-lg shadow-violet-900/20 transition-colors">
                            <i class="fas fa-check"></i> Apply Setup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== SUCCESS TOAST ==================== -->
    <div id="ms-toast" class="ms-toast" role="status" aria-live="polite">
        <div class="ms-toast-icon"><i class="fas fa-check"></i></div>
        <div class="ms-toast-content">
            <p class="ms-toast-title">Setup applied successfully</p>
            <p class="ms-toast-detail" id="ms-toast-detail">Settings saved and ready to use.</p>
        </div>
        <button type="button" class="ms-toast-close" onclick="hideMarksmanshipToast()" aria-label="Dismiss">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <style>
        .ms-overlay{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(3,3,7,0.6);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);opacity:0;visibility:hidden;transition:opacity .3s ease,visibility .3s ease;padding:16px;overflow-y:auto}
        .ms-overlay.active{opacity:1;visibility:visible}
        .ms-overlay.closing{opacity:0;visibility:visible;transition:opacity .25s ease,visibility .25s ease}
        .ms-modal{background:#fff;border:1px solid #e9d5ff;border-radius:24px;box-shadow:0 25px 60px -12px rgba(30,5,82,0.35),0 0 0 1px rgba(124,58,237,0.08);overflow:hidden;transform:scale(0.85) translateY(20px);opacity:0;transition:transform .35s cubic-bezier(0.34,1.56,0.64,1),opacity .3s ease;max-height:calc(100vh - 32px);overflow-y:auto}
        .ms-overlay.active .ms-modal{transform:scale(1) translateY(0);opacity:1}
        .ms-overlay.closing .ms-modal{transform:scale(0.9) translateY(10px);opacity:0;transition:transform .25s ease,opacity .25s ease}
        .ms-card{border:2px solid #e5e7eb;background:#ffffff;border-radius:12px;padding:10px;cursor:pointer;transition:all 0.2s;box-shadow:0 1px 2px rgba(15,23,42,0.04)}
        .ms-card:hover{border-color:#c4b5fd;background:#f8f5ff}
        .ms-card.active{border-color:#7C3AED;background:#f3e8ff;box-shadow:0 0 0 3px rgba(124,58,237,0.12)}
        .menu-shell{background:#fff;border:1px solid #e9d5ff;border-radius:24px;box-shadow:0 25px 60px -12px rgba(30,5,82,0.18);overflow:hidden}
        .menu-hero{background:linear-gradient(135deg,#1E0552,#5B21B6);color:#fff}
        .ms-save-btn{background:linear-gradient(135deg,#5B21B6,#7C3AED)}
        .ms-save-btn:hover{background:linear-gradient(135deg,#4c1d95,#6D28D9)}
        .ms-setup-btn{background:linear-gradient(135deg,#5B21B6,#7C3AED);border:1px solid rgba(255,255,255,0.08)}
        .ms-setup-btn:hover{background:linear-gradient(135deg,#4c1d95,#6D28D9);transform:translateY(-1px)}
        .ms-setup-btn:active{transform:translateY(0)}
        #marksmanship-settings-btn .fa-chevron-right{transition:transform 0.2s}
        #marksmanship-settings-btn:hover .fa-chevron-right{transform:translateX(2px)}
        .ms-toast{position:fixed;top:24px;right:24px;z-index:10000;display:flex;align-items:center;gap:12px;background:#fff;border:1px solid #e9d5ff;border-radius:16px;padding:14px 16px;box-shadow:0 18px 40px -12px rgba(30,5,82,0.28),0 0 0 1px rgba(124,58,237,0.08);min-width:280px;max-width:360px;opacity:0;transform:translateX(120%) scale(0.9);transition:opacity .3s ease,transform .35s cubic-bezier(0.34,1.56,0.64,1);pointer-events:none}
        .ms-toast.show{opacity:1;transform:translateX(0) scale(1);pointer-events:auto}
        .ms-toast-icon{flex-shrink:0;width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 10px -3px rgba(34,197,94,0.45)}
        .ms-toast-icon i{font-size:14px}
        .ms-toast-content{flex:1;min-width:0}
        .ms-toast-title{font-family:'Inter',sans-serif;font-weight:700;font-size:13px;color:#0f172a;margin:0;line-height:1.3}
        .ms-toast-detail{font-size:11px;color:#64748b;margin:2px 0 0;line-height:1.4}
        .ms-toast-close{flex-shrink:0;background:transparent;border:none;color:#94a3b8;cursor:pointer;padding:4px;border-radius:6px;transition:color .15s,background .15s}
        .ms-toast-close:hover{color:#475569;background:#f1f5f9}
        .glow-arrow{position:absolute;right:100%;top:50%;transform:translateY(-50%);margin-right:8px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;pointer-events:none}
        .glow-arrow::before{content:'\f178';font-family:'Font Awesome 6 Free';font-weight:900;font-size:22px;color:#7C3AED;text-shadow:0 0 8px rgba(124,58,237,.6),0 0 20px rgba(124,58,237,.4),0 0 40px rgba(124,58,237,.2);animation:glowPulse 1.2s ease-in-out infinite}
        @keyframes glowPulse{0%,100%{opacity:.7;transform:translateX(0);text-shadow:0 0 8px rgba(124,58,237,.6),0 0 20px rgba(124,58,237,.4),0 0 40px rgba(124,58,237,.2)}50%{opacity:1;transform:translateX(4px);text-shadow:0 0 12px rgba(124,58,237,.9),0 0 30px rgba(124,58,237,.6),0 0 60px rgba(124,58,237,.3)}}
        @media(prefers-reduced-motion:reduce){.ms-modal,.ms-overlay,.ms-toast{transition:none !important}.glow-arrow::before{animation:none}}
    </style>

    <script>
        const MS_STORAGE_KEY = 'marksmanshipSetup';
        const MS_DEFAULTS = { time: 60, weapon: '9mm', mode: 'steady' };
        const MS_ALLOWED_TIMES = [30, 60, 90, 120];
        const MS_ALLOWED_WEAPONS = ['9mm', '.45', 'all'];
        const MS_ALLOWED_MODES = ['steady', 'sideways', 'all'];
        const MS_WEAPON_CYCLE = { all: ['9mm', '.45'] };
        const MS_MODE_CYCLE = { all: ['steady', 'sideways'] };
        const MS_WEAPON_LABELS = { '9mm': '9mm Pistol', '.45': '.45 Caliber', 'all': 'All (9mm → .45)' };
        const MS_MODE_LABELS = { steady: 'Steady', sideways: 'Sideways', all: 'All (Steady → Sideways)' };

        let msState = { ...MS_DEFAULTS };
        let msIsOpen = false;
        let msCloseTimer = null;
        let msToastTimer = null;

        function loadMarksmanshipSettings() {
            try {
                const raw = localStorage.getItem(MS_STORAGE_KEY);
                if (!raw) return { ...MS_DEFAULTS };
                const parsed = JSON.parse(raw);
                return {
                    time: MS_ALLOWED_TIMES.includes(parsed.time) || (Number.isInteger(parsed.time) && parsed.time >= 5 && parsed.time <= 999) ? parsed.time : MS_DEFAULTS.time,
                    weapon: MS_ALLOWED_WEAPONS.includes(parsed.weapon) ? parsed.weapon : MS_DEFAULTS.weapon,
                    mode: MS_ALLOWED_MODES.includes(parsed.mode) ? parsed.mode : MS_DEFAULTS.mode
                };
            } catch (err) {
                return { ...MS_DEFAULTS };
            }
        }

        function saveMarksmanshipSettingsStorage() {
            try { localStorage.setItem(MS_STORAGE_KEY, JSON.stringify(msState)); } catch (err) {}
        }

        function openMarksmanshipSettings() {
            if (msIsOpen) return;
            const overlay = document.getElementById('marksmanship-settings-overlay');
            if (msCloseTimer) { clearTimeout(msCloseTimer); msCloseTimer = null; }
            msState = loadMarksmanshipSettings();
            applyMarksmanshipSelection();
            updateMarksmanshipSummary();
            overlay.classList.remove('closing');
            overlay.classList.add('active');
            msIsOpen = true;
        }

        function closeMarksmanshipSettings() {
            if (!msIsOpen) return;
            const overlay = document.getElementById('marksmanship-settings-overlay');
            overlay.classList.add('closing');
            overlay.classList.remove('active');
            msIsOpen = false;
            if (msCloseTimer) clearTimeout(msCloseTimer);
            msCloseTimer = setTimeout(() => {
                overlay.classList.remove('closing');
                msCloseTimer = null;
            }, 300);
        }

        function selectMarksmanshipTime(time, element) {
            if (time === 'custom') {
                focusMarksmanshipCustomTime(element);
                return;
            }
            msState.time = time;
            const ci = document.getElementById('marksmanship-custom-time');
            if (ci) ci.value = '';
            document.getElementById('ms-custom-input-wrap')?.classList.add('hidden');
            updateMarksmanshipSummary();
            document.querySelectorAll('#marksmanship-settings-overlay [data-time]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        function focusMarksmanshipCustomTime(element) {
            document.getElementById('ms-custom-input-wrap')?.classList.remove('hidden');
            const ci = document.getElementById('marksmanship-custom-time');
            if (ci) { ci.focus(); ci.select(); }
            document.querySelectorAll('#marksmanship-settings-overlay [data-time]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        function selectMarksmanshipWeapon(weapon, element) {
            msState.weapon = weapon;
            updateMarksmanshipSummary();
            document.querySelectorAll('#marksmanship-settings-overlay [data-weapon]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        function selectMarksmanshipMode(mode, element) {
            msState.mode = mode;
            updateMarksmanshipSummary();
            document.querySelectorAll('#marksmanship-settings-overlay [data-mode]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        function applyMarksmanshipSelection() {
            document.querySelectorAll('#marksmanship-settings-overlay [data-time]').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('#marksmanship-settings-overlay [data-weapon]').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('#marksmanship-settings-overlay [data-mode]').forEach(el => el.classList.remove('active'));

            const ci = document.getElementById('marksmanship-custom-time');
            const cw = document.getElementById('ms-custom-input-wrap');
            if (ci) ci.value = '';
            if (cw) cw.classList.add('hidden');

            if (MS_ALLOWED_TIMES.includes(msState.time)) {
                const el = document.querySelector('#marksmanship-settings-overlay [data-time="' + msState.time + '"]');
                if (el) el.classList.add('active');
            } else {
                const el = document.querySelector('#marksmanship-settings-overlay [data-time="custom"]');
                if (el) el.classList.add('active');
                if (ci) ci.value = msState.time;
                if (cw) cw.classList.remove('hidden');
            }

            const wEl = document.querySelector('#marksmanship-settings-overlay [data-weapon="' + msState.weapon + '"]');
            if (wEl) wEl.classList.add('active');

            const mEl = document.querySelector('#marksmanship-settings-overlay [data-mode="' + msState.mode + '"]');
            if (mEl) mEl.classList.add('active');
        }

        function updateMarksmanshipSummary() {
            const tEl = document.getElementById('ms-summary-time');
            const wEl = document.getElementById('ms-summary-weapon');
            const mEl = document.getElementById('ms-summary-mode');
            if (tEl) tEl.innerText = msState.time + 's';
            if (wEl) wEl.innerText = MS_WEAPON_LABELS[msState.weapon] || '9mm Pistol';
            if (mEl) mEl.innerText = MS_MODE_LABELS[msState.mode] || 'Steady';
        }

        function showMarksmanshipToast(detail) {
            const toast = document.getElementById('ms-toast');
            const detailEl = document.getElementById('ms-toast-detail');
            if (!toast) return;
            if (detailEl && detail) detailEl.textContent = detail;
            if (msToastTimer) { clearTimeout(msToastTimer); msToastTimer = null; }
            toast.classList.add('show');
            msToastTimer = setTimeout(() => { hideMarksmanshipToast(); }, 3200);
        }

        function hideMarksmanshipToast() {
            const toast = document.getElementById('ms-toast');
            if (!toast) return;
            toast.classList.remove('show');
            if (msToastTimer) { clearTimeout(msToastTimer); msToastTimer = null; }
        }

        function saveMarksmanshipSettings() {
            const ci = document.getElementById('marksmanship-custom-time');
            if (ci && ci.value !== '' && !document.getElementById('ms-custom-input-wrap')?.classList.contains('hidden')) {
                const v = Math.max(5, Math.min(999, parseInt(ci.value, 10) || MS_DEFAULTS.time));
                msState.time = v;
                if (ci) ci.value = v;
            }
            saveMarksmanshipSettingsStorage();
            applyMarksmanshipSelection();
            updateMarksmanshipSummary();
            updateProceedLinks();
            closeMarksmanshipSettings();
            const weaponLabel = MS_WEAPON_LABELS[msState.weapon] || msState.weapon;
            const modeLabel = MS_MODE_LABELS[msState.mode] || msState.mode;
            showMarksmanshipToast(msState.time + 's · ' + weaponLabel + ' · ' + modeLabel);
        }

        function resetMarksmanshipSettings() {
            msState = { ...MS_DEFAULTS };
            applyMarksmanshipSelection();
            updateMarksmanshipSummary();
        }

        function updateProceedLinks() {
            const baseUrl = @json(route('instructor.manage-module.module-4'));
            const weaponValue = msState.weapon === 'all' ? MS_WEAPON_CYCLE.all.join(',') : msState.weapon;
            const modeValue = msState.mode === 'all' ? MS_MODE_CYCLE.all.join(',') : msState.mode;
            document.querySelectorAll('a.proceed-btn').forEach(a => {
                const sid = a.getAttribute('data-student-id') || '';
                const params = new URLSearchParams();
                params.set('weapon', weaponValue);
                params.set('time', String(msState.time));
                params.set('mode', modeValue);
                params.set('student_id', sid);
                a.href = baseUrl + '?' + params.toString();
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            msState = loadMarksmanshipSettings();
            updateProceedLinks();

            const ci = document.getElementById('marksmanship-custom-time');
            if (ci) {
                ci.addEventListener('input', function () {
                    const v = Math.max(5, Math.min(999, parseInt(this.value, 10) || 0));
                    if (this.value !== '' && Number.isFinite(parseInt(this.value, 10))) {
                        msState.time = v;
                        updateMarksmanshipSummary();
                    }
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && msIsOpen) {
                    closeMarksmanshipSettings();
                }
            });
        });
    </script>

    <script>
        (function () {
            function initMarksmanshipFilters() {
                const tbody = document.querySelector('section.glass-card.rounded-3xl.overflow-hidden table tbody');
                const summaryEl = document.getElementById('mm-summary-text');
                const searchInput = document.getElementById('mm-search');
                const filterSection = document.getElementById('mm-filter-section');
                const filterCourse = document.getElementById('mm-filter-course');
                if (!tbody || !searchInput || !filterSection || !filterCourse) return;

                const dataRows = Array.from(tbody.querySelectorAll('tr[data-search]'));
                const totalRows = dataRows.length;
                const sectionOptions = new Set();
                const courseOptions = new Set();
                const defaultSummary = summaryEl ? summaryEl.textContent : '';

                dataRows.forEach(r => {
                    const sec = r.getAttribute('data-section');
                    const course = r.getAttribute('data-course');
                    if (sec && sec !== '—') sectionOptions.add(sec);
                    if (course && course !== '—') courseOptions.add(course);
                });

                Array.from(sectionOptions).sort().forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s;
                    opt.textContent = s;
                    filterSection.appendChild(opt);
                });

                Array.from(courseOptions).sort().forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c;
                    opt.textContent = c;
                    filterCourse.appendChild(opt);
                });

                function matchesFilters(row) {
                    const q = (searchInput.value || '').trim().toLowerCase();
                    const section = filterSection.value || '';
                    const course = filterCourse.value || '';

                    if (q) {
                        const hay = (row.getAttribute('data-search') || '').toLowerCase();
                        if (hay.indexOf(q) === -1) return false;
                    }
                    if (section && (row.getAttribute('data-section') || '') !== section) return false;
                    if (course && (row.getAttribute('data-course') || '') !== course) return false;
                    return true;
                }

                function render() {
                    let visibleCount = 0;
                    dataRows.forEach(r => {
                        if (matchesFilters(r)) {
                            r.style.display = '';
                            visibleCount++;
                        } else {
                            r.style.display = 'none';
                        }
                    });

                    if (summaryEl) {
                        const isFiltering = !!(searchInput.value || filterSection.value || filterCourse.value);
                        if (isFiltering) {
                            summaryEl.textContent = 'Showing ' + visibleCount + ' of ' + totalRows + ' completed student' + (totalRows === 1 ? '' : 's');
                        } else if (defaultSummary) {
                            summaryEl.textContent = defaultSummary;
                        }
                    }
                }

                searchInput.addEventListener('input', render);
                filterSection.addEventListener('change', render);
                filterCourse.addEventListener('change', render);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initMarksmanshipFilters);
            } else {
                initMarksmanshipFilters();
            }
        })();
    </script>
@endsection
