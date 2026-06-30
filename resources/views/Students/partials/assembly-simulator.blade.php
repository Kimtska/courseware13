<div class="assembly-simulator" data-embedded="1">
    <style>
        .assembly-simulator{position:relative;width:100%;max-width:100%;padding:0}
        .assembly-simulator .as-header{display:flex;align-items:center;gap:12px;margin-bottom:14px;flex-wrap:wrap}
        .assembly-simulator .as-header h1{font-size:17px;font-weight:600;color:#111827;letter-spacing:.04em;margin:0}
        .assembly-simulator .as-header span.badge{font-size:11px;background:#f3e8ff;border:1px solid #ddd6fe;padding:3px 9px;border-radius:4px;color:#6d28d9}
        .assembly-simulator .as-session-strip{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}
        .assembly-simulator .as-session-pill{font-size:11px;padding:6px 10px;border-radius:9999px;background:#f3e8ff;border:1px solid #ddd6fe;color:#6d28d9;font-weight:700}
        .assembly-simulator .as-mode-row{display:flex;gap:8px;align-items:center;margin-bottom:12px;flex-wrap:wrap}
        .assembly-simulator .as-mbtn{font-size:12px;padding:6px 16px;border-radius:6px;border:1px solid #ddd6fe;background:#fff;color:#6b7280;cursor:pointer;transition:all .15s}
        .assembly-simulator .as-mbtn.on{background:#7c3aed;color:#fff;border-color:#7c3aed;font-weight:700}
        .assembly-simulator .as-mbtn:hover:not(.on){background:#f8f5ff;color:#111827}
        .assembly-simulator .as-rbtn{margin-left:auto;font-size:12px;padding:6px 14px;border-radius:6px;border:1px solid #ddd6fe;background:#fff;color:#6d28d9;cursor:pointer}
        .assembly-simulator .as-rbtn:hover{color:#4c1d95;border-color:#c4b5fd}
        .assembly-simulator .as-prow{display:flex;align-items:center;gap:10px;margin-bottom:12px}
        .assembly-simulator .as-pbar{flex:1;height:4px;background:#e5e7eb;border-radius:2px;overflow:hidden}
        .assembly-simulator .as-pfill{height:100%;background:#7c3aed;border-radius:2px;transition:width .35s ease}
        .assembly-simulator .as-ptxt{font-size:11px;color:#6b7280;white-space:nowrap;min-width:60px;text-align:right}
        .assembly-simulator .as-info{background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:9px 14px;font-size:12px;color:#6b7280;margin-bottom:14px;min-height:36px;line-height:1.5;box-shadow:0 12px 28px -18px rgba(30,5,82,.18)}
        .assembly-simulator .as-info b{color:#7c3aed}
        .assembly-simulator .as-layout{display:grid;grid-template-columns:320px minmax(0,1fr);gap:14px;align-items:start}
        .assembly-simulator .as-tray-wrap{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:10px;box-shadow:0 12px 28px -18px rgba(30,5,82,.18);width:100%}
        .assembly-simulator .as-tray-lbl{font-size:10px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px;font-weight:700}
        .assembly-simulator .as-tray{min-height:320px;border:1.5px dashed #ddd6fe;border-radius:7px;padding:6px;display:flex;flex-direction:row;flex-wrap:wrap;gap:8px;align-content:flex-start;transition:border-color .15s;background:rgba(248,245,255,0.7);overflow-y:hidden}
        .assembly-simulator .as-tray.over{border-color:#7c3aed;background:#f5f3ff}
        .assembly-simulator .as-pcard{cursor:grab;border:1px solid #e5e7eb;border-radius:7px;background:#fff;padding:6px;display:flex;flex-direction:column;align-items:center;gap:4px;width:calc(25% - 6px);min-width:0;transition:border-color .15s,transform .1s;user-select:none}
        .assembly-simulator .as-pcard:active{cursor:grabbing;transform:scale(1.03)}
        .assembly-simulator .as-pcard.ghost{opacity:.25}
        .assembly-simulator .as-pcard img{width:140px;height:auto;display:block;border-radius:4px;object-fit:contain}
        .assembly-simulator .as-pcard .nm{font-size:9px;color:#6b7280;text-align:center;letter-spacing:.04em}
        .assembly-simulator .as-canvas-wrap{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px;display:flex;flex-direction:column;align-items:center;min-width:0;box-shadow:0 12px 28px -18px rgba(30,5,82,.18)}
        .assembly-simulator .as-canvas-lbl{font-size:10px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px;align-self:flex-start;font-weight:700}
        .assembly-simulator .as-gun-stage{position:relative;width:min(660px,100%);aspect-ratio:660/360;border-radius:8px;overflow:hidden;background:#ffffff;border:1px solid #e5e7eb}
        .assembly-simulator .as-stage-bg{position:absolute;inset:0;background:radial-gradient(circle at 50% 12%, rgba(124,58,237,.05), transparent 35%), linear-gradient(180deg, rgba(255,255,255,.96), rgba(248,250,252,.96));}
        .assembly-simulator .as-layer{position:absolute;inset:0;pointer-events:none;transition:opacity .25s ease, transform .25s ease}
        .assembly-simulator .as-layer img{width:100%;height:100%;object-fit:contain;display:block;filter:drop-shadow(0 10px 14px rgba(0,0,0,.12))}
        .assembly-simulator .as-dzone{position:absolute;border:1.5px solid transparent;border-radius:6px;transition:border-color .2s,background .2s,box-shadow .2s,transform .2s;cursor:default;display:flex;align-items:flex-end;justify-content:center;padding-bottom:3px;z-index:3}
        .assembly-simulator .as-dzone.empty{border-color:#ddd6fe}
        .assembly-simulator .as-dzone.over{border-color:#7c3aed;background:rgba(124,58,237,.08)}
        .assembly-simulator .as-dzone .hint{font-size:8px;color:#6b7280;pointer-events:none;text-align:center;line-height:1.2}
        .assembly-simulator .as-dzone.filled{border-color:transparent}
        .assembly-simulator .as-dzone.next{border-color:transparent;background:transparent;box-shadow:none;animation:none}
        .assembly-simulator .as-guide-art{position:absolute;inset:0;width:100%;height:100%;object-fit:contain;pointer-events:none;z-index:1;filter:brightness(0) saturate(100%) invert(67%) sepia(78%) saturate(435%) hue-rotate(84deg) brightness(102%) contrast(97%) drop-shadow(0 0 8px rgba(34,197,94,.95)) drop-shadow(0 0 18px rgba(34,197,94,.55))}
        .assembly-simulator .as-dzone.next .hint{position:relative;z-index:2;color:#15803d;font-weight:700;text-shadow:0 0 8px rgba(34,197,94,.35)}
        .assembly-simulator .as-gun-stage.done{box-shadow:0 0 30px 4px rgba(124,58,237,.18)}
        .assembly-simulator .as-gun-stage.snap-glow{box-shadow:0 0 0 1px rgba(34,197,94,.3),0 0 34px 8px rgba(34,197,94,.22),0 12px 28px -18px rgba(30,5,82,.18)}
        .assembly-simulator .as-toast{position:fixed;bottom:20px;left:50%;transform:translateX(-50%);padding:8px 20px;border-radius:7px;font-size:13px;font-weight:600;opacity:0;transition:opacity .25s;pointer-events:none;z-index:999;white-space:nowrap}
        .assembly-simulator .as-toast.show{opacity:1}
        .assembly-simulator .as-toast.ok{background:#7c3aed;color:#fff}
        .assembly-simulator .as-toast.err{background:#ef4444;color:#fff}

        .assembly-simulator .as-result-overlay{position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.4);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;padding:16px}
        .assembly-simulator .as-result-overlay.open{display:flex}
        .assembly-simulator .as-result-panel{background:#fff;border-radius:20px;padding:32px;max-width:400px;width:100%;text-align:center;box-shadow:0 30px 70px -20px rgba(0,0,0,.35)}
        .assembly-simulator .as-result-icon{width:64px;height:64px;border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-size:28px}
        .assembly-simulator .as-result-icon.pass{background:#d1fae5;color:#059669}
        .assembly-simulator .as-result-icon.fail{background:#fef2f2;color:#dc2626}
        .assembly-simulator .as-result-title{font-size:20px;font-weight:800;color:#111827;margin-bottom:8px}
        .assembly-simulator .as-result-score{font-size:32px;font-weight:800;color:#7c3aed;margin-bottom:12px}
        .assembly-simulator .as-result-details{font-size:13px;color:#6b7280;line-height:1.6;margin-bottom:20px}
        .assembly-simulator .as-result-actions{display:flex;gap:8px;justify-content:center}
        .assembly-simulator .as-result-actions .as-rbtn{padding:10px 24px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;transition:all .15s;margin:0}
        .assembly-simulator .as-result-actions .as-rbtn.primary{background:#7c3aed;color:#fff;border:none}
        .assembly-simulator .as-result-actions .as-rbtn.primary:hover{background:#6d28d9}
        .assembly-simulator .as-result-actions .as-rbtn.secondary{background:#fff;color:#374151;border:1px solid #e5e7eb}
        .assembly-simulator .as-result-actions .as-rbtn.secondary:hover{background:#f9fafb}
        @media (max-width:900px){.assembly-simulator .as-layout{grid-template-columns:1fr}.assembly-simulator .as-tray{min-height:auto}.assembly-simulator .as-pcard{width:calc(50% - 4px);min-width:0}.assembly-simulator .as-rbtn{margin-left:0}}
    </style>
    <div class="as-layout">
        <div class="as-tray-wrap">
            <div class="as-tray-lbl">Parts Tray</div>
            <div class="as-tray" id="as-tray"></div>
        </div>

        <div class="as-canvas-wrap">
            <div class="as-canvas-lbl">Assembly View - drag parts onto their position</div>
            <div class="as-gun-stage" id="as-stage">
                <div class="as-stage-bg"></div>
            </div>
        </div>
    </div>

    <div class="as-mode-row" style="margin-top:14px">
        <button class="as-mbtn on" id="as-btn-asm" type="button">▲ Assemble</button>
        <button class="as-mbtn" id="as-btn-dis" type="button">▼ Disassemble</button>
        <button class="as-rbtn" id="as-btn-reset" type="button">↺ Reset</button>
    </div>

    <div class="as-prow">
        <div class="as-pbar"><div class="as-pfill" id="as-pfill" style="width:0%"></div></div>
        <span class="as-ptxt" id="as-ptxt">0 / 4</span>
    </div>

    <div class="as-info" id="as-info">Drag each part from the tray onto the pistol to assemble it layer by layer.</div>

    <div class="as-toast" id="as-toast"></div>

    <div class="as-result-overlay" id="as-result-overlay">
        <div class="as-result-panel">
            <div class="as-result-icon" id="as-result-icon">
                <i class="fas fa-check" id="as-result-check" style="display:none"></i>
                <i class="fas fa-xmark" id="as-result-cross" style="display:none"></i>
            </div>
            <h3 class="as-result-title" id="as-result-title">Complete!</h3>
            <div class="as-result-score" id="as-result-score">0 / 100</div>
            <div class="as-result-details" id="as-result-details"></div>
            <div class="as-result-actions">
                <button class="as-rbtn" id="as-result-close" type="button">Close</button>
            </div>
        </div>
    </div>

</div>

@php
    $asSimulations = $simulations ?? \App\Models\AssessmentSimulation::with('parts')->get();
    $asSimulationsData = [];
    foreach ($asSimulations as $asS) {
        $partsData = [];
        foreach ($asS->parts as $asP) {
            $partsData[] = [
                'id' => strtoupper($asP->slug),
                'name' => $asP->name,
                'desc' => $asP->description ?? '',
                'slug' => $asP->slug,
                'sort_order' => $asP->sort_order,
                'zOrder' => $asP->z_order,
                'img' => $asP->image_path ? asset($asP->image_path) : null,
                'glow' => $asP->glow_image_path ? asset($asP->glow_image_path) : null,
                'zone' => [
                    'x' => (int)$asP->zone_x,
                    'y' => (int)$asP->zone_y,
                    'w' => (int)$asP->zone_w,
                    'h' => (int)$asP->zone_h,
                ],
            ];
        }
        $asSimulationsData[$asS->slug] = [
            'label' => $asS->name,
            'parts' => $partsData,
        ];
    }
@endphp

<script>
(function () {
    const root = document.currentScript ? document.currentScript.previousElementSibling : null;
    const scope = (root && root.classList && root.classList.contains('assembly-simulator')) ? root : document;

    const $ = (id) => scope.querySelector('#' + id);

    const SIMULATIONS_DATA = @json($asSimulationsData);

    let currentSimulationSlug = '9mm';

    function getSimulationParts() {
        return SIMULATIONS_DATA[currentSimulationSlug]?.parts || [];
    }

    function getSimulationLabel() {
        return SIMULATIONS_DATA[currentSimulationSlug]?.label || '9mm Pistol';
    }

    function getSimulationImg(part) {
        return part.img || '';
    }

    function getSimulationGlow(part) {
        return part.glow || part.img || '';
    }

    let mode = 'asm';
    let placed = {};
    let dragId = null;
    let selectedTimeLimit = 30;
    let selectedSimulation = '9mm';
    let simulationStarted = true;

    let asmResult = {
        mode: 'asm',
        simulation_slug: '9mm',
        started_at: null,
        completed_at: null,
        wrong_attempts: 0,
        part_attempts: {},
        mistakes: [],
        parts_order: [],
        score: 0,
        max_score: 100,
        passed: false,
        submitted: false
    };

    function resetAsmResult() {
        asmResult.mode = mode;
        asmResult.simulation_slug = selectedSimulation;
        asmResult.started_at = null;
        asmResult.completed_at = null;
        asmResult.wrong_attempts = 0;
        asmResult.part_attempts = {};
        asmResult.mistakes = [];
        asmResult.parts_order = [];
        asmResult.score = 0;
        asmResult.passed = false;
        asmResult.submitted = false;
    }

    function setInfo(html){ const el = $('as-info'); if (el) el.innerHTML = html; }
    function updateMenuSummary(){
        const timeText = selectedTimeLimit + 's';
        const simulationText = getSimulationLabel();
        const st = $('as-session-time-pill'); if (st) st.textContent = 'Time: ' + timeText;
        const sf = $('as-session-simulation-pill'); if (sf) sf.textContent = 'Simulation: ' + simulationText;
    }

    function getNextPart(){ return getSimulationParts().find(part => !placed[part.id]) || null; }
    function pulseStage(){ const stage = $('as-stage'); if (!stage) return; stage.classList.add('snap-glow'); clearTimeout(stage._snapTimer); stage._snapTimer = setTimeout(() => stage.classList.remove('snap-glow'), 850); }
    function asToast(msg, type){
        const t = $('as-toast'); if (!t) return;
        t.textContent = msg;
        t.className = 'as-toast ' + type + ' show';
        clearTimeout(t._t);
        t._t = setTimeout(() => t.classList.remove('show'), 2500);
    }
    function setMode(nextMode){
        mode = nextMode;
        const ba = $('as-btn-asm'); if (ba) ba.classList.toggle('on', nextMode === 'asm');
        const bd = $('as-btn-dis'); if (bd) bd.classList.toggle('on', nextMode === 'dis');
        const bg = $('as-badge'); if (bg) bg.textContent = nextMode === 'asm' ? 'ASSEMBLY MODE' : 'DISASSEMBLY MODE';
        const ov = $('as-result-overlay'); if (ov) ov.classList.remove('open');
        resetAsmResult();
        reset();
        if (nextMode === 'dis') {
            getSimulationParts().forEach(part => placed[part.id] = true);
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
        const stage = $('as-stage'); if (stage) stage.classList.remove('done');
    }
    function render(){
        renderLayers();
        renderZones();
        renderTray();
    }
    function renderLayers(){
        const stage = $('as-stage'); if (!stage) return;
        stage.querySelectorAll('.as-layer').forEach(n => n.remove());
        [...getSimulationParts()].sort((a,b) => a.zOrder - b.zOrder).forEach(part => {
            if (!placed[part.id]) return;
            const layer = document.createElement('div');
            layer.className = 'as-layer';
            layer.dataset.pid = part.id;
            layer.style.zIndex = part.zOrder + 1;
            const img = document.createElement('img');
            img.src = getSimulationImg(part);
            img.alt = part.name;
            img.style.cssText = 'opacity:1;filter:drop-shadow(0 10px 16px rgba(0,0,0,.28))';
            layer.appendChild(img);
            if (mode === 'dis') {
                layer.style.cursor = 'grab';
                layer.style.pointerEvents = 'auto';
                layer.setAttribute('draggable', 'true');
            }
            stage.appendChild(layer);
        });
    }
    function renderZones(){
        const stage = $('as-stage'); if (!stage) return;
        stage.querySelectorAll('.as-dzone').forEach(n => n.remove());
        if (mode !== 'asm') return;
        const nextPart = getNextPart();
        stage.querySelectorAll('.as-guide-layer').forEach(n => n.remove());
        getSimulationParts().forEach(part => {
            if (placed[part.id]) return;
            const z = part.zone;
            const zone = document.createElement('div');
            zone.className = 'as-dzone empty';
            zone.dataset.pid = part.id;
            if (nextPart && nextPart.id === part.id) {
                zone.classList.add('next');
                const guideLayer = document.createElement('div');
                guideLayer.className = 'as-layer as-guide-layer';
                guideLayer.id = 'as-guide-' + part.id;
                guideLayer.style.zIndex = part.zOrder;
                const guideImg = document.createElement('img');
                guideImg.src = getSimulationGlow(part);
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
            stage.appendChild(zone);
        });
    }
    function renderTray(){
        const tray = $('as-tray'); if (!tray) return;
        tray.innerHTML = '';
        const items = getSimulationParts().filter(part => mode === 'asm' ? !placed[part.id] : placed[part.id]);
        if (!items.length) {
            tray.innerHTML = '<div style="font-size:11px;color:#555;padding:20px;text-align:center">' + (mode === 'asm' ? 'All parts assembled!' : 'All parts removed!') + '</div>';
            return;
        }
        items.forEach(part => {
            const card = document.createElement('div');
            card.className = 'as-pcard';
            card.dataset.pid = part.id;
            card.setAttribute('draggable', 'true');
            const img = document.createElement('img');
            img.src = getSimulationImg(part);
            img.alt = part.name;
            img.style.cssText = 'filter:drop-shadow(0 8px 12px rgba(0,0,0,.12))';
            const label = document.createElement('div');
            label.className = 'nm';
            label.textContent = part.name;
            card.appendChild(img);
            card.appendChild(label);
            tray.appendChild(card);
        });
    }
    function asDropZone(pid){
        if (!dragId) return;
        if (!asmResult.started_at) asmResult.started_at = new Date().toISOString();
        if (dragId !== pid) {
            const correct = getSimulationParts().find(part => part.id === pid);
            asmResult.wrong_attempts++;
            asmResult.mistakes.push({ partId: dragId, expectedPid: pid, zonePid: pid, timeMs: Date.now() });
            asmResult.part_attempts[dragId] = (asmResult.part_attempts[dragId] || 0) + 1;
            asToast('Wrong spot! That slot is for the ' + correct.name, 'err');
            return;
        }
        placed[pid] = true;
        asmResult.part_attempts[pid] = (asmResult.part_attempts[pid] || 0) + 1;
        asmResult.parts_order.push(pid);
        dragId = null;
        render();
        prog();
        pulseStage();
        asToast(getSimulationParts().find(part => part.id === pid).name + ' installed', 'ok');
        checkDone();
    }
    function checkDone(){
        const parts = getSimulationParts();
        if (Object.keys(placed).length === parts.length && parts.length > 0) {
            const stage = $('as-stage'); if (stage) stage.classList.add('done');
            asmResult.completed_at = new Date().toISOString();
            asToast(mode === 'asm' ? 'All parts assembled!' : 'All parts removed!', 'ok');
            setTimeout(submitAssemblyResult, 800);
        }
    }
    function prog(){
        const n = Object.values(placed).filter(Boolean).length;
        const t = getSimulationParts().length;
        const f = $('as-pfill'); if (f) f.style.width = (t > 0 ? (n / t * 100) : 0) + '%';
        const tx = $('as-ptxt'); if (tx) tx.textContent = n + ' / ' + t;
    }

    function submitAssemblyResult() {
        if (asmResult.submitted) return;
        const parts = getSimulationParts();
        const total = parts.length;
        const base = 100 / total;
        const penalty = base * 0.5;
        let totalScore = 0;
        parts.forEach(function(p) {
            const att = asmResult.part_attempts[p.id] || 0;
            const wrong = Math.max(0, att - 1);
            let ps = base - (wrong * penalty);
            if (ps < 0) ps = 0;
            totalScore += ps;
        });
        totalScore = Math.round(totalScore * 100) / 100;
        if (totalScore < 0) totalScore = 0;
        asmResult.score = totalScore;
        asmResult.passed = (totalScore >= 100);
        asmResult.submitted = true;

        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        fetch('{{ route("student.assembly.save-score") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN': csrf},
            body: JSON.stringify({
                simulation_slug: asmResult.simulation_slug,
                mode: asmResult.mode,
                score: totalScore,
                max_score: 100,
                wrong_attempts: asmResult.wrong_attempts,
                passed: asmResult.passed,
                metadata: {
                    started_at: asmResult.started_at,
                    completed_at: asmResult.completed_at,
                    part_attempts: asmResult.part_attempts,
                    mistakes: asmResult.mistakes,
                    parts_order: asmResult.parts_order,
                    total_parts: total,
                }
            })
        }).then(function(r){ return r.json(); }).then(function(d){
            showResultOverlay();
        }).catch(function(){});
    }

    function showResultOverlay() {
        const overlay = $('as-result-overlay');
        if (!overlay) return;
        const icon = $('as-result-icon');
        const check = $('as-result-check');
        const cross = $('as-result-cross');
        const title = $('as-result-title');
        const scoreEl = $('as-result-score');
        const details = $('as-result-details');

        if (asmResult.passed) {
            icon.className = 'as-result-icon pass';
            if (check) check.style.display = 'inline';
            if (cross) cross.style.display = 'none';
            title.textContent = 'Perfect ' + (asmResult.mode === 'asm' ? 'Assembly' : 'Disassembly') + '!';
        } else {
            icon.className = 'as-result-icon fail';
            if (check) check.style.display = 'none';
            if (cross) cross.style.display = 'inline';
            title.textContent = (asmResult.mode === 'asm' ? 'Assembly' : 'Disassembly') + ' Complete';
        }
        scoreEl.textContent = asmResult.score + ' / ' + asmResult.max_score;
        var det = '';
        det += 'Wrong attempts: ' + asmResult.wrong_attempts + '<br>';
        det += 'Parts: ' + Object.keys(asmResult.part_attempts).length + ' / ' + getSimulationParts().length;
        if (asmResult.wrong_attempts === 0) det += '<br><strong style="color:#059669">Correct order &mdash; No mistakes!</strong>';
        details.innerHTML = det;

        overlay.classList.add('open');
    }

    function asHandleDragStart(e) {
        var target = e.target.closest('.as-pcard, .as-layer');
        if (!target) return;
        dragId = target.dataset.pid;
        if (!dragId) return;
        e.dataTransfer.effectAllowed = 'move';
        target.classList.add('ghost');
    }
    function asHandleDragEnd(e) {
        var target = e.target.closest('.as-pcard, .as-layer');
        if (target) target.classList.remove('ghost');
        dragId = null;
    }
    function asHandleDragOver(e) {
        var zone = e.target.closest('.as-dzone, #as-tray');
        if (zone) { e.preventDefault(); zone.classList.add('over'); }
    }
    function asHandleDragLeave(e) {
        var zone = e.target.closest('.as-dzone, #as-tray');
        if (zone) zone.classList.remove('over');
    }
    function asHandleDrop(e) {
        e.preventDefault();
        var zone = e.target.closest('.as-dzone');
        var tray = e.target.closest('#as-tray');
        if (zone) {
            zone.classList.remove('over');
            if (dragId) asDropZone(zone.dataset.pid);
        } else if (tray) {
            tray.classList.remove('over');
            if (dragId && mode === 'dis' && placed[dragId]) {
                if (!asmResult.started_at) asmResult.started_at = new Date().toISOString();
                var partName = getSimulationParts().find(function(p) { return p.id === dragId; })?.name || 'Part';
                placed[dragId] = false;
                asmResult.part_attempts[dragId] = (asmResult.part_attempts[dragId] || 0) + 1;
                asmResult.parts_order.push(dragId);
                render();
                prog();
                asToast(partName + ' removed', 'ok');
                dragId = null;
                checkDone();
            }
        }
    }
    function asHandleMouseEnter(e) {
        var target = e.target.closest('.as-pcard, .as-dzone');
        if (target) {
            var pid = target.dataset.pid;
            if (pid) {
                var part = getSimulationParts().find(function(p) { return p.id === pid; });
                if (part) setInfo(part.desc);
            }
        }
    }

    var simRoot = scope.querySelector('.assembly-simulator') || scope;
    simRoot.addEventListener('dragstart', asHandleDragStart);
    simRoot.addEventListener('dragend', asHandleDragEnd);
    simRoot.addEventListener('dragover', asHandleDragOver);
    simRoot.addEventListener('dragleave', asHandleDragLeave);
    simRoot.addEventListener('drop', asHandleDrop);
    simRoot.addEventListener('mouseenter', asHandleMouseEnter, true);

    const ba = $('as-btn-asm'); if (ba) ba.addEventListener('click', () => setMode('asm'));
    const bd = $('as-btn-dis'); if (bd) bd.addEventListener('click', () => setMode('dis'));
    const br = $('as-btn-reset'); if (br) br.addEventListener('click', () => { const ov = $('as-result-overlay'); if (ov) ov.classList.remove('open'); resetAsmResult(); reset(); });
    const rc = $('as-result-close'); if (rc) rc.addEventListener('click', function(){ const ov = $('as-result-overlay'); if (ov) ov.classList.remove('open'); });
    updateMenuSummary();
    resetAsmResult();
    render();
    prog();
})();
</script>
