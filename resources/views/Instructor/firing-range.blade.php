<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VirtualArm - Firing Range</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="margin:0;overflow:hidden;background:#f8fafc;height:100vh">

    <input type="hidden" id="fr-config-weapon" value="{{ $weapon ?? '9mm' }}">
    <input type="hidden" id="fr-config-time" value="{{ $time ?? 30 }}">
    <input type="hidden" id="fr-config-mode" value="{{ $mode ?? 'steady' }}">
    <input type="hidden" id="fr-config-student" value="{{ $studentId ?? '' }}">

    <div id="game-area" style="height:100vh;overflow:hidden;background:#f8fafc;position:relative">
        <div style="position:absolute;inset:0;background:url('{{ asset('images/firing-range/firing-rangebg.jpg') }}') center center/cover no-repeat;filter:saturate(1.08) contrast(1.05)"></div>
        <div style="position:absolute;inset:0;background:linear-gradient(180deg, rgba(10,10,15,0.08) 0%, rgba(10,10,15,0.02) 28%, rgba(10,10,15,0.12) 100%);pointer-events:none"></div>
        <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(124,58,237,0.05) 1px, transparent 1px),linear-gradient(90deg, rgba(124,58,237,0.05) 1px, transparent 1px);background-size:40px 40px;pointer-events:none"></div>

        <div id="target-container" style="position:absolute;inset:0;z-index:10;overflow:hidden"></div>

        <div style="position:absolute;inset-inline:0;top:0;padding:16px;display:flex;justify-content:space-between;align-items:flex-start;z-index:20;pointer-events:none">
            <div style="background:rgba(3,3,7,0.8);backdrop-filter:blur(4px);border:1px solid rgba(124,58,237,0.5);border-radius:12px;padding:10px 16px">
                <p style="font-size:10px;color:#a78bfa;text-transform:uppercase;letter-spacing:0.05em;font-weight:700">Score</p>
                <p id="hud-score" style="font-size:30px;font-weight:700;color:#fff;font-family:'Space Grotesk',sans-serif">0</p>
            </div>
            <div style="display:flex;flex-direction:column;align-items:center;gap:10px">
                <div style="background:rgba(3,3,7,0.8);backdrop-filter:blur(4px);border:1px solid rgba(124,58,237,0.5);border-radius:12px;padding:10px 16px;text-align:center">
                    <p style="font-size:10px;color:#a78bfa;text-transform:uppercase;letter-spacing:0.05em;font-weight:700">Time</p>
                    <p id="hud-timer" style="font-size:30px;font-weight:700;color:#fff;font-family:'Space Grotesk',sans-serif">60</p>
                </div>
                <div id="timer-controls" style="display:flex;align-items:center;gap:6px;background:rgba(3,3,7,0.8);backdrop-filter:blur(4px);border:1px solid rgba(124,58,237,0.5);border-radius:999px;padding:6px;pointer-events:auto;transition:opacity 0.25s ease, transform 0.25s ease">
                    <button id="btn-start" onclick="startFiringRangeFromControl()" title="Start Simulation" style="width:34px;height:34px;border-radius:50%;border:none;background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:transform 0.15s, box-shadow 0.15s, opacity 0.15s;box-shadow:0 4px 10px -3px rgba(34,197,94,0.55)">
                        <i class="fas fa-play" style="font-size:12px;margin-left:2px"></i>
                    </button>
                    <button id="btn-reset" onclick="resetFiringRange()" title="Reset" style="width:34px;height:34px;border-radius:50%;border:none;background:linear-gradient(135deg,#475569,#64748b);color:#fff;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:transform 0.15s, box-shadow 0.15s, opacity 0.15s;box-shadow:0 4px 10px -3px rgba(71,85,105,0.45)">
                        <i class="fas fa-rotate-left" style="font-size:12px"></i>
                    </button>
                </div>
            </div>
            <div style="background:rgba(3,3,7,0.8);backdrop-filter:blur(4px);border:1px solid rgba(124,58,237,0.5);border-radius:12px;padding:10px 16px;text-align:right">
                <p style="font-size:10px;color:#a78bfa;text-transform:uppercase;letter-spacing:0.05em;font-weight:700">Accuracy</p>
                <p id="hud-accuracy" style="font-size:30px;font-weight:700;color:#fff;font-family:'Space Grotesk',sans-serif">0%</p>
            </div>
        </div>

        <div style="position:absolute;inset-inline:0;bottom:0;padding:16px;display:flex;flex-direction:column;align-items:center;z-index:20;pointer-events:none">
            <div id="reload-ui" style="width:192px;margin-bottom:8px;display:none">
                <p style="font-size:10px;color:#a78bfa;text-align:center;text-transform:uppercase;font-weight:700;margin-bottom:4px;animation:pulse 1.5s infinite">Reloading...</p>
                <div style="height:4px;background:#1e1e2e;border-radius:2px;overflow:hidden;width:100%"><div id="reload-fill" style="height:100%;background:#A78BFA;width:0%"></div></div>
            </div>
            <div style="background:rgba(3,3,7,0.8);backdrop-filter:blur(4px);border:1px solid rgba(124,58,237,0.5);border-top-left-radius:12px;border-top-right-radius:12px;padding:12px 24px;display:flex;align-items:flex-end;gap:24px">
                <div style="text-align:center">
                    <p style="font-size:10px;color:#a78bfa;text-transform:uppercase;font-weight:700">Weapon</p>
                    <p id="hud-weapon" style="font-size:14px;font-weight:700;color:#fff;font-family:'Space Grotesk',sans-serif">9mm Pistol</p>
                </div>
                <div style="width:1px;height:32px;background:rgba(124,58,237,0.5)"></div>
                <div style="text-align:center">
                    <p style="font-size:10px;color:#a78bfa;text-transform:uppercase;font-weight:700">Ammo</p>
                    <div style="display:flex;align-items:center;gap:6px">
                        <i class="fas fa-crosshairs" style="color:#a78bfa;font-size:12px"></i>
                        <span id="hud-ammo" style="font-size:20px;font-weight:700;color:#fff;font-family:'Space Grotesk',sans-serif">15</span>
                        <span id="hud-reserve" style="font-size:12px;color:#a78bfa;font-weight:500">/ 45</span>
                    </div>
                </div>
                <div style="width:1px;height:32px;background:rgba(124,58,237,0.5)"></div>
                <div style="text-align:center;pointer-events:auto">
                    <button id="btn-reload" onclick="initiateReload()" style="padding:6px 16px;background:rgba(124,58,237,0.5);border:none;color:#fff;font-size:11px;font-weight:700;border-radius:8px;cursor:pointer;text-transform:uppercase;letter-spacing:0.05em;transition:background 0.2s" disabled>
                        <i class="fas fa-sync-alt" style="margin-right:4px"></i> Reload [R]
                    </button>
                </div>
            </div>
        </div>

        <div id="crosshair" style="position:fixed;pointer-events:none;z-index:100;transform:translate(-50%,-50%);display:none">
            <div style="position:absolute;background:#fff;box-shadow:0 0 4px rgba(255,255,255,0.8);width:16px;height:2px;top:50%;left:50%;transform:translate(-50%,-50%)"></div>
            <div style="position:absolute;background:#fff;box-shadow:0 0 4px rgba(255,255,255,0.8);width:2px;height:16px;top:50%;left:50%;transform:translate(-50%,-50%)"></div>
            <div style="position:absolute;width:4px;height:4px;background:#ef4444;border-radius:50%;top:50%;left:50%;transform:translate(-50%,-50%);box-shadow:0 0 6px #ef4444"></div>
        </div>
        <div id="hit-marker" style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none;z-index:100;opacity:0">
            <div style="position:absolute;width:20px;height:2px;background:#fff;box-shadow:0 0 4px #fff;transform:rotate(45deg);top:-1px;left:-10px"></div>
            <div style="position:absolute;width:20px;height:2px;background:#fff;box-shadow:0 0 4px #fff;transform:rotate(-45deg);top:-1px;left:-10px"></div>
        </div>
        <div id="muzzle-flash" style="position:fixed;bottom:0;left:50%;transform:translateX(-50%);width:100%;height:40%;pointer-events:none;z-index:90;opacity:0;transition:opacity 0.05s"></div>

        <div id="count-in-overlay" style="position:fixed;inset:0;z-index:60;display:flex;align-items:center;justify-content:center;pointer-events:none;opacity:0;visibility:hidden;transition:opacity 0.2s ease, visibility 0.2s ease" aria-live="polite" aria-atomic="true">
            <div style="min-width:220px;padding:24px 28px;border-radius:28px;background:rgba(255,255,255,0.92);backdrop-filter:blur(10px);border:1px solid #e9d5ff;box-shadow:0 20px 50px -16px rgba(30,5,82,0.24);text-align:center">
                <div id="count-in-number" style="font-family:'Space Grotesk',sans-serif;font-size:72px;line-height:1;font-weight:800;color:#5B21B6;letter-spacing:-0.04em">3</div>
                <div style="margin-top:8px;font-size:11px;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;color:#7c3aed">Simulation Starts</div>
            </div>
        </div>

        <div id="end-overlay" style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.78);backdrop-filter:blur(8px);opacity:0;visibility:hidden;transition:opacity 0.3s ease, visibility 0.3s ease" role="dialog" aria-modal="true">
            <div style="background:#fff;border-radius:20px;width:92%;max-width:420px;padding:0;overflow:hidden;box-shadow:0 25px 60px -12px rgba(30,5,82,0.35);transform:scale(0.85) translateY(20px);transition:transform 0.35s cubic-bezier(0.34,1.56,0.64,1), opacity 0.3s ease;text-align:center">
                <div style="padding-top:32px;padding-bottom:8px"><div style="width:64px;height:64px;border-radius:50%;background:#f8f5ff;margin:0 auto;display:flex;align-items:center;justify-content:center;border:4px solid #ede9fe"><i class="fas fa-flag-checkered" style="font-size:24px;color:#7c3aed"></i></div></div>
                <div style="padding:16px 32px 12px">
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:22px;color:#0f172a;margin:0">Simulation Complete</h3>
                    <p id="res-reason" style="font-size:14px;color:#6b7280;margin-top:4px">Time's up!</p>
                </div>
                <div style="margin:0 32px 20px;background:#f9fafb;border-radius:12px;padding:16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;text-align:center;border:1px solid #f3f4f6">
                    <div><p style="font-size:10px;color:#9ca3af;text-transform:uppercase;font-weight:700">Score</p><p id="res-score" style="font-size:20px;font-weight:700;color:#7c3aed;font-family:'Space Grotesk',sans-serif">0</p></div>
                    <div><p style="font-size:10px;color:#9ca3af;text-transform:uppercase;font-weight:700">Accuracy</p><p id="res-accuracy" style="font-size:20px;font-weight:700;color:#7c3aed;font-family:'Space Grotesk',sans-serif">0%</p></div>
                    <div><p style="font-size:10px;color:#9ca3af;text-transform:uppercase;font-weight:700">Bullseyes</p><p id="res-bullseyes" style="font-size:20px;font-weight:700;color:#7c3aed;font-family:'Space Grotesk',sans-serif">0</p></div>
                </div>
                <div style="padding:0 32px 32px;display:flex;align-items:center;gap:12px">
                    <button onclick="restartGame()" style="flex:1;border:none;cursor:pointer;font-family:'Inter',sans-serif;font-weight:700;font-size:13px;padding:12px 24px;border-radius:12px;background:linear-gradient(135deg,#5B21B6,#7C3AED);color:#fff;box-shadow:0 4px 14px -3px rgba(91,33,182,0.4);display:inline-flex;align-items:center;justify-content:center;gap:6px"><i class="fas fa-redo" style="font-size:12px"></i> Try Again</button>
                    <a href="{{ route('instructor.manage-marksmanship') }}" style="border:none;cursor:pointer;font-family:'Inter',sans-serif;font-weight:700;font-size:13px;padding:12px 24px;border-radius:12px;color:#7c3aed;border:1px solid #ede9fe;background:transparent;display:inline-flex;align-items:center;justify-content:center;gap:6px;text-decoration:none"><i class="fas fa-sign-out-alt" style="font-size:12px"></i> Exit</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:0.5}}
        @keyframes targetHit{0%{transform:scale(1);opacity:1}50%{transform:scale(1.2);opacity:0.5}100%{transform:scale(0);opacity:0}}
        @keyframes hitMarkerAnim{0%{opacity:1;transform:translate(-50%,-50%) scale(0.5)}100%{opacity:0;transform:translate(-50%,-50%) scale(1.5)}}
        .target-container{position:absolute;transition:transform 0.2s ease-out, opacity 0.2s}
        .target-container.hit{animation:targetHit 0.4s ease-out forwards}
        .target{width:150px;height:210px;position:relative;cursor:none;filter:drop-shadow(0 12px 18px rgba(0,0,0,0.35));transform-origin:center bottom}
        .target-board{position:absolute;inset:0;border-radius:18px;background:linear-gradient(180deg, rgba(245,240,228,0.98), rgba(228,218,198,0.98));border:2px solid #c8b89a;overflow:hidden;box-shadow:inset 0 0 0 1px rgba(255,255,255,0.6)}
        .target-board::before{content:'';position:absolute;inset:0;background:repeating-linear-gradient(90deg, rgba(255,255,255,0.12) 0 2px, transparent 2px 20px), repeating-linear-gradient(0deg, rgba(143,116,82,0.06) 0 1px, transparent 1px 14px);opacity:0.8}
        .target-board::after{content:'';position:absolute;inset:12px;border-radius:14px;border:1px solid rgba(120,94,61,0.16)}
        .target-stand{position:absolute;bottom:-22px;width:6px;height:28px;background:linear-gradient(180deg,#8a5a2b,#5d3a1a);border-radius:3px}
        .target-stand.left{left:34px;transform:rotate(-5deg)}
        .target-stand.right{right:34px;transform:rotate(5deg)}
        .target-center{position:absolute;left:50%;top:46%;width:82px;height:118px;transform:translate(-50%,-50%)}
        .target-silhouette{position:absolute;left:50%;top:50%;width:64px;height:96px;transform:translate(-50%,-50%);background:linear-gradient(180deg, rgba(19,24,39,0.98), rgba(35,41,57,0.98));clip-path:path('M 32 0 C 42 0 50 8 50 18 C 50 24 47 29 42 33 L 42 39 C 50 46 56 57 57 71 L 57 78 C 57 82 54 85 50 85 L 44 85 L 44 96 L 20 96 L 20 85 L 14 85 C 10 85 7 82 7 78 L 7 71 C 8 57 14 46 22 39 L 22 33 C 17 29 14 24 14 18 C 14 8 22 0 32 0 Z');opacity:0.92}
        .target-ring{position:absolute;left:50%;top:50%;border-radius:50%;transform:translate(-50%,-50%)}
        .target-ring.outer{width:104px;height:104px;border:3px solid rgba(104,81,48,0.75)}
        .target-ring.middle{width:74px;height:74px;border:3px solid rgba(185,28,28,0.7)}
        .target-ring.inner{width:42px;height:42px;border:3px solid rgba(104,81,48,0.85)}
        .target-bullseye{position:absolute;left:50%;top:50%;width:14px;height:14px;transform:translate(-50%,-50%);border-radius:50%;background:#b91c1c;box-shadow:0 0 12px rgba(185,28,28,0.6)}
        .target-head-zone{position:absolute;left:50%;top:18px;width:34px;height:34px;transform:translateX(-50%);border:2px solid rgba(185,28,28,0.62);border-radius:50%}
        .bullet-hole{position:fixed;width:8px;height:8px;background:radial-gradient(circle, #111 0%, #333 60%, transparent 100%);border-radius:50%;pointer-events:none;z-index:5;box-shadow:0 0 2px rgba(0,0,0,0.8)}
        #game-area.cursor-hidden{cursor:none}
        #hit-marker.show{animation:hitMarkerAnim 0.2s ease-out forwards}
        #muzzle-flash.active{opacity:1}
        .reload-fill{transition:width 1.5s linear}
        #timer-controls button:not(:disabled):hover{transform:translateY(-1px) scale(1.06)}
        #timer-controls button:not(:disabled):active{transform:translateY(0) scale(0.96)}
    </style>

    <script>
        const configWeapon = document.getElementById('fr-config-weapon')?.value || '9mm';
        const configTime = parseInt(document.getElementById('fr-config-time')?.value || '30', 10);
        const configMode = document.getElementById('fr-config-mode')?.value || 'steady';
        const configStudentId = document.getElementById('fr-config-student')?.value || '';

        const weaponStats = {
            '9mm': { name: '9mm Pistol', magSize: 15, totalAmmo: 45, reloadTime: 1500, recoil: 8, flashColor: 'rgba(255,200,50,0.4)', flashSize: '40%' },
            '.45': { name: '.45 Caliber', magSize: 7, totalAmmo: 28, reloadTime: 2200, recoil: 15, flashColor: 'rgba(255,150,50,0.5)', flashSize: '50%' },
            '38': { name: '.38 Pistol Revolver', magSize: 6, totalAmmo: 24, reloadTime: 1800, recoil: 12, flashColor: 'rgba(255,215,140,0.55)', flashSize: '45%' }
        };

        const state = {
            selectedWeapon: configWeapon,
            targetMode: configMode,
            selectedTime: configTime,
            score: 0,
            timeLeft: configTime,
            currentAmmo: weaponStats[configWeapon]?.magSize || 15,
            reserveAmmo: weaponStats[configWeapon]?.totalAmmo || 45,
            isReloading: false,
            isPlaying: false,
            hasStarted: false,
            totalShots: 0,
            hits: 0,
            bullseyes: 0
        };

        let gameTimer = null;
        let countdownTimer = null;
        let activeTarget = null;
        let lastFrameTime = performance.now();

        const gameArea = document.getElementById('game-area');
        const crosshair = document.getElementById('crosshair');
        const targetContainer = document.getElementById('target-container');
        const muzzleFlash = document.getElementById('muzzle-flash');
        const hitMarker = document.getElementById('hit-marker');
        const endOverlay = document.getElementById('end-overlay');
        const countInOverlay = document.getElementById('count-in-overlay');
        const countInNumber = document.getElementById('count-in-number');

        const hudScore = document.getElementById('hud-score');
        const hudTimer = document.getElementById('hud-timer');
        const hudWeapon = document.getElementById('hud-weapon');
        const hudAmmo = document.getElementById('hud-ammo');
        const hudReserve = document.getElementById('hud-reserve');
        const hudAccuracy = document.getElementById('hud-accuracy');
        const btnReload = document.getElementById('btn-reload');
        const reloadUI = document.getElementById('reload-ui');
        const reloadFill = document.getElementById('reload-fill');
        const btnStart = document.getElementById('btn-start');
        const btnReset = document.getElementById('btn-reset');
        const timerControls = document.getElementById('timer-controls');

        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        function createNoiseBuffer(duration) { const bufferSize = audioCtx.sampleRate * duration; const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate); const data = buffer.getChannelData(0); for (let i = 0; i < bufferSize; i++) { data[i] = Math.random() * 2 - 1; } return buffer; }
        const noiseBuffer = createNoiseBuffer(0.5);

        function playSound(filterType, frequency, q, duration, volume) {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const source = audioCtx.createBufferSource(); source.buffer = noiseBuffer;
            const filter = audioCtx.createBiquadFilter(); filter.type = filterType; filter.frequency.setValueAtTime(frequency, audioCtx.currentTime); filter.Q.setValueAtTime(q, audioCtx.currentTime);
            const gainNode = audioCtx.createGain(); gainNode.gain.setValueAtTime(volume, audioCtx.currentTime); gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + duration);
            source.connect(filter); filter.connect(gainNode); gainNode.connect(audioCtx.destination); source.start(); source.stop(audioCtx.currentTime + duration);
        }
        function addLowThump(freq, duration, volume) { const osc = audioCtx.createOscillator(); const gain = audioCtx.createGain(); osc.type = 'sine'; osc.frequency.setValueAtTime(freq, audioCtx.currentTime); osc.frequency.exponentialRampToValueAtTime(20, audioCtx.currentTime + duration); gain.gain.setValueAtTime(volume, audioCtx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + duration); osc.connect(gain); gain.connect(audioCtx.destination); osc.start(); osc.stop(audioCtx.currentTime + duration); }

        function playGunshot() {
            const w = state.selectedWeapon;
            if (w === '9mm') { playSound('bandpass', 2000, 1, 0.12, 0.6); addLowThump(150, 0.08, 0.8); }
            else if (w === '.45') { playSound('lowpass', 800, 2, 0.25, 1.0); addLowThump(80, 0.15, 1.2); }
            else if (w === '38') { playSound('bandpass', 1300, 1.4, 0.18, 0.7); addLowThump(110, 0.11, 0.95); }
        }
        function playHitSound() { const osc = audioCtx.createOscillator(); const gain = audioCtx.createGain(); osc.type = 'sine'; osc.frequency.setValueAtTime(1200, audioCtx.currentTime); osc.frequency.exponentialRampToValueAtTime(600, audioCtx.currentTime + 0.1); gain.gain.setValueAtTime(0.2, audioCtx.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1); osc.connect(gain); gain.connect(audioCtx.destination); osc.start(); osc.stop(audioCtx.currentTime + 0.1); }
        function playEmptyClickSound() { playSound('highpass', 4000, 1, 0.02, 0.3); }

        function showCountInOverlay(value) { if (countInNumber) countInNumber.innerText = value; countInOverlay.style.opacity = '1'; countInOverlay.style.visibility = 'visible'; }
        function hideCountInOverlay() { countInOverlay.style.opacity = '0'; countInOverlay.style.visibility = 'hidden'; }

        function startFiring() {
            state.isPlaying = true;
            state.hasStarted = true;
            state.timeLeft = state.selectedTime;
            gameArea.classList.add('cursor-hidden');
            crosshair.style.display = 'block';
            updateHUD();
            setTimerControlsVisible(false);
            updateControlButtons();

            gameTimer = setInterval(() => {
                state.timeLeft--;
                updateHUD();
                if (state.timeLeft <= 0) endGame("Time's up!");
            }, 1000);

            spawnTarget();
            lastFrameTime = performance.now();
            requestAnimationFrame(moveTargets);
        }

        function beginCountdown() {
            const steps = ['3', '2', '1', 'Start!'];
            let stepIndex = 0;
            showCountInOverlay(steps[stepIndex]);
            setTimerControlsVisible(false);

            countdownTimer = setInterval(() => {
                stepIndex++;
                if (stepIndex < steps.length) {
                    showCountInOverlay(steps[stepIndex]);
                    return;
                }
                clearInterval(countdownTimer);
                countdownTimer = null;
                hideCountInOverlay();
                startFiring();
            }, 1000);
        }

        function startFiringRangeFromControl() {
            if (state.hasStarted) return;
            if (countdownTimer) return;
            if (state.isPlaying) return;
            hideEndOverlay();
            beginCountdown();
        }

        function resetFiringRange() {
            if (gameTimer) { clearInterval(gameTimer); gameTimer = null; }
            if (countdownTimer) { clearInterval(countdownTimer); countdownTimer = null; }
            hideCountInOverlay();
            hideEndOverlay();

            const wStats = weaponStats[state.selectedWeapon];
            state.score = 0;
            state.timeLeft = state.selectedTime;
            state.currentAmmo = wStats.magSize;
            state.reserveAmmo = wStats.totalAmmo;
            state.isReloading = false;
            state.totalShots = 0;
            state.hits = 0;
            state.bullseyes = 0;
            state.isPlaying = false;
            state.hasStarted = false;
            targetContainer.innerHTML = '';
            activeTarget = null;
            gameArea.classList.remove('cursor-hidden');
            crosshair.style.display = 'none';
            updateHUD();
            setTimerControlsVisible(true);
            updateControlButtons();
        }

        function setTimerControlsVisible(visible) {
            if (!timerControls) return;
            if (visible) {
                timerControls.style.opacity = '1';
                timerControls.style.transform = 'scale(1)';
                timerControls.style.pointerEvents = 'auto';
            } else {
                timerControls.style.opacity = '0';
                timerControls.style.transform = 'scale(0.85)';
                timerControls.style.pointerEvents = 'none';
            }
        }

        function updateControlButtons() {
            if (!btnStart || !btnReset) return;
            btnStart.style.display = 'inline-flex';
            btnReset.style.display = 'inline-flex';
        }

        function hideEndOverlay() {
            endOverlay.style.opacity = '0';
            endOverlay.style.visibility = 'hidden';
            const inner = endOverlay.querySelector('div:first-child');
            if (inner) inner.style.transform = 'scale(0.85) translateY(20px)';
        }

        function endGame(reason) {
            state.isPlaying = false;
            clearInterval(gameTimer);
            gameTimer = null;
            targetContainer.innerHTML = '';
            activeTarget = null;
            gameArea.classList.remove('cursor-hidden');
            crosshair.style.display = 'none';
            setTimerControlsVisible(true);
            updateControlButtons();

            document.getElementById('res-reason').innerText = reason || 'Simulation ended';
            document.getElementById('res-score').innerText = state.score;
            document.getElementById('res-accuracy').innerText = state.totalShots > 0 ? Math.round((state.hits / state.totalShots) * 100) + '%' : '0%';
            document.getElementById('res-bullseyes').innerText = state.bullseyes;

            if (configStudentId) {
                var body = {
                    student_id: configStudentId,
                    score: state.score,
                    max_score: state.totalShots * 20 || 100,
                    accuracy: state.totalShots > 0 ? Math.round((state.hits / state.totalShots) * 100) : 0,
                    bullseyes: state.bullseyes,
                    total_shots: state.totalShots,
                    hits: state.hits,
                    weapon: configWeapon,
                    time_limit: configTime,
                    target_mode: configMode
                };
                fetch('{{ route('instructor.firing-range.save-score') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(body)
                }).catch(function(err) { console.error('Score save failed', err); });
            }

            setTimeout(() => {
                endOverlay.style.opacity = '1';
                endOverlay.style.visibility = 'visible';
                endOverlay.querySelector('div:first-child').style.transform = 'scale(1) translateY(0)';
            }, 500);
        }

        function restartGame() {
            if (gameTimer) { clearInterval(gameTimer); gameTimer = null; }
            if (countdownTimer) { clearInterval(countdownTimer); countdownTimer = null; }
            hideCountInOverlay();
            hideEndOverlay();

            const wStats = weaponStats[state.selectedWeapon];
            state.score = 0;
            state.timeLeft = state.selectedTime;
            state.currentAmmo = wStats.magSize;
            state.reserveAmmo = wStats.totalAmmo;
            state.isReloading = false;
            state.totalShots = 0;
            state.hits = 0;
            state.bullseyes = 0;
            state.isPlaying = false;
            state.hasStarted = false;
            targetContainer.innerHTML = '';
            activeTarget = null;
            gameArea.classList.remove('cursor-hidden');
            crosshair.style.display = 'none';
            updateHUD();
            beginCountdown();
        }

        function spawnTarget() {
            if (!state.isPlaying) return;
            targetContainer.innerHTML = ''; activeTarget = null;

            const targetEl = document.createElement('div');
            targetEl.classList.add('target-container');
            targetEl.innerHTML = `
                <div class="target" data-points="2">
                    <div class="target-board"></div>
                    <div class="target-stand left"></div>
                    <div class="target-stand right"></div>
                    <div class="target-center">
                        <div class="target-ring outer"></div>
                        <div class="target-ring middle"></div>
                        <div class="target-ring inner"></div>
                        <div class="target-head-zone"></div>
                        <div class="target-silhouette"></div>
                        <div class="target-bullseye"></div>
                    </div>
                </div>
            `;

            const bounds = gameArea.getBoundingClientRect();
            const targetWidth = 150;
            const targetHeight = 210;

            if (state.targetMode === 'steady') {
                const x = (bounds.width / 2) - (targetWidth / 2);
                const y = (bounds.height / 2) - (targetHeight / 2);
                targetEl.style.left = x + 'px'; targetEl.style.top = y + 'px';
                targetEl.timeoutId = setTimeout(() => removeTarget(targetEl, false), 4000);
            } else if (state.targetMode === 'sideways') {
                const y = (bounds.height / 2) - (targetHeight / 2);
                const startX = Math.random() > 0.5 ? -targetWidth : bounds.width;
                targetEl.style.left = startX + 'px'; targetEl.style.top = y + 'px';
                const dirX = startX < 0 ? 1 : -1;
                targetEl.dataset.vx = (3 + Math.random() * 4) * dirX;
                targetEl.dataset.vy = 0;
            } else if (state.targetMode === 'around') {
                const x = Math.random() * (bounds.width - targetWidth - 200) + 100;
                const y = Math.random() * (bounds.height - targetHeight - 200) + 80;
                targetEl.style.left = x + 'px'; targetEl.style.top = y + 'px';
                targetEl.dataset.vx = (2.5 + Math.random() * 3) * (Math.random() > 0.5 ? 1 : -1);
                targetEl.dataset.vy = (1.5 + Math.random() * 2) * (Math.random() > 0.5 ? 1 : -1);
            }

            targetEl.style.transform = 'scale(0)';
            targetContainer.appendChild(targetEl);
            setTimeout(() => { targetEl.style.transform = 'scale(1)'; }, 50);
            activeTarget = targetEl;
        }

        function moveTargets(currentTime) {
            if (!state.isPlaying) return;
            const deltaTime = (currentTime - lastFrameTime) / 16.667;
            lastFrameTime = currentTime;

            if (activeTarget && state.targetMode !== 'steady') {
                const bounds = gameArea.getBoundingClientRect();
                const targetWidth = 150;
                const targetHeight = 210;
                const bufferBottom = 120;

                let vx = parseFloat(activeTarget.dataset.vx);
                let vy = parseFloat(activeTarget.dataset.vy);
                let currentLeft = parseFloat(activeTarget.style.left);
                let currentTop = parseFloat(activeTarget.style.top);

                currentLeft += vx * deltaTime;
                currentTop += vy * deltaTime;

                if (state.targetMode === 'sideways') {
                    currentTop = (bounds.height / 2) - (targetHeight / 2);
                    if (currentLeft <= 0) { currentLeft = 0; activeTarget.dataset.vx = Math.abs(vx); }
                    else if (currentLeft >= bounds.width - targetWidth) { currentLeft = bounds.width - targetWidth; activeTarget.dataset.vx = -Math.abs(vx); }
                } else if (state.targetMode === 'around') {
                    if (currentLeft <= 0) { currentLeft = 0; activeTarget.dataset.vx = Math.abs(vx); }
                    else if (currentLeft >= bounds.width - targetWidth) { currentLeft = bounds.width - targetWidth; activeTarget.dataset.vx = -Math.abs(vx); }
                    if (currentTop <= 0) { currentTop = 0; activeTarget.dataset.vy = Math.abs(vy); }
                    else if (currentTop >= bounds.height - targetHeight - bufferBottom) { currentTop = bounds.height - targetHeight - bufferBottom; activeTarget.dataset.vy = -Math.abs(vy); }
                }

                activeTarget.style.left = currentLeft + 'px';
                activeTarget.style.top = currentTop + 'px';
            }
            requestAnimationFrame(moveTargets);
        }

        function removeTarget(element, wasHit) {
            if (!element.parentElement) return;
            if (element.timeoutId) clearTimeout(element.timeoutId);
            activeTarget = null;
            if (wasHit) {
                element.classList.add('hit');
                setTimeout(() => { element.remove(); spawnTarget(); }, 400);
            } else {
                element.style.transform = 'scale(0)';
                setTimeout(() => { element.remove(); spawnTarget(); }, 300);
            }
        }

        function handleShot(e) {
            if (!state.isPlaying || state.isReloading) return;
            if (audioCtx.state === 'suspended') audioCtx.resume();

            if (state.currentAmmo <= 0) { playEmptyClickSound(); initiateReload(); return; }

            state.currentAmmo--; state.totalShots++; updateHUD();
            playGunshot();

            muzzleFlash.classList.add('active');
            setTimeout(() => muzzleFlash.classList.remove('active'), 80);

            gameArea.style.transform = 'translateY(' + weaponStats[state.selectedWeapon].recoil + 'px)';
            setTimeout(() => gameArea.style.transform = 'translateY(0)', 100);

            const targetCheck = e.target.closest('.target');
            if (targetCheck) {
                state.hits++;
                let points = 2;
                if (e.target.classList.contains('target-bullseye')) { points = 20; state.bullseyes++; }
                else if (e.target.classList.contains('target-ring') && e.target.classList.contains('inner')) points = 10;
                else if (e.target.classList.contains('target-ring') && e.target.classList.contains('middle')) points = 5;

                state.score += points; playHitSound();
                hitMarker.classList.remove('show'); void hitMarker.offsetWidth; hitMarker.classList.add('show');
                removeTarget(targetCheck.parentElement, true);
            } else { createBulletHole(e.clientX, e.clientY); }

            updateHUD();
            if (state.currentAmmo <= 0 && state.reserveAmmo <= 0) { setTimeout(() => endGame("Out of ammunition!"), 1000); }
            else if (state.currentAmmo <= 0) { initiateReload(); }
        }

        function createBulletHole(x, y) {
            const hole = document.createElement('div'); hole.classList.add('bullet-hole');
            hole.style.left = x + 'px'; hole.style.top = y + 'px'; document.body.appendChild(hole);
            setTimeout(() => { hole.style.transition = 'opacity 1s'; hole.style.opacity = '0'; setTimeout(() => hole.remove(), 1000); }, 3000);
        }

        function initiateReload() {
            if (state.isReloading || state.currentAmmo === weaponStats[state.selectedWeapon].magSize || state.reserveAmmo <= 0 || !state.isPlaying) return;
            state.isReloading = true; btnReload.disabled = true; reloadUI.style.display = 'block';
            reloadFill.style.transition = 'width ' + weaponStats[state.selectedWeapon].reloadTime + 'ms linear';
            setTimeout(() => reloadFill.style.width = '100%', 50);

            setTimeout(() => {
                const wStats = weaponStats[state.selectedWeapon]; const needed = wStats.magSize - state.currentAmmo; const available = Math.min(needed, state.reserveAmmo);
                state.currentAmmo += available; state.reserveAmmo -= available;
                state.isReloading = false; btnReload.disabled = false; reloadFill.style.width = '0%';
                setTimeout(() => reloadUI.style.display = 'none', 300); updateHUD();
            }, weaponStats[state.selectedWeapon].reloadTime);
        }

        function updateHUD() {
            hudScore.innerText = state.score; hudTimer.innerText = state.timeLeft; hudAmmo.innerText = state.currentAmmo; hudReserve.innerText = '/ ' + state.reserveAmmo;
            const accuracy = state.totalShots > 0 ? Math.round((state.hits / state.totalShots) * 100) : 0; hudAccuracy.innerText = accuracy + '%';

            if (state.timeLeft <= 10) hudTimer.style.color = '#ef4444'; else hudTimer.style.color = '#fff';
            if (state.currentAmmo <= 3 && state.currentAmmo > 0) hudAmmo.style.color = '#f59e0b'; else if (state.currentAmmo === 0) hudAmmo.style.color = '#ef4444'; else hudAmmo.style.color = '#fff';
            btnReload.disabled = state.isReloading || state.currentAmmo === weaponStats[state.selectedWeapon].magSize || state.reserveAmmo <= 0;
        }

        document.addEventListener('mousemove', (e) => { crosshair.style.left = e.clientX + 'px'; crosshair.style.top = e.clientY + 'px'; });
        gameArea.addEventListener('mouseenter', () => { if (state.isPlaying) crosshair.style.display = 'block'; });
        gameArea.addEventListener('mouseleave', () => { crosshair.style.display = 'none'; });
        gameArea.addEventListener('mousedown', handleShot);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'r' || e.key === 'R') initiateReload();
        });
        gameArea.addEventListener('contextmenu', e => e.preventDefault());

        hudWeapon.innerText = weaponStats[configWeapon]?.name || '9mm Pistol';
        const wSt = weaponStats[configWeapon];
        if (wSt) {
            muzzleFlash.style.background = 'radial-gradient(ellipse at bottom, ' + wSt.flashColor + ' 0%, transparent 70%)';
            muzzleFlash.style.height = wSt.flashSize;
        }

        if (timerControls) {
            timerControls.style.opacity = '1';
            timerControls.style.transform = 'scale(1)';
        }
        updateHUD();
        updateControlButtons();
    </script>

</body>
</html>