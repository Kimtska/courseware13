@extends('Instructor.layout')

@section('title', 'Manage Lessons')
@section('pageTitle', 'Manage Lessons')
@section('pageSubtitle', 'Training modules available for instructor-led sessions')

@section('content')
    <style>
        .portal-card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;box-shadow:0 4px 14px -8px rgba(30,5,82,.1)}
        .chip{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:9999px;font-size:11px;font-weight:700;letter-spacing:.04em}
        .module-launch{box-shadow:0 4px 12px -4px rgba(124,58,237,.45)}
        .ml-pulse-dot{display:inline-block;width:8px;height:8px;border-radius:50%;background:#22c55e;box-shadow:0 0 0 0 rgba(34,197,94,.7);animation:mlPulse 1.6s infinite}
        @keyframes mlPulse{0%{box-shadow:0 0 0 0 rgba(34,197,94,.7)}70%{box-shadow:0 0 0 10px rgba(34,197,94,0)}100%{box-shadow:0 0 0 0 rgba(34,197,94,0)}}
    </style>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach($modules as $module)
            <section class="glass-card rounded-2xl p-5 bg-white border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                        <i class="fas fa-book-open text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-sm text-gray-900">View Lessons and Activity</h3>
                    </div>
                    <a href="{{ $module['route'] }}" class="module-launch inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-violet-700 text-white text-xs font-bold hover:bg-violet-800 transition-colors whitespace-nowrap">
                        <i class="fas fa-door-open text-[10px]"></i> Open
                    </a>
                </div>
            </section>
        @endforeach

        <section class="glass-card rounded-2xl p-5 bg-white border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <i class="fas fa-users text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">Students Opening</p>
                    <p class="text-xl font-display font-bold text-gray-900 leading-tight" id="ml-student-number">0</p>
                </div>
                <span class="ml-pulse-dot"></span>
            </div>
        </section>
    </div>

    <section class="glass-card rounded-3xl p-5 sm:p-6 mb-6">
        <form id="ml-filter-form" class="grid grid-cols-1 md:grid-cols-3 gap-4" onsubmit="return false;">
            <div class="relative">
                <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Live Search</label>
                <input type="text" name="q" id="ml-search" placeholder="Search student ID or full name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                <i class="fas fa-magnifying-glass absolute right-4 top-10 text-gray-400"></i>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Section</label>
                <select name="section" id="ml-filter-section" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                    <option value="">All Sections</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Status</label>
                <select name="status" id="ml-filter-status" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="idle">Idle (over 15s)</option>
                </select>
            </div>
        </form>
    </section>

    <section class="glass-card rounded-3xl overflow-hidden">
        <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="font-display font-bold text-xl text-gray-900">Student Lesson Tracking</h2>
                <p class="text-sm text-gray-500">Students currently opening the lesson. Auto-refreshes every 5 seconds.</p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs">
                <span class="chip bg-emerald-100 text-emerald-700"><span class="ml-pulse-dot"></span> Active</span>
                <span class="chip bg-violet-100 text-violet-700"><i class="fas fa-book-open"></i> Gun Parts Lesson</span>
                <span class="chip bg-cyan-100 text-cyan-700"><i class="fas fa-file-lines"></i> Page-aware</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[1100px]">
                <thead class="bg-violet-950 text-xs text-violet-100 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 sm:px-6 py-4 font-semibold">Student ID</th>
                        <th class="px-5 sm:px-6 py-4 font-semibold">Full Name</th>
                        <th class="px-5 sm:px-6 py-4 font-semibold">Course/Year/Section</th>
                        <th class="px-5 sm:px-6 py-4 font-semibold">Current Page</th>
                        <th class="px-5 sm:px-6 py-4 font-semibold">Last Active</th>
                        <th class="px-5 sm:px-6 py-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white" id="ml-tbody">
                    <tr id="ml-empty-row">
                        <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                            <div class="max-w-md mx-auto">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-2xl">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <p class="font-semibold text-gray-900 mb-2">No students are currently opening the lesson</p>
                                <p class="text-sm">When a student opens the gun parts lesson, they will appear here within 30 seconds.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-5 sm:px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-sm text-gray-500" id="ml-summary-text">Showing 0 active students</div>
            <div class="text-xs text-gray-500 flex items-center gap-1.5">
                <span class="ml-pulse-dot"></span> Updates every 5 seconds
            </div>
        </div>
    </section>

    <script>
        (function () {
            const apiUrl = @json(route('api.lesson.active-students')) + '?lesson=gun-parts-presentation';
            const tbody = document.getElementById('ml-tbody');
            const summaryEl = document.getElementById('ml-summary-text');
            const numberEl = document.getElementById('ml-student-number');
            const searchInput = document.getElementById('ml-search');
            const filterSection = document.getElementById('ml-filter-section');
            const filterStatus = document.getElementById('ml-filter-status');

            let timer = null;
            let inFlight = false;
            let allRows = [];
            let sectionOptions = new Set();

            function escapeHtml(s) {
                if (s == null) return '';
                return String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
            }

            function relativeTime(ts) {
                const diff = Math.max(0, Math.floor(Date.now() / 1000) - ts);
                if (diff < 5) return 'just now';
                if (diff < 60) return diff + 's ago';
                if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
                return Math.floor(diff / 3600) + 'h ago';
            }

            function statusForRow(r) {
                const idle = Math.max(0, Math.floor(Date.now() / 1000) - r.last_active_at);
                return idle > 15 ? 'idle' : 'active';
            }

            function matchesFilters(r) {
                const q = (searchInput.value || '').trim().toLowerCase();
                const section = filterSection.value || '';
                const status = filterStatus.value || '';

                if (q) {
                    const hay = ((r.full_name || '') + ' ' + (r.student_id || '')).toLowerCase();
                    if (hay.indexOf(q) === -1) return false;
                }
                if (section && (r.section || '') !== section) return false;
                if (status && statusForRow(r) !== status) return false;
                return true;
            }

            function rowHtml(r) {
                const page = Math.max(1, (parseInt(r.current_page, 10) || 0) + 1);
                const last = escapeHtml(relativeTime(r.last_active_at));
                const meta = ((r.course || '—') + ' / ' + (r.year_level || '—') + ' / ' + (r.section || '—')).replace(/^[\s\/]+|[\s\/]+$/g, '');
                const idle = Math.max(0, Math.floor(Date.now() / 1000) - r.last_active_at);
                const statusKey = idle > 15 ? 'idle' : 'active';
                const statusPill = statusKey === 'idle'
                    ? '<span class="status-pill bg-amber-100 text-amber-700"><i class="fas fa-circle-pause"></i> idle</span>'
                    : '<span class="status-pill bg-emerald-100 text-emerald-700"><span class="ml-pulse-dot"></span> active</span>';
                return '<tr class="hover:bg-violet-50/50 transition-colors" data-last-active="' + r.last_active_at + '" data-status="' + statusKey + '">' +
                    '<td class="px-5 sm:px-6 py-4 font-semibold text-gray-900">' + escapeHtml(r.student_id) + '</td>' +
                    '<td class="px-5 sm:px-6 py-4">' +
                        '<div class="font-medium text-gray-900">' + escapeHtml(r.full_name || '—') + '</div>' +
                        '<div class="text-xs text-gray-500">Viewing: Gun Parts Lesson</div>' +
                    '</td>' +
                    '<td class="px-5 sm:px-6 py-4 text-sm text-gray-600">' + escapeHtml(meta) + '</td>' +
                    '<td class="px-5 sm:px-6 py-4"><span class="status-pill bg-violet-100 text-violet-700"><i class="fas fa-file-lines"></i> Page ' + page + '</span></td>' +
                    '<td class="px-5 sm:px-6 py-4 text-sm text-gray-700" data-ts="' + r.last_active_at + '">' + last + '</td>' +
                    '<td class="px-5 sm:px-6 py-4">' + statusPill + '</td>' +
                '</tr>';
            }

            function rebuildSectionOptions() {
                const current = filterSection.value || '';
                const next = new Set();
                allRows.forEach(r => { if (r.section) next.add(r.section); });
                if (next.size === sectionOptions.size) {
                    let same = true;
                    next.forEach(v => { if (!sectionOptions.has(v)) same = false; });
                    if (same) return;
                }
                sectionOptions = next;
                const opts = ['<option value="">All Sections</option>']
                    .concat(Array.from(sectionOptions).sort().map(c => '<option value="' + escapeHtml(c) + '"' + (c === current ? ' selected' : '') + '>' + escapeHtml(c) + '</option>'));
                filterSection.innerHTML = opts.join('');
            }

            function render() {
                numberEl.textContent = allRows.length;
                const filtered = allRows.filter(matchesFilters);
                summaryEl.textContent = 'Showing ' + filtered.length + ' of ' + allRows.length + ' active student' + (allRows.length === 1 ? '' : 's');
                if (filtered.length === 0) {
                    const isFiltering = !!(searchInput.value || filterSection.value || filterStatus.value);
                    const emptyMsg = isFiltering
                        ? '<p class="font-semibold text-gray-900 mb-2">No students match the current filters.</p><p class="text-sm">Try adjusting your search or filter selections.</p>'
                        : '<p class="font-semibold text-gray-900 mb-2">No students are currently opening the lesson</p><p class="text-sm">When a student opens the gun parts lesson, they will appear here within 30 seconds.</p>';
                    tbody.innerHTML = '<tr id="ml-empty-row"><td colspan="6" class="px-6 py-16 text-center text-gray-500"><div class="max-w-md mx-auto"><div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-2xl"><i class="fas fa-user-graduate"></i></div>' + emptyMsg + '</div></td></tr>';
                    return;
                }
                tbody.innerHTML = filtered.map(rowHtml).join('');
            }

            function refreshRelativeTimes() {
                tbody.querySelectorAll('tr[data-last-active]').forEach(tr => {
                    const ts = parseInt(tr.getAttribute('data-last-active'), 10);
                    if (!Number.isFinite(ts)) return;
                    const cell = tr.querySelector('td[data-ts]');
                    if (cell) cell.textContent = relativeTime(ts);
                });
                if (allRows.length > 0) render();
            }

            async function tick() {
                if (inFlight) return;
                inFlight = true;
                try {
                    const res = await fetch(apiUrl, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
                    if (!res.ok) throw new Error('http ' + res.status);
                    const data = await res.json();
                    allRows = data.students || [];
                    rebuildSectionOptions();
                    render();
                } catch (err) {
                    console.error('Activity refresh failed', err);
                } finally {
                    inFlight = false;
                }
            }

            function start() {
                if (timer) clearInterval(timer);
                timer = setInterval(() => { tick(); refreshRelativeTimes(); }, 5000);
            }

            searchInput.addEventListener('input', render);
            filterSection.addEventListener('change', render);
            filterStatus.addEventListener('change', render);

            tick();
            start();
        })();
    </script>
@endsection
