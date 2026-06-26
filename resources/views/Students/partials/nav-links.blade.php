@php
    $__navStudent = Auth::guard('student')->user();
    if (!$__navStudent && Auth::guard('web')->check() && Auth::guard('web')->user()->role === 'student') {
        $__navStudent = \App\Models\ManagedStudent::withArchived()->where('student_id_number', Auth::guard('web')->user()->email)->first();
    }
    $m1Unlocked = $__navStudent ? $__navStudent->isModuleUnlocked('module-1') : true;
    $m2Unlocked = $__navStudent ? $__navStudent->isModuleUnlocked('module-2') : false;
    $m4Unlocked = $__navStudent ? $__navStudent->isModuleUnlocked('module-4') : false;
@endphp

@if ($type === 'desktop')
            <!-- Center: Navigation (Desktop) -->
            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ route('student.dashboard') }}" class="nav-link {{ $activeNav === 'dashboard' ? 'active' : '' }} inline-flex items-center gap-1">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('student.gun-parts') }}" class="nav-link module-link {{ $activeNav === 'module-checkpoint-node' ? 'active' : '' }} inline-flex items-center gap-1" data-module-key="module-1" data-unlocked="{{ $m1Unlocked ? '1' : '0' }}">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 3h12v2H6V3zm0 4h12v12H6V7zm2 2v8h8V9H8z"/></svg>
                    Module
                </a>
                <a href="{{ route('student.reports') }}" class="nav-link {{ $activeNav === 'reports' ? 'active' : '' }} inline-flex items-center gap-1">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 2h9l5 5v13a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm7 1.5V8h4.5L13 3.5zM8 12h8v2H8v-2zm0 4h5v2H8v-2z"/></svg>
                    Assessment Reports
                </a>
            </nav>
