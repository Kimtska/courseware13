<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IOT-Based Marksmanship - Reports</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/Marksmanship innovatech.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">
    <style>
        body{font-family:'Inter',sans-serif;background:linear-gradient(180deg,#f8fafc 0%,#eef2ff 100%);min-height:100vh}
        .nav-link{position:relative;padding:8px 16px;color:rgba(255,255,255,0.65);transition:all .2s;font-size:13px;font-weight:500;border-radius:6px;white-space:nowrap}
        .nav-link:hover{color:#fff;background:rgba(255,255,255,0.1)}
        .nav-link.active{color:#fff;background:rgba(255,255,255,0.15);font-weight:600}
        .nav-link.active::after{content:'';position:absolute;bottom:-14px;left:50%;transform:translateX(-50%);width:20px;height:3px;background:#A78BFA;border-radius:3px}
        .mobile-menu{transform:translateY(-100%);opacity:0;transition:all .3s ease;pointer-events:none}
        .mobile-menu.open{transform:translateY(0);opacity:1;pointer-events:auto}
        .card{background:#fff;border:1px solid #e5e7eb;border-radius:20px;box-shadow:0 18px 45px -20px rgba(30,5,82,.18)}
        .tab-bar{display:flex;gap:4px;border-bottom:2px solid #e5e7eb;margin-bottom:0;overflow-x:auto}
        .tab-btn{padding:10px 20px;font-size:13px;font-weight:700;color:#6b7280;background:transparent;border:none;border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer;transition:all .15s;white-space:nowrap}
        .tab-btn:hover{color:#374151}
        .tab-btn.active{color:#7c3aed;border-bottom-color:#7c3aed;background:#faf5ff}
        .tab-panel{display:none}
        .tab-panel.active{display:block}
        .table-wrap{overflow-x:auto}
        .ds-table{font-size:13px;width:100%}
        .ds-table thead th{background:#f8fafc;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;padding:12px 14px;border-bottom:2px solid #e5e7eb;text-align:left}
        .ds-table tbody td{padding:10px 14px;border-bottom:1px solid #f3f4f6;vertical-align:middle}
        .ds-table tbody tr:hover td{background:#faf5ff}
        .view-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:10px;border:1px solid #ddd6fe;background:#fff;font-size:12px;font-weight:700;color:#6d28d9;cursor:pointer;transition:all .15s}
        .view-btn:hover{background:#f5f3ff;border-color:#c4b5fd;transform:translateY(-1px)}
        .badge-attempt{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:9999px;font-size:10px;font-weight:700;background:#f5f3ff;color:#7c3aed;border:1px solid #ddd6fe;margin-left:4px}
        .badge-pass{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:9999px;font-size:10px;font-weight:700;background:#d1fae5;color:#065f46;border:1px solid #6ee7b7}
        .badge-fail{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:9999px;font-size:10px;font-weight:700;background:#fef2f2;color:#991b1b;border:1px solid #fca5a5}
        .zone-pills{display:flex;gap:2px;flex-wrap:wrap}
        .zone-pill{display:inline-flex;align-items:center;justify-content:center;min-width:22px;height:22px;border-radius:4px;font-size:9px;font-weight:700;padding:0 5px}
        .zone-pill.bullseye{background:#fef3c7;color:#92400e}
        .zone-pill.alpha{background:#d1fae5;color:#065f46}
        .zone-pill.bravo{background:#dbeafe;color:#1e40af}
        .zone-pill.charlie{background:#fce7f3;color:#831843}
        .zone-pill.delta{background:#f3e8ff;color:#5b21b6}
        .zone-pill.miss{background:#fef2f2;color:#991b1b}
        .modal-backdrop{position:fixed;inset:0;z-index:50;background:rgba(0,0,0,.45);backdrop-filter:blur(4px);display:flex;align-items:center;justify-content:center;padding:16px;opacity:0;pointer-events:none;transition:opacity .25s}
        .modal-backdrop.open{opacity:1;pointer-events:auto}
        .modal-panel{background:#fff;border-radius:20px;box-shadow:0 30px 70px -20px rgba(0,0,0,.35);max-width:640px;width:100%;max-height:85vh;display:flex;flex-direction:column;transform:scale(.95) translateY(10px);transition:transform .25s;overflow:hidden}
        .modal-backdrop.open .modal-panel{transform:scale(1) translateY(0)}
        .modal-header{display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid #f3f4f6;flex-shrink:0}
        .modal-header h3{font-size:16px;font-weight:700;color:#111827}
        .modal-close{width:32px;height:32px;border-radius:50%;border:none;background:#f3f4f6;color:#6b7280;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;transition:all .15s}
        .modal-close:hover{background:#e5e7eb;color:#111827}
        .modal-body{padding:24px;overflow-y:auto;flex:1}
        .modal-footer{display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:14px 24px;border-top:1px solid #f3f4f6;flex-shrink:0}
        .modal-footer .meta-info{font-size:12px;color:#9ca3af;margin-right:auto}
        .modal-footer .close-btn{padding:8px 20px;border-radius:10px;border:1px solid #e5e7eb;background:#fff;font-size:12px;font-weight:700;color:#374151;cursor:pointer;transition:all .15s}
        .modal-footer .close-btn:hover{background:#f9fafb}
        .empty-state{padding:40px 20px;text-align:center}
        .empty-state .icon{width:56px;height:56px;margin:0 auto 12px;border-radius:50%;background:#f3f4f6;display:flex;align-items:center;justify-content:center;font-size:20px;color:#d1d5db}
        .empty-state .title{font-size:15px;font-weight:600;color:#6b7280;margin-bottom:4px}
        .empty-state .sub{font-size:12px;color:#9ca3af}
    </style>
    @include('shared.back-button-prevention')
</head>
<body>
    @php
        $__studentUser = Auth::guard('student')->user() ?? (Auth::guard('web')->user() && Auth::guard('web')->user()->role === 'student' ? Auth::guard('web')->user() : null);
        if (!isset($name) || $name === null || $name === '') {
            $name = $__studentUser->full_name ?? $__studentUser->name ?? 'Student';
        }
        if (!isset($firstName) || $firstName === null || $firstName === '') {
            $firstName = $__studentUser->first_name ?? (preg_split('/\s+/', trim($name))[0] ?? $name);
        }
        if (!isset($lastName) || $lastName === null) {
            $lastName = $__studentUser->last_name ?? (preg_split('/\s+/', trim($name))[1] ?? '');
        }
        $name = $name ?? 'Student';
        $firstName = $firstName ?? $name;
        $lastName = $lastName ?? '';
    @endphp
    <header class="bg-violet-950 text-white sticky top-0 z-50 shadow-lg shadow-violet-950/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3 flex-shrink-0">
                    <img src="{{ asset('images/assets/Marksmanship innovatech.png') }}" alt="SPC" class="h-9 w-auto">
                    <div class="hidden sm:block"><span class="font-display font-bold text-sm">IOT-Based Marksmanship</span><span class="block text-[8px] text-violet-300 uppercase tracking-widest leading-none">Student Portal</span></div>
                </div>
                <div class="flex-1 flex justify-center">
                    @include('Students.partials.nav-links', ['type' => 'desktop', 'activeNav' => 'reports'])
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-2 pl-3 border-l border-violet-800/50">
                        <div class="w-8 h-8 rounded-full bg-violet-700 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($firstName ?: $name, 0, 1)) }}{{ strtoupper(substr($lastName ?: $name, 0, 1)) }}</div>
                        <span class="text-sm font-medium">{{ $name }}</span>
                    </div>
                    <button type="button" class="student-settings-btn p-2 rounded-lg hover:bg-violet-800/50 transition-colors text-violet-300 hover:text-white" title="Settings" aria-label="Settings">
                        <i class="fas fa-cog text-sm"></i>
                    </button>
                    <button onclick="showLogoutAlert()" class="p-2 rounded-lg hover:bg-violet-800/50 transition-colors text-violet-300 hover:text-white" title="Logout" aria-label="Logout">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                    <button id="mobile-toggle" class="md:hidden p-2 rounded-lg hover:bg-violet-800/50 transition-colors">
                        <svg class="inline-block align-middle w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z"/></svg>
                    </button>
                </div>
            </div>
        </div>
        @include('Students.partials.nav-links', ['type' => 'mobile', 'activeNav' => 'reports'])
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="card p-6 sm:p-8 mb-6">
            <h1 class="font-display font-bold text-2xl">Assessment Reports</h1>
            <p class="text-sm text-gray-500 mt-1">Detailed review of all your checkpoint answers, marksmanship, and assembly results.</p>
        </div>

        @php
            $moduleScoresTotal = 0;
            $moduleScoresMax = 0;
            $marksmanshipTotal = 0;
            $marksmanshipMax = 0;
            foreach ($scores as $s) {
                if (($s->module_key ?? '') === 'final') {
                    $marksmanshipTotal += $s->score;
                    $marksmanshipMax += $s->max_score;
                } else {
                    $moduleScoresTotal += $s->score;
                    $moduleScoresMax += $s->max_score;
                }
            }
            $modulePercent = $moduleScoresMax > 0 ? round(($moduleScoresTotal / $moduleScoresMax) * 100, 1) : 0;
            $marksmanshipPercent = $marksmanshipMax > 0 ? round(($marksmanshipTotal / $marksmanshipMax) * 100, 1) : 0;
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="card p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                        <i class="fas fa-book-open text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">Module Score</p>
                        <p class="text-xl font-display font-bold text-gray-900 leading-tight">{{ $moduleScoresTotal }} / {{ $moduleScoresMax }}</p>
                        <p class="text-xs text-gray-500">{{ $modulePercent }}% overall</p>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <i class="fas fa-bullseye text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">Marksmanship Assessment</p>
                        <p class="text-xl font-display font-bold text-gray-900 leading-tight">{{ $marksmanshipTotal }} / {{ $marksmanshipMax }}</p>
                        <p class="text-xs text-gray-500">{{ $marksmanshipPercent }}% accuracy</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="tab-bar px-4 sm:px-6 pt-2">
                <button class="tab-btn active" data-tab="checkpoint">Checkpoint Questions</button>
                <button class="tab-btn" data-tab="marksmanship">Marksmanship Assessment</button>
                <button class="tab-btn" data-tab="assembly">Assembly / Disassembly</button>
            </div>

            {{-- TAB 1: Checkpoint Questions --}}
            <div class="tab-panel active p-4 sm:p-6" id="tab-checkpoint">
                @if ($allRows->isNotEmpty())
                <div class="table-wrap">
                    <table class="ds-table" id="checkpoint-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Question</th>
                                <th>Correct Answer</th>
                                <th>Your Answer</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allRows as $row)
                            <tr>
                                <td class="font-semibold text-gray-800">{{ $row['question_number'] }}</td>
                                <td class="text-gray-700 max-w-xs truncate" title="{{ e($row['question_text']) }}">{{ $row['question_text'] }}</td>
                                <td class="text-emerald-700 font-medium">{{ $row['correct_answer_text'] }}</td>
                                <td>
                                    @if ($row['is_correct'])
                                    <span class="text-emerald-700 font-medium">{{ $row['selected_answer_text'] }}</span>
                                    @elseif ($row['selected_answer'] === -1 || $row['selected_answer'] === null)
                                    <span class="text-gray-400 italic">No answer</span>
                                    @else
                                    <span class="text-red-600 font-medium">{{ $row['selected_answer_text'] }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="view-btn view-checkpoint" data-row="{{ base64_encode(json_encode($row)) }}">
                                        <i class="fas fa-eye text-xs"></i> View
                                        <span class="badge-attempt">{{ $row['attempt_number'] }}/{{ $row['total_attempts'] }}</span>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="icon"><i class="fas fa-file-lines"></i></div>
                    <div class="title">No checkpoint attempts</div>
                    <div class="sub">Complete a module checkpoint to see your results here.</div>
                </div>
                @endif
            </div>

            {{-- TAB 2: Marksmanship Assessment --}}
            <div class="tab-panel p-4 sm:p-6" id="tab-marksmanship">
                @if ($marksmanshipRows->isNotEmpty())
                <div class="table-wrap">
                    <table class="ds-table" id="marksmanship-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Weapon</th>
                                <th>Target Mode</th>
                                <th>Score</th>
                                <th>Accuracy</th>
                                <th>Zone Breakdown</th>
                                <th>Passed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($marksmanshipRows as $m)
                            <tr>
                                <td class="font-semibold text-gray-800">{{ $loop->iteration }}</td>
                                <td class="text-xs text-gray-500">{{ optional($m['recorded_at'])->format('M d, Y H:i') ?? 'N/A' }}</td>
                                <td>{{ $m['weapon'] }}</td>
                                <td class="text-xs">{{ $m['target_mode'] }}</td>
                                <td class="font-semibold">{{ $m['score'] }} / {{ $m['max_score'] }}</td>
                                <td>{{ $m['accuracy'] !== null ? $m['accuracy'] . '%' : 'N/A' }}</td>
                                <td>
                                    <div class="zone-pills">
                                        <span class="zone-pill bullseye" title="Bullseye">B {{ $m['bullseye_count'] }}</span>
                                        <span class="zone-pill alpha" title="Alpha">A {{ $m['alpha_count'] }}</span>
                                        <span class="zone-pill bravo" title="Bravo">Br {{ $m['bravo_count'] }}</span>
                                        <span class="zone-pill charlie" title="Charlie">C {{ $m['charlie_count'] }}</span>
                                        <span class="zone-pill delta" title="Delta">D {{ $m['delta_count'] }}</span>
                                        <span class="zone-pill miss" title="Miss">M {{ $m['miss_count'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($m['passed'])
                                    <span class="badge-pass"><i class="fas fa-check" style="font-size:8px"></i> Pass</span>
                                    @else
                                    <span class="badge-fail"><i class="fas fa-xmark" style="font-size:8px"></i> Fail</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="view-btn view-marksmanship" data-row="{{ base64_encode(json_encode($m)) }}">
                                        <i class="fas fa-eye text-xs"></i> View
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="icon"><i class="fas fa-bullseye"></i></div>
                    <div class="title">No marksmanship records</div>
                    <div class="sub">Complete a firing range simulation to see your marksmanship results here.</div>
                </div>
                @endif
            </div>

            {{-- TAB 3: Assembly / Disassembly --}}
            <div class="tab-panel p-4 sm:p-6" id="tab-assembly">
                @if ($assemblyRows->isNotEmpty())
                <div class="table-wrap">
                    <table class="ds-table" id="assembly-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Weapon</th>
                                <th>Mode</th>
                                <th>Score</th>
                                <th>Wrong Att.</th>
                                <th>Passed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assemblyRows as $a)
                            <tr>
                                <td class="font-semibold text-gray-800">{{ $loop->iteration }}</td>
                                <td class="text-xs text-gray-500">{{ optional($a['recorded_at'])->format('M d, Y H:i') ?? 'N/A' }}</td>
                                <td>{{ $a['simulation_slug'] }}</td>
                                <td>
                                    <span class="text-xs font-semibold {{ $a['mode'] === 'asm' ? 'text-violet-700' : 'text-amber-700' }}">
                                        {{ $a['mode'] === 'asm' ? 'Assemble' : 'Disassemble' }}
                                    </span>
                                </td>
                                <td class="font-semibold">{{ $a['score'] }} / {{ $a['max_score'] }}</td>
                                <td>{{ $a['wrong_attempts'] }}</td>
                                <td>
                                    @if ($a['passed'])
                                    <span class="badge-pass"><i class="fas fa-check" style="font-size:8px"></i> Pass</span>
                                    @else
                                    <span class="badge-fail"><i class="fas fa-xmark" style="font-size:8px"></i> Fail</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="view-btn view-assembly" data-row="{{ base64_encode(json_encode($a)) }}">
                                        <i class="fas fa-eye text-xs"></i> View
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="icon"><i class="fas fa-toolbox"></i></div>
                    <div class="title">No assembly records</div>
                    <div class="sub">Complete the assembly/disassembly trainer to see your results here.</div>
                </div>
                @endif
            </div>
        </div>
    </main>

    {{-- Modal (shared across all tabs) --}}
    <div class="modal-backdrop" id="detail-modal">
        <div class="modal-panel">
            <div class="modal-header">
                <h3 id="modal-title"><i class="fas fa-file-pen text-violet-600 mr-2"></i>Details</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modal-body"></div>
            <div class="modal-footer">
                <span class="meta-info" id="modal-meta"></span>
                <button class="close-btn" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    @include('shared.sweet-alerts.logout', ['logoutLabel' => 'Student — ' . $name, 'logoutSubtext' => 'Student Reports', 'logoutDescription' => 'You are about to end your session.', 'redirectUrl' => url('/login')])

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-toggle')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            if (menu) menu.classList.toggle('open');
        });

        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
                document.querySelectorAll('.tab-panel').forEach(function(p) { p.classList.remove('active'); });
                this.classList.add('active');
                document.getElementById('tab-' + this.dataset.tab)?.classList.add('active');
            });
        });

        // Init DataTables
        function initTable(id, opts) {
            var el = document.getElementById(id);
            if (!el) return null;
            try { return new simpleDatatables.DataTable(el, Object.assign({ perPage: 20, perPageSelect: [10,20,50,100], labels: { placeholder: 'Search...', perPage: '{select} per page', noRows: 'No entries', noResults: 'No results', info: 'Showing {start} to {end} of {rows} entries' } }, opts || {})); } catch(e) { return null; }
        }
        initTable('checkpoint-table', { columns: [{ select: 4, sortable: false }] });
        initTable('marksmanship-table', { columns: [{ select: 8, sortable: false }] });
        initTable('assembly-table', { columns: [{ select: 7, sortable: false }] });

        // Modal functions
        function openModal(title, bodyHtml, metaText) {
            document.getElementById('modal-title').innerHTML = '<i class="fas fa-file-pen text-violet-600 mr-2"></i>' + title;
            document.getElementById('modal-body').innerHTML = bodyHtml;
            document.getElementById('modal-meta').textContent = metaText || '';
            document.getElementById('detail-modal').classList.add('open');
        }
        function closeModal() { document.getElementById('detail-modal')?.classList.remove('open'); }
        document.getElementById('detail-modal')?.addEventListener('click', function(e) { if (e.target === this) closeModal(); });
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });

        // Checkpoint View
        document.querySelectorAll('.view-checkpoint').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var raw = this.dataset.row;
                try {
                    var d = JSON.parse(atob(raw));
                    var opts = d.options || [];
                    var correctIdx = d.correct_answer;
                    var selectedIdx = d.selected_answer;
                    var html = '<div style="font-size:16px;font-weight:700;color:#111827;line-height:1.5;margin-bottom:20px">' + d.question_text + '</div>';
                    opts.forEach(function(txt, idx) {
                        var isCorrect = (idx === correctIdx);
                        var isSelected = (idx === selectedIdx);
                        var rowClass = isCorrect ? 'is-correct' : (isSelected ? 'is-wrong' : '');
                        var markerClass = isCorrect ? 'correct' : (isSelected ? 'wrong' : 'neutral');
                        var markerIcon = isCorrect ? '&#10003;' : (isSelected ? '&#10007;' : '');
                        var badge = '';
                        if (isSelected && isCorrect) badge = '<span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:9999px;background:#ede9fe;color:#6d28d9;margin-left:auto;flex-shrink:0">Your answer</span>';
                        else if (isSelected) badge = '<span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:9999px;background:#ede9fe;color:#6d28d9;margin-left:auto;flex-shrink:0">Your answer</span>';
                        else if (isCorrect) badge = '<span style="font-size:9px;font-weight:700;padding:2px 8px;border-radius:9999px;background:#d1fae5;color:#065f46;margin-left:auto;flex-shrink:0">Correct answer</span>';
                        var style = isCorrect ? 'border-color:#6ee7b7;background:#ecfdf5' : (isSelected ? 'border-color:#fca5a5;background:#fef2f2' : 'border-color:#e5e7eb');
                        var markerStyle = isCorrect ? 'background:#10b981;color:#fff' : (isSelected ? 'background:#ef4444;color:#fff' : 'background:#e5e7eb;color:#9ca3af');
                        html += '<div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:12px;border:1.5px solid;margin-bottom:8px;font-size:14px;color:#374151;' + style + '">' +
                            '<span style="width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;flex-shrink:0;' + markerStyle + '">' + markerIcon + '</span>' +
                            '<span>' + txt + '</span>' + badge + '</div>';
                    });
                    openModal('Question #' + d.question_number, html, d.module_title + ' \u00b7 ' + d.score_value + '/' + d.max_score);
                } catch(e) {}
            });
        });

        // Marksmanship View
        document.querySelectorAll('.view-marksmanship').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var raw = this.dataset.row;
                try {
                    var d = JSON.parse(atob(raw));
                    var shots = d.shotResults || [];
                    var html = '<div style="margin-bottom:16px">';
                    html += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px">';
                    html += '<div style="background:#f8fafc;border-radius:10px;padding:12px"><span style="font-size:11px;color:#6b7280;display:block">Score</span><span style="font-size:18px;font-weight:800;color:#111827">' + d.score + '/' + d.max_score + '</span></div>';
                    html += '<div style="background:#f8fafc;border-radius:10px;padding:12px"><span style="font-size:11px;color:#6b7280;display:block">Accuracy</span><span style="font-size:18px;font-weight:800;color:#111827">' + (d.accuracy !== null ? d.accuracy + '%' : 'N/A') + '</span></div>';
                    html += '<div style="background:#f8fafc;border-radius:10px;padding:12px"><span style="font-size:11px;color:#6b7280;display:block">Weapon</span><span style="font-size:18px;font-weight:800;color:#111827">' + d.weapon + '</span></div>';
                    html += '<div style="background:#f8fafc;border-radius:10px;padding:12px"><span style="font-size:11px;color:#6b7280;display:block">Target Mode</span><span style="font-size:18px;font-weight:800;color:#111827">' + d.target_mode + '</span></div>';
                    html += '</div>';
                    html += '<div style="display:flex;gap:4px;flex-wrap:wrap;margin-bottom:16px">';
                    var zones = [
                        {label:'Bullseye', count:d.bullseye_count, cls:'#fef3c7', txt:'#92400e'},
                        {label:'Alpha', count:d.alpha_count, cls:'#d1fae5', txt:'#065f46'},
                        {label:'Bravo', count:d.bravo_count, cls:'#dbeafe', txt:'#1e40af'},
                        {label:'Charlie', count:d.charlie_count, cls:'#fce7f3', txt:'#831843'},
                        {label:'Delta', count:d.delta_count, cls:'#f3e8ff', txt:'#5b21b6'},
                        {label:'Miss', count:d.miss_count, cls:'#fef2f2', txt:'#991b1b'},
                    ];
                    zones.forEach(function(z) {
                        html += '<span style="display:flex;align-items:center;gap:4px;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;background:' + z.cls + ';color:' + z.txt + '">' + z.label + ': ' + z.count + '</span>';
                    });
                    html += '</div>';
                    if (shots.length > 0) {
                        html += '<div style="font-size:13px;font-weight:700;color:#374151;margin-bottom:8px">Shot Results (' + shots.length + ' shots)</div>';
                        html += '<div style="display:flex;flex-wrap:wrap;gap:4px">';
                        shots.forEach(function(s) {
                            var bg = s.is_hit ? '#d1fae5' : '#fef2f2';
                            var cl = s.is_hit ? '#065f46' : '#991b1b';
                            var ic = s.is_hit ? '&#10003;' : '&#10007;';
                            html += '<span style="display:flex;align-items:center;gap:3px;padding:3px 8px;border-radius:5px;font-size:10px;font-weight:700;background:' + bg + ';color:' + cl + '">#' + s.shot_number + ' ' + ic + '</span>';
                        });
                        html += '</div>';
                    }
                    html += '</div>';
                    openModal('Marksmanship Details', html, d.weapon + ' \u00b7 ' + d.target_mode);
                } catch(e) {}
            });
        });

        // Assembly View
        document.querySelectorAll('.view-assembly').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var raw = this.dataset.row;
                try {
                    var d = JSON.parse(atob(raw));
                    var html = '<div style="margin-bottom:16px">';
                    html += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px">';
                    html += '<div style="background:#f8fafc;border-radius:10px;padding:12px"><span style="font-size:11px;color:#6b7280;display:block">Score</span><span style="font-size:18px;font-weight:800;color:#111827">' + d.score + '/' + d.max_score + '</span></div>';
                    html += '<div style="background:#f8fafc;border-radius:10px;padding:12px"><span style="font-size:11px;color:#6b7280;display:block">Wrong Attempts</span><span style="font-size:18px;font-weight:800;color:#111827">' + d.wrong_attempts + '</span></div>';
                    html += '<div style="background:#f8fafc;border-radius:10px;padding:12px"><span style="font-size:11px;color:#6b7280;display:block">Weapon</span><span style="font-size:18px;font-weight:800;color:#111827">' + d.simulation_slug + '</span></div>';
                    html += '<div style="background:#f8fafc;border-radius:10px;padding:12px"><span style="font-size:11px;color:#6b7280;display:block">Mode</span><span style="font-size:18px;font-weight:800;color:#111827">' + (d.mode === 'asm' ? 'Assemble' : 'Disassemble') + '</span></div>';
                    html += '</div>';
                    var order = d.parts_order || [];
                    var mistakes = d.mistakes || [];
                    var partAttempts = d.part_attempts || {};
                    if (order.length > 0) {
                        html += '<div style="font-size:13px;font-weight:700;color:#374151;margin-bottom:6px">Placement Order</div>';
                        html += '<div style="display:flex;flex-wrap:wrap;gap:3px;margin-bottom:12px">';
                        order.forEach(function(pid, idx) {
                            html += '<span style="padding:3px 8px;border-radius:5px;font-size:10px;font-weight:700;background:#f5f3ff;color:#6d28d9;border:1px solid #ddd6fe">' + (idx + 1) + '. ' + pid + '</span>';
                        });
                        html += '</div>';
                    }
                    if (Object.keys(partAttempts).length > 0) {
                        html += '<div style="font-size:13px;font-weight:700;color:#374151;margin-bottom:6px">Per-Part Attempts</div>';
                        html += '<div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:12px">';
                        Object.keys(partAttempts).forEach(function(pid) {
                            var att = partAttempts[pid];
                            var bg = att <= 1 ? '#d1fae5' : '#fef2f2';
                            var cl = att <= 1 ? '#065f46' : '#991b1b';
                            html += '<span style="padding:3px 8px;border-radius:5px;font-size:10px;font-weight:700;background:' + bg + ';color:' + cl + '">' + pid + ': ' + att + 'x</span>';
                        });
                        html += '</div>';
                    }
                    if (mistakes.length > 0) {
                        html += '<div style="font-size:13px;font-weight:700;color:#374151;margin-bottom:6px">Mistakes (' + mistakes.length + ')</div>';
                        html += '<div style="font-size:12px;color:#6b7280;line-height:1.6">';
                        mistakes.forEach(function(m) {
                            html += '<div style="padding:4px 0;border-bottom:1px solid #f3f4f6">Dropped <strong>' + (m.partId || '?') + '</strong> on <strong>' + (m.expectedPid || '?') + '</strong> zone</div>';
                        });
                        html += '</div>';
                    }
                    if (d.wrong_attempts === 0) {
                        html += '<div style="margin-top:12px;padding:10px 14px;background:#d1fae5;border-radius:10px;font-size:13px;font-weight:700;color:#065f46;text-align:center"><i class="fas fa-check-circle mr-1"></i> Perfect! No mistakes in correct order.</div>';
                    }
                    html += '</div>';
                    openModal('Assembly Details', html, 'Attempt \u00b7 ' + d.simulation_slug + ' \u00b7 ' + (d.mode === 'asm' ? 'Assemble' : 'Disassemble'));
                } catch(e) {}
            });
        });
    </script>
</body>
</html>