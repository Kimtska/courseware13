<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IOT-Based Marksmanship - Assembly Trainer</title>
<link rel="icon" type="image/png" href="{{ asset('images/assets/Marksmanship innovatech.png') }}">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
*{box-sizing:border-box;margin:0;padding:0}
html,body{background:linear-gradient(180deg,#f8fafc 0%,#eef2ff 100%);font-family:'Inter',system-ui,sans-serif;color:#1f2937;min-height:100vh;overflow-x:hidden}
body{padding:0}
body.simulation-locked{overflow:hidden}
#assembly-shell{position:relative;min-height:100vh}
#app{max-width:1100px;margin:0 auto;padding:16px}
.nav-link{position:relative;padding:8px 16px;color:rgba(255,255,255,0.65);transition:all .2s;font-size:13px;font-weight:500;border-radius:6px;white-space:nowrap}
.nav-link:hover{color:#fff;background:rgba(255,255,255,0.1)}
.nav-link.active{color:#fff;background:rgba(255,255,255,0.15);font-weight:600}
.nav-link.active::after{content:'';position:absolute;bottom:-14px;left:50%;transform:translateX(-50%);width:20px;height:3px;background:#A78BFA;border-radius:3px}
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
.main-header{transition:all 0.4s ease}
.main-header.game-active{background:rgba(3,3,7,0.7);backdrop-filter:blur(4px);box-shadow:none}
.main-header.game-active .hide-play{display:none}
.main-header .show-play{display:none}
.main-header.game-active .show-play{display:flex}
/* Keep header and nav links above overlays and modals */
#main-header{z-index:99999 !important;position:sticky;top:0}
#main-header .nav-link{position:relative;z-index:100000}
#main-header .hide-play, #main-header .show-play{position:relative;z-index:100000}
.mobile-menu{transform:translateY(-100%);opacity:0;transition:all .3s ease;pointer-events:none}
.mobile-menu.open{transform:translateY(0);opacity:1;pointer-events:auto}
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

*{scrollbar-width:thin;scrollbar-color:#7c3aed #ddd6fe}
*::-webkit-scrollbar{width:10px;height:10px}
*::-webkit-scrollbar-track{background:#ddd6fe;border-radius:9999px}
*::-webkit-scrollbar-thumb{background:#7c3aed;border-radius:9999px;border:2px solid #ddd6fe}
*::-webkit-scrollbar-thumb:hover{background:#5b21b6}
@media (max-width:900px){.layout{grid-template-columns:1fr}.tray{min-height:auto;flex-direction:row;flex-wrap:wrap}.pcard{width:min(100%,170px)}.rbtn{margin-left:0}}
</style>
    @include('shared.back-button-prevention')
</head>
<body>
@php
  $assetBase = asset('images/assemble');
  $embedded = request()->boolean('embedded');
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
@unless ($embedded)
<header id="main-header" data-turbo-permanent class="bg-violet-950 text-white sticky top-0 z-50 shadow-lg shadow-violet-950/30 main-header">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex items-center justify-between h-16">
      <div class="flex items-center gap-3 flex-shrink-0 hide-play">
        <img src="{{ asset('images/assets/Marksmanship innovatech.png') }}" alt="SPC" class="h-9 w-auto">
        <div class="hidden sm:block"><span class="font-display font-bold text-sm">IOT-Based Marksmanship</span><span class="block text-[8px] text-violet-300 uppercase tracking-widest leading-none">Student Portal</span></div>
      </div>
      <div class="hide-play">
        @include('Students.partials.nav-links', ['type' => 'desktop', 'activeNav' => 'assembly'])
      </div>
      <div class="show-play items-center gap-2 px-3 py-1.5 bg-violet-900/50 hover:bg-violet-800/50 rounded-lg text-violet-200 hover:text-white transition-colors text-sm font-medium border border-violet-700/30">
        <i class="fas fa-arrow-left text-xs"></i> Exit
      </div>
      <div class="hide-play flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-2 pl-3 border-l border-violet-800/50">
          <div class="w-8 h-8 rounded-full bg-violet-700 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($firstName ?: $name, 0, 1)) }}{{ strtoupper(substr($lastName ?: $name, 0, 1)) }}</div>
          <span class="text-sm font-medium">{{ $name }}</span>
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
  @include('Students.partials.nav-links', ['type' => 'mobile', 'activeNav' => 'assembly'])
</header>
@endunless
<main id="assembly-shell" class="relative min-h-screen">
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

@php
    $asFirearmsData = [];
    foreach ($firearms as $asF) {
        $asPartsData = [];
        foreach ($asF->parts as $asP) {
            $asPartsData[] = [
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
        $asFirearmsData[$asF->slug] = [
            'label' => $asF->name,
            'parts' => $asPartsData,
        ];
    }
@endphp

<script>
const FIREARMS_DATA = @json($asFirearmsData);

function getFirearmParts() {
    return FIREARMS_DATA[selectedFirearm]?.parts || [];
}

function getFirearmLabel() {
    return FIREARMS_DATA[selectedFirearm]?.label || '9mm Pistol';
}

function getFirearmImg(part) {
    return part.img || '';
}

function getFirearmGlow(part) {
    return part.glow || part.img || '';
}

let mode = 'asm';
let placed = {};
let dragId = null;
let selectedTimeLimit = 30;
let selectedFirearm = '9mm';
let simulationStarted = false;

function setInfo(html){ document.getElementById('info').innerHTML = html; }

function updateMenuSummary(){
  const timeText = selectedTimeLimit + 's';
  const firearmText = getFirearmLabel();
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
  reset();
  updateMenuSummary();
}

function startSimulation(){
  simulationStarted = true;
  document.body.classList.remove('simulation-locked');
  setInfo('Drag each part from the tray onto the pistol to assemble it layer by layer.');
  toast('Simulation started: ' + getFirearmLabel() + ' · ' + selectedTimeLimit + 's', 'ok');
}

function getNextPart(){
  return getFirearmParts().find(part => !placed[part.id]) || null;
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
    getFirearmParts().forEach(part => placed[part.id] = true);
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
  document.querySelectorAll('.layer').forEach(node => node.remove());
  const stage = document.getElementById('stage');
  [...getFirearmParts()].sort((a,b) => a.zOrder - b.zOrder).forEach(part => {
    if(!placed[part.id]) return;
    const layer = document.createElement('div');
    layer.className = 'layer';
    layer.id = 'layer-' + part.id;
    layer.style.zIndex = part.zOrder + 1;

    const img = document.createElement('img');
    img.src = getFirearmImg(part);
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
  document.querySelectorAll('.dzone').forEach(node => node.remove());
  if(mode !== 'asm') return;
  const nextPart = getNextPart();
  const stage = document.getElementById('stage');
  document.querySelectorAll('.guide-layer').forEach(n => n.remove());
  getFirearmParts().forEach(part => {
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
      guideImg.src = getFirearmGlow(part);
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
  const items = getFirearmParts().filter(part => mode === 'asm' ? !placed[part.id] : placed[part.id]);
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
    img.src = getFirearmImg(part);
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
    const correct = getFirearmParts().find(part => part.id === pid);
    toast('Wrong spot! That slot is for the ' + correct.name, 'err');
    return;
  }
  placed[pid] = true;
  dragId = null;
  render();
  prog();
  pulseStage();
  toast(getFirearmParts().find(part => part.id === pid).name + ' installed', 'ok');
  checkDone();
}

function dropTray(e){
  e.preventDefault();
  document.getElementById('tray').classList.remove('over');
  if(!simulationStarted) return;
  if(!dragId) return;
  if(mode === 'dis' && placed[dragId]){
    const partName = getFirearmParts().find(part => part.id === dragId)?.name || 'Part';
    placed[dragId] = false;
    render();
    prog();
    toast(partName + ' removed', 'ok');
    dragId = null;
  }
}

function checkDone(){
  const parts = getFirearmParts();
  if(Object.keys(placed).length === parts.length && parts.length > 0){
    document.getElementById('stage').classList.add('done');
    toast('Pistol fully assembled!', 'ok');
  }
}

function prog(){
  const n = Object.values(placed).filter(Boolean).length;
  const t = getFirearmParts().length;
  document.getElementById('pfill').style.width = (t > 0 ? (n / t * 100) : 0) + '%';
  document.getElementById('ptxt').textContent = n + ' / ' + t;
}

document.getElementById('btn-asm').addEventListener('click', () => setMode('asm'));
document.getElementById('btn-dis').addEventListener('click', () => setMode('dis'));
document.getElementById('btn-reset').addEventListener('click', () => reset());
document.getElementById('tray').addEventListener('dragover', e => { e.preventDefault(); document.getElementById('tray').classList.add('over'); });
document.getElementById('tray').addEventListener('dragleave', () => document.getElementById('tray').classList.remove('over'));
document.getElementById('tray').addEventListener('drop', dropTray);
document.getElementById('mobile-toggle')?.addEventListener('click', () => document.getElementById('mobile-menu')?.classList.toggle('open'));
document.getElementById('assembly-range-custom-time')?.addEventListener('input', function () {
  const customValue = Math.max(5, parseInt(this.value || '30', 10) || 30);
  this.value = customValue;
  selectedTimeLimit = customValue;
  updateMenuSummary();
});

document.body.classList.add('simulation-locked');
updateMenuSummary();

startSimulation();

document.body.classList.remove('simulation-locked');

render();
prog();
</script>

@include('shared.sweet-alerts.logout', ['logoutLabel' => 'Student — ' . $name, 'logoutSubtext' => 'IOT-Based Assembly Trainer', 'logoutDescription' => 'You are about to end your session. Make sure your progress is saved before logging out.', 'redirectUrl' => url('/login')])
</main>
</body>
</html>
