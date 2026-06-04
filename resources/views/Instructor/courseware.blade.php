@extends('Instructor.layout')

@section('title', 'Courseware')
@section('pageTitle', 'Courseware')
@section('pageSubtitle', 'Instructor module view with courseware content')

@section('headerActions')
    <div class="flex items-center gap-3">
        <form method="POST" action="{{ route('instructor.manage-portal.unlock', $moduleKey) }}" data-module-access-form>
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg {{ ($moduleState->is_unlocked ?? false) ? 'bg-rose-600 hover:bg-rose-700' : 'bg-violet-700 hover:bg-violet-800' }} text-white text-xs font-bold uppercase transition-colors" data-module-access-button>
                <i class="fas {{ ($moduleState->is_unlocked ?? false) ? 'fa-lock' : 'fa-lock-open' }}"></i>
                <span>{{ ($moduleState->is_unlocked ?? false) ? 'Lock Module Access' : 'Unlock Module Access?' }}</span>
            </button>
        </form>
    </div>
@endsection

@section('content')
    @if (session('status'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <i class="fas fa-circle-check mr-2"></i>{{ session('status') }}
        </div>
    @endif

    <style>
        .card{background:#fff;border:1px solid #e5e7eb;border-radius:20px;box-shadow:0 18px 45px -20px rgba(30,5,82,.15)}
        .presentation-shell{background:linear-gradient(180deg,rgba(255,255,255,.96),rgba(248,250,252,.98));border:1px solid #ddd6fe;border-radius:28px;box-shadow:0 26px 70px -28px rgba(30,5,82,.2);overflow:hidden;min-height:clamp(760px,86vh,980px);display:flex;flex-direction:column}
        .presentation-stage{position:relative;flex:1;min-height:0;height:100%}
        .presentation-page{position:absolute;inset:0;padding:24px 28px;opacity:0;transform:translateX(18px);pointer-events:none;transition:opacity .28s ease,transform .28s ease;display:flex;flex-direction:column;min-height:0;overflow:hidden}
        .presentation-page.active{opacity:1;transform:translateX(0);pointer-events:auto}
        .presentation-content{display:flex;flex-direction:column;justify-content:flex-start;gap:1rem;min-height:0;flex:1;overflow:hidden;position:relative}
        .presentation-content .layer-delete-zone,
        .presentation-content .layer-handle{display:none!important}
        .presentation-body{display:flex;flex-direction:column;gap:1.25rem;min-height:0;flex:1;overflow-y:auto;padding-right:6px}
        .presentation-kicker{font-size:11px;letter-spacing:.28em;text-transform:uppercase;font-weight:800;color:#7c3aed}
        .presentation-title{font-family:'Space Grotesk',sans-serif;font-weight:800;color:#111827}
        .presentation-nav{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
        .presentation-btn{display:inline-flex;align-items:center;gap:.5rem;border-radius:14px;border:1px solid #ddd6fe;background:#fff;padding:12px 16px;font-size:14px;font-weight:800;color:#6d28d9;transition:all .2s}
        .presentation-btn:hover{background:#f5f3ff;border-color:#c4b5fd;transform:translateY(-1px)}
        .presentation-btn:disabled{opacity:.45;cursor:not-allowed;transform:none}
        .presentation-dots{display:flex;gap:8px;align-items:center;justify-content:center}
        .presentation-dot{width:10px;height:10px;border-radius:9999px;background:#ddd6fe;transition:all .2s;cursor:pointer}
        .presentation-dot.active{width:28px;background:#7c3aed}
        .presentation-wrapper{position:relative;display:block;width:100%;overflow:visible}
        .presentation-shell-container{max-width:1040px;margin:0 auto;position:relative}
        .checkpoint-sidebar{position:absolute;left:0;top:0;bottom:0;transform:translateX(calc(-100% - 18px));width:130px;display:flex;flex-direction:column;padding:32px 0}
        .checkpoint-track{position:absolute;top:52px;bottom:52px;left:19px;width:3px;border-radius:4px;background:#e9d5ff;overflow:hidden;z-index:0}
        .checkpoint-track-fill{position:absolute;top:0;left:0;width:100%;height:0%;background:linear-gradient(180deg,#8b5cf6,#7c3aed);border-radius:4px;transition:height .45s cubic-bezier(.4,0,.2,1)}
        .checkpoint-item{position:relative;z-index:1;display:flex;flex-direction:row;align-items:center;gap:12px;cursor:pointer;flex:1;padding:4px 0}
        .checkpoint-node{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;border:2.5px solid #d8b4fe;background:#fff;color:#a78bfa;transition:all .3s cubic-bezier(.4,0,.2,1);position:relative;flex-shrink:0}
        .checkpoint-item:hover .checkpoint-node{border-color:#a78bfa;transform:scale(1.06)}
        .checkpoint-label{font-size:12px;font-weight:600;color:#a78bfa;transition:color .3s;white-space:nowrap;line-height:1.2}
        .checkpoint-item.completed .checkpoint-node{background:#7c3aed;border-color:#7c3aed;color:#fff;box-shadow:0 4px 14px -4px rgba(124,58,237,.4)}
        .checkpoint-item.completed .checkpoint-label{color:#6d28d9}
        .checkpoint-item.active .checkpoint-node{background:#fff;border-color:#7c3aed;color:#7c3aed;box-shadow:0 0 0 4px rgba(124,58,237,.12),0 4px 16px -4px rgba(124,58,237,.35)}
        .checkpoint-item.active .checkpoint-node::after{content:'';position:absolute;inset:-7px;border-radius:50%;border:2px solid rgba(124,58,237,.2);animation:checkpointPulse 2s ease-in-out infinite}
        .checkpoint-item.active .checkpoint-label{color:#4c1d95;font-weight:700}
        @keyframes checkpointPulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.2);opacity:0}}
        @media(max-width:767px){
            .presentation-wrapper{overflow:visible}
            .presentation-shell-container{max-width:100%}
            .checkpoint-sidebar{width:44px;padding:24px 0;transform:translateX(calc(-100% - 8px))}
            .checkpoint-track{left:9px}
            .checkpoint-node{width:20px;height:20px;font-size:9px;border-width:2px}
            .checkpoint-label{display:none}
        }
    </style>

    <div class="grid gap-6">
        <div class="glass-card rounded-3xl p-6 sm:p-8">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.28em] text-violet-500 font-bold">Global module access</p>
                    <h1 class="font-display font-bold text-3xl text-gray-900">{{ $moduleTitle }}</h1>
                    <p class="text-sm text-gray-500">{{ $moduleDescription }}</p>
                </div>
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider">
                    <i class="fas fa-shield-halved"></i> Unlocked for all verified students
                </span>
            </div>
        </div>

        <div class="glass-card rounded-3xl p-6 sm:p-8">
            <div class="presentation-wrapper">
                <aside class="checkpoint-sidebar" aria-label="Presentation progress">
                    <div class="checkpoint-track">
                        <div class="checkpoint-track-fill" id="track-fill"></div>
                    </div>
                    @php
                        $lessonIndexes = $lessonPages->pluck('lesson_index')->unique()->sort()->values();
                    @endphp
                    @foreach($lessonIndexes as $li)
                        <div class="checkpoint-item {{ $loop->first ? 'active' : '' }}" data-cp="{{ $li }}">
                            <div class="checkpoint-node">{{ $loop->index + 1 }}</div>
                            <span class="checkpoint-label">Lesson {{ $loop->index + 1 }}</span>
                        </div>
                    @endforeach
                </aside>

                @include('Students.partials.gun-parts-presentation-shell')
            </div>
        </div>
    </div>

    @include('shared.sweet-alerts.module-access', [
        'moduleTitle' => $moduleTitle,
        'moduleState' => $moduleState,
    ])

    <script>
        const pages = Array.from(document.querySelectorAll('.presentation-page'));
        const dots = Array.from(document.querySelectorAll('.presentation-dot'));
        const cpItems = Array.from(document.querySelectorAll('.checkpoint-item'));
        const trackFill = document.getElementById('track-fill');
        const prevButton = document.getElementById('presentation-prev');
        const nextButton = document.getElementById('presentation-next');
        const totalSteps = cpItems.length;
        const totalPages = pages.length;
        let currentPage = 0;

        function getLessonIndex(pageIndex) {
            const page = pages[pageIndex];
            const raw = page?.dataset?.lesson;
            return Number.isNaN(parseInt(raw, 10)) ? 0 : parseInt(raw, 10);
        }

        function firstPageIndexForLesson(lessonIndex) {
            const match = pages.findIndex(page => parseInt(page.dataset.lesson, 10) === lessonIndex);
            return match === -1 ? 0 : match;
        }

        function updatePresentationPage() {
            const currentLesson = getLessonIndex(currentPage);
            pages.forEach((page, index) => page.classList.toggle('active', index === currentPage));
            dots.forEach((dot, index) => dot.classList.toggle('active', index === currentPage));
            cpItems.forEach((cp, index) => {
                cp.classList.remove('active', 'completed');
                if (index < currentLesson) cp.classList.add('completed');
                else if (index === currentLesson) cp.classList.add('active');
            });
            cpItems.forEach((cp, index) => {
                const node = cp.querySelector('.checkpoint-node');
                if (!node) return;
                node.innerHTML = index < currentLesson ? '<i class="fas fa-check text-[11px]"></i>' : String(index + 1);
            });
            if (trackFill) {
                const pct = totalSteps > 1 ? (currentLesson / (totalSteps - 1)) * 100 : 0;
                trackFill.style.height = pct + '%';
            }
            if (prevButton) prevButton.disabled = currentPage === 0;
            if (nextButton) nextButton.disabled = currentPage === totalPages - 1;
        }

        function goToPresentationPage(pageIndex) {
            currentPage = Math.max(0, Math.min(pageIndex, pages.length - 1));
            updatePresentationPage();
        }

        prevButton?.addEventListener('click', () => goToPresentationPage(currentPage - 1));
        nextButton?.addEventListener('click', () => goToPresentationPage(currentPage + 1));
        dots.forEach(dot => {
            dot.addEventListener('click', () => goToPresentationPage(parseInt(dot.dataset.dot, 10) || 0));
        });

        cpItems.forEach(cp => {
            cp.addEventListener('click', () => {
                const lessonIndex = parseInt(cp.dataset.cp, 10) || 0;
                goToPresentationPage(firstPageIndexForLesson(lessonIndex));
            });
        });

        updatePresentationPage();
    </script>
@endsection