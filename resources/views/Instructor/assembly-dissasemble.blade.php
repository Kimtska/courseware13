@extends('Instructor.layout')

@section('title', 'Assembly Dissasemble')
@section('pageTitle', 'Assembly Dissasemble')
@section('pageSubtitle', 'Instructor module view with assembly content')

@section('headerActions')
    <form method="POST" action="{{ route('instructor.manage-portal.unlock', $moduleKey) }}" class="flex items-center gap-3" data-module-access-form>
        @csrf
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg {{ ($moduleState->is_unlocked ?? false) ? 'bg-rose-600 hover:bg-rose-700' : 'bg-violet-700 hover:bg-violet-800' }} text-white text-xs font-bold uppercase transition-colors" data-module-access-button>
            <i class="fas {{ ($moduleState->is_unlocked ?? false) ? 'fa-lock' : 'fa-lock-open' }}"></i>
            <span>{{ ($moduleState->is_unlocked ?? false) ? 'Lock Module Access' : 'Unlock Module Access?' }}</span>
        </button>
    </form>
@endsection

@section('content')
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        #assembly-shell{font-family:'Inter',system-ui,sans-serif;color:#1f2937;min-height:100vh;overflow-x:hidden;position:relative}
        #assembly-shell body{padding:0}
        #app{max-width:1100px;margin:0 auto;padding:16px}
        .menu-shell{background:#fff;border:1px solid #e9d5ff;border-radius:28px;box-shadow:0 25px 60px -12px rgba(30,5,82,0.18);overflow:hidden}
        .menu-hero{background:linear-gradient(135deg,#1E0552,#5B21B6);color:#fff}
        #start-overlay{position:absolute;inset:0;z-index:50;background:rgba(255,255,255,.72);backdrop-filter:blur(10px);display:flex;align-items:flex-start;justify-content:center;padding:24px 16px;overflow-y:auto}
        #start-overlay.hidden{display:none}
        .sel-card{border:2px solid #e5e7eb;background:#fff;border-radius:12px;padding:16px;cursor:pointer;transition:all .2s;box-shadow:0 1px 2px rgba(15,23,42,.04)}
        .sel-card:hover{border-color:#c4b5fd;background:#f8f5ff}
        .sel-card.active{border-color:#7c3aed;background:#f3e8ff;box-shadow:0 0 0 3px rgba(124,58,237,.12)}
        .start-sim-btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;border-radius:14px;background:#7c3aed;padding:14px 24px;font-size:14px;font-weight:800;color:#fff;transition:background-color .2s,transform .2s,box-shadow .2s;box-shadow:0 16px 30px -18px rgba(91,33,182,.55)}
        .start-sim-btn:hover{background:#6d28d9;transform:translateY(-1px)}
        .menu-summary{font-size:12px;color:#6b7280}
        .menu-summary strong{color:#111827}
        .menu-footnote{font-size:11px;color:#6b7280;line-height:1.5}
        .session-strip{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}
        .session-pill{font-size:11px;padding:6px 10px;border-radius:9999px;background:#f3e8ff;border:1px solid #ddd6fe;color:#6d28d9;font-weight:700}
        .header{display:flex;align-items:center;gap:12px;margin-bottom:14px;flex-wrap:wrap}
        .header h1{font-size:17px;font-weight:600;color:#111827;letter-spacing:.04em}
        .header span{font-size:11px;background:#f3e8ff;border:1px solid #ddd6fe;padding:3px 9px;border-radius:4px;color:#6d28d9}
        .mode-row{display:flex;gap:8px;align-items:center;margin-bottom:12px;flex-wrap:wrap}
        .mbtn{font-size:12px;padding:6px 16px;border-radius:6px;border:1px solid #ddd6fe;background:#fff;color:#6b7280;cursor:pointer;transition:all .15s}
        .mbtn.on{background:#7c3aed;color:#fff;border-color:#7c3aed;font-weight:700}
        .mbtn:hover:not(.on){background:#f8f5ff;color:#111827}
        .rbtn{margin-left:auto;font-size:12px;padding:6px 14px;border-radius:6px;border:1px solid #ddd6fe;background:#fff;color:#6d28d9;cursor:pointer}
        .rbtn:hover{color:#4c1d95;border-color:#c4b5fd}
        .prow{display:flex;align-items:center;gap:10px;margin-bottom:12px}
        .pbar{flex:1;height:4px;background:#e5e7eb;border-radius:2px;overflow:hidden}
        .pfill{height:100%;background:#7c3aed;border-radius:2px;transition:width .35s ease}
        .ptxt{font-size:11px;color:#6b7280;white-space:nowrap;min-width:60px;text-align:right}
        .info{background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:9px 14px;font-size:12px;color:#6b7280;margin-bottom:14px;min-height:36px;line-height:1.5;box-shadow:0 12px 28px -18px rgba(30,5,82,.18)}
        .info b{color:#7c3aed}
        .layout{display:grid;grid-template-columns:320px minmax(0,1fr);gap:14px;align-items:start}
        .tray-wrap{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:10px;box-shadow:0 12px 28px -18px rgba(30,5,82,.18);width:100%}
        .tray-lbl{font-size:10px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px;font-weight:700}
        .tray{min-height:320px;border:1.5px dashed #ddd6fe;border-radius:7px;padding:6px;display:flex;flex-direction:row;flex-wrap:wrap;gap:8px;align-content:flex-start;transition:border-color .15s;background:rgba(248,245,255,0.7);overflow-y:hidden}
        .tray.over{border-color:#7c3aed;background:#f5f3ff}
        .pcard{cursor:grab;border:1px solid #e5e7eb;border-radius:7px;background:#fff;padding:6px;display:flex;flex-direction:column;align-items:center;gap:4px;width:calc(50% - 4px);min-width:140px;transition:border-color .15s,transform .1s;user-select:none}
        .pcard:active{cursor:grabbing;transform:scale(1.03)}
        .pcard.ghost{opacity:.25}
        .pcard img{width:140px;height:auto;display:block;border-radius:4px;object-fit:contain}
        .pcard .nm{font-size:9px;color:#6b7280;text-align:center;letter-spacing:.04em}
        .canvas-wrap{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px;display:flex;flex-direction:column;align-items:center;min-width:0;box-shadow:0 12px 28px -18px rgba(30,5,82,.18)}
        .canvas-lbl{font-size:10px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px;align-self:flex-start;font-weight:700}
        .gun-stage{position:relative;width:min(660px,100%);aspect-ratio:660/360;border-radius:8px;overflow:hidden;background:#ffffff;border:1px solid #e5e7eb}
        .stage-bg{position:absolute;inset:0;background:radial-gradient(circle at 50% 12%, rgba(124,58,237,.05), transparent 35%), linear-gradient(180deg, rgba(255,255,255,.96), rgba(248,250,252,.96));}
        .layer{position:absolute;inset:0;pointer-events:none;transition:opacity .25s ease, transform .25s ease}
        .layer img{width:100%;height:100%;object-fit:contain;display:block;filter:drop-shadow(0 10px 14px rgba(0,0,0,.12))}
        .dzone{position:absolute;border:1.5px solid transparent;border-radius:6px;transition:border-color .2s,background .2s,box-shadow .2s,transform .2s;cursor:default;display:flex;align-items:flex-end;justify-content:center;padding-bottom:3px;z-index:3}
        .dzone.empty{border-color:#ddd6fe}
        .dzone.over{border-color:#7c3aed;background:rgba(124,58,237,.08)}
        .dzone .hint{font-size:8px;color:#6b7280;pointer-events:none;text-align:center;line-height:1.2}
        .dzone.filled{border-color:transparent}
        .dzone.next{border-color:transparent;background:transparent;box-shadow:none;animation:none}
        .guide-art{position:absolute;inset:0;width:100%;height:100%;object-fit:contain;pointer-events:none;z-index:1;filter:brightness(0) saturate(100%) invert(67%) sepia(78%) saturate(435%) hue-rotate(84deg) brightness(102%) contrast(97%) drop-shadow(0 0 8px rgba(34,197,94,.95)) drop-shadow(0 0 18px rgba(34,197,94,.55))}
        .dzone.next .hint{position:relative;z-index:2;color:#15803d;font-weight:700;text-shadow:0 0 8px rgba(34,197,94,.35)}
        .gun-stage.done{box-shadow:0 0 30px 4px rgba(124,58,237,.18)}
        .gun-stage.snap-glow{box-shadow:0 0 0 1px rgba(34,197,94,.3),0 0 34px 8px rgba(34,197,94,.22),0 12px 28px -18px rgba(30,5,82,.18)}
        @keyframes pulseGuide{0%,100%{filter:brightness(0) saturate(100%) invert(67%) sepia(78%) saturate(435%) hue-rotate(84deg) brightness(102%) contrast(97%) drop-shadow(0 0 8px rgba(34,197,94,.85)) drop-shadow(0 0 16px rgba(34,197,94,.4))}50%{filter:brightness(0) saturate(100%) invert(67%) sepia(78%) saturate(435%) hue-rotate(84deg) brightness(102%) contrast(97%) drop-shadow(0 0 12px rgba(34,197,94,1)) drop-shadow(0 0 24px rgba(34,197,94,.65))}}
        .toast{position:fixed;bottom:20px;left:50%;transform:translateX(-50%);padding:8px 20px;border-radius:7px;font-size:13px;font-weight:600;opacity:0;transition:opacity .25s;pointer-events:none;z-index:999;white-space:nowrap}
        .toast.show{opacity:1}
        .ok{background:#7c3aed;color:#fff}
        .err{background:#ef4444;color:#fff}
        .timer-widget{position:fixed;right:16px;top:80px;z-index:55;width:220px;background:rgba(255,255,255,0.96);backdrop-filter:blur(8px);border:1px solid #e9d5ff;border-radius:20px;box-shadow:0 18px 40px -16px rgba(30,5,82,0.2)}
        .timer-widget.hidden{display:none}
        .count-in-overlay{position:fixed;inset:0;z-index:60;display:flex;align-items:center;justify-content:center;pointer-events:none;opacity:0;visibility:hidden;transition:opacity .2s ease, visibility .2s ease}
        .count-in-overlay.active{opacity:1;visibility:visible}
        .count-in-card{min-width:220px;padding:24px 28px;border-radius:28px;background:rgba(255,255,255,0.92);backdrop-filter:blur(10px);border:1px solid #e9d5ff;box-shadow:0 20px 50px -16px rgba(30,5,82,0.24);text-align:center}
        .count-in-number{font-family:'Space Grotesk',sans-serif;font-size:72px;line-height:1;font-weight:800;color:#5B21B6;letter-spacing:-0.04em}
        .count-in-label{margin-top:8px;font-size:11px;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;color:#7c3aed}
        #assembly-shell ::-webkit-scrollbar{width:10px;height:10px}
        #assembly-shell ::-webkit-scrollbar-track{background:#ddd6fe;border-radius:9999px}
        #assembly-shell ::-webkit-scrollbar-thumb{background:#7c3aed;border-radius:9999px;border:2px solid #ddd6fe}
        #assembly-shell ::-webkit-scrollbar-thumb:hover{background:#5b21b6}
        @media (max-width:900px){.layout{grid-template-columns:1fr}.tray{min-height:auto;flex-direction:row;flex-wrap:wrap}.pcard{width:min(100%,170px)}.rbtn{margin-left:0}}
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

        <div class="glass-card rounded-3xl p-0 overflow-hidden">
            <div id="assembly-shell">
                <div id="start-overlay" class="light-selection">
                    <div class="menu-shell w-full max-w-4xl mt-2">
                        <div class="menu-hero px-6 sm:px-8 py-6 text-left">
                            <p class="text-[10px] uppercase tracking-[0.28em] text-violet-200 font-bold">Simulation Setup</p>
                            <h1 class="font-display font-bold text-3xl md:text-4xl mt-2">Assembly Menu</h1>
                            <p class="text-violet-100 text-sm mt-2 max-w-2xl">Choose a time limit and firearm profile before starting the assembly simulation.</p>
                        </div>
                        <div class="p-6 sm:p-8 text-left space-y-6">
                            <div>
                                <h3 class="text-xs text-violet-700 uppercase tracking-widest font-bold mb-3 flex items-center gap-2"><i class="fas fa-clock text-violet-500"></i> Time Limit</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                                    <div class="sel-card active" data-time="30" onclick="selectTime(30, this)"><p class="text-center font-bold text-gray-900">30s</p></div>
                                    <div class="sel-card" data-time="60" onclick="selectTime(60, this)"><p class="text-center font-bold text-gray-900">60s</p></div>
                                    <div class="sel-card" data-time="90" onclick="selectTime(90, this)"><p class="text-center font-bold text-gray-900">90s</p></div>
                                    <div class="sel-card" data-time="120" onclick="selectTime(120, this)"><p class="text-center font-bold text-gray-900">120s</p></div>
                                    <div class="sel-card" data-time="custom" onclick="selectCustomAssemblyTime(this)">
                                        <div class="flex flex-col items-center gap-2">
                                            <p class="text-center font-bold text-gray-900">Custom</p>
                                            <input id="assembly-range-custom-time" type="number" min="5" max="999" value="30" class="w-full rounded-lg border border-violet-200 bg-white px-3 py-2 text-center text-sm font-bold text-gray-900 outline-none focus:border-violet-400">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xs text-violet-700 uppercase tracking-widest font-bold mb-3 flex items-center gap-2"><i class="fas fa-gun text-violet-500"></i> Select Firearm</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="sel-card active" data-firearm="9mm" onclick="selectFirearm('9mm', this)">
                                        <div class="flex items-center gap-3 mb-2"><div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-gun"></i></div><h4 class="font-display font-bold text-gray-900">9mm Pistol</h4></div>
                                        <p class="text-[10px] text-gray-500 leading-relaxed">Default trainer profile for the assembly view.</p>
                                    </div>
                                    <div class="sel-card" data-firearm="45" onclick="selectFirearm('45', this)">
                                        <div class="flex items-center gap-3 mb-2"><div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-gun"></i></div><h4 class="font-display font-bold text-gray-900">.45 Caliber</h4></div>
                                        <p class="text-[10px] text-gray-500 leading-relaxed">Heavier caliber profile for menu selection only.</p>
                                    </div>
                                    <div class="sel-card" data-firearm="38" onclick="selectFirearm('38', this)">
                                        <div class="flex items-center gap-3 mb-2"><div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-crosshairs"></i></div><h4 class="font-display font-bold text-gray-900">.38 Pistol Revolver</h4></div>
                                        <p class="text-[10px] text-gray-500 leading-relaxed">Revolver-style profile for the simulation menu.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2 border-t border-violet-100">
                                <div class="menu-summary">
                                    Selected: <strong id="menu-summary-time">30s</strong> · <strong id="menu-summary-firearm">9mm Pistol</strong>
                                </div>
                                <button onclick="startSimulation()" class="start-sim-btn">
                                    <i class="fas fa-play"></i> Start Simulation
                                </button>
                            </div>
                            <p class="menu-footnote">The chosen firearm updates the session label only. The assembly workflow remains the same once the simulation starts.</p>
                        </div>
                    </div>
                </div>
                <div id="app">
                    <div class="header">
                        <h1>Glock 9mm - Assembly Trainer</h1>
                        <span id="badge">ASSEMBLY MODE</span>
                    </div>
                    <div class="session-strip">
                        <div class="session-pill" id="session-time-pill">Time: 30s</div>
                        <div class="session-pill" id="session-firearm-pill">Firearm: 9mm Pistol</div>
                    </div>
                    <div class="mode-row">
                        <button class="mbtn on" id="btn-asm" type="button">▲ Assemble</button>
                        <button class="mbtn" id="btn-dis" type="button">▼ Disassemble</button>
                        <button class="rbtn" id="btn-reset" type="button">↺ Reset</button>
                    </div>
                    <div class="prow">
                        <div class="pbar"><div class="pfill" id="pfill" style="width:0%"></div></div>
                        <span class="ptxt" id="ptxt">0 / 4</span>
                    </div>
                    <div class="info" id="info">Drag each part from the tray onto the pistol to assemble it layer by layer.</div>
                    <div class="layout">
                        <div class="tray-wrap">
                            <div class="tray-lbl">Parts Tray</div>
                            <div class="tray" id="tray" ondragover="event.preventDefault();this.classList.add('over')" ondragleave="this.classList.remove('over')" ondrop="dropTray(event)"></div>
                        </div>
                        <div class="canvas-wrap">
                            <div class="canvas-lbl">Assembly View - drag parts onto their position</div>
                            <div class="gun-stage" id="stage">
                                <div class="stage-bg"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="toast" id="toast"></div>
                <div id="page-timer-widget" class="timer-widget hidden p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.22em] text-violet-500 font-bold">Timer</p>
                            <p class="text-xs text-gray-500">Countdown control</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center">
                            <i class="fas fa-stopwatch"></i>
                        </div>
                    </div>
                    <div class="rounded-2xl bg-violet-50 border border-violet-100 px-4 py-3 text-center mb-3">
                        <div id="page-timer-display" class="font-display font-bold text-3xl text-violet-700">30</div>
                        <div class="text-[10px] uppercase tracking-widest text-violet-500 font-bold mt-1">Seconds</div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="startPageTimer()" class="rounded-xl bg-violet-700 px-3 py-2 text-xs font-bold text-white hover:bg-violet-600 transition-colors">Start Timer</button>
                        <button type="button" onclick="resetPageTimer()" class="rounded-xl bg-gray-100 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-200 transition-colors">Reset Timer</button>
                    </div>
                </div>
                <div id="count-in-overlay" class="count-in-overlay" aria-live="polite" aria-atomic="true">
                    <div class="count-in-card">
                        <div id="count-in-number" class="count-in-number">3</div>
                        <div class="count-in-label">Simulation Starts</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('shared.sweet-alerts.module-access', [
        'moduleTitle' => $moduleTitle,
        'moduleState' => $moduleState,
    ])

    <script>
        const IMGS = {
            FRAME: @json(asset('images/assemble/FRAME.png')),
            SLIDE: @json(asset('images/assemble/SLIDE.png')),
            BARREL: @json(asset('images/assemble/BARREL.png')),
            MAGAZINE: @json(asset('images/assemble/MAGAZINE.png'))
        };
        const GLOW_IMGS = {
            FRAME: @json(asset('images/assemble/glow guide/FRAME-GLOW.png')),
            SLIDE: @json(asset('images/assemble/glow guide/SLIDE-GLOW.png')),
            BARREL: @json(asset('images/assemble/glow guide/BARREL-GLOW.png')),
            MAGAZINE: @json(asset('images/assemble/glow guide/MAGAZINE-GLOW.png'))
        };
        const IMAGE_SIZE = { width: 1408, height: 768 };
        const STAGE_SIZE = { width: 660, height: 360 };
        const IMAGE_SCALE = STAGE_SIZE.width / IMAGE_SIZE.width;
        const IMAGE_BOUNDS = {
            FRAME: { x: 242, y: 155, w: 965, h: 596 },
            SLIDE: { x: 234, y: 52, w: 894, h: 509 },
            BARREL: { x: 240, y: 226, w: 564, h: 245 },
            MAGAZINE: { x: 825, y: 319, w: 358, h: 388 }
        };
        const PARTS = [
            { id:'FRAME', name:'Frame / Lower Receiver', desc:'<b>Frame / Lower Receiver</b> - Polymer lower. Houses trigger group, mag well, and grip. The serialized component.', zone:{x:55,y:30,w:420,h:310}, zOrder:1 },
            { id:'BARREL', name:'Barrel', desc:'<b>Barrel</b> - Rifled steel barrel. Drops into the slide from the top.', zone:{x:112,y:106,w:270,h:120}, zOrder:2 },
            { id:'SLIDE', name:'Slide', desc:'<b>Slide</b> - Steel slide with rear serrations. Rides on the frame rails.', zone:{x:42,y:20,w:510,h:145}, zOrder:3 },
            { id:'MAGAZINE', name:'Magazine', desc:'<b>Magazine</b> - Double-stack 9mm mag, 15-17 round capacity. Slides up into the grip from below.', zone:{x:230,y:160,w:130,h:195}, zOrder:0 }
        ];
        const FIREARM_LABELS = { '9mm': '9mm Pistol', '45': '.45 Caliber', '38': '.38 Pistol Revolver' };
        let mode = 'asm';
        let placed = {};
        let dragId = null;
        let selectedTimeLimit = 30;
        let selectedFirearm = '9mm';
        let simulationStarted = false;

        function setInfo(html){ document.getElementById('info').innerHTML = html; }

        function updateMenuSummary(){
            const timeText = selectedTimeLimit + 's';
            const firearmText = FIREARM_LABELS[selectedFirearm] || FIREARM_LABELS['9mm'];
            document.getElementById('menu-summary-time').textContent = timeText;
            document.getElementById('menu-summary-firearm').textContent = firearmText;
            document.getElementById('session-time-pill').textContent = 'Time: ' + timeText;
            document.getElementById('session-firearm-pill').textContent = 'Firearm: ' + firearmText;
        }

        function selectTime(time, element){
            selectedTimeLimit = time;
            document.querySelectorAll('[data-time]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            updateMenuSummary();
        }

        function selectCustomAssemblyTime(element){
            const customInput = document.getElementById('assembly-range-custom-time');
            const customValue = Math.max(5, parseInt(customInput?.value || '30', 10) || 30);
            if(customInput) customInput.value = customValue;
            selectTime(customValue, element);
        }

        function selectFirearm(firearm, element){
            selectedFirearm = firearm;
            document.querySelectorAll('[data-firearm]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            updateMenuSummary();
        }

        function startSimulation(){
            simulationStarted = true;
            document.getElementById('start-overlay').classList.add('hidden');
            setInfo('Drag each part from the tray onto the pistol to assemble it layer by layer.');
            toast('Simulation started: ' + FIREARM_LABELS[selectedFirearm] + ' · ' + selectedTimeLimit + 's', 'ok');
            pageTimerRemaining = selectedTimeLimit;
            updatePageTimerDisplay();
            showPageTimerWidget();
        }

        let pageTimerInterval = null;
        let pageCountInInterval = null;
        let pageTimerRemaining = selectedTimeLimit;
        const pageTimerDisplay = () => document.getElementById('page-timer-display');

        function showPageTimerWidget(){
            const w = document.getElementById('page-timer-widget');
            if(w) w.classList.remove('hidden');
        }

        function hidePageTimerWidget(){
            const w = document.getElementById('page-timer-widget');
            if(w) w.classList.add('hidden');
        }

        function showCountInOverlay(text){
            const o = document.getElementById('count-in-overlay');
            const n = document.getElementById('count-in-number');
            if(n) n.innerText = text;
            if(o) o.classList.add('active');
        }

        function hideCountInOverlay(){
            const o = document.getElementById('count-in-overlay');
            if(o) o.classList.remove('active');
        }

        function stopPageTimer(){
            if(pageTimerInterval){ clearInterval(pageTimerInterval); pageTimerInterval = null; }
        }

        function updatePageTimerDisplay(){
            const pd = pageTimerDisplay();
            if(pd) pd.innerText = pageTimerRemaining;
        }

        function startPageTimer(){
            stopPageTimer();
            if(pageCountInInterval){ clearInterval(pageCountInInterval); pageCountInInterval = null; }
            pageTimerRemaining = selectedTimeLimit;
            updatePageTimerDisplay();
            hidePageTimerWidget();
            const steps = ['3','2','1','Start!'];
            let stepIndex = 0;
            showCountInOverlay(steps[stepIndex]);
            pageCountInInterval = setInterval(() => {
                stepIndex++;
                if(stepIndex < steps.length){
                    showCountInOverlay(steps[stepIndex]);
                    return;
                }
                clearInterval(pageCountInInterval);
                pageCountInInterval = null;
                hideCountInOverlay();
                pageTimerInterval = setInterval(() => {
                    pageTimerRemaining--;
                    updatePageTimerDisplay();
                    if(pageTimerRemaining <= 0){
                        pageTimerRemaining = 0;
                        updatePageTimerDisplay();
                        stopPageTimer();
                        toast("Time's up!", 'err');
                    }
                }, 1000);
            }, 1000);
        }

        function resetPageTimer(){
            stopPageTimer();
            if(pageCountInInterval){ clearInterval(pageCountInInterval); pageCountInInterval = null; }
            pageTimerRemaining = selectedTimeLimit;
            updatePageTimerDisplay();
            showPageTimerWidget();
            hideCountInOverlay();
        }

        function getNextPart(){
            return PARTS.find(part => !placed[part.id]) || null;
        }

        function pulseStage(){
            const stage = document.getElementById('stage');
            stage.classList.add('snap-glow');
            clearTimeout(stage._snapTimer);
            stage._snapTimer = setTimeout(() => stage.classList.remove('snap-glow'), 850);
        }

        function toast(msg, type){
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.className = 'toast ' + type + ' show';
            clearTimeout(t._t);
            t._t = setTimeout(() => t.classList.remove('show'), 2500);
        }

        function setMode(nextMode){
            mode = nextMode;
            document.getElementById('btn-asm').classList.toggle('on', nextMode === 'asm');
            document.getElementById('btn-dis').classList.toggle('on', nextMode === 'dis');
            document.getElementById('badge').textContent = nextMode === 'asm' ? 'ASSEMBLY MODE' : 'DISASSEMBLY MODE';
            reset();
            if(nextMode === 'dis'){
                PARTS.forEach(part => placed[part.id] = true);
                render();
                setInfo('Drag each part off the pistol back to the tray to disassemble it.');
            }
        }

        function reset(){
            placed = {};
            dragId = null;
            render();
            prog();
            setInfo('Drag each part from the tray onto the pistol to assemble it layer by layer.');
            document.getElementById('stage').classList.remove('done');
        }

        function render(){
            renderLayers();
            renderZones();
            renderTray();
        }

        function renderLayers(){
            document.querySelectorAll('#assembly-shell .layer').forEach(node => node.remove());
            const stage = document.getElementById('stage');
            [...PARTS].sort((a,b) => a.zOrder - b.zOrder).forEach(part => {
                if(!placed[part.id]) return;
                const layer = document.createElement('div');
                layer.className = 'layer';
                layer.id = 'layer-' + part.id;
                layer.style.zIndex = part.zOrder + 1;
                const img = document.createElement('img');
                img.src = IMGS[part.id];
                img.alt = part.name;
                img.style.cssText = 'opacity:1;filter:drop-shadow(0 10px 16px rgba(0,0,0,.28))';
                layer.appendChild(img);
                if(mode === 'dis'){
                    layer.style.cursor = 'grab';
                    layer.style.pointerEvents = 'auto';
                    layer.setAttribute('draggable', 'true');
                    layer.addEventListener('dragstart', e => { dragId = part.id; e.dataTransfer.effectAllowed = 'move'; layer.style.opacity = '.3'; });
                    layer.addEventListener('dragend', () => { layer.style.opacity = '1'; dragId = null; });
                    layer.addEventListener('mouseenter', () => setInfo(part.desc));
                }
                stage.appendChild(layer);
            });
        }

        function renderZones(){
            document.querySelectorAll('#assembly-shell .dzone').forEach(node => node.remove());
            if(mode !== 'asm') return;
            const nextPart = getNextPart();
            const stage = document.getElementById('stage');
            document.querySelectorAll('#assembly-shell .guide-layer').forEach(n => n.remove());
            PARTS.forEach(part => {
                if(placed[part.id]) return;
                const z = part.zone;
                const zone = document.createElement('div');
                zone.className = 'dzone empty';
                if(nextPart && nextPart.id === part.id){
                    zone.classList.add('next');
                    const guideLayer = document.createElement('div');
                    guideLayer.className = 'layer guide-layer';
                    guideLayer.id = 'guide-' + part.id;
                    guideLayer.style.zIndex = part.zOrder;
                    const guideImg = document.createElement('img');
                    guideImg.src = GLOW_IMGS[part.id] || IMGS[part.id];
                    guideImg.alt = part.name + ' guide';
                    guideImg.style.cssText = 'width:100%;height:100%;object-fit:contain;display:block;filter:drop-shadow(0 10px 16px rgba(34,197,94,.45))';
                    guideLayer.appendChild(guideImg);
                    stage.appendChild(guideLayer);
                }
                zone.style.left = z.x + 'px';
                zone.style.top = z.y + 'px';
                zone.style.width = z.w + 'px';
                zone.style.height = z.h + 'px';
                const hint = document.createElement('span');
                hint.className = 'hint';
                hint.textContent = part.name;
                zone.appendChild(hint);
                zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('over'); });
                zone.addEventListener('dragleave', () => zone.classList.remove('over'));
                zone.addEventListener('drop', e => { e.preventDefault(); zone.classList.remove('over'); dropZone(part.id); });
                zone.addEventListener('mouseenter', () => setInfo(part.desc));
                stage.appendChild(zone);
            });
        }

        function renderTray(){
            const tray = document.getElementById('tray');
            tray.innerHTML = '';
            const items = PARTS.filter(part => mode === 'asm' ? !placed[part.id] : placed[part.id]);
            if(!items.length){
                tray.innerHTML = `<div style="font-size:11px;color:#555;padding:20px;text-align:center">${mode === 'asm' ? 'All parts assembled!' : 'All parts removed!'}</div>`;
                return;
            }
            items.forEach(part => {
                const card = document.createElement('div');
                card.className = 'pcard';
                card.dataset.pid = part.id;
                card.setAttribute('draggable', 'true');
                const img = document.createElement('img');
                img.src = IMGS[part.id];
                img.alt = part.name;
                img.style.cssText = 'filter:drop-shadow(0 8px 12px rgba(0,0,0,.12))';
                const label = document.createElement('div');
                label.className = 'nm';
                label.textContent = part.name;
                card.appendChild(img);
                card.appendChild(label);
                card.addEventListener('dragstart', e => { dragId = part.id; e.dataTransfer.effectAllowed = 'move'; card.classList.add('ghost'); });
                card.addEventListener('dragend', () => { card.classList.remove('ghost'); dragId = null; });
                card.addEventListener('mouseenter', () => setInfo(part.desc));
                tray.appendChild(card);
            });
        }

        function dropZone(pid){
            if(!simulationStarted) return;
            if(!dragId) return;
            if(dragId !== pid){
                const correct = PARTS.find(part => part.id === pid);
                toast('Wrong spot! That slot is for the ' + correct.name, 'err');
                return;
            }
            placed[pid] = true;
            dragId = null;
            render();
            prog();
            pulseStage();
            toast(PARTS.find(part => part.id === pid).name + ' installed', 'ok');
            checkDone();
        }

        function dropTray(e){
            e.preventDefault();
            document.getElementById('tray').classList.remove('over');
            if(!simulationStarted) return;
            if(!dragId) return;
            if(mode === 'dis' && placed[dragId]){
                const partName = PARTS.find(part => part.id === dragId)?.name || 'Part';
                placed[dragId] = false;
                render();
                prog();
                toast(partName + ' removed', 'ok');
                dragId = null;
            }
        }

        function checkDone(){
            if(Object.keys(placed).length === PARTS.length){
                document.getElementById('stage').classList.add('done');
                toast('Pistol fully assembled!', 'ok');
            }
        }

        function prog(){
            const n = Object.values(placed).filter(Boolean).length;
            const t = PARTS.length;
            document.getElementById('pfill').style.width = (n / t * 100) + '%';
            document.getElementById('ptxt').textContent = n + ' / ' + t;
        }

        document.getElementById('btn-asm').addEventListener('click', () => setMode('asm'));
        document.getElementById('btn-dis').addEventListener('click', () => setMode('dis'));
        document.getElementById('btn-reset').addEventListener('click', () => reset());
        document.getElementById('tray').addEventListener('dragover', e => { e.preventDefault(); document.getElementById('tray').classList.add('over'); });
        document.getElementById('tray').addEventListener('dragleave', () => document.getElementById('tray').classList.remove('over'));
        document.getElementById('tray').addEventListener('drop', dropTray);
        document.getElementById('assembly-range-custom-time')?.addEventListener('input', function () {
            const customValue = Math.max(5, parseInt(this.value || '30', 10) || 30);
            this.value = customValue;
            selectedTimeLimit = customValue;
            updateMenuSummary();
        });

        updateMenuSummary();
        render();
        prog();
    </script>
@endsection