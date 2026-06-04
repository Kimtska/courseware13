<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VirtualArm - Firing Range</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{font-family:'Inter',sans-serif;margin:0;background:#f8fafc;min-height:100vh;overflow:hidden}
        body::before{content:'';position:fixed;inset:0;background:url('{{ asset('images/firing-range/firing-rangebg.jpg') }}') center center/cover no-repeat;filter:saturate(1.08) contrast(1.05);z-index:-2}
        body::after{content:'';position:fixed;inset:0;background:linear-gradient(180deg, rgba(10,10,15,0.08) 0%, rgba(10,10,15,0.02) 28%, rgba(10,10,15,0.12) 100%);z-index:-1;pointer-events:none}
        .nav-link{position:relative;padding:8px 16px;color:rgba(255,255,255,0.65);transition:all .2s;font-size:13px;font-weight:500;border-radius:6px;white-space:nowrap}
        .nav-link:hover{color:#fff;background:rgba(255,255,255,0.10000000)}
        .nav-link.active{color:#fff;background:rgba(255,255,255,0.15);font-weight:600}
        .nav-link.active::after{content:'';position:absolute;bottom:-14px;left:50%;transform:translateX(-50%);width:20px;height:3px;background:#A78BFA;border-radius:3px}

        /* Header Game Mode Transition */
        #main-header{transition:all 0.4s ease}
        #main-header.game-active{background:rgba(3,3,7,0.7);backdrop-filter:blur(4px);box-shadow:none}
        #main-header.game-active .hide-play{display:none}
        #main-header .show-play{display:none}
        #main-header.game-active .show-play{display:flex}

        /* Game Custom Cursor */
        #game-area{cursor:auto;background:transparent}
        #game-area.cursor-hidden{cursor:none}
        #crosshair{position:fixed;pointer-events:none;z-index:100;transform:translate(-50%,-50%);display:none}
        #crosshair .ch-line{position:absolute;background:#fff;box-shadow:0 0 4px rgba(255,255,255,0.8)}
        #crosshair .ch-h{width:16px;height:2px;top:50%;left:50%;transform:translate(-50%,-50%)}
        #crosshair .ch-v{width:2px;height:16px;top:50%;left:50%;transform:translate(-50%,-50%)}
        #crosshair .ch-dot{position:absolute;width:4px;height:4px;background:#ef4444;border-radius:50%;top:50%;left:50%;transform:translate(-50%,-50%);box-shadow:0 0 6px #ef4444}

        /* Target Styles */
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
        
        #muzzle-flash{position:fixed;bottom:0;left:50%;transform:translateX(-50%);width:100%;height:40%;pointer-events:none;z-index:90;opacity:0;transition:opacity 0.05s}
        #muzzle-flash.active{opacity:1}
        
        #hit-marker{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none;z-index:100;opacity:0}
        #hit-marker .hm-line{position:absolute;background:#fff;box-shadow:0 0 4px #fff}
        #hit-marker .hm-1{width:20px;height:2px;transform:rotate(45deg);top:-1px;left:-10px}
        #hit-marker .hm-2{width:20px;height:2px;transform:rotate(-45deg);top:-1px;left:-10px}
        #hit-marker.show{animation:hitMarkerAnim 0.2s ease-out forwards}
        @keyframes hitMarkerAnim{0%{opacity:1;transform:translate(-50%,-50%) scale(0.5)}100%{opacity:0;transform:translate(-50%,-50%) scale(1.5)}}

        @keyframes targetHit{0%{transform:scale(1);opacity:1}50%{transform:scale(1.2);opacity:0.5}100%{transform:scale(0);opacity:0}}

        .reload-bar{height:4px;background:#1e1e2e;border-radius:2px;overflow:hidden;width:100%}
        .reload-fill{height:100%;background:#A78BFA;width:0%;transition:width 1.5s linear}

        /* Selection Cards */
        .sel-card{border:2px solid #e5e7eb;background:#ffffff;border-radius:12px;padding:16px;cursor:pointer;transition:all 0.2s;box-shadow:0 1px 2px rgba(15,23,42,0.04)}
        .sel-card:hover{border-color:#c4b5fd;background:#f8f5ff}
        .sel-card.active{border-color:#7C3AED;background:#f3e8ff;box-shadow:0 0 0 3px rgba(124,58,237,0.12)}
        .menu-shell{background:#fff;border:1px solid #e9d5ff;border-radius:28px;box-shadow:0 25px 60px -12px rgba(30,5,82,0.18);overflow:hidden}
        .menu-hero{background:linear-gradient(135deg,#1E0552,#5B21B6);color:#fff}
        .timer-widget{position:fixed;right:16px;top:80px;z-index:55;width:220px;background:rgba(255,255,255,0.96);backdrop-filter:blur(8px);border:1px solid #e9d5ff;border-radius:20px;box-shadow:0 18px 40px -16px rgba(30,5,82,0.2)}
        .count-in-overlay{position:fixed;inset:0;z-index:60;display:flex;align-items:center;justify-content:center;pointer-events:none;opacity:0;visibility:hidden;transition:opacity .2s ease, visibility .2s ease}
        .count-in-overlay.active{opacity:1;visibility:visible}
        .count-in-card{min-width:220px;padding:24px 28px;border-radius:28px;background:rgba(255,255,255,0.92);backdrop-filter:blur(10px);border:1px solid #e9d5ff;box-shadow:0 20px 50px -16px rgba(30,5,82,0.24);text-align:center}
        .count-in-number{font-family:'Space Grotesk',sans-serif;font-size:72px;line-height:1;font-weight:800;color:#5B21B6;letter-spacing:-0.04em}
        .count-in-label{margin-top:8px;font-size:11px;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;color:#7c3aed}
        .light-selection .text-white{color:#111827 !important}
        .light-selection .text-violet-300{color:#6b7280 !important}
        .light-selection .text-violet-400{color:#7c3aed !important}
        .light-selection .text-violet-500{color:#6d28d9 !important}
        .light-selection .bg-dark-950\/50{background:#f8fafc !important}
        .light-selection .bg-dark-950\/80{background:#ffffff !important}
        .light-selection .bg-dark-950\/95{background:rgba(255,255,255,0.97) !important}
        .light-selection .border-violet-900\/50{border-color:#e5e7eb !important}
        .light-selection .swal-title{color:#111827}
        .light-selection .start-sim-btn{color:#fff !important}

        /* Sweet Alert CSS */
        .swal-overlay{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.78);backdrop-filter:blur(8px);opacity:0;visibility:hidden;transition:opacity .3s ease, visibility .3s ease}
        .swal-overlay.active{opacity:1;visibility:visible}
        .swal-modal{background:#fff;border-radius:20px;width:92%;max-width:420px;padding:0;overflow:hidden;box-shadow:0 25px 60px -12px rgba(30,5,82,0.35);transform:scale(0.85) translateY(20px);opacity:0;transition:transform .35s cubic-bezier(0.34,1.56,0.64,1), opacity .3s ease}
        .swal-overlay.active .swal-modal{transform:scale(1) translateY(0);opacity:1}
        .swal-title{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:22px;color:#0f172a;margin:0}
        .swal-btn{border:none;cursor:pointer;font-family:'Inter',sans-serif;font-weight:700;font-size:13px;padding:12px 24px;border-radius:12px;transition:all .2s ease;display:inline-flex;align-items:center;justify-content:center;gap:6px;letter-spacing:0.02em}
        .swal-btn:active{transform:scale(0.96)}
        .swal-btn-primary{background:linear-gradient(135deg,#5B21B6,#7C3AED);color:#fff;box-shadow:0 4px 14px -3px rgba(91,33,182,0.4)}
        .swal-btn-primary:hover{background:linear-gradient(135deg,#4c1d95,#6D28D9);transform:translateY(-1px)}

        .bg-grid{background-image:linear-gradient(rgba(124,58,237,0.05) 1px, transparent 1px),linear-gradient(90deg, rgba(124,58,237,0.05) 1px, transparent 1px);background-size:40px 40px}

        /* Mobile Menu */
        .mobile-menu{transform:translateY(-100%);opacity:0;transition:all .3s ease;pointer-events:none}
        .mobile-menu.open{transform:translateY(0);opacity:1;pointer-events:auto}
    </style>
</head>
<body class="bg-gray-50">

    @php $embedded = request()->boolean('embedded'); @endphp

    <!-- ==================== TOP NAVIGATION ==================== -->
    @unless ($embedded)
    <header id="main-header" data-turbo-permanent class="bg-violet-950 text-white sticky top-0 z-50 shadow-lg shadow-violet-950/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Hidden during game -->
                <div class="flex items-center gap-3 flex-shrink-0 hide-play">
                    <img src="{{ asset('images/assets/logo.png') }}" alt="SPC" class="h-9 w-auto">
                    <div class="hidden sm:block"><span class="font-display font-bold text-sm">VirtualArm</span><span class="block text-[8px] text-violet-300 uppercase tracking-widest leading-none">Student Portal</span></div>
                </div>
                <div class="hide-play">
                    @include('Students.partials.nav-links', ['type' => 'desktop', 'activeNav' => 'firing-range'])
                </div>

                <!-- Exit button (Visible only during game) -->
                <button onclick="exitGame()" class="show-play items-center gap-2 px-3 py-1.5 bg-violet-900/50 hover:bg-violet-800/50 rounded-lg text-violet-200 hover:text-white transition-colors text-sm font-medium border border-violet-700/30">
                    <i class="fas fa-arrow-left text-xs"></i> Exit Range
                </button>

                <!-- Right: User Menu -->
                <div class="hide-play flex items-center gap-3">
                    <span class="hidden lg:inline-flex items-center px-3 py-1 bg-violet-800/50 text-violet-200 text-[10px] font-bold rounded-full border border-violet-700/50">Student</span>
                    <div class="hidden sm:flex items-center gap-2 pl-3 border-l border-violet-800/50">
                        <div class="w-8 h-8 rounded-full bg-violet-700 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($firstName ?: ($name ?? 'S'), 0, 1)) }}{{ strtoupper(substr($lastName ?: ($name ?? 'T'), 0, 1)) }}</div>
                        <span class="text-sm font-medium">{{ $name ?? 'Student' }}</span>
                    </div>
                    <button onclick="showLogoutAlert()" class="p-2 rounded-lg hover:bg-violet-800/50 transition-colors text-violet-300 hover:text-white" title="Logout" aria-label="Logout">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                    <!-- Mobile Hamburger -->
                    <button id="mobile-toggle" class="md:hidden p-2 rounded-lg hover:bg-violet-800/50 transition-colors">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        @include('Students.partials.nav-links', ['type' => 'mobile', 'activeNav' => 'firing-range'])
    </header>
    @endunless

    <!-- ==================== GAME AREA ==================== -->
    <main id="game-area" class="relative w-full bg-gray-50 bg-grid" style="height: {{ $embedded ? '100vh' : 'calc(100vh - 56px)' }}">
        
        <div class="absolute inset-0 flex justify-around pointer-events-none z-0">
            <div class="w-px h-full bg-violet-900/20"></div><div class="w-px h-full bg-violet-900/20"></div><div class="w-px h-full bg-violet-900/20"></div>
        </div>

        <div id="target-container" class="absolute inset-0 z-10 overflow-hidden"></div>

        <!-- HUD Overlay -->
        <div class="absolute inset-x-0 top-0 p-4 flex justify-between items-start z-20 pointer-events-none">
            <div class="bg-dark-950/80 backdrop-blur-sm border border-violet-900/50 rounded-xl px-5 py-3">
                <p class="text-[10px] text-violet-300 uppercase tracking-wider font-bold">Score</p>
                <p id="hud-score" class="text-3xl font-display font-bold text-white">0</p>
            </div>
            <div class="bg-dark-950/80 backdrop-blur-sm border border-violet-900/50 rounded-xl px-5 py-3 text-center">
                <p class="text-[10px] text-violet-300 uppercase tracking-wider font-bold">Time</p>
                <p id="hud-timer" class="text-3xl font-display font-bold text-white">60</p>
            </div>
            <div class="bg-dark-950/80 backdrop-blur-sm border border-violet-900/50 rounded-xl px-5 py-3 text-right">
                <p class="text-[10px] text-violet-300 uppercase tracking-wider font-bold">Accuracy</p>
                <p id="hud-accuracy" class="text-3xl font-display font-bold text-white">0%</p>
            </div>
        </div>

        <!-- Bottom HUD -->
        <div class="absolute inset-x-0 bottom-0 p-4 flex flex-col items-center z-20 pointer-events-none">
            <div id="reload-ui" class="w-48 mb-2 hidden">
                <p class="text-[10px] text-violet-300 text-center uppercase font-bold mb-1 animate-pulse">Reloading...</p>
                <div class="reload-bar"><div id="reload-fill" class="reload-fill"></div></div>
            </div>
            <div class="bg-dark-950/80 backdrop-blur-sm border border-violet-900/50 rounded-t-xl px-6 py-3 flex items-end gap-6">
                <div class="text-center">
                    <p class="text-[10px] text-violet-300 uppercase font-bold">Weapon</p>
                    <p id="hud-weapon" class="text-sm font-display font-bold text-white">9mm Pistol</p>
                </div>
                <div class="w-px h-8 bg-violet-800/50"></div>
                <div class="text-center">
                    <p class="text-[10px] text-violet-300 uppercase font-bold">Ammo</p>
                    <div class="flex items-center gap-1.5">
                        <i class="fas fa-crosshairs text-violet-400 text-xs"></i>
                        <span id="hud-ammo" class="text-xl font-display font-bold text-white">15</span>
                        <span id="hud-reserve" class="text-xs text-violet-400 font-medium">/ 45</span>
                    </div>
                </div>
                <div class="w-px h-8 bg-violet-800/50"></div>
                <div class="text-center pointer-events-auto">
                    <button id="btn-reload" onclick="initiateReload()" class="px-4 py-1.5 bg-violet-800 hover:bg-violet-700 text-white text-xs font-bold rounded-lg transition-colors uppercase tracking-wider disabled:opacity-40 disabled:cursor-not-allowed">
                        <i class="fas fa-sync-alt mr-1"></i> Reload [R]
                    </button>
                </div>
            </div>
        </div>

        <div id="crosshair"><div class="ch-line ch-h"></div><div class="ch-line ch-v"></div><div class="ch-dot"></div></div>
        <div id="hit-marker"><div class="hm-line hm-1"></div><div class="hm-line hm-2"></div></div>
        <div id="muzzle-flash"></div>

        <!-- ==================== SELECTION OVERLAY ==================== -->
        <div id="start-overlay" class="absolute inset-0 z-50 bg-white/72 backdrop-blur-md flex items-start justify-center text-center px-4 overflow-y-auto py-6 light-selection">
            <div class="menu-shell w-full max-w-4xl mt-2">
                <div class="menu-hero px-6 sm:px-8 py-6 text-left">
                    <p class="text-[10px] uppercase tracking-[0.28em] text-violet-200 font-bold">Simulation Setup</p>
                    <h1 class="font-display font-bold text-3xl md:text-4xl mt-2">Firing Range Menu</h1>
                    <p class="text-violet-100 text-sm mt-2 max-w-2xl">Choose your time limit, firearm, and target style before starting the simulation.</p>
                </div>

                <div class="p-6 sm:p-8 text-left">
            <div class="w-full max-w-3xl space-y-6 text-left mx-auto">
                <!-- Time Limit -->
                <div>
                    <h3 class="text-xs text-violet-700 uppercase tracking-widest font-bold mb-3 flex items-center gap-2"><i class="fas fa-clock text-violet-500"></i> Time Limit</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        <div class="sel-card active" data-time="30" onclick="selectTime(30, this)"><p class="text-center font-bold text-gray-900">30s</p></div>
                        <div class="sel-card" data-time="60" onclick="selectTime(60, this)"><p class="text-center font-bold text-gray-900">60s</p></div>
                        <div class="sel-card" data-time="90" onclick="selectTime(90, this)"><p class="text-center font-bold text-gray-900">90s</p></div>
                        <div class="sel-card" data-time="120" onclick="selectTime(120, this)"><p class="text-center font-bold text-gray-900">120s</p></div>
                        <div class="sel-card" data-time="custom" onclick="selectCustomRangeTime(this)">
                            <div class="flex flex-col items-center gap-2">
                                <p class="text-center font-bold text-gray-900">Custom</p>
                                <input id="range-custom-time" type="number" min="5" max="999" value="30" class="w-full rounded-lg border border-violet-200 bg-white px-3 py-2 text-center text-sm font-bold text-gray-900 outline-none focus:border-violet-400">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Firearm Selection -->
                <div>
                    <h3 class="text-xs text-violet-700 uppercase tracking-widest font-bold mb-3 flex items-center gap-2"><i class="fas fa-gun text-violet-500"></i> Select Firearm</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="sel-card active" data-weapon="9mm" onclick="selectWeapon('9mm', this)">
                                <div class="flex items-center gap-3 mb-2"><div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-gun"></i></div><h4 class="font-display font-bold text-gray-900">9mm Pistol</h4></div>
                            <div class="grid grid-cols-2 gap-2 text-[10px] text-gray-500">
                                <div class="bg-gray-50 border border-gray-200 p-1.5 rounded"><span class="block text-violet-700 font-bold">Mag</span> 15</div><div class="bg-gray-50 border border-gray-200 p-1.5 rounded"><span class="block text-violet-700 font-bold">Reserve</span> 45</div>
                            </div>
                        </div>
                        <div class="sel-card" data-weapon=".45" onclick="selectWeapon('.45', this)">
                            <div class="flex items-center gap-3 mb-2"><div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-gun"></i></div><h4 class="font-display font-bold text-gray-900">.45 Caliber</h4></div>
                            <div class="grid grid-cols-2 gap-2 text-[10px] text-gray-500">
                                <div class="bg-gray-50 border border-gray-200 p-1.5 rounded"><span class="block text-violet-700 font-bold">Mag</span> 7</div><div class="bg-gray-50 border border-gray-200 p-1.5 rounded"><span class="block text-violet-700 font-bold">Reserve</span> 28</div>
                            </div>
                        </div>
                        <div class="sel-card" data-weapon="38" onclick="selectWeapon('38', this)">
                            <div class="flex items-center gap-3 mb-2"><div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-700"><i class="fas fa-crosshairs"></i></div><h4 class="font-display font-bold text-gray-900">.38 Pistol Revolver</h4></div>
                            <div class="grid grid-cols-2 gap-2 text-[10px] text-gray-500">
                                <div class="bg-gray-50 border border-gray-200 p-1.5 rounded"><span class="block text-violet-700 font-bold">Mag</span> 6</div><div class="bg-gray-50 border border-gray-200 p-1.5 rounded"><span class="block text-violet-700 font-bold">Reserve</span> 24</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Target Style Selection -->
                <div>
                    <h3 class="text-xs text-violet-700 uppercase tracking-widest font-bold mb-3 flex items-center gap-2"><i class="fas fa-bullseye text-violet-500"></i> Target Style</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="sel-card active" data-mode="steady" onclick="selectTargetMode('steady', this)">
                            <div class="text-center py-2">
                                <i class="fas fa-bullseye text-2xl text-violet-600 mb-2"></i>
                                <h4 class="font-display font-bold text-gray-900 text-sm">Steady</h4>
                                <p class="text-[9px] text-gray-500 mt-1">Center target. Pure accuracy.</p>
                            </div>
                        </div>
                        <div class="sel-card" data-mode="sideways" onclick="selectTargetMode('sideways', this)">
                            <div class="text-center py-2">
                                <i class="fas fa-arrows-alt-h text-2xl text-violet-600 mb-2"></i>
                                <h4 class="font-display font-bold text-gray-900 text-sm">Sideways</h4>
                                <p class="text-[9px] text-gray-500 mt-1">Horizontal tracking.</p>
                            </div>
                        </div>
                        <div class="sel-card" data-mode="around" onclick="selectTargetMode('around', this)">
                            <div class="text-center py-2">
                                <i class="fas fa-arrows-alt text-2xl text-violet-600 mb-2"></i>
                                <h4 class="font-display font-bold text-gray-900 text-sm">Moving Around</h4>
                                <p class="text-[9px] text-gray-500 mt-1">Full 2D movement.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                    <div class="text-xs text-gray-500">
                        Selected: <span id="range-summary-time" class="font-semibold text-gray-700">30s</span> · <span id="range-summary-weapon" class="font-semibold text-gray-700">9mm Pistol</span> · <span id="range-summary-mode" class="font-semibold text-gray-700">Steady</span>
                    </div>
                    <button onclick="startGame()" class="start-sim-btn inline-flex items-center justify-center gap-2 rounded-xl bg-violet-700 px-6 py-3 text-sm font-bold text-white hover:bg-violet-600 transition-colors shadow-lg shadow-violet-900/20">
                        <i class="fas fa-play"></i> Start Simulation
                    </button>
                </div>
                </div>
            </div>
        </div>

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

        <!-- End Sweet Alert -->
        <div id="end-overlay" class="swal-overlay" role="dialog" aria-modal="true">
            <div class="swal-modal text-center">
                <div class="pt-8 pb-2"><div class="w-16 h-16 rounded-full bg-violet-50 mx-auto flex items-center justify-center border-4 border-violet-100"><i class="fas fa-flag-checkered text-2xl text-violet-600"></i></div></div>
                <div class="px-8 pt-4 pb-3">
                    <h3 class="swal-title">Simulation Complete</h3>
                    <p id="res-reason" class="text-sm text-gray-500 mt-1">Time's up!</p>
                </div>
                <div class="mx-8 mb-5 bg-gray-50 rounded-xl p-4 grid grid-cols-3 gap-4 text-center border border-gray-100">
                    <div><p class="text-[10px] text-gray-400 uppercase font-bold">Score</p><p id="res-score" class="text-xl font-display font-bold text-violet-700">0</p></div>
                    <div><p class="text-[10px] text-gray-400 uppercase font-bold">Accuracy</p><p id="res-accuracy" class="text-xl font-display font-bold text-violet-700">0%</p></div>
                    <div><p class="text-[10px] text-gray-400 uppercase font-bold">Bullseyes</p><p id="res-bullseyes" class="text-xl font-display font-bold text-violet-700">0</p></div>
                </div>
                <div class="px-8 pb-8 flex items-center gap-3">
                    <button onclick="restartGame()" class="swal-btn swal-btn-primary flex-1"><i class="fas fa-redo text-xs"></i> Try Again</button>
                    <button onclick="restartGame()" class="swal-btn ml-2 text-violet-700 border border-violet-100 bg-white/5 px-4 py-2 rounded-md">Exit</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // ==================== WEAPON & GAME CONFIG ====================
        const weaponStats = {
            '9mm': { name: '9mm Pistol', magSize: 15, totalAmmo: 45, reloadTime: 1500, recoil: 8, flashColor: 'rgba(255,200,50,0.4)', flashSize: '40%' },
            '.45': { name: '.45 Caliber', magSize: 7, totalAmmo: 28, reloadTime: 2200, recoil: 15, flashColor: 'rgba(255,150,50,0.5)', flashSize: '50%' },
            '38': { name: '.38 Pistol Revolver', magSize: 6, totalAmmo: 24, reloadTime: 1800, recoil: 12, flashColor: 'rgba(255,215,140,0.55)', flashSize: '45%' }
        };

        const state = {
            selectedWeapon: '9mm',
            targetMode: 'steady', 
            selectedTime: 30,
            score: 0,
            timeLeft: 30,
            currentAmmo: 15,
            reserveAmmo: 45,
            isReloading: false,
            isPlaying: false,
            totalShots: 0,
            hits: 0,
            bullseyes: 0
        };

        let gameTimer = null;
        let activeTarget = null; 
        let lastFrameTime = performance.now();
        let pageTimerRemaining = 30;
        let pageTimerInterval = null;
        let pageCountInInterval = null;

        // ==================== DOM ELEMENTS ====================
        const mainHeader = document.getElementById('main-header');
        const gameArea = document.getElementById('game-area');
        const crosshair = document.getElementById('crosshair');
        const targetContainer = document.getElementById('target-container');
        const muzzleFlash = document.getElementById('muzzle-flash');
        const hitMarker = document.getElementById('hit-marker');
        const startOverlay = document.getElementById('start-overlay');
        const endOverlay = document.getElementById('end-overlay');

        const hudScore = document.getElementById('hud-score');
        const hudTimer = document.getElementById('hud-timer');
        const hudWeapon = document.getElementById('hud-weapon');
        const hudAmmo = document.getElementById('hud-ammo');
        const hudReserve = document.getElementById('hud-reserve');
        const hudAccuracy = document.getElementById('hud-accuracy');
        const btnReload = document.getElementById('btn-reload');
        const reloadUI = document.getElementById('reload-ui');
        const reloadFill = document.getElementById('reload-fill');
        const pageTimerDisplay = document.getElementById('page-timer-display');
        const countInOverlay = document.getElementById('count-in-overlay');
        const countInNumber = document.getElementById('count-in-number');
        const rangeSummaryTime = document.getElementById('range-summary-time');
        const rangeSummaryWeapon = document.getElementById('range-summary-weapon');
        const rangeSummaryMode = document.getElementById('range-summary-mode');

        // ==================== SELECTION LOGIC ====================
        function selectTime(time, element) {
            state.selectedTime = time;
            pageTimerRemaining = time;
            updatePageTimerDisplay();
            updateRangeSummary();
            document.querySelectorAll('[data-time]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        function selectCustomRangeTime(element) {
            const customInput = document.getElementById('range-custom-time');
            const customValue = Math.max(5, parseInt(customInput?.value || '30', 10) || 30);
            if (customInput) customInput.value = customValue;
            selectTime(customValue, element);
        }

        function selectWeapon(weaponKey, element) {
            state.selectedWeapon = weaponKey;
            updateRangeSummary();
            document.querySelectorAll('[data-weapon]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        function selectTargetMode(mode, element) {
            state.targetMode = mode;
            updateRangeSummary();
            document.querySelectorAll('[data-mode]').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        function updateRangeSummary() {
            if (rangeSummaryTime) rangeSummaryTime.innerText = `${state.selectedTime}s`;
            if (rangeSummaryWeapon) {
                rangeSummaryWeapon.innerText = state.selectedWeapon === '9mm' ? '9mm Pistol' : state.selectedWeapon === '.45' ? '.45 Caliber' : '.38 Pistol Revolver';
            }
            if (rangeSummaryMode) {
                rangeSummaryMode.innerText = state.targetMode === 'steady' ? 'Steady' : state.targetMode === 'sideways' ? 'Sideways' : 'Moving Around';
            }
        }

        document.getElementById('range-custom-time')?.addEventListener('input', function () {
            const customValue = Math.max(5, parseInt(this.value || '30', 10) || 30);
            this.value = customValue;
            state.selectedTime = customValue;
            pageTimerRemaining = customValue;
            updatePageTimerDisplay();
            updateRangeSummary();
        });

        // ==================== AUDIO ENGINE ====================
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

        function showPageTimerWidget() {
            document.getElementById('page-timer-widget')?.classList.remove('hidden');
        }

        function hidePageTimerWidget() {
            document.getElementById('page-timer-widget')?.classList.add('hidden');
        }

        function showCountInOverlay(value) {
            if (countInNumber) {
                countInNumber.innerText = value;
            }
            countInOverlay?.classList.add('active');
        }

        function hideCountInOverlay() {
            countInOverlay?.classList.remove('active');
        }

        function hideGameCursor() {
            gameArea?.classList.add('cursor-hidden');
        }

        function showGameCursor() {
            gameArea?.classList.remove('cursor-hidden');
        }

        function updatePageTimerDisplay() {
            if (pageTimerDisplay) {
                pageTimerDisplay.innerText = pageTimerRemaining;
            }
        }

        function stopPageTimer() {
            if (pageTimerInterval) {
                clearInterval(pageTimerInterval);
                pageTimerInterval = null;
            }
        }

        function runPageCountdown(onComplete) {
            const steps = ['3', '2', '1', 'Start!'];
            let stepIndex = 0;

            stopPageTimer();
            if (pageTimerDisplay) pageTimerDisplay.innerText = steps[stepIndex];

            const countIn = setInterval(() => {
                stepIndex++;
                if (stepIndex < steps.length) {
                    if (pageTimerDisplay) pageTimerDisplay.innerText = steps[stepIndex];
                    return;
                }

                clearInterval(countIn);
                if (typeof onComplete === 'function') onComplete();
            }, 1000);
        }

        function startPageTimer() {
            stopPageTimer();
            if (pageCountInInterval) {
                clearInterval(pageCountInInterval);
                pageCountInInterval = null;
            }

            pageTimerRemaining = state.selectedTime;
            updatePageTimerDisplay();
            hidePageTimerWidget();
            showGameCursor();

            const steps = ['3', '2', '1', 'Start!'];
            let stepIndex = 0;
            showCountInOverlay(steps[stepIndex]);

            pageCountInInterval = setInterval(() => {
                stepIndex++;
                if (stepIndex < steps.length) {
                    showCountInOverlay(steps[stepIndex]);
                    return;
                }

                clearInterval(pageCountInInterval);
                pageCountInInterval = null;
                hideCountInOverlay();
                beginFiringRangeTimer();
                pageTimerInterval = setInterval(() => {
                    pageTimerRemaining--;
                    updateHUD();
                    if (pageTimerRemaining <= 0) {
                        pageTimerRemaining = 0;
                        updatePageTimerDisplay();
                        stopPageTimer();
                        return;
                    }
                    updatePageTimerDisplay();
                }, 1000);
            }, 1000);
        }

        function resetPageTimer() {
            stopPageTimer();
            if (pageCountInInterval) {
                clearInterval(pageCountInInterval);
                pageCountInInterval = null;
            }
            pageTimerRemaining = state.selectedTime;
            updatePageTimerDisplay();
            showPageTimerWidget();
            hideCountInOverlay();
            updateRangeSummary();
        }

        function beginFiringRangeTimer() {
            state.isPlaying = true;
            state.timeLeft = state.selectedTime;
            hideGameCursor();
            updateHUD();

            gameTimer = setInterval(() => {
                state.timeLeft--;
                updateHUD();
                if (state.timeLeft <= 0) endGame("Time's up!");
            }, 1000);

            spawnTarget();
            lastFrameTime = performance.now();
            requestAnimationFrame(moveTargets);
        }

        // ==================== GAME LOGIC ====================
        function startGame() {
            startOverlay.classList.add('hidden');
            endOverlay.classList.remove('active');
            showPageTimerWidget();
            showGameCursor();
            updateRangeSummary();

            const wStats = weaponStats[state.selectedWeapon];
            state.score = 0; state.timeLeft = state.selectedTime;
            state.currentAmmo = wStats.magSize; state.reserveAmmo = wStats.totalAmmo;
            state.isReloading = false; state.totalShots = 0; state.hits = 0; state.bullseyes = 0;
            targetContainer.innerHTML = ''; activeTarget = null;

            hudWeapon.innerText = wStats.name;
            muzzleFlash.style.background = `radial-gradient(ellipse at bottom, ${wStats.flashColor} 0%, transparent 70%)`;
            muzzleFlash.style.height = wStats.flashSize;

            updateHUD();
            state.isPlaying = false;

            if (mainHeader) {
                mainHeader.classList.add('game-active');
            }
            gameArea.style.height = `calc(100vh - 56px)`; // Maintain height for smooth transition
        }

        function exitGame() {
            endGame("Exited Early");
        }

        function endGame(reason) {
            state.isPlaying = false;
            clearInterval(gameTimer);
            stopPageTimer();
            if (pageCountInInterval) {
                clearInterval(pageCountInInterval);
                pageCountInInterval = null;
            }
            targetContainer.innerHTML = ''; activeTarget = null;
            showPageTimerWidget();
            showGameCursor();

            // Reset UI
            if (mainHeader) {
                mainHeader.classList.remove('game-active');
            }

            document.getElementById('res-reason').innerText = reason || "Simulation ended";
            document.getElementById('res-score').innerText = state.score;
            document.getElementById('res-accuracy').innerText = state.totalShots > 0 ? Math.round((state.hits / state.totalShots) * 100) + '%' : '0%';
            document.getElementById('res-bullseyes').innerText = state.bullseyes;
            
            setTimeout(() => endOverlay.classList.add('active'), 500);
        }

        function restartGame() {
            endOverlay.classList.remove('active');
            startOverlay.classList.remove('hidden');
            showPageTimerWidget();
            showGameCursor();
            hideCountInOverlay();
            stopPageTimer();
            if (pageCountInInterval) {
                clearInterval(pageCountInInterval);
                pageCountInInterval = null;
            }
            pageTimerRemaining = state.selectedTime;
            updatePageTimerDisplay();
        }

        // ==================== TARGET MECHANICS ====================
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
                targetEl.style.left = `${x}px`; targetEl.style.top = `${y}px`;
                targetEl.timeoutId = setTimeout(() => removeTarget(targetEl, false), 4000);
                
            } else if (state.targetMode === 'sideways') {
                // Start from left or right, middle height
                const y = (bounds.height / 2) - (targetHeight / 2);
                const startX = Math.random() > 0.5 ? -targetWidth : bounds.width;
                targetEl.style.left = `${startX}px`; targetEl.style.top = `${y}px`;
                
                const dirX = startX < 0 ? 1 : -1;
                targetEl.dataset.vx = (3 + Math.random() * 4) * dirX;
                targetEl.dataset.vy = 0; // No vertical movement
                
            } else if (state.targetMode === 'around') {
                const x = Math.random() * (bounds.width - targetWidth - 200) + 100;
                const y = Math.random() * (bounds.height - targetHeight - 200) + 80;
                targetEl.style.left = `${x}px`; targetEl.style.top = `${y}px`;
                
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

                // Sideways only logic
                if (state.targetMode === 'sideways') {
                    currentTop = (bounds.height / 2) - (targetHeight / 2); // Force lock Y
                    if (currentLeft <= 0) { currentLeft = 0; activeTarget.dataset.vx = Math.abs(vx); } 
                    else if (currentLeft >= bounds.width - targetWidth) { currentLeft = bounds.width - targetWidth; activeTarget.dataset.vx = -Math.abs(vx); }
                } 
                // Moving Around logic
                else if (state.targetMode === 'around') {
                    if (currentLeft <= 0) { currentLeft = 0; activeTarget.dataset.vx = Math.abs(vx); } 
                    else if (currentLeft >= bounds.width - targetWidth) { currentLeft = bounds.width - targetWidth; activeTarget.dataset.vx = -Math.abs(vx); }
                    if (currentTop <= 0) { currentTop = 0; activeTarget.dataset.vy = Math.abs(vy); } 
                    else if (currentTop >= bounds.height - targetHeight - bufferBottom) { currentTop = bounds.height - targetHeight - bufferBottom; activeTarget.dataset.vy = -Math.abs(vy); }
                }

                activeTarget.style.left = `${currentLeft}px`;
                activeTarget.style.top = `${currentTop}px`;
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

        // ==================== SHOOTING MECHANICS ====================
        function handleShot(e) {
            if (!state.isPlaying || state.isReloading) return;
            if (audioCtx.state === 'suspended') audioCtx.resume();

            if (state.currentAmmo <= 0) { playEmptyClickSound(); initiateReload(); return; }

            state.currentAmmo--; state.totalShots++; updateHUD();
            playGunshot();
            
            muzzleFlash.classList.add('active');
            setTimeout(() => muzzleFlash.classList.remove('active'), 80);

            gameArea.style.transform = `translateY(${weaponStats[state.selectedWeapon].recoil}px)`;
            setTimeout(() => gameArea.style.transform = 'translateY(0)', 100);

            const targetCheck = e.target.closest('.target');
            if (targetCheck) {
                state.hits++;
                let points = 2;
                if (e.target.classList.contains('ring-bullseye')) { points = 20; state.bullseyes++; }
                else if (e.target.classList.contains('ring-inner')) points = 10;
                else if (e.target.classList.contains('ring-middle')) points = 5;

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
            hole.style.left = `${x}px`; hole.style.top = `${y}px`; document.body.appendChild(hole);
            setTimeout(() => { hole.style.transition = 'opacity 1s'; hole.style.opacity = '0'; setTimeout(() => hole.remove(), 1000); }, 3000);
        }

        // ==================== RELOAD MECHANICS ====================
        function initiateReload() {
            if (state.isReloading || state.currentAmmo === weaponStats[state.selectedWeapon].magSize || state.reserveAmmo <= 0 || !state.isPlaying) return;
            state.isReloading = true; btnReload.disabled = true; reloadUI.classList.remove('hidden');
            reloadFill.style.transition = `width ${weaponStats[state.selectedWeapon].reloadTime}ms linear`;
            setTimeout(() => reloadFill.style.width = '100%', 50);

            setTimeout(() => {
                const wStats = weaponStats[state.selectedWeapon]; const needed = wStats.magSize - state.currentAmmo; const available = Math.min(needed, state.reserveAmmo);
                state.currentAmmo += available; state.reserveAmmo -= available;
                state.isReloading = false; btnReload.disabled = false; reloadFill.style.width = '0%';
                setTimeout(() => reloadUI.classList.add('hidden'), 300); updateHUD();
            }, weaponStats[state.selectedWeapon].reloadTime);
        }

        // ==================== HUD UPDATES ====================
        function updateHUD() {
            hudScore.innerText = state.score; hudTimer.innerText = state.timeLeft; hudAmmo.innerText = state.currentAmmo; hudReserve.innerText = `/ ${state.reserveAmmo}`;
            const accuracy = state.totalShots > 0 ? Math.round((state.hits / state.totalShots) * 100) : 0; hudAccuracy.innerText = accuracy + '%';

            if (state.timeLeft <= 10) hudTimer.classList.replace('text-white', 'text-red-500'); else hudTimer.classList.replace('text-red-500', 'text-white');
            if (state.currentAmmo <= 3 && state.currentAmmo > 0) hudAmmo.classList.replace('text-white', 'text-amber-500'); else if (state.currentAmmo === 0) hudAmmo.classList.replace('text-white', 'text-red-500'); else hudAmmo.className = hudAmmo.className.replace(/text-(amber|red)-500/g, 'text-white');
            btnReload.disabled = state.isReloading || state.currentAmmo === weaponStats[state.selectedWeapon].magSize || state.reserveAmmo <= 0;
        }

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('mousemove', (e) => { crosshair.style.left = e.clientX + 'px'; crosshair.style.top = e.clientY + 'px'; });
        gameArea.addEventListener('mouseenter', () => { if (state.isPlaying) crosshair.style.display = 'block'; });
        gameArea.addEventListener('mouseleave', () => { crosshair.style.display = 'none'; });
        gameArea.addEventListener('mousedown', handleShot);
        document.addEventListener('keydown', (e) => { if (e.key === 'r' || e.key === 'R') initiateReload(); });
        gameArea.addEventListener('contextmenu', e => e.preventDefault());

    </script>

    @include('Students.partials.module-access-watch', ['currentModuleKey' => 'module-4', 'currentModuleLabel' => 'Firing Range'])
    @include('shared.sweet-alerts.logout', ['logoutLabel' => 'Student — ' . ($name ?? 'Student'), 'logoutSubtext' => 'VirtualArm Firing Range', 'logoutDescription' => 'You are about to end your session.', 'redirectUrl' => url('/')])

    <script>
        // ==================== MOBILE MENU ====================
        const mobileToggle = document.getElementById('mobile-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('open');
                const icon = mobileToggle.querySelector('i');
                if (mobileMenu.classList.contains('open')) { icon.classList.remove('fa-bars'); icon.classList.add('fa-times'); }
                else { icon.classList.remove('fa-times'); icon.classList.add('fa-bars'); }
            });
        }
    </script>

    <script>
        resetPageTimer();
        document.getElementById('page-timer-widget')?.classList.add('hidden');
    </script>
</body>
</html>