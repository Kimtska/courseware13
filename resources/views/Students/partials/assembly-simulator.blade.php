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

        @media (max-width:900px){.assembly-simulator .as-layout{grid-template-columns:1fr}.assembly-simulator .as-tray{min-height:auto}.assembly-simulator .as-pcard{width:calc(50% - 4px);min-width:0}.assembly-simulator .as-rbtn{margin-left:0}}
    </style>
    <div class="as-layout">
        <div class="as-tray-wrap">
            <div class="as-tray-lbl">Parts Tray</div>
            <div class="as-tray" id="as-tray" ondragover="event.preventDefault();this.classList.add('over')" ondragleave="this.classList.remove('over')" ondrop="asDropTray(event)"></div>
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




</div>

<script>
(function () {
    const root = document.currentScript ? document.currentScript.previousElementSibling : null;
    const scope = (root && root.classList && root.classList.contains('assembly-simulator')) ? root : document;

    const $ = (id) => scope.querySelector('#' + id);

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
    let simulationStarted = true;

    function setInfo(html){ const el = $('as-info'); if (el) el.innerHTML = html; }
    function updateMenuSummary(){
        const timeText = selectedTimeLimit + 's';
        const firearmText = FIREARM_LABELS[selectedFirearm] || FIREARM_LABELS['9mm'];
        const st = $('as-session-time-pill'); if (st) st.textContent = 'Time: ' + timeText;
        const sf = $('as-session-firearm-pill'); if (sf) sf.textContent = 'Firearm: ' + firearmText;
    }


    function getNextPart(){ return PARTS.find(part => !placed[part.id]) || null; }
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
        reset();
        if (nextMode === 'dis') {
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
        [...PARTS].sort((a,b) => a.zOrder - b.zOrder).forEach(part => {
            if (!placed[part.id]) return;
            const layer = document.createElement('div');
            layer.className = 'as-layer';
            layer.id = 'as-layer-' + part.id;
            layer.style.zIndex = part.zOrder + 1;
            const img = document.createElement('img');
            img.src = IMGS[part.id];
            img.alt = part.name;
            img.style.cssText = 'opacity:1;filter:drop-shadow(0 10px 16px rgba(0,0,0,.28))';
            layer.appendChild(img);
            if (mode === 'dis') {
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
        const stage = $('as-stage'); if (!stage) return;
        stage.querySelectorAll('.as-dzone').forEach(n => n.remove());
        if (mode !== 'asm') return;
        const nextPart = getNextPart();
        stage.querySelectorAll('.as-guide-layer').forEach(n => n.remove());
        PARTS.forEach(part => {
            if (placed[part.id]) return;
            const z = part.zone;
            const zone = document.createElement('div');
            zone.className = 'as-dzone empty';
            if (nextPart && nextPart.id === part.id) {
                zone.classList.add('next');
                const guideLayer = document.createElement('div');
                guideLayer.className = 'as-layer as-guide-layer';
                guideLayer.id = 'as-guide-' + part.id;
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
            zone.addEventListener('drop', e => { e.preventDefault(); zone.classList.remove('over'); asDropZone(part.id); });
            zone.addEventListener('mouseenter', () => setInfo(part.desc));
            stage.appendChild(zone);
        });
    }
    function renderTray(){
        const tray = $('as-tray'); if (!tray) return;
        tray.innerHTML = '';
        const items = PARTS.filter(part => mode === 'asm' ? !placed[part.id] : placed[part.id]);
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
    function asDropZone(pid){
        if (!dragId) return;
        if (dragId !== pid) {
            const correct = PARTS.find(part => part.id === pid);
            asToast('Wrong spot! That slot is for the ' + correct.name, 'err');
            return;
        }
        placed[pid] = true;
        dragId = null;
        render();
        prog();
        pulseStage();
        asToast(PARTS.find(part => part.id === pid).name + ' installed', 'ok');
        checkDone();
    }
    window.asDropTray = function (e){
        e.preventDefault();
        const tray = $('as-tray'); if (tray) tray.classList.remove('over');
        if (!dragId) return;
        if (mode === 'dis' && placed[dragId]) {
            const partName = PARTS.find(part => part.id === dragId)?.name || 'Part';
            placed[dragId] = false;
            render();
            prog();
            asToast(partName + ' removed', 'ok');
            dragId = null;
        }
    };
    function checkDone(){
        if (Object.keys(placed).length === PARTS.length) {
            const stage = $('as-stage'); if (stage) stage.classList.add('done');
            asToast('Pistol fully assembled!', 'ok');
        }
    }
    function prog(){
        const n = Object.values(placed).filter(Boolean).length;
        const t = PARTS.length;
        const f = $('as-pfill'); if (f) f.style.width = (n / t * 100) + '%';
        const tx = $('as-ptxt'); if (tx) tx.textContent = n + ' / ' + t;
    }
    const ba = $('as-btn-asm'); if (ba) ba.addEventListener('click', () => setMode('asm'));
    const bd = $('as-btn-dis'); if (bd) bd.addEventListener('click', () => setMode('dis'));
    const br = $('as-btn-reset'); if (br) br.addEventListener('click', () => reset());
    const tray = $('as-tray');
    if (tray) {
        tray.addEventListener('dragover', e => { e.preventDefault(); tray.classList.add('over'); });
        tray.addEventListener('dragleave', () => tray.classList.remove('over'));
        tray.addEventListener('drop', window.asDropTray);
    }
    updateMenuSummary();
    render();
    prog();
})();
</script>
