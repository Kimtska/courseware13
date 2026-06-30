@php
    $moduleKey = $moduleKey ?? 'module-1';
    $currentModule = \App\Models\Module::where('module_key', $moduleKey)
        ->with(['lessons' => function ($q) {
            $q->orderBy('sort_order');
        }, 'lessons.pages'])
        ->first();

    $simulations = $simulations ?? \App\Models\AssessmentSimulation::with('parts')->get();

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
            $questions = $currentModule
            ? \App\Models\Activity::whereHas('lessonDetail.lesson', function ($q) use ($currentModule) {
                $q->where('module_id', $currentModule->id);
            })->orderBy('question_number')->get()
            : collect();
        @endphp
        @if ($questions->isNotEmpty())
        @foreach ($questions as $i => $question)
        <section class="presentation-page" data-page="{{ $pageCounter }}" data-lesson="assessment" data-correct-answer="{{ $question->correct_answer }}" data-qnum="{{ $i + 1 }}">
            <div class="presentation-content">
                <div class="flex flex-col items-stretch justify-start h-full px-6 sm:px-8 pt-6 pb-2">
                    <div class="w-full flex-1 flex flex-col min-h-0">
                        <div class="text-center mb-3 flex-shrink-0">
                            <p class="text-gray-400 text-xs">Question {{ $i + 1 }} of {{ count($questions) }}</p>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm flex-1 flex flex-col min-h-0">
                            <p class="font-bold text-gray-900 mb-5 text-2xl flex-shrink-0 text-justify">{{ $i + 1 }}. {{ $question->question_text }}</p>
                            <div class="space-y-4 flex-1 flex flex-col justify-center min-h-0">
                                @foreach ($question->options as $j => $opt)
                                <label class="flex items-center gap-4 p-5 rounded-xl border border-gray-100 hover:bg-violet-50 cursor-pointer flex-shrink-0">
                                    <input type="radio" name="m{{ $moduleNum }}q{{ $i + 1 }}" value="{{ $j }}" class="accent-violet-600 w-5 h-5 flex-shrink-0">
                                    <span class="text-gray-700 text-lg flex-1 text-justify">{{ $opt }}</span>
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

        {{-- Assessment checkpoint result (per-question review) --}}
        <section class="presentation-page" data-page="{{ $pageCounter }}" data-lesson="result" data-score-module="{{ $moduleKey }}" data-score-max="{{ $questions->count() }}">
            <div class="presentation-content">
                <div id="assessment-result" class="flex flex-col items-center px-4 sm:px-6 py-6 overflow-y-auto" style="flex:1 1 auto;min-height:0">
                    <div class="max-w-2xl w-full">
                        <div class="text-center mb-8">
                            <div class="result-icon-wrap w-20 h-20 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                                <span class="result-percentage text-3xl font-bold text-gray-400">--%</span>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Module {{ $moduleNum }} — Checkpoint</h2>
                            <p class="result-summary text-gray-500 text-sm mt-1">Calculating your score...</p>
                        </div>
                        <div class="result-checkpoint-list space-y-4 mb-8"></div>
                        <div class="text-center">
                            <button type="button" id="review-wrong-btn" class="presentation-btn hidden" onclick="document.getElementById('presentation-prev')?.click()">
                                <i class="fas fa-arrow-left text-sm"></i> Review Wrong Answers
                            </button>
                        </div>
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

        {{-- Simulation profile pages (module 3 only) --}}
        @if ($moduleKey === 'module-3' && count($simulations) > 0)
        @foreach ($simulations as $fi => $simulation)
        @php $pageCounter++; @endphp
        <section class="presentation-page" data-page="{{ $pageCounter }}" data-lesson="simulation-profile-{{ $fi }}" data-simulation-slug="{{ $simulation->slug }}">
            <div class="presentation-content">
                <div class="flex flex-col items-center justify-center min-h-full px-6 sm:px-8 py-10 overflow-y-auto">
                    <div class="max-w-3xl w-full">
                        <p class="text-violet-600 text-sm font-semibold uppercase tracking-wide mb-1">Module {{ $moduleNum }} — Simulation Profile</p>
                        <div class="bg-white border border-violet-100 rounded-2xl overflow-hidden shadow-sm">
                            <div class="bg-gradient-to-r from-violet-950 to-violet-800 px-6 py-5">
                                <h2 class="text-2xl font-bold text-white">{{ $simulation->name }}</h2>
                                <p class="text-violet-200 text-sm mt-1">{{ ucfirst($simulation->type ?? 'Simulation') }} · {{ $simulation->caliber ?? 'N/A' }}</p>
                            </div>
                            <div class="p-6 flex flex-col sm:flex-row gap-6">
                                <div class="sm:w-1/3 flex-shrink-0">
                                    @if ($simulation->image_url)
                                    <img src="{{ asset($simulation->image_url) }}" alt="{{ $simulation->name }}" class="w-full rounded-xl border border-gray-100">
                                    @else
                                    <div class="w-full aspect-square rounded-xl bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-300">
                                        <i class="fas fa-gun text-6xl"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="sm:w-2/3 space-y-4">
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Description</h3>
                                        <p class="text-gray-600 text-sm leading-relaxed mt-1">{{ $simulation->description ?? 'No description available.' }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-violet-50 rounded-xl p-3">
                                            <p class="text-[10px] uppercase tracking-wider text-violet-600 font-bold">Caliber</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $simulation->caliber ?? 'N/A' }}</p>
                                        </div>
                                        <div class="bg-violet-50 rounded-xl p-3">
                                            <p class="text-[10px] uppercase tracking-wider text-violet-600 font-bold">Type</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ ucfirst($simulation->type ?? 'N/A') }}</p>
                                        </div>
                                        @if ($simulation->mag_size)
                                        <div class="bg-violet-50 rounded-xl p-3">
                                            <p class="text-[10px] uppercase tracking-wider text-violet-600 font-bold">Mag Capacity</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $simulation->mag_size }} rounds</p>
                                        </div>
                                        @endif
                                        <div class="bg-violet-50 rounded-xl p-3">
                                            <p class="text-[10px] uppercase tracking-wider text-violet-600 font-bold">Part Count</p>
                                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $simulation->parts->count() }} parts</p>
                                        </div>
                                    </div>
                                    @if ($simulation->parts->isNotEmpty())
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Assembly Parts</h3>
                                        <ul class="mt-2 space-y-1">
                                            @foreach ($simulation->parts as $part)
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
            const qPages = Array.from(shell.querySelectorAll('.presentation-page[data-lesson="assessment"]'));
            let correct = 0;
            const total = qPages.length;
            qPages.forEach(function(qp) {
                var qnum = parseInt(qp.dataset.qnum, 10);
                var correctAns = parseInt(qp.dataset.correctAnswer, 10);
                var name = prefix + qnum;
                var selected = shell.querySelector('input[name="' + name + '"]:checked');
                if (selected) {
                    var val = parseInt(selected.value, 10);
                    if (val === correctAns) correct++;
                }
            });
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
            var wrongCount = 0;
            var items = [];

            qPages.forEach(function(qp) {
                var correctAns = parseInt(qp.dataset.correctAnswer, 10);
                var qnum = qp.dataset.qnum || '?';
                var questionTextEl = qp.querySelector('.font-semibold');
                var questionText = questionTextEl ? questionTextEl.textContent : '';
                var selected = qp.querySelector('input[type="radio"]:checked');
                var userAnswer = selected ? parseInt(selected.value, 10) : -1;

                var correctRadio = qp.querySelector('input[type="radio"][value="' + correctAns + '"]');
                var correctText = correctRadio ? correctRadio.closest('label').querySelector('span').textContent : '';

                var userText = 'No answer';
                var userLabel = '';
                if (selected) {
                    userText = selected.closest('label').querySelector('span').textContent;
                    userLabel = selected.closest('label').querySelector('span').textContent;
                }

                var isCorrect = userAnswer === correctAns;
                if (isCorrect) { correctCount++; } else { wrongCount++; }

                items.push({
                    qnum: qnum,
                    text: questionText,
                    isCorrect: isCorrect,
                    userAnswer: userAnswer,
                    correctAnswer: correctAns,
                    userLabel: userLabel || userText,
                    correctLabel: correctText
                });
            });

            var pct = totalQ > 0 ? Math.round((correctCount / totalQ) * 100) : 0;
            var container = document.getElementById('assessment-result');
            if (!container) return;

            var pctColor = pct >= 70 ? 'text-emerald-600' : (pct >= 40 ? 'text-amber-600' : 'text-red-600');
            var bgColor = pct >= 70 ? 'bg-emerald-100' : (pct >= 40 ? 'bg-amber-100' : 'bg-red-100');
            container.querySelector('.result-percentage').textContent = pct + '%';
            container.querySelector('.result-percentage').className = 'result-percentage text-3xl font-bold ' + pctColor;
            container.querySelector('.result-icon-wrap').className = 'result-icon-wrap w-20 h-20 mx-auto mb-3 rounded-full ' + bgColor + ' flex items-center justify-center';
            container.querySelector('.result-summary').textContent = correctCount + ' of ' + totalQ + ' correct (' + wrongCount + ' wrong)';

            var html = '';
            items.forEach(function(it) {
                var borderColor = it.isCorrect ? 'border-emerald-300 bg-emerald-50/60' : 'border-red-300 bg-red-50/60';
                var iconColor = it.isCorrect ? 'text-emerald-500 bg-emerald-100' : 'text-red-500 bg-red-100';
                var iconHtml = it.isCorrect
                    ? '<i class="fas fa-check" style="font-size:11px"></i>'
                    : '<i class="fas fa-xmark" style="font-size:11px"></i>';
                var statusBadge = it.isCorrect
                    ? '<span class="text-[10px] font-bold text-emerald-700">CORRECT</span>'
                    : '<span class="text-[10px] font-bold text-red-700">WRONG</span>';

                var optionsHtml = '';
                var labels = qPages[parseInt(it.qnum) - 1].querySelectorAll('.space-y-3 label');
                labels.forEach(function(lb) {
                    var radio = lb.querySelector('input[type="radio"]');
                    if (!radio) return;
                    var val = parseInt(radio.value, 10);
                    var txt = lb.querySelector('span').textContent;
                    var isUser = (val === it.userAnswer);
                    var isCorrectOpt = (val === it.correctAnswer);
                    var optClass = isUser && isCorrectOpt ? 'ring-2 ring-emerald-400 bg-emerald-50 border-emerald-300' :
                        isUser ? 'ring-2 ring-red-400 bg-red-50 border-red-300' :
                        isCorrectOpt ? 'ring-2 ring-emerald-400 bg-emerald-50 border-emerald-300' :
                        'border-gray-100';
                    var marker = isUser && isCorrectOpt ? '<i class="fas fa-check text-emerald-500" style="font-size:10px"></i>' :
                        isUser ? '<i class="fas fa-xmark text-red-500" style="font-size:10px"></i>' :
                        isCorrectOpt ? '<i class="fas fa-check text-emerald-500" style="font-size:10px"></i>' :
                        '';
                    optionsHtml += '<div class="flex items-center gap-2 px-3 py-2 rounded-lg border ' + optClass + '" style="transition:all .15s">' +
                        '<span style="width:14px;height:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0">' + marker + '</span>' +
                        '<span class="text-base ' + (isUser ? 'font-semibold' : '') + '" style="color:' + (isUser && !isCorrectOpt ? '#dc2626' : '#374151') + '">' + txt + '</span>' +
                        (isUser && it.isCorrect ? '<span class="text-[9px] font-bold text-emerald-600 ml-auto">Your answer</span>' : '') +
                        (isUser && !it.isCorrect ? '<span class="text-[9px] font-bold text-red-600 ml-auto">Your answer</span>' : '') +
                        (!isUser && isCorrectOpt && !it.isCorrect ? '<span class="text-[9px] font-bold text-emerald-600 ml-auto">Correct answer</span>' : '') +
                        '</div>';
                });

                html += '<div class="rounded-xl border ' + borderColor + ' overflow-hidden">' +
                    '<div class="flex items-start gap-3 px-5 py-4">' +
                    '<div class="w-7 h-7 rounded-full ' + iconColor + ' flex items-center justify-center flex-shrink-0" style="margin-top:2px">' + iconHtml + '</div>' +
                    '<div class="flex-1 min-w-0">' +
                    '<div class="flex items-center gap-2 mb-1">' +
                    '<span class="text-[11px] font-bold text-gray-400">Q' + it.qnum + '</span>' +
                    statusBadge +
                    '</div>' +
                    '<p class="text-base font-semibold text-gray-900 leading-snug">' + it.text + '</p>' +
                    '</div>' +
                    '</div>' +
                    '<div class="px-4 pb-4 space-y-2.5">' + optionsHtml + '</div>' +
                    '</div>';
            });

            container.querySelector('.result-checkpoint-list').innerHTML = html;

            var reviewBtn = document.getElementById('review-wrong-btn');
            if (reviewBtn && wrongCount > 0) {
                reviewBtn.classList.remove('hidden');
                reviewBtn.textContent = 'Review ' + wrongCount + ' Wrong Answer' + (wrongCount > 1 ? 's' : '');
            }
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

    section.presentation-page[data-lesson="result"] {
        overflow: hidden auto !important;
    }

    section.presentation-page[data-lesson="result"] .presentation-content {
        overflow: hidden auto !important;
    }
</style>