@elseif ($type === 'mobile')
            <!-- Mobile Menu Dropdown -->
            <div id="mobile-menu" class="mobile-menu md:hidden bg-violet-900 border-t border-violet-800/50">
                <div class="px-4 py-3 space-y-1">
                    <a href="{{ route('student.dashboard') }}" class="block px-4 py-2.5 rounded-lg {{ $activeNav === 'dashboard' ? 'text-white bg-violet-800/50 font-medium' : 'text-violet-200 hover:bg-violet-800/30' }} text-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z"/></svg>
                            <span>Dashboard</span>
                        </span>
                    </a>
                    <a href="{{ route('student.gun-parts') }}" class="block px-4 py-2.5 rounded-lg module-link {{ $activeNav === 'module-checkpoint-node' ? 'text-white bg-violet-800/50 font-medium' : 'text-violet-200 hover:bg-violet-800/30' }} text-sm" data-module-key="module-1" data-unlocked="{{ $m1Unlocked ? '1' : '0' }}">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 3h12v2H6V3zm0 4h12v12H6V7zm2 2v8h8V9H8z"/></svg>
                            <span>Module</span>
                        </span>
                    </a>
                    <a href="{{ route('student.reports') }}" class="block px-4 py-2.5 rounded-lg {{ $activeNav === 'reports' ? 'text-white bg-violet-800/50 font-medium' : 'text-violet-200 hover:bg-violet-800/30' }} text-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 2h9l5 5v13a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm7 1.5V8h4.5L13 3.5zM8 12h8v2H8v-2zm0 4h5v2H8v-2z"/></svg>
                            <span>Reports</span>
                        </span>
                    </a>
                </div>
                <div class="px-4 py-3 border-t border-violet-800/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-violet-700 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($firstName ?: ($name ?? 'S'), 0, 1)) }}{{ strtoupper(substr($lastName ?: ($name ?? 'T'), 0, 1)) }}</div>
                        <div>
                            <div class="text-sm font-medium">{{ $name ?? 'Student' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button type="button" class="student-settings-btn px-3 py-1.5 text-violet-300 hover:text-white hover:bg-violet-800/30 rounded-lg text-xs font-medium" title="Settings">
                            <i class="fas fa-cog text-sm"></i>
                        </button>
                        <button onclick="showLogoutAlert()" class="px-3 py-1.5 text-red-300 hover:text-white hover:bg-red-800/30 rounded-lg text-xs font-medium">
                            <i class="fas fa-sign-out-alt text-sm mr-1"></i> Logout
                        </button>
                    </div>
                </div>
            </div>
@endif
<!-- Locked Module SweetAlert (global for student pages) -->
<div id="locked-module-overlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-lg text-center">
            <div class="w-14 h-14 mx-auto rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
            <svg class="w-7 h-7 align-middle" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 17a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM17 8V7a5 5 0 1 0-10 0v1H5v11h14V8h-2zm-8-1a3 3 0 1 1 6 0v1H9V7z"/></svg>
        </div>
        <h3 id="locked-module-title" class="font-display font-bold text-lg text-gray-900 mb-2">Module Locked</h3>
        <p id="locked-module-desc" class="text-sm text-gray-600 mb-4">This module is currently locked. Please contact your instructor to request access.</p>
        <div class="flex gap-3 justify-center">
            <button id="locked-module-close" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700">OK</button>
        </div>
    </div>
</div>

<div id="student-settings-modal" class="fixed inset-0 z-[99999] hidden opacity-0 pointer-events-none items-center justify-center p-4 modal-backdrop transition-opacity duration-200 ease-out backdrop-blur-sm bg-slate-950/35">
    <div class="modal-panel w-full max-w-lg rounded-3xl bg-white shadow-2xl overflow-hidden transform scale-95 translate-y-3 opacity-0 transition-all duration-200 ease-out">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-4">
            <div>
                <h3 class="font-display font-bold text-xl text-gray-900">Profile Settings</h3>
                <p class="text-sm text-gray-500">Your student account details.</p>
            </div>
            <button type="button" class="close-student-settings w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors"><i class="fas fa-xmark"></i></button>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                <div class="w-14 h-14 rounded-full bg-violet-100 flex items-center justify-center text-xl font-bold text-violet-700 flex-shrink-0">
                    {{ strtoupper(substr($firstName ?: ($name ?? 'S'), 0, 1)) }}{{ strtoupper(substr($lastName ?: ($name ?? 'T'), 0, 1)) }}
                </div>
                <div>
                    <div class="font-display font-bold text-base text-gray-900">{{ $name ?? 'Student' }}</div>
                    <div class="text-sm text-gray-500">{{ $__studentIdNumber ?? '' }}</div>
                    <div class="text-xs text-violet-600 font-medium">{{ $__yearLevel ?? '' }} &middot; {{ $__section ?? 'Student Portal' }}</div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <h4 class="font-display font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-edit text-violet-600"></i> Edit Name
                    </h4>
                    <form id="student-name-form" onsubmit="return false;">
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <input type="text" id="student-name-input" value="{{ $name ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" placeholder="Full name">
                            </div>
                            <button type="submit" class="px-5 py-3 rounded-xl bg-violet-700 text-white text-sm font-bold hover:bg-violet-800 transition-colors whitespace-nowrap">
                                <i class="fas fa-check"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
                <div class="pt-6 border-t border-gray-100">
                    <h4 class="font-display font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-lock text-violet-600"></i> Change Password
                    </h4>
                    <form id="student-password-form" onsubmit="return false;">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Current Password</label>
                                <input type="password" name="current_password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" placeholder="Enter current password">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">New Password</label>
                                <input type="password" name="new_password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" placeholder="Min. 8 characters" minlength="8">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" placeholder="Re-enter new password" minlength="8">
                            </div>
                        </div>
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

<script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@7.3.0/dist/turbo.min.js" data-turbo-eval="false"></script>
<script>
    (function () {
        // Initialize locked-module handler on initial load and after Turbo navigations
        function initModuleNav() {
            const overlay = document.getElementById('locked-module-overlay');
            const closeBtn = document.getElementById('locked-module-close');

            if (window.__moduleNav && window.__moduleNav.initialized) return;
            window.__moduleNav = { initialized: true };

            function showLockedModal(moduleKey) {
                const title = document.getElementById('locked-module-title');
                const desc = document.getElementById('locked-module-desc');
                title.textContent = 'Module Locked';
                desc.textContent = 'This module is currently locked. Please complete the previous module first.';
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            }

            function hideLockedModal() {
                overlay.classList.remove('flex');
                overlay.classList.add('hidden');
            }

            document.addEventListener('click', function (e) {
                const link = e.target.closest('.module-link');
                if (!link) return;
                const unlocked = link.getAttribute('data-unlocked') === '1';
                if (!unlocked) {
                    e.preventDefault();
                    const moduleKey = link.getAttribute('data-module-key');
                    showLockedModal(moduleKey);
                }
            });

            closeBtn?.addEventListener('click', hideLockedModal);
            overlay?.addEventListener('click', function (e) { if (e.target === overlay) hideLockedModal(); });
        }

        // --- Student Settings Modal ---
        function initSettingsModal() {
            const modal = document.getElementById('student-settings-modal');
            const settingsBtns = document.querySelectorAll('.student-settings-btn');
            const closeBtns = document.querySelectorAll('.close-student-settings');
            if (!modal) return;
            if (window.__settingsNav && window.__settingsNav.initialized) return;
            window.__settingsNav = { initialized: true };

            function openModal() {
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
            }

            function closeModal() {
                const panel = modal.querySelector('.modal-panel');
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0', 'pointer-events-none');
                if (panel) {
                    panel.classList.remove('scale-100', 'translate-y-0', 'opacity-100');
                    panel.classList.add('scale-95', 'translate-y-3', 'opacity-0');
                }
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 200);
            }

            settingsBtns.forEach(btn => btn.addEventListener('click', openModal));
            closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
            modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });
        }

        document.addEventListener('turbo:load', () => { initModuleNav(); initSettingsModal(); });
        document.addEventListener('DOMContentLoaded', () => { initModuleNav(); initSettingsModal(); });
    })();
</script>
