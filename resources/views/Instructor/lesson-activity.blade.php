@extends('Instructor.layout')

@section('title', 'Lesson Activity')
@section('pageTitle', 'Lesson Activity')
@section('pageSubtitle', 'Students currently opening the gun parts lesson')

@section('headerActions')
    <div class="flex items-center gap-3">
        <a href="{{ route('instructor.manage-module') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Lessons
        </a>
        <button type="button" id="la-refresh-btn" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-700 hover:bg-violet-800 text-white text-xs font-bold transition-colors">
            <i class="fas fa-rotate"></i> Refresh
        </button>
    </div>
@endsection

@section('content')
    <style>
        .la-shell{background:#fff;border:1px solid #e9d5ff;border-radius:24px;box-shadow:0 18px 45px -20px rgba(30,5,82,.15);overflow:hidden}
        .la-hero{background:linear-gradient(135deg,#1E0552,#5B21B6);color:#fff;padding:24px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
        .la-stat{display:inline-flex;align-items:center;gap:10px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:14px;padding:10px 14px;color:#fff}
        .la-stat .la-stat-icon{width:34px;height:34px;border-radius:10px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center}
        .la-stat .la-stat-num{font-family:'Space Grotesk',sans-serif;font-weight:800;font-size:22px;line-height:1}
        .la-stat .la-stat-lbl{font-size:10px;text-transform:uppercase;letter-spacing:.18em;color:#c4b5fd;font-weight:700}
        .la-pulse{display:inline-block;width:10px;height:10px;border-radius:50%;background:#22c55e;box-shadow:0 0 0 0 rgba(34,197,94,.6);animation:laPulse 1.8s infinite}
        @keyframes laPulse{0%{box-shadow:0 0 0 0 rgba(34,197,94,.6)}70%{box-shadow:0 0 0 12px rgba(34,197,94,0)}100%{box-shadow:0 0 0 0 rgba(34,197,94,0)}}
        .la-table-wrap{padding:0}
        .la-table{width:100%;border-collapse:separate;border-spacing:0}
        .la-table th{background:#faf5ff;font-size:10px;text-transform:uppercase;letter-spacing:.18em;color:#5b21b6;font-weight:800;padding:14px 18px;text-align:left;border-bottom:1px solid #ede9fe}
        .la-table td{padding:14px 18px;border-bottom:1px solid #f3f4f6;font-size:13px;color:#1f2937;vertical-align:middle}
        .la-table tbody tr:hover{background:#faf5ff}
        .la-table tbody tr:last-child td{border-bottom:none}
        .la-empty{padding:48px 24px;text-align:center;color:#6b7280}
        .la-badge{display:inline-flex;align-items:center;gap:6px;padding:3px 9px;border-radius:9999px;font-size:10px;font-weight:700;letter-spacing:.04em}
        .la-badge-page{background:#ede9fe;color:#5b21b6}
        .la-badge-idle{background:#fef3c7;color:#92400e}
        .la-badge-fresh{background:#dcfce7;color:#166534}
        .la-row-name{font-weight:700;color:#111827}
        .la-row-meta{color:#6b7280;font-size:11px;margin-top:2px}
        .la-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#5b21b6);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;flex-shrink:0}
    </style>

    <div class="grid gap-6">
        <div class="la-shell">
            <div class="la-hero">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.28em] text-violet-200 font-bold">Live activity</p>
                    <h2 class="font-display font-bold text-2xl text-white mt-1">Students currently opening the lesson</h2>
                    <p class="text-violet-100 text-xs mt-1">Auto-refreshes every 5 seconds. A student is considered active if they sent a heartbeat in the last 30 seconds.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="la-stat">
                        <div class="la-stat-icon"><span class="la-pulse"></span></div>
                        <div>
                            <div class="la-stat-num" id="la-active-count">{{ count($activeStudents) }}</div>
                            <div class="la-stat-lbl">Active now</div>
                        </div>
                    </div>
                    <div class="la-stat">
                        <div class="la-stat-icon"><i class="fas fa-book-open"></i></div>
                        <div>
                            <div class="la-stat-num" style="font-size:14px;line-height:1.2">Gun Parts</div>
                            <div class="la-stat-lbl">Lesson</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto la-table-wrap">
                <table class="la-table min-w-[860px]">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Student</th>
                            <th>Student ID</th>
                            <th>Course / Year / Section</th>
                            <th>Current Page</th>
                            <th>Last Active</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="la-tbody">
                        @forelse ($activeStudents as $i => $row)
                            <tr data-sid="{{ $row['student_id'] }}">
                                <td class="text-gray-400 font-semibold">{{ $i + 1 }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="la-avatar">{{ strtoupper(substr($row['first_name'] ?? $row['full_name'] ?? '?', 0, 1)) }}{{ strtoupper(substr($row['last_name'] ?? '', 0, 1)) }}</div>
                                        <div>
                                            <div class="la-row-name">{{ $row['full_name'] ?? '—' }}</div>
                                            <div class="la-row-meta">Viewing: Gun Parts Lesson</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-semibold text-gray-900">{{ $row['student_id'] }}</td>
                                <td class="text-sm text-gray-700">{{ trim(($row['course'] ?? '—') . ' / ' . ($row['year_level'] ?? '—') . ' / ' . ($row['section'] ?? '—'), ' /') }}</td>
                                <td>
                                    <span class="la-badge la-badge-page"><i class="fas fa-file-lines"></i> Page {{ max(1, ((int) ($row['current_page'] ?? 0)) + 1) }}</span>
                                </td>
                                <td class="text-sm text-gray-700" data-last-active="{{ $row['last_active_at'] }}">{{ \Carbon\Carbon::createFromTimestamp($row['last_active_at'])->diffForHumans() }}</td>
                                <td>
                                    <span class="la-badge la-badge-fresh"><span class="la-pulse"></span> Active</span>
                                </td>
                            </tr>
                        @empty
                            <tr id="la-empty-row">
                                <td colspan="7" class="la-empty">
                                    <i class="fas fa-users-slash text-3xl text-gray-300 mb-3"></i>
                                    <p class="font-semibold text-gray-700">No students are currently opening the lesson.</p>
                                    <p class="text-xs mt-1">When a student opens the gun parts lesson, they will appear here within 30 seconds.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-xs text-gray-500 flex items-center gap-2">
            <i class="fas fa-circle-info text-violet-500"></i>
            Tip: open the gun parts lesson in another tab as a student to see this list update in real time.
        </div>
    </div>

    <script>
        (function () {
            const apiUrl = @json(route('api.lesson.active-students')) + '?lesson=' + @json($lessonKey);
            const tbody = document.getElementById('la-tbody');
            const countEl = document.getElementById('la-active-count');
            const refreshBtn = document.getElementById('la-refresh-btn');
            let timer = null;
            let inFlight = false;

            function initials(first, last, full) {
                const f = (first || '').trim();
                const l = (last || '').trim();
                if (f || l) return (f.charAt(0) + l.charAt(0)).toUpperCase();
                const parts = (full || '?').split(/\s+/);
                return ((parts[0] || '?').charAt(0) + (parts[1] || '').charAt(0)).toUpperCase();
            }

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

            function render(rows) {
                countEl.textContent = rows.length;
                if (rows.length === 0) {
                    tbody.innerHTML = '<tr id="la-empty-row"><td colspan="7" class="la-empty"><i class="fas fa-users-slash text-3xl text-gray-300 mb-3"></i><p class="font-semibold text-gray-700">No students are currently opening the lesson.</p><p class="text-xs mt-1">When a student opens the gun parts lesson, they will appear here within 30 seconds.</p></td></tr>';
                    return;
                }
                const html = rows.map((r, i) => {
                    const page = Math.max(1, (parseInt(r.current_page, 10) || 0) + 1);
                    const init = initials(r.first_name, r.last_name, r.full_name);
                    const last = escapeHtml(relativeTime(r.last_active_at));
                    return '<tr data-sid="' + escapeHtml(r.student_id) + '">' +
                        '<td class="text-gray-400 font-semibold">' + (i + 1) + '</td>' +
                        '<td><div class="flex items-center gap-3"><div class="la-avatar">' + escapeHtml(init) + '</div><div><div class="la-row-name">' + escapeHtml(r.full_name || '—') + '</div><div class="la-row-meta">Viewing: Gun Parts Lesson</div></div></div></td>' +
                        '<td class="font-semibold text-gray-900">' + escapeHtml(r.student_id) + '</td>' +
                        '<td class="text-sm text-gray-700">' + escapeHtml(((r.course || '—') + ' / ' + (r.year_level || '—') + ' / ' + (r.section || '—')).replace(/^[\s\/]+|[\s\/]+$/g, '')) + '</td>' +
                        '<td><span class="la-badge la-badge-page"><i class="fas fa-file-lines"></i> Page ' + page + '</span></td>' +
                        '<td class="text-sm text-gray-700" data-last-active="' + r.last_active_at + '">' + last + '</td>' +
                        '<td><span class="la-badge la-badge-fresh"><span class="la-pulse"></span> Active</span></td>' +
                    '</tr>';
                }).join('');
                tbody.innerHTML = html;
            }

            async function tick() {
                if (inFlight) return;
                inFlight = true;
                try {
                    const res = await fetch(apiUrl, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
                    if (!res.ok) throw new Error('http ' + res.status);
                    const data = await res.json();
                    render(data.students || []);
                } catch (err) {
                    console.error('Lesson activity refresh failed', err);
                } finally {
                    inFlight = false;
                }
            }

            function start() {
                if (timer) clearInterval(timer);
                timer = setInterval(tick, 5000);
            }

            refreshBtn && refreshBtn.addEventListener('click', function () {
                tick();
            });

            tick();
            start();
        })();
    </script>
@endsection
