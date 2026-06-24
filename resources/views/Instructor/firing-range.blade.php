<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IOT-Based Marksmanship - Firing Range</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/Marksmanship innovatech.png') }}">
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
                <div id="timer-controls" style="display:flex;align-items:center;gap:6px;background:rgba(3,3,7,0.8);backdrop-filter:blur(4px);border:1px solid rgba(124,58,237,0.5);border-radius:999px;padding:6px;pointer-events:auto;transition:opacity 0.25s ease, transform 0.25s ease"></div>
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
        <!-- ==================== START SIMULATION MODAL ==================== -->
        <div id="start-modal-overlay" class="start-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="start-modal-title">
            <div class="start-modal">
                <svg class="start-bg-shape" style="top:-20px;right:-20px;width:120px;height:120px" viewBox="0 0 120 120"><circle cx="60" cy="60" r="60" fill="#7C3AED"></circle></svg>
                <svg class="start-bg-shape" style="bottom:-30px;left:-30px;width:150px;height:150px" viewBox="0 0 150 150"><circle cx="75" cy="75" r="75" fill="#7C3AED"></circle></svg>
                <div class="pt-8 pb-2">
                    <div class="start-icon-wrap">
                        <div class="start-icon-ring"><i class="fas fa-crosshairs"></i></div>
                        <span class="start-dot"></span><span class="start-dot"></span><span class="start-dot"></span><span class="start-dot"></span>
                    </div>
                </div>
                <div class="px-8 pt-4 pb-3 text-center">
                    <h3 id="start-modal-title" class="start-title">Firing Range Ready</h3>
                    <p class="start-text mt-2">You are about to begin the marksmanship simulation. Make sure you are focused and ready to fire.</p>
                </div>
                <div class="mx-8 mb-5 p-3 bg-violet-50/70 rounded-xl border border-violet-100/80">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-violet-200/60 flex items-center justify-center"><i class="fas fa-gun text-violet-600 text-xs"></i></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-violet-900 truncate" id="start-modal-weapon">9mm Pistol</p>
                            <p class="text-[10px] text-violet-500" id="start-modal-time">60s &middot; Steady</p>
                        </div>
                        <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse flex-shrink-0"></div>
                    </div>
                </div>
                <div class="px-8 pb-8 flex items-center gap-3">
                    <button id="start-modal-cancel" class="start-btn start-btn-cancel flex-1" type="button" onclick="cancelStartModal()"><i class="fas fa-times text-xs"></i> Cancel</button>
                    <button id="start-modal-reset" class="start-btn start-btn-reset flex-1" type="button" onclick="confirmReset()"><i class="fas fa-rotate-left text-xs"></i> Reset</button>
                    <button id="start-modal-confirm" class="start-btn start-btn-start flex-1" type="button" onclick="confirmStart()"><i class="fas fa-play text-xs"></i> Start Simulation</button>
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
        .target{width:160px;height:160px;position:relative;cursor:none;filter:drop-shadow(0 12px 24px rgba(0,0,0,0.4));transform-origin:center center}
        .target-board{position:absolute;inset:0;border-radius:50%;background:#1a1a1a;border:3px solid #000;overflow:hidden;box-shadow:inset 0 0 0 1px rgba(255,255,255,0.05), inset 0 -4px 20px rgba(0,0,0,0.6), 0 8px 24px rgba(0,0,0,0.4)}
        .target-board::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 40% 40%, rgba(255,255,255,0.04) 0%, transparent 50%);opacity:0.5}
        .target-board::after{content:'';position:absolute;inset:8px;border-radius:50%;border:1px solid rgba(255,255,255,0.03);box-shadow:inset 0 2px 8px rgba(255,255,255,0.01)}
        .target-rings{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:100%;height:100%;pointer-events:none}
        .target-ring{position:absolute;left:50%;top:50%;border-radius:50%;transform:translate(-50%,-50%)}
        .target-ring.delta{width:140px;height:140px;background:#000;border:2px solid #111}
        .target-ring.charlie{width:106px;height:106px;background:#000;border:2px solid #111}
        .target-ring.bravo{width:72px;height:72px;background:#fff;border:2px solid #ddd}
        .target-ring.alpha{width:38px;height:38px;background:#dc2626;border:2px solid #b91c1c}
        .target-bullseye{position:absolute;left:50%;top:50%;width:16px;height:16px;transform:translate(-50%,-50%);border-radius:50%;background:radial-gradient(circle at 35% 35%, #fff1 0%, #dc2626 40%, #7f1d1d 100%);box-shadow:0 0 16px rgba(220,38,38,0.9), inset 0 -2px 4px rgba(0,0,0,0.4), inset 0 2px 4px rgba(255,255,255,0.2);border:2px solid #b91c1c}
        .target-bullseye::before{content:'';position:absolute;inset:-3px;border-radius:50%;background:conic-gradient(from 0deg, transparent, rgba(220,38,38,0.3), transparent);animation:bullseyePulse 2s ease-in-out infinite}
        @keyframes bullseyePulse{0%,100%{opacity:0.3;transform:scale(1)}50%{opacity:0.6;transform:scale(1.15)}}
        .target-zone-label{position:absolute;left:50%;transform:translateX(-50%);font-family:'Space Grotesk',sans-serif;font-size:7px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#555;text-shadow:0 1px 2px rgba(0,0,0,0.9);pointer-events:none;white-space:nowrap;text-align:center}
        .target-zone-label.delta{top:74px;color:#444}
        .target-zone-label.charlie{top:56px;color:#444}
        .target-zone-label.bravo{top:39px;color:#888}
        .target-zone-label.alpha{top:24px;color:#b91c1c}
        .target-crosshair-lines{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:150px;height:150px;pointer-events:none;opacity:0.15}
        .target-crosshair-lines::before,.target-crosshair-lines::after{content:'';position:absolute;background:#fff;opacity:0.3}
        .target-crosshair-lines::before{width:1px;height:100%;left:50%;top:0;transform:translateX(-50%)}
        .target-crosshair-lines::after{width:100%;height:1px;left:0;top:50%;transform:translateY(-50%)}
        .target-indicator{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:100%;height:100%;pointer-events:none}
        .target-indicator::before{content:'';position:absolute;left:50%;top:50%;width:154px;height:154px;border:1px dashed rgba(255,255,255,0.06);border-radius:50%;transform:translate(-50%,-50%)}
        .bullet-hole{position:fixed;width:8px;height:8px;background:radial-gradient(circle, #111 0%, #333 60%, transparent 100%);border-radius:50%;pointer-events:none;z-index:5;box-shadow:0 0 2px rgba(0,0,0,0.8)}
        #game-area.cursor-hidden{cursor:none}
        #hit-marker.show{animation:hitMarkerAnim 0.2s ease-out forwards}
        #muzzle-flash.active{opacity:1}
        .reload-fill{transition:width 1.5s linear}
        #timer-controls button:not(:disabled):hover{transform:translateY(-1px) scale(1.06)}
        #timer-controls button:not(:disabled):active{transform:translateY(0) scale(0.96)}

        .start-modal-overlay{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(3,3,7,0.6);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);opacity:0;visibility:hidden;transition:opacity .3s ease, visibility .3s ease}
        .start-modal-overlay.active{opacity:1;visibility:visible}
        .start-modal-overlay.closing{opacity:0;visibility:visible;transition:opacity .25s ease, visibility .25s ease}
        .start-modal{background:#fff;border-radius:20px;width:92%;max-width:420px;padding:0;overflow:hidden;box-shadow:0 25px 60px -12px rgba(30,5,82,0.35), 0 0 0 1px rgba(124,58,237,0.08);transform:scale(0.85) translateY(20px);opacity:0;transition:transform .35s cubic-bezier(0.34,1.56,0.64,1), opacity .3s ease}
        .start-modal-overlay.active .start-modal{transform:scale(1) translateY(0);opacity:1}
        .start-modal-overlay.closing .start-modal{transform:scale(0.9) translateY(10px);opacity:0;transition:transform .25s ease, opacity .25s ease}
        .start-icon-wrap{position:relative;width:80px;height:80px;margin:0 auto}
        .start-icon-ring{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#EDE9FE,#DDD6FE);display:flex;align-items:center;justify-content:center;position:relative}
        .start-icon-ring::before{content:'';position:absolute;inset:-4px;border-radius:50%;background:conic-gradient(from 0deg, #7C3AED, #A78BFA, #C4B5FD, #7C3AED);z-index:-1;animation:startRingRotate 4s linear infinite}
        @keyframes startRingRotate{to{transform:rotate(360deg)}}
        .start-icon-ring i{font-size:32px;background:linear-gradient(135deg,#5B21B6,#7C3AED);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        .start-dot{position:absolute;width:5px;height:5px;border-radius:50%;background:#A78BFA;animation:startDotPulse 2s ease-in-out infinite}
        .start-dot:nth-child(2){top:-8px;left:50%;transform:translateX(-50%);animation-delay:0s}
        .start-dot:nth-child(3){top:50%;right:-8px;transform:translateY(-50%);animation-delay:0.5s}
        .start-dot:nth-child(4){bottom:-8px;left:50%;transform:translateX(-50%);animation-delay:1s}
        .start-dot:nth-child(5){top:50%;left:-8px;transform:translateY(-50%);animation-delay:1.5s}
        @keyframes startDotPulse{0%,100%{opacity:.3;transform:scale(1)}50%{opacity:1;transform:scale(1.4)}}
        .start-title{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:22px;color:#0f172a;margin:0}
        .start-text{font-size:14px;color:#64748b;line-height:1.6;margin:0}
        .start-btn{border:none;cursor:pointer;font-family:'Inter',sans-serif;font-weight:700;font-size:13px;padding:12px 18px;border-radius:12px;transition:all .2s ease;display:inline-flex;align-items:center;justify-content:center;gap:6px;letter-spacing:0.02em}
        .start-btn:active{transform:scale(0.96)}
        .start-btn-cancel{background:#f1f5f9;color:#475569}
        .start-btn-cancel:hover{background:#e2e8f0;color:#334155}
        .start-btn-reset{background:#f1f5f9;color:#475569}
        .start-btn-reset:hover{background:#e2e8f0;color:#334155}
        .start-btn-start{background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;box-shadow:0 4px 14px -3px rgba(34,197,94,0.4)}
        .start-btn-start:hover{background:linear-gradient(135deg,#15803d,#16a34a);box-shadow:0 6px 20px -3px rgba(34,197,94,0.5);transform:translateY(-1px)}
        .start-btn-start:active{transform:translateY(0) scale(0.96)}
        .start-bg-shape{position:absolute;pointer-events:none;opacity:0.04}
        @media(prefers-reduced-motion:reduce){.start-modal,.start-modal-overlay{transition:none !important}.start-icon-ring::before,.start-dot{animation:none !important}}
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

        let startModalOpen = false;

        function openStartModal() {
            if (state.hasStarted) return;
            if (startModalOpen) return;
            const overlay = document.getElementById('start-modal-overlay');
            const weaponEl = document.getElementById('start-modal-weapon');
            const timeEl = document.getElementById('start-modal-time');
            if (weaponEl) weaponEl.innerText = weaponStats[state.selectedWeapon]?.name || '9mm Pistol';
            if (timeEl) timeEl.innerText = state.selectedTime + 's &middot; ' + (state.targetMode.charAt(0).toUpperCase() + state.targetMode.slice(1));
            overlay.classList.remove('closing');
            overlay.classList.add('active');
            startModalOpen = true;
        }

        function closeStartModal() {
            if (!startModalOpen) return;
            const overlay = document.getElementById('start-modal-overlay');
            overlay.classList.add('closing');
            overlay.classList.remove('active');
            startModalOpen = false;
            setTimeout(() => overlay.classList.remove('closing'), 280);
        }

        function confirmStart() {
            closeStartModal();
            startFiringRangeFromControl();
        }

        function confirmReset() {
            resetFiringRange();
            openStartModal();
        }

        function cancelStartModal() {
            window.location.href = @json(route('instructor.manage-marksmanship'));
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
                    <div class="target-indicator"></div>
                    <div class="target-crosshair-lines"></div>
                    <div class="target-rings">
                        <div class="target-ring delta" data-zone="delta" data-points="1"></div>
                        <div class="target-zone-label delta">Delta</div>
                        <div class="target-ring charlie" data-zone="charlie" data-points="3"></div>
                        <div class="target-zone-label charlie">Charlie</div>
                        <div class="target-ring bravo" data-zone="bravo" data-points="5"></div>
                        <div class="target-zone-label bravo">Bravo</div>
                        <div class="target-ring alpha" data-zone="alpha" data-points="10"></div>
                        <div class="target-zone-label alpha">Alpha</div>
                        <div class="target-bullseye" data-zone="bullseye" data-points="20"></div>
                    </div>
                </div>
            `;

            const bounds = gameArea.getBoundingClientRect();
            const targetWidth = 160;
            const targetHeight = 160;

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
                const targetWidth = 160;
                const targetHeight = 160;
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

            const targetCheck = e.target.closest('.target');
            if (targetCheck) {
                state.hits++;
                let points = 2;
                let zoneName = 'body';
                const hitElement = e.target.closest('[data-zone]');
                if (hitElement) {
                    points = parseInt(hitElement.dataset.points || '2', 10);
                    zoneName = hitElement.dataset.zone || 'body';
                    if (zoneName === 'bullseye') state.bullseyes++;
                } else if (e.target.classList.contains('target-bullseye')) {
                    points = 20; state.bullseyes++; zoneName = 'bullseye';
                } else if (e.target.classList.contains('target-ring')) {
                    if (e.target.classList.contains('alpha')) { points = 10; zoneName = 'alpha'; }
                    else if (e.target.classList.contains('bravo')) { points = 5; zoneName = 'bravo'; }
                    else if (e.target.classList.contains('charlie')) { points = 3; zoneName = 'charlie'; }
                    else if (e.target.classList.contains('delta')) { points = 1; zoneName = 'delta'; }
                } else if (e.target.classList.contains('target-head-zone')) {
                    points = 15; zoneName = 'head';
                }

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
            if (e.key === 'Escape' && startModalOpen) cancelStartModal();
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
        openStartModal();
    </script>

</body>
</html>