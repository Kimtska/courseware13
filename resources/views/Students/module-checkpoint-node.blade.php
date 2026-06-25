<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IOT-Based Marksmanship - Gun Parts</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/Marksmanship innovatech.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{font-family:'Inter',sans-serif;background:linear-gradient(180deg,#f8fafc 0%,#eef2ff 100%);min-height:100vh}
        .card{background:#fff;border:1px solid #e5e7eb;border-radius:20px;box-shadow:0 18px 45px -20px rgba(30,5,82,.15)}
        .presentation-shell{background:linear-gradient(180deg,rgba(255,255,255,.96),rgba(248,250,252,.98));border:1px solid #ddd6fe;border-radius:28px;box-shadow:0 26px 70px -28px rgba(30,5,82,.2);overflow:hidden;min-height:clamp(760px,86vh,980px);display:flex;flex-direction:column;flex:1;min-width:0}
        .presentation-stage{position:relative;flex:1;min-height:0;height:100%}
        .presentation-page{position:absolute;inset:0;padding:24px 28px;opacity:0;transform:translateX(18px);pointer-events:none;transition:opacity .28s ease,transform .28s ease;display:flex;flex-direction:column;min-height:0;overflow:hidden}
        .presentation-page.active{opacity:1;transform:translateX(0);pointer-events:auto}
        .presentation-content{display:flex;flex-direction:column;justify-content:flex-start;gap:1rem;min-height:0;flex:1;overflow:hidden;position:relative}
        .presentation-content .layer-delete-zone,
        .presentation-content .layer-handle{display:none!important}
        .presentation-body{display:flex;flex-direction:column;gap:1.25rem;min-height:0;flex:1;overflow-y:auto;padding-right:6px}
        .presentation-kicker{font-size:11px;letter-spacing:.28em;text-transform:uppercase;font-weight:800;color:#7c3aed}
        .presentation-title{font-family:'Space Grotesk',sans-serif;font-weight:800}
        .presentation-nav{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
        .presentation-btn{display:inline-flex;align-items:center;gap:.5rem;border-radius:14px;border:1px solid #ddd6fe;background:#fff;padding:12px 16px;font-size:14px;font-weight:800;color:#6d28d9;transition:all .2s}
        .presentation-btn:hover{background:#f5f3ff;border-color:#c4b5fd;transform:translateY(-1px)}
        .presentation-btn:disabled{opacity:.45;cursor:not-allowed;transform:none}
        .presentation-page-counter{font-size:14px;font-weight:700;color:#6d28d9;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:14px;padding:8px 20px;user-select:none}
        .nav-link{position:relative;padding:8px 16px;color:rgba(255,255,255,0.65);transition:all .2s;font-size:13px;font-weight:500;border-radius:6px;white-space:nowrap}
        .nav-link:hover{color:#fff;background:rgba(255,255,255,0.1)}
        .nav-link.active{color:#fff;background:rgba(255,255,255,0.15);font-weight:600}
        .nav-link.active::after{content:'';position:absolute;bottom:-14px;left:50%;transform:translateX(-50%);width:20px;height:3px;background:#A78BFA;border-radius:3px}
        .mobile-menu{transform:translateY(-100%);opacity:0;transition:all .3s ease;pointer-events:none}
        .mobile-menu.open{transform:translateY(0);opacity:1;pointer-events:auto}
        .presentation-wrapper{display:flex;gap:24px;align-items:flex-start;width:100%}
        @media(max-width:1023px){.presentation-wrapper{flex-direction:column;gap:16px}}

        .cp-sidebar{flex:0 0 380px;position:relative;padding:20px 0 20px 18px;display:flex;flex-direction:column;gap:24px;overflow:hidden;border-radius:16px}
        @media(max-width:1023px){.cp-sidebar{flex:1 1 auto;width:100%;padding:20px 0 20px 36px;margin-bottom:0;gap:16px}}
        .cp-track{position:absolute;top:0;bottom:0;left:37px;width:2.5px;border-radius:4px;background:#e9d5ff;overflow:hidden;z-index:0;transform:translateX(-50%)}
        .cp-track-fill{position:absolute;top:0;left:0;width:100%;height:0%;background:linear-gradient(180deg,#a78bfa,#7c3aed);border-radius:4px;transition:height .6s cubic-bezier(.4,0,.2,1)}

        .cp-module{position:relative;z-index:1;display:flex;align-items:flex-start;gap:14px;flex-shrink:0}
        .cp-node{width:38px;height:38px;min-width:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;font-family:'Rajdhani','Inter',sans-serif;border:2.5px solid;position:relative;z-index:2;background:#fff;transition:all .35s cubic-bezier(.4,0,.2,1);flex-shrink:0}
        .cp-node.completed{background:#7c3aed;border-color:#7c3aed;color:#fff;box-shadow:0 0 0 4px rgba(124,58,237,.12),0 4px 18px -6px rgba(124,58,237,.45)}
        .cp-node.completed .cp-node-check{width:16px;height:16px}
        .cp-node.completed .cp-node-glow{position:absolute;inset:-6px;border-radius:50%;background:radial-gradient(circle,rgba(124,58,237,.25) 0%,transparent 70%);animation:cpGlowPulse 2.5s ease-in-out infinite;pointer-events:none}
        .cp-node.in-progress{background:#fff;border-color:#7c3aed;color:#7c3aed;box-shadow:0 0 0 4px rgba(124,58,237,.10),0 4px 16px -6px rgba(124,58,237,.35)}
        .cp-node.in-progress .cp-node-pulse{position:absolute;inset:-8px;border-radius:50%;border:2px solid rgba(124,58,237,.2);animation:cpPulseRing 2s ease-in-out infinite;pointer-events:none}
        .cp-node.locked{background:#f1f0f5;border-color:#d4d0db;color:#aca7b8}
        @keyframes cpGlowPulse{0%,100%{opacity:.6;transform:scale(1)}50%{opacity:1;transform:scale(1.15)}}
        @keyframes cpPulseRing{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.25);opacity:0}}

        .cp-card{flex:1;background:#fff;border:1px solid #e9e3f5;border-radius:16px;overflow:hidden;box-shadow:0 2px 10px -4px rgba(90,30,150,.08);transition:box-shadow .25s}
        .cp-card:hover{box-shadow:0 6px 22px -8px rgba(90,30,150,.12)}
        .cp-card-header{display:flex;align-items:center;gap:14px;padding:14px 16px;cursor:pointer;user-select:none;transition:background .2s;position:relative}
        .cp-card-header:hover{background:#faf9ff}
        .cp-icon-box{width:40px;height:40px;min-width:40px;border-radius:12px;background:linear-gradient(135deg,#f5f0ff,#ede5ff);display:flex;align-items:center;justify-content:center;color:#7c3aed;font-size:17px;flex-shrink:0}
        .cp-module.completed .cp-icon-box{background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff}
        .cp-card-info{flex:1;min-width:0}
        .cp-card-title{font-family:'Rajdhani','Inter',sans-serif;font-size:17px;font-weight:700;color:#2d1b69;line-height:1.25;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .cp-module.locked .cp-card-title{color:#aca7b8}
        .cp-card-meta{display:flex;align-items:center;gap:6px;font-size:12px;color:#a78bfa;margin-top:2px}
        .cp-module.locked .cp-card-meta{color:#c8c3d3}
        .cp-card-meta .dot{color:#d8b4fe}
        .cp-progress-bar{width:64px;min-width:64px;height:3px;border-radius:4px;background:#ede9f7;overflow:hidden}
        .cp-progress-fill{height:100%;border-radius:4px;background:linear-gradient(90deg,#a78bfa,#7c3aed);transition:width .6s cubic-bezier(.4,0,.2,1)}
        .cp-module.locked .cp-progress-fill{background:#d4d0db}
        .cp-chevron{width:28px;height:28px;border:none;background:transparent;color:#c4b5fd;cursor:pointer;display:flex;align-items:center;justify-content:center;border-radius:8px;transition:all .3s ease;font-size:14px;flex-shrink:0}
        .cp-chevron:hover{background:#f5f0ff;color:#7c3aed}
        .cp-chevron.rotated{transform:rotate(180deg)}

        .cp-card-body{max-height:0;overflow:hidden;transition:max-height .35s cubic-bezier(.16,1,.3,1),opacity .3s;opacity:0;border-top:0 solid #e9e3f5}
        .cp-card-body.open{max-height:600px;opacity:1;border-top-width:1px;border-style:solid}

        .cp-lesson{display:flex;align-items:center;gap:10px;padding:10px 16px;transition:all .2s;border-left:3px solid transparent;cursor:pointer}
        .cp-lesson:hover{background:#faf9ff}
        .cp-lesson.active{border-left-color:#7c3aed;background:linear-gradient(90deg,rgba(124,58,237,.05),transparent)}
        .cp-lesson-icon{width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;color:#9188a5}
        .cp-lesson.completed .cp-lesson-icon{color:#22c55e}
        .cp-lesson.active .cp-lesson-icon{color:#7c3aed}
        .cp-lesson.locked .cp-lesson-icon{color:#d4d0db}
        .cp-lesson-title{flex:1;font-size:13px;font-weight:500;color:#3d2a6b;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .cp-lesson.locked .cp-lesson-title{color:#aca7b8}
        .cp-badge{font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:.02em;text-transform:uppercase;flex-shrink:0;line-height:1.4}
        .cp-badge-lavender{background:#ede5ff;color:#7c3aed}
        .cp-badge-yellow{background:#fef7e6;color:#d97706}
        .cp-badge-green{background:#dcfce7;color:#16a34a}

        .cp-toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(120px);background:#2d1b69;color:#fff;padding:14px 24px;border-radius:14px;display:flex;align-items:center;gap:10px;font-size:14px;font-weight:600;box-shadow:0 8px 32px -8px rgba(45,27,105,.35);z-index:9999;opacity:0;transition:all .35s cubic-bezier(.16,1,.3,1);pointer-events:none}
        .cp-toast.show{opacity:1;transform:translateX(-50%) translateY(0);pointer-events:auto}
        .cp-toast i{color:#c4b5fd;font-size:16px}

        @media(max-width:1023px){
            .cp-track{left:55px}
        }
        @media(max-width:767px){
            .cp-sidebar{padding:16px 0 16px 34px}
            .cp-track{left:49px;top:38px;bottom:38px}
            .cp-node{width:30px;height:30px;min-width:30px;font-size:12px}
            .cp-card-header{padding:12px 14px;gap:10px;flex-wrap:wrap}
            .cp-icon-box{width:34px;height:34px;min-width:34px;font-size:15px}
            .cp-card-title{font-size:15px}
            .cp-progress-bar{width:48px;min-width:48px}
            .cp-card-body.open{max-height:900px}
            .cp-lesson{padding:9px 14px}
        }
        @keyframes cpShake{0%,100%{transform:translateX(0)}20%{transform:translateX(-5px)}40%{transform:translateX(5px)}60%{transform:translateX(-4px)}80%{transform:translateX(4px)}}
    </style>
    @include('shared.back-button-prevention')
</head>
<body>
    @php
        $embedded = request()->boolean('embedded');
    @endphp
    @unless($embedded)
    <header id="main-header" data-turbo-permanent class="bg-violet-950 text-white sticky top-0 z-50 shadow-lg shadow-violet-950/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3 flex-shrink-0">
                    <img src="{{ asset('images/assets/Marksmanship innovatech.png') }}" alt="SPC" class="h-9 w-auto">
                    <div class="hidden sm:block">
                        <span class="font-display font-bold text-sm">IOT-Based Marksmanship</span>
                        <span class="block text-[8px] text-violet-300 uppercase tracking-widest leading-none">Student Portal</span>
                    </div>
                </div>
                <div>
                    @include('Students.partials.nav-links', ['type' => 'desktop', 'activeNav' => 'module-checkpoint-node'])
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-2 pl-3 border-l border-violet-800/50">
                        <div class="w-8 h-8 rounded-full bg-violet-700 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($firstName ?: ($name ?? 'S'), 0, 1)) }}{{ strtoupper(substr($lastName ?: ($name ?? 'T'), 0, 1)) }}</div>
                        <span class="text-sm font-medium">{{ $name ?? 'Student' }}</span>
                    </div>
                    <button type="button" class="student-settings-btn p-2 rounded-lg hover:bg-violet-800/50 transition-colors text-violet-300 hover:text-white" title="Settings" aria-label="Settings">
                        <i class="fas fa-cog text-sm"></i>
                    </button>
                    <button onclick="showLogoutAlert()" type="button" class="p-2 rounded-lg hover:bg-violet-800/50 transition-colors text-violet-300 hover:text-white" title="Logout" aria-label="Logout">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                    <button id="mobile-toggle" class="md:hidden p-2 rounded-lg hover:bg-violet-800/50 transition-colors" type="button">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
        @include('Students.partials.nav-links', ['type' => 'mobile', 'activeNav' => 'module-checkpoint-node'])
    </header>
    @endunless

    <main class="max-w-full px-6 sm:px-8 py-8">

        <div class="presentation-wrapper">
            <aside class="cp-sidebar" aria-label="Course progress">
                <div class="cp-track">
                    <div class="cp-track-fill" id="track-fill"></div>
                </div>

                @php
                    $moduleIcons = ['module-1' => 'fa-book-open', 'module-2' => 'fa-wrench', 'module-3' => 'fa-tools'];
                @endphp

                @foreach ($modules as $mIdx => $mod)
                @php
                    $modKey = $mod->module_key;
                    $modNum = $mIdx + 1;
                    $lessonCount = $mod->lessons->count();
                    $isActive = $modKey === $moduleKey;
                    // Check module access control
                    $access = \App\Models\ModuleAccessControl::where('module_key', $modKey)->first();
                    $isUnlocked = $access && $access->is_unlocked;
                    $canAccess = $isUnlocked || $mIdx === 0; // module-1 is always accessible
                    $stateClass = $isActive ? 'active' : ($canAccess && !$isActive ? 'completed' : 'locked');
                    $icon = $moduleIcons[$modKey] ?? 'fa-book';
                    $nodeState = $isActive ? 'in-progress' : ($canAccess ? 'completed' : 'locked');
                @endphp
                <div class="cp-module {{ $stateClass }}" data-cp="{{ $mIdx }}" data-module-key="{{ $modKey }}">
                    <div class="cp-node {{ $nodeState }}" data-state="{{ $nodeState }}">
                        @if ($canAccess && !$isActive)
                        <svg class="cp-node-check" viewBox="0 0 16 16" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13.5 4.5L6 12l-3.5-3.5"/></svg>
                        <div class="cp-node-glow"></div>
                        @elseif ($isActive)
                        <span>{{ $modNum }}</span>
                        <div class="cp-node-pulse"></div>
                        @else
                        <span>{{ $modNum }}</span>
                        @endif
                    </div>
                    <div class="cp-card" @if ($isActive) style="border-color:#c4b5fd" @endif>
                        <div class="cp-card-header" data-toggle="cp-body-{{ $mIdx }}">
                            <div class="cp-icon-box"><i class="fa-solid {{ $icon }}"></i></div>
                            <div class="cp-card-info">
                                <div class="cp-card-title">{{ $mod->title }}</div>
                                <div class="cp-card-meta"><i class="fa-regular fa-clock"></i> {{ $lessonCount }} lessons</div>
                            </div>
                            <div class="cp-progress-bar"><div class="cp-progress-fill" style="width:{{ $isActive ? '0%' : ($canAccess ? '100%' : '0%') }}"></div></div>
                            <button class="cp-chevron {{ $isActive ? 'rotated' : '' }}" type="button"><i class="fa-solid fa-chevron-down"></i></button>
                        </div>
                        <div class="cp-card-body {{ $isActive ? 'open' : '' }}" id="cp-body-{{ $mIdx }}">
                            @foreach ($mod->lessons as $l)
                            <div class="cp-lesson {{ $canAccess ? 'completed' : 'locked' }}" data-module="{{ $modKey }}" data-lesson-key="{{ $l->key }}">
                                <span class="cp-lesson-icon"><i class="fa-solid {{ $canAccess ? 'fa-circle-check' : 'fa-lock' }}"></i></span>
                                <span class="cp-lesson-title">{{ $l->title }}</span>
                                <span class="cp-badge cp-badge-lavender">Lesson</span>
                            </div>
                            @endforeach
                            <div class="cp-lesson {{ $canAccess ? '' : 'locked' }}" data-module="{{ $modKey }}" data-lesson-key="assessment">
                                <span class="cp-lesson-icon"><i class="fa-solid {{ $canAccess ? 'fa-file-pen' : 'fa-lock' }}"></i></span>
                                <span class="cp-lesson-title">Module {{ $modNum }} Assessment</span>
                                <span class="cp-badge cp-badge-yellow">Assessment</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </aside>

            <div class="cp-toast" id="locked-toast"><i class="fa-solid fa-lock"></i> <span>Complete the previous module first</span></div>

            <div class="presentation-shell w-full">
                <div class="presentation-stage presentation-stage-main">
                    @include('Students.partials.lesson-presentation-shell', ['moduleKey' => $moduleKey])
                </div>
            </div>
        </div>
    </main>

    @unless($embedded)
    @include('Students.partials.module-access-watch', ['currentModuleKey' => $moduleKey, 'currentModuleLabel' => $activeModule?->title ?? ''])
    @include('shared.sweet-alerts.logout', ['logoutLabel' => 'Student — ' . ($name ?? 'Student'), 'logoutSubtext' => $activeModule?->title ?? '', 'logoutDescription' => 'You are about to end your session.', 'redirectUrl' => url('/login')])
    @endunless

    <script>
        const mobileToggle = document.getElementById('mobile-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        function initPresentation() {
            const shell = document.querySelector('.presentation-stage-main');
            if (!shell) return;
            const pages = Array.from(shell.querySelectorAll('.presentation-page'));
            const counterEl = document.getElementById('page-counter');
            const prevButton = document.getElementById('presentation-prev');
            const nextButton = document.getElementById('presentation-next');
            if (pages.length === 0) return;
            let currentPage = 0;
            let highestVisitedPage = 0;

            function updatePage() {
                pages.forEach((p, i) => p.classList.toggle('active', i === currentPage));
                if (counterEl) counterEl.textContent = (currentPage + 1) + ' / ' + pages.length;
                if (prevButton) prevButton.disabled = currentPage === 0;
                if (nextButton) nextButton.disabled = currentPage === pages.length - 1;
            }

            function goToPage(idx) {
                currentPage = Math.max(0, Math.min(idx, pages.length - 1));
                highestVisitedPage = Math.max(highestVisitedPage, currentPage);
                updatePage();
            }

            prevButton?.addEventListener('click', () => goToPage(currentPage - 1));
            nextButton?.addEventListener('click', () => goToPage(currentPage + 1));
            updatePage();
        }

        function switchModule(moduleKey) {
            const params = new URLSearchParams(window.location.search);
            params.set('module', moduleKey);
            window.location.search = params.toString();
        }

        // Sidebar module toggle
        document.querySelector('.cp-sidebar')?.addEventListener('click', (e) => {
            const header = e.target.closest('.cp-card-header');
            const lesson = e.target.closest('.cp-lesson');

            if (header) {
                const mod = header.closest('.cp-module');
                if (!mod) return;
                const modKey = mod.dataset.moduleKey;
                if (mod.classList.contains('locked')) {
                    const toast = document.getElementById('locked-toast');
                    if (toast) {
                        toast.classList.add('show');
                        setTimeout(() => toast.classList.remove('show'), 3000);
                    }
                    return;
                }
                if (modKey && !mod.classList.contains('active')) {
                    switchModule(modKey);
                    return;
                }
                const body = mod.querySelector('.cp-card-body');
                const chevron = header.querySelector('.cp-chevron');
                if (body) {
                    body.classList.toggle('open');
                    if (chevron) chevron.classList.toggle('rotated');
                }
                return;
            }

            if (lesson) {
                const mod = lesson.closest('.cp-module');
                if (!mod || mod.classList.contains('locked')) return;
                const modKey = lesson.dataset.module;
                const lessonKey = lesson.dataset.lessonKey;

                if (modKey && !mod.classList.contains('active')) {
                    switchModule(modKey);
                } else if (lessonKey && typeof window.jumpToLessonPage === 'function') {
                    window.jumpToLessonPage(lessonKey);
                }
            }
        });

        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('open');
                const icon = mobileToggle.querySelector('i');
                if (icon) { icon.classList.toggle('fa-bars'); icon.classList.toggle('fa-times'); }
            });
        }

        initPresentation();
    </script>
</body>
</html>
