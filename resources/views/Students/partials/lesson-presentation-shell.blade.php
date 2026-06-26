@php
    $moduleKey = $moduleKey ?? 'module-1';
    $currentModule = \App\Models\Module::where('module_key', $moduleKey)
        ->with(['lessons' => function ($q) {
            $q->orderBy('sort_order');
        }, 'lessons.pages', 'firearms'])
        ->first();

    $allLessonPages = $currentModule?->lessons->flatMap(function ($lesson) {
        return $lesson->pages->map(function ($page) use ($lesson) {
            return (object) [
                'lesson_key' => $lesson->key,
                'lesson_title' => $lesson->title,
                'title' => $page->title,
                'body_html' => $page->body_html,
                'sort_order' => $lesson->sort_order,
                'page_index' => $page->page_index,
            ];
        });
    })->sortBy(function ($p) {
        return $p->sort_order * 100 + $p->page_index;
    })->values() ?? collect();

    $moduleNum = match($moduleKey) {
        'module-1' => 1,
        'module-2' => 2,
        'module-3' => 3,
        default => 1,
    };
@endphp

<div class="presentation-shell">
    <div class="bg-gradient-to-r from-violet-950 to-violet-800 text-white px-6 sm:px-8 py-5 flex items-center justify-between gap-4 flex-wrap">
        <div>
            <p class="presentation-kicker text-violet-200 text-xs">Presentation</p>
            <h2 class="presentation-title text-3xl sm:text-4xl text-white mt-1">{{ $currentModule?->title ?? 'Module Content' }}</h2>
        </div>
        <div class="text-xs text-violet-100 max-w-md">
            Use the arrows to move page by page. Scrolling is disabled in this shell.
        </div>
    </div>

    <div class="presentation-stage" id="presentation-stage">

        @php $pageCounter = 0; @endphp

        {{-- Lesson pages from database --}}
        @foreach ($allLessonPages as $lp)
        <section class="presentation-page {{ $loop->first ? 'active' : '' }}" data-page="{{ $pageCounter }}" data-lesson="{{ $lp->lesson_key }}">
            <div class="presentation-content">
                {!! $lp->body_html !!}
            </div>
        </section>
        @php $pageCounter++; @endphp
        @endforeach

        {{-- Assessment pages --}}
        @php
            $questions = \App\Models\Activity::where('module_id', $moduleNum)->orderBy('question_number')->get();
        @endphp
        @if ($questions->isNotEmpty())
        @foreach ($questions as $i => $question)
        <section class="presentation-page" data-page="{{ $pageCounter }}" data-lesson="assessment" data-correct-answer="{{ $question->correct_answer }}" data-qnum="{{ $i + 1 }}">
            <div class="presentation-content">
                <div class="flex flex-col items-center justify-center h-full px-6 sm:px-8 py-12">
                    <div class="max-w-2xl w-full">
                        <div class="text-center mb-6">
                            <span class="text-violet-600 text-sm font-semibold uppercase tracking-wide">Module {{ $moduleNum }} Assessment</span>
                            <p class="text-gray-400 text-xs mt-1">Question {{ $i + 1 }} of {{ count($questions) }}</p>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <p class="font-semibold text-gray-900 mb-4 text-lg">{{ $i + 1 }}. {{ $question->question_text }}</p>
                            <div class="space-y-3">
                                @foreach ($question->options as $j => $opt)
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-violet-50 cursor-pointer">
                                    <input type="radio" name="m{{ $moduleNum }}q{{ $i + 1 }}" value="{{ $j }}" class="accent-violet-600">
                                    <span class="text-gray-700">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @php $pageCounter++; @endphp
        @endforeach

        {{-- Assessment result page (dynamically populated by JS) --}}
        <section class="presentation-page" data-page="{{ $pageCounter }}" data-lesson="result" data-score-module="{{ $moduleKey }}" data-score-max="{{ $questions->count() }}">
            <div class="presentation-content">
                <div id="assessment-result" class="flex flex-col items-center justify-center h-full px-6 sm:px-8 py-10 overflow-y-auto">
                    <div class="max-w-2xl w-full text-center">
                        <div class="result-icon-wrap w-24 h-24 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <span class="result-percentage text-3xl font-bold text-gray-400">--%</span>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-1">Module {{ $moduleNum }} Assessment Result</h2>
                        <p class="result-summary text-gray-500 mb-6">Calculating your score...</p>
                        <div class="result-wrong-list text-left space-y-3 mb-8"></div>
                    </div>
                </div>
            </div>
        </section>
        @php $pageCounter++; @endphp
        @endif

        {{-- Module 3: Final Marksmanship Assessment eligibility + assessment --}}
        @if ($moduleKey === 'module-3')
        {{-- Eligibility modal page --}}
        <section class="presentation-page" data-page="{{ $pageCounter }}" data-lesson="final-eligible">
            <div class="presentation-content">
                <div class="flex flex-col justify-center items-center h-full px-6 sm:px-8 py-12">
                    <div class="max-w-lg mx-auto text-center">
                        <div class="w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-trophy text-3xl text-amber-600"></i>
                        </div>
                        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Final Assessment</h1>
                        <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                            You have successfully completed Module 3! You are now eligible to proceed to the <strong>Firing Range Simulation</strong>, which serves as your Final Marksmanship Assessment.
                        </p>
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-6 text-left">
                            <p class="text-sm font-semibold text-amber-900 mb-2"><i class="fas fa-info-circle text-amber-600 mr-2"></i>Proceed with your instructor</p>
                            <p class="text-sm text-amber-700">The firing range simulation should be taken with the assistance and guidance of your instructor present. Click Next when you and your instructor are ready.</p>
                        </div>
                        <button type="button" class="presentation-btn inline-flex items-center gap-2" onclick="document.getElementById('presentation-next')?.click()">
                            I'm Ready <i class="fas fa-arrow-right text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
        @php $pageCounter++; @endphp

        {{-- Firing range eligibility (after module 3) --}}
        <section class="presentation-page" data-page="{{ $pageCounter }}" data-lesson="firing-range">
            <div class="presentation-content">
                <div class="flex flex-col justify-center items-center h-full px-6 sm:px-8 py-12">
                    <div class="max-w-lg mx-auto text-center">
                        <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-check-circle text-4xl text-emerald-600"></i>
                        </div>
                        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Ready for the Firing Range</h1>
                        <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                            You have completed all course modules and are now eligible to proceed to the Firing Range marksmanship simulation.
                        </p>
                        <div class="bg-violet-50 border border-violet-100 rounded-xl p-5 mb-8 text-left">
                            <p class="text-sm font-semibold text-violet-900 mb-2"><i class="fas fa-gun text-violet-600 mr-2"></i>What's next?</p>
                            <ul class="text-sm text-violet-700 space-y-2">
                                <li class="flex gap-2"><i class="fas fa-arrow-right text-violet-400 mt-0.5"></i><span>Apply your firearm knowledge in a timed simulation</span></li>
                                <li class="flex gap-2"><i class="fas fa-arrow-right text-violet-400 mt-0.5"></i><span>Test your accuracy with different weapon types</span></li>
                                <li class="flex gap-2"><i class="fas fa-arrow-right text-violet-400 mt-0.5"></i><span>Earn scores and track your marksmanship progress</span></li>
                            </ul>
                        </div>
                        <p class="text-sm text-gray-500">Ask your instructor to grant you access to the Firing Range.</p>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- Firearm profile pages (for modules with firearms in pivot) --}}
        @if ($currentModule && $currentModule->firearms->isNotEmpty())
        @foreach ($currentModule->firearms as $fi => $firearm)
        @php $pageCounter++; @endphp
        <section class="presentation-page" data-page="{{ $pageCounter }}" data-lesson="firearm-profile-{{ $fi }}" data-firearm-slug="{{ $firearm->slug }}">
            <div class="presentation-content">
                <div class="flex flex-col items-center justify-center min-h-full px-6 sm:px-8 py-10 overflow-y-auto">
                    <div class="max-w-3xl w-full">
                        <p class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-1">Module {{ $moduleNum }} — Firearm Profile</p>
                        <div class="bg-white border border-violet-100 rounded-2xl overflow-hidden shadow-sm">
                            <div class="bg-gradient-to-r from-violet-950 to-violet-800 px-6 py-5">
                                <h2 class="text-2xl font-bold text-white">{{ $firearm->name }}</h2>
                                <p class="text-violet-200 text-sm mt-1">{{ ucfirst($firearm->type ?? 'Firearm') }} · {{ $firearm->caliber ?? 'N/A' }}</p>
                            </div>
                            <div class="p-6 flex flex-col sm:flex-row gap-6">
                                <div class="sm:w-1/3 flex-shrink-0">
                                    @if ($firearm->image_url)
                                    <img src="{{ asset($firearm->image_url) }}" alt="{{ $firearm->name }}" class="w-full rounded-xl border border-gray-100">
                                    @else
                                    <div class="w-full aspect-square rounded-xl bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-300">
                                        <i class="fas fa-gun text-6xl"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="sm:w-2/3 space-y-4">
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Description</h3>
                                        <p class="text-gray-600 text-sm leading-relaxed mt-1">{{ $firearm->description ?? 'No description available.' }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-violet-50 rounded-xl p-3">
                                            <p class="text-[10px] uppercase tracking-wider text-violet-600 font-bold">Caliber</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $firearm->caliber ?? 'N/A' }}</p>
                                        </div>
                                        <div class="bg-violet-50 rounded-xl p-3">
                                            <p class="text-[10px] uppercase tracking-wider text-violet-600 font-bold">Type</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ ucfirst($firearm->type ?? 'N/A') }}</p>
                                        </div>
                                        @if ($firearm->mag_size)
                                        <div class="bg-violet-50 rounded-xl p-3">
                                            <p class="text-[10px] uppercase tracking-wider text-violet-600 font-bold">Mag Capacity</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $firearm->mag_size }} rounds</p>
                                        </div>
                                        @endif
                                        <div class="bg-violet-50 rounded-xl p-3">
                                            <p class="text-[10px] uppercase tracking-wider text-violet-600 font-bold">Part Count</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $firearm->parts->count() }} parts</p>
                                        </div>
                                    </div>
                                    @if ($firearm->parts->isNotEmpty())
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Assembly Parts</h3>
                                        <ul class="mt-2 space-y-1">
                                            @foreach ($firearm->parts as $part)
                                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                                <span class="w-5 h-5 rounded-full bg-violet-100 flex items-center justify-center text-[10px] font-bold text-violet-700">{{ $part->sort_order }}</span>
                                                {{ $part->name }} — <span class="text-gray-400 text-xs">{{ ucfirst($part->slug) }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endforeach
        @endif

        {{-- Assembly trainer for module 2 --}}
        @if ($moduleKey === 'module-2')
        <section class="presentation-page" data-page="{{ $pageCounter }}" data-lesson="assembly-intro">
            <div class="presentation-content">
                <div class="flex flex-col items-center justify-center h-full px-6 sm:px-8 py-10 overflow-y-auto">
                    <div class="max-w-2xl w-full text-center">
                        <p class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-1">Hands-on Practice</p>
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Assemble &amp; Disassemble Trainer</h2>
                        <div class="h-1 w-24 bg-violet-500 mx-auto mb-6"></div>
                        <p class="text-gray-600 mb-6">Drag each part from the tray onto the pistol to assemble it layer by layer, or switch to Disassemble to remove the parts back into the tray.</p>
                        <button type="button" class="presentation-btn inline-flex items-center gap-2" onclick="document.getElementById('presentation-next')?.click()">
                            Get Started <i class="fas fa-arrow-right text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
        @php $pageCounter++; @endphp
        <section class="presentation-page" data-page="{{ $pageCounter }}" data-lesson="assembly-trainer">
            <div class="presentation-content p-4 sm:p-6 overflow-auto" style="overflow: auto !important;">
                @include('Students.partials.assembly-simulator')
            </div>
        </section>
        @php $pageCounter++; @endphp
        @endif

    </div>

    <div class="border-t border-violet-100 bg-white/90 px-6 sm:px-8 py-4">
        <div class="presentation-nav">
            <button type="button" id="presentation-prev" class="presentation-btn">
                <i class="fas fa-arrow-left text-sm"></i> Previous
            </button>
            <span class="presentation-page-counter" id="page-counter">1 / 1</span>
            <button type="button" id="presentation-next" class="presentation-btn">
                Next <i class="fas fa-arrow-right text-sm"></i>
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    function initShell() {
        const shell = document.querySelector('.presentation-stage');
        if (!shell) return;
        const pages = Array.from(shell.querySelectorAll('.presentation-page'));
        const counterEl = document.getElementById('page-counter');
        const prevBtn = document.getElementById('presentation-prev');
        const nextBtn = document.getElementById('presentation-next');
        if (pages.length === 0) return;
        let currentPage = 0;
        let scoreSaved = {};

        function updateDisplay() {
            pages.forEach((p, i) => p.classList.toggle('active', i === currentPage));
            if (counterEl) counterEl.textContent = (currentPage + 1) + ' / ' + pages.length;
            if (prevBtn) prevBtn.disabled = currentPage === 0;
            if (nextBtn) nextBtn.disabled = currentPage === pages.length - 1;
            autoSaveScore();
            trackProgress();
            renderResults();
        }

        let progressTimer = null;
        function trackProgress() {
            clearTimeout(progressTimer);
            progressTimer = setTimeout(function() {
                var page = pages[currentPage];
                if (!page) return;
                var lessonKey = page.dataset.lesson || '';
                fetch('{{ route("student.progress.update") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        module_key: '{{ $moduleKey }}',
                        lesson_key: lessonKey,
                        current_page: currentPage,
                        total_pages: pages.length,
                    })
                }).catch(function(){});
            }, 500);
        }

        function calculateScore(prefix) {
            const radios = shell.querySelectorAll('input[type="radio"][name^="' + prefix + '"]');
            let correct = 0;
            const total = radios.length / 4;
            for (let i = 1; i <= total; i++) {
                const selected = shell.querySelector('input[name="' + prefix + i + '"]:checked');
                if (selected) {
                    const val = parseInt(selected.value, 10);
                    if (val === 0) correct++;
                }
            }
            return { score: correct, max: total };
        }

        function autoSaveScore() {
            const page = pages[currentPage];
            if (!page) return;
            const modKey = page.dataset.scoreModule;
            const maxScore = parseInt(page.dataset.scoreMax, 10);
            if (!modKey || !maxScore || scoreSaved[modKey]) return;
            let prefix, modKeySave;
            if (modKey === 'final') {
                prefix = 'finalq';
                modKeySave = 'final';
            } else {
                const num = modKey.replace('module-', '');
                prefix = 'm' + num + 'q';
                modKeySave = modKey;
            }
            const result = calculateScore(prefix);
            if (result.max < 1) return;
            scoreSaved[modKeySave] = true;
            fetch('{{ route("student.assessment.save-score") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    module_key: modKeySave,
                    score: result.score,
                    max_score: result.max,
                })
            }).catch(function(){});
        }

        function renderResults() {
            var page = pages[currentPage];
            if (!page || page.dataset.lesson !== 'result') return;
            if (page.dataset.rendered) return;
            page.dataset.rendered = '1';

            var qPages = Array.from(shell.querySelectorAll('.presentation-page[data-lesson="assessment"]'));
            var totalQ = qPages.length;
            var correctCount = 0;
            var wrongItems = [];

            qPages.forEach(function(qp) {
                var correctAns = parseInt(qp.dataset.correctAnswer, 10);
                var qnum = qp.dataset.qnum || '?';
                var questionTextEl = qp.querySelector('.font-semibold');
                var questionText = questionTextEl ? questionTextEl.textContent : '';
                var selected = qp.querySelector('input[type="radio"]:checked');
                var userAnswer = selected ? parseInt(selected.value, 10) : -1;

                var correctRadio = qp.querySelector('input[type="radio"][value="' + correctAns + '"]');
                var correctText = correctRadio ? correctRadio.closest('label').querySelector('span').textContent : '';

                if (userAnswer === correctAns) {
                    correctCount++;
                } else {
                    var userText = selected ? selected.closest('label').querySelector('span').textContent : 'No answer';
                    wrongItems.push({ qnum: qnum, text: questionText, correct: correctText, user: userText });
                }
            });

            var pct = totalQ > 0 ? Math.round((correctCount / totalQ) * 100) : 0;
            var container = document.getElementById('assessment-result');
            if (!container) return;

            var pctColor = pct >= 70 ? 'text-emerald-600' : (pct >= 40 ? 'text-amber-600' : 'text-red-600');
            var bgColor = pct >= 70 ? 'bg-emerald-100' : (pct >= 40 ? 'bg-amber-100' : 'bg-red-100');
            container.querySelector('.result-percentage').textContent = pct + '%';
            container.querySelector('.result-percentage').className = 'result-percentage text-3xl font-bold ' + pctColor;
            container.querySelector('.result-icon-wrap').className = 'result-icon-wrap w-24 h-24 mx-auto mb-4 rounded-full ' + bgColor + ' flex items-center justify-center';
            container.querySelector('.result-summary').textContent = 'You answered ' + correctCount + ' of ' + totalQ + ' questions correctly.';

            var wrongHtml = '';
            wrongItems.forEach(function(w) {
                wrongHtml += '<div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4">' +
                    '<div class="flex items-start gap-3">' +
                    '<i class="fas fa-xmark text-red-500 mt-1"></i>' +
                    '<div>' +
                    '<p class="font-semibold text-gray-900 text-sm">' + w.text + ' — <span class="text-red-600">Incorrect</span></p>' +
                    '<p class="text-sm text-gray-600 mt-1"><span class="font-medium">Your answer:</span> ' + w.user + '</p>' +
                    '<p class="text-sm text-emerald-700 mt-1"><i class="fas fa-check mr-1"></i>Correct answer: <span class="font-medium">' + w.correct + '</span></p>' +
                    '</div></div></div>';
            });

            if (wrongItems.length === 0) {
                wrongHtml = '<div class="bg-emerald-50 border-l-4 border-emerald-400 rounded-lg p-4 text-center"><p class="text-emerald-700 font-semibold">Perfect score! All answers are correct.</p></div>';
            }

            container.querySelector('.result-wrong-list').innerHTML = wrongHtml;
        }

        window.jumpToLessonPage = function(lessonKey) {
            var target = shell.querySelector('.presentation-page[data-lesson="' + lessonKey + '"]');
            if (target) {
                var idx = parseInt(target.dataset.page, 10);
                if (!isNaN(idx) && idx >= 0 && idx < pages.length) {
                    currentPage = idx;
                    updateDisplay();
                }
            }
        };

        prevBtn?.addEventListener('click', function() { currentPage = Math.max(0, currentPage - 1); updateDisplay(); });
        nextBtn?.addEventListener('click', function() { currentPage = Math.min(pages.length - 1, currentPage + 1); updateDisplay(); });
        updateDisplay();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initShell);
    } else {
        initShell();
    }
})();
</script>

<style>
    .presentation-content .layer-delete-zone,
    .presentation-content .layer-handle {
        display: none !important;
    }
</style>
