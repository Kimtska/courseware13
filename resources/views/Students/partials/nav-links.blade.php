@php
    $moduleStates = \App\Models\ModuleAccessControl::whereIn('module_key', ['module-1','module-2','module-3','module-4'])->get()->keyBy('module_key');
    $m1Unlocked = ($moduleStates->get('module-1') && $moduleStates->get('module-1')->is_unlocked);
    $m2Unlocked = ($moduleStates->get('module-2') && $moduleStates->get('module-2')->is_unlocked);
    $m3Unlocked = ($moduleStates->get('module-3') && $moduleStates->get('module-3')->is_unlocked);
    $m4Unlocked = ($moduleStates->get('module-4') && $moduleStates->get('module-4')->is_unlocked);
@endphp

@if ($type === 'desktop')
            <!-- Center: Navigation (Desktop) -->
            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ route('student.dashboard') }}" class="nav-link {{ $activeNav === 'dashboard' ? 'active' : '' }} inline-flex items-center gap-1">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('student.gun-parts') }}" class="nav-link module-link {{ $activeNav === 'gun-parts' ? 'active' : '' }} inline-flex items-center gap-1" data-module-key="module-1" data-unlocked="{{ $m1Unlocked ? '1' : '0' }}">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 3h12v2H6V3zm0 4h12v12H6V7zm2 2v8h8V9H8z"/></svg>
                    Lessons
                </a>
                <a href="{{ route('student.assembly') }}" class="nav-link module-link {{ $activeNav === 'assembly' ? 'active' : '' }} inline-flex items-center gap-1" data-module-key="module-3" data-unlocked="{{ $m3Unlocked ? '1' : '0' }}">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M21 13.5v5.5a1 1 0 0 1-1 1h-5.5l-6-6V8.5A3.5 3.5 0 0 1 13 5h2.5L21 9.5V13.5zM3 21v-2.5l8.5-8.5 2.5 2.5L5.5 21H3z"/></svg>
                    Assembly
                </a>
                <a href="{{ route('student.reports') }}" class="nav-link {{ $activeNav === 'reports' ? 'active' : '' }} inline-flex items-center gap-1">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 2h9l5 5v13a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1zm7 1.5V8h4.5L13 3.5zM8 12h8v2H8v-2zm0 4h5v2H8v-2z"/></svg>
                    Reports
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
                    <a href="{{ route('student.gun-parts') }}" class="block px-4 py-2.5 rounded-lg module-link {{ $activeNav === 'gun-parts' ? 'text-white bg-violet-800/50 font-medium' : 'text-violet-200 hover:bg-violet-800/30' }} text-sm" data-module-key="module-1" data-unlocked="{{ $m1Unlocked ? '1' : '0' }}">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 3h12v2H6V3zm0 4h12v12H6V7zm2 2v8h8V9H8z"/></svg>
                            <span>Lessons</span>
                        </span>
                    </a>
                    <a href="{{ route('student.assembly') }}" class="block px-4 py-2.5 rounded-lg module-link {{ $activeNav === 'assembly' ? 'text-white bg-violet-800/50 font-medium' : 'text-violet-200 hover:bg-violet-800/30' }} text-sm" data-module-key="module-3" data-unlocked="{{ $m3Unlocked ? '1' : '0' }}">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M21 13.5v5.5a1 1 0 0 1-1 1h-5.5l-6-6V8.5A3.5 3.5 0 0 1 13 5h2.5L21 9.5V13.5zM3 21v-2.5l8.5-8.5 2.5 2.5L5.5 21H3z"/></svg>
                            <span>Assembly</span>
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
                            <div class="text-xs text-violet-300">Student</div>
                        </div>
                    </div>
                    <button onclick="showLogoutAlert()" class="px-3 py-1.5 text-red-300 hover:text-white hover:bg-red-800/30 rounded-lg text-xs font-medium">
                        <i class="fas fa-sign-out-alt text-sm mr-1"></i> Logout
                    </button>
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

<script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@7.3.0/dist/turbo.min.js" data-turbo-eval="false"></script>
<script>
    (function () {
        // Initialize module polling and locked-module handlers on initial load and after Turbo navigations
        function initModuleNav() {
            const overlay = document.getElementById('locked-module-overlay');
            const closeBtn = document.getElementById('locked-module-close');
            const statesUrl = "{{ route('student.module-states') }}";

            // Avoid duplicate intervals/handlers across navigations
            if (window.__moduleNav && window.__moduleNav.initialized) return;
            window.__moduleNav = { initialized: true };

            let lastBlockedModuleKey = null;

            function showLockedModal(moduleKey) {
                const title = document.getElementById('locked-module-title');
                const desc = document.getElementById('locked-module-desc');
                title.textContent = 'Module Locked';
                desc.textContent = 'This module is currently locked. Please contact your instructor to request access.';
                lastBlockedModuleKey = moduleKey;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            }

            function hideLockedModal() {
                lastBlockedModuleKey = null;
                overlay.classList.remove('flex');
                overlay.classList.add('hidden');
            }

            function updateModuleElements(moduleKey, unlocked) {
                const anchors = document.querySelectorAll('[data-module-key="' + moduleKey + '"]');
                anchors.forEach(anchor => {
                    anchor.setAttribute('data-unlocked', unlocked ? '1' : '0');
                    const badge = anchor.querySelector('span.inline-flex');
                    if (badge) {
                        if (unlocked) {
                            badge.textContent = 'Unlocked';
                            badge.classList.remove('bg-rose-100', 'text-rose-800', 'border-rose-200');
                            badge.classList.add('bg-emerald-100', 'text-emerald-800', 'border-emerald-200');
                        } else {
                            badge.textContent = 'Locked';
                            badge.classList.remove('bg-emerald-100', 'text-emerald-800', 'border-emerald-200');
                            badge.classList.add('bg-rose-100', 'text-rose-800', 'border-rose-200');
                        }
                    }
                });
            }

            const AUTO_REDIRECT_ON_UNLOCK = overlay?.dataset?.autoRedirect === '1' || overlay?.dataset?.autoRedirect === 'true' || false;

            async function pollStates() {
                try {
                    const res = await fetch(statesUrl, { credentials: 'same-origin' });
                    if (!res.ok) return;
                    const data = await res.json();
                    const modules = data.modules || {};
                    Object.keys(modules).forEach(k => {
                        const unlocked = !!modules[k].is_unlocked;
                        updateModuleElements(k, unlocked);
                        if (lastBlockedModuleKey === k && unlocked) {
                            hideLockedModal();
                            if (AUTO_REDIRECT_ON_UNLOCK) {
                                const anchor = document.querySelector('[data-module-key="' + k + '"]');
                                if (anchor && anchor.href) window.location.href = anchor.href;
                            }
                        }
                    });
                    try { window.__moduleStates = modules; } catch (e) { /* ignore */ }
                    window.dispatchEvent(new CustomEvent('moduleStatesUpdated', { detail: modules }));
                } catch (err) {
                    console.error('Module states poll error', err);
                }
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

            pollStates();
            setInterval(() => { if (!document.hidden) pollStates(); }, 15000);
        }

        document.addEventListener('turbo:load', initModuleNav);
        document.addEventListener('DOMContentLoaded', initModuleNav);
    })();
</script>
