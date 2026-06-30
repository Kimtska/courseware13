<!DOCTYPE html>
<html lang="en" style="zoom:133%;overflow-x:auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IOT-BASED MARKSMANSHIP TRAINING SIMULATOR FOR SPC CRIMINOLOGY</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/Marksmanship innovatech.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    .carousel-container{position:relative;overflow:hidden;min-height:420px}
    .carousel-slide{position:absolute;top:0;left:0;width:100%;opacity:0;transform:translateX(40px);transition:all .5s cubic-bezier(.4,0,.2,1);pointer-events:none}
    .carousel-slide.active{position:relative;opacity:1;transform:translateX(0);pointer-events:auto}
    .carousel-slide .gun-img{background:none!important}
    @keyframes pulse{0%,100%{opacity:1}50%{opacity:0.5}}
    @keyframes targetHit{0%{transform:scale(1);opacity:1}50%{transform:scale(1.2);opacity:0.5}100%{transform:scale(0);opacity:0}}
    @keyframes hitMarkerAnim{0%{opacity:1;transform:translate(-50%,-50%) scale(0.5)}100%{opacity:0;transform:translate(-50%,-50%) scale(1.5)}}
    .target-container{position:absolute;transition:transform 0.2s ease-out, opacity 0.2s}
    .target-container.hit{animation:targetHit 0.4s ease-out forwards}
    .target{width:160px;height:160px;position:relative;cursor:none;filter:drop-shadow(0 12px 24px rgba(0,0,0,0.4));transform-origin:center center}
    .target-board{position:absolute;inset:0;border-radius:50%;background:#1a1a1a;border:3px solid #000;overflow:hidden;box-shadow:inset 0 0 0 1px rgba(255,255,255,0.05), inset 0 -4px 20px rgba(0,0,0,0.6), 0 8px 24px rgba(0,0,0,0.4)}
    .target-board::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 40% 40%, rgba(255,255,255,0.04) 0%, transparent 50%);opacity:0.5}
    .target-rings{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:100%;height:100%}
    .target-ring{position:absolute;left:50%;top:50%;border-radius:50%;transform:translate(-50%,-50%)}
    .target-ring.delta{width:140px;height:140px;background:#000;border:2px solid #111}
    .target-ring.charlie{width:106px;height:106px;background:#000;border:2px solid #111}
    .target-ring.bravo{width:72px;height:72px;background:#fff;border:2px solid #ddd}
    .target-ring.alpha{width:38px;height:38px;background:#dc2626;border:2px solid #b91c1c}
    .target-bullseye{position:absolute;left:50%;top:50%;width:16px;height:16px;transform:translate(-50%,-50%);border-radius:50%;background:radial-gradient(circle at 35% 35%, #fff1 0%, #dc2626 40%, #7f1d1d 100%);box-shadow:0 0 16px rgba(220,38,38,0.9), inset 0 -2px 4px rgba(0,0,0,0.4), inset 0 2px 4px rgba(255,255,255,0.2);border:2px solid #b91c1c}
    .target-bullseye::before{content:'';position:absolute;inset:-3px;border-radius:50%;background:conic-gradient(from 0deg, transparent, rgba(220,38,38,0.3), transparent);animation:bullseyePulse 2s ease-in-out infinite}
    @keyframes bullseyePulse{0%,100%{opacity:0.3;transform:scale(1)}50%{opacity:0.6;transform:scale(1.15)}}
    .bullet-hole{position:fixed;width:8px;height:8px;background:radial-gradient(circle, #111 0%, #333 60%, transparent 100%);border-radius:50%;pointer-events:none;z-index:5;box-shadow:0 0 2px rgba(0,0,0,0.8)}
    #game-area.cursor-hidden{cursor:none}
    #hit-marker.show{animation:hitMarkerAnim 0.2s ease-out forwards}
    #muzzle-flash.active{opacity:1}
    .reload-fill{transition:width 1.5s linear}
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
    .start-btn-start{background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;box-shadow:0 4px 14px -3px rgba(34,197,94,0.4)}
    .start-btn-start:hover{background:linear-gradient(135deg,#15803d,#16a34a);box-shadow:0 6px 20px -3px rgba(34,197,94,0.5);transform:translateY(-1px)}
    .start-btn-start:active{transform:translateY(0) scale(0.96)}
    .start-bg-shape{position:absolute;pointer-events:none;opacity:0.04}
    .scroll-dot{animation:scrollBounce 1.5s ease-in-out infinite}
    @keyframes scrollBounce{0%,100%{transform:translateY(0);opacity:1}50%{transform:translateY(10px);opacity:0.15}}
    @media(prefers-reduced-motion:reduce){.start-modal,.start-modal-overlay{transition:none!important}.start-icon-ring::before,.start-dot,.scroll-indicator,.scroll-dot{animation:none!important}}
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-500 bg-white">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="#" class="flex items-center gap-3">
                <img src="{{ asset('images/assets/Marksmanship innovatech.png') }}" alt="SPC" class="h-12 w-auto">
                <div class="flex flex-col">
                    <span class="font-display font-bold text-lg tracking-tight text-black leading-none">IOT-Based<span class="text-violet-700"> Marksmanship</span></span>
                    <span class="text-[9px] text-gray-400 tracking-wider uppercase font-medium">SPC Criminology</span>
                </div>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="#modules" class="text-xs font-medium tracking-widest uppercase text-gray-500 hover:text-violet-700 transition-colors">Modules</a>
                <a href="#parts" class="text-xs font-medium tracking-widest uppercase text-gray-500 hover:text-violet-700 transition-colors">Gun Parts</a>
                <a href="#assembly" class="text-xs font-medium tracking-widest uppercase text-gray-500 hover:text-violet-700 transition-colors">Assembly</a>
                <a href="#firing" class="text-xs font-medium tracking-widest uppercase text-gray-500 hover:text-violet-700 transition-colors">Firing Range</a>
                <a href="#about" class="text-xs font-medium tracking-widest uppercase text-gray-500 hover:text-violet-700 transition-colors">About</a>
                <a href="/login" class="btn-shine px-6 py-2.5 bg-violet-700 hover:bg-violet-800 text-white text-xs font-bold tracking-wider uppercase rounded transition-all duration-300 hover:shadow-[0_4px_20px_rgba(91,33,182,.35)] flex items-center gap-2"><i class="fas fa-user-plus text-[10px]"></i> Sign In</a>
            </div>
            <button id="menuToggle" class="md:hidden w-10 h-10 flex flex-col items-center justify-center gap-1.5 z-60">
                <span class="menu-line w-6 h-0.5 bg-black transition-all duration-300"></span>
                <span class="menu-line w-6 h-0.5 bg-black transition-all duration-300"></span>
                <span class="menu-line w-4 h-0.5 bg-black transition-all duration-300 ml-auto"></span>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu fixed inset-0 z-40 bg-white/98 flex flex-col items-center justify-center gap-8">
        <img src="{{ asset('images/assets/Marksmanship innovatech.png') }}" alt="SPC Logo" class="h-20 w-auto mb-4">
        <a href="#modules" class="mobile-link text-2xl font-display font-bold text-black hover:text-violet-700">Modules</a>
        <a href="#parts" class="mobile-link text-2xl font-display font-bold text-black hover:text-violet-700">Gun Parts</a>
        <a href="#assembly" class="mobile-link text-2xl font-display font-bold text-black hover:text-violet-700">Assembly</a>
        <a href="#firing" class="mobile-link text-2xl font-display font-bold text-black hover:text-violet-700">Firing Range</a>
        <a href="#about" class="mobile-link text-2xl font-display font-bold text-black hover:text-violet-700">About</a>
        <a href="/login" class="mobile-link px-8 py-3 bg-violet-700 text-white font-bold tracking-wider uppercase rounded text-sm">Sign In / Sign Up</a>
    </div>

    <!-- HERO -->
    <section class="relative min-h-screen flex items-center grid-pattern overflow-hidden bg-white">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-violet-100/60 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-violet-50/80 rounded-full blur-[100px]"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-6 pt-4 pb-10 w-full">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-3/5 text-center lg:text-left">
                    <h1 class="font-display font-bold tracking-tight leading-[.95]">
                        <span class="block text-5xl md:text-7xl lg:text-9xl text-black">IOT-BASED</span>
                        <span class="block text-5xl md:text-7xl lg:text-9xl text-highlight">MARKSMANSHIP</span>
                    </h1>
                    <p class="mt-4 text-base md:text-lg font-medium text-violet-700 tracking-wide">TRAINING SIMULATOR FOR SPC CRIMINOLOGY</p>
                    <p class="mt-4 text-lg md:text-xl font-light text-gray-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Master firearm knowledge - from <span class="text-black font-medium">parts identification</span> to <span class="text-black font-medium">assembly & disassembly</span>, then test your aim in a <span class="text-black font-medium">virtual firing range</span>.
                    </p>
                    <div class="flex flex-wrap items-center gap-3 mt-8 justify-center lg:justify-start">
                        <span class="px-4 py-2 rounded-full bg-violet-100 border border-violet-200 text-xs font-semibold text-violet-700 flex items-center gap-2"><i class="fas fa-gun text-[10px]"></i> 9mm Pistol</span>
                        <span class="px-4 py-2 rounded-full bg-violet-100 border border-violet-200 text-xs font-semibold text-violet-700 flex items-center gap-2"><i class="fas fa-gun text-[10px]"></i> .45 Pistol</span>
                        <span class="px-4 py-2 rounded-full bg-violet-100 border border-violet-200 text-xs font-semibold text-violet-700 flex items-center gap-2"><i class="fas fa-gun text-[10px]"></i> .38 Pistol Revolver</span>
                        <span class="px-4 py-2 rounded-full bg-amber-100 border border-amber-200 text-xs font-semibold text-amber-700 flex items-center gap-2"><i class="fas fa-gun text-[10px]"></i> 12-Gauge Shotgun</span>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-4 mt-10 justify-center lg:justify-start">
                        <a href="/login" class="btn-shine group px-8 py-3.5 bg-violet-700 hover:bg-violet-800 text-white font-bold text-sm tracking-wider uppercase rounded transition-all duration-300 hover:shadow-[0_4px_25px_rgba(91,33,182,.4)] flex items-center gap-3"><span>Get Started</span><i class="fas fa-arrow-right text-violet-300 group-hover:translate-x-1 transition-transform"></i></a>
                        <a href="#modules" class="group px-8 py-3.5 border-2 border-violet-200 hover:border-violet-400 text-violet-700 hover:text-violet-800 font-bold text-sm tracking-wider uppercase rounded transition-all duration-300 hover:bg-violet-50 flex items-center gap-3"><span>Explore Modules</span></a>
                    </div>
                </div>
                <div class="lg:w-2/5 relative flex items-center justify-center">
                    <div class="relative w-full max-w-md" id="heroVisual">
                        <div class="relative">
                            <div class="carousel-container" id="gunCarousel">
                                <div class="carousel-slide active">
                                    <img src="/images/assets/9mm.png" alt="9mm Pistol" class="w-full h-96 object-contain gun-img" loading="lazy">
                                    <div class="text-center mt-4">
                                        <span class="text-lg font-mono font-bold text-violet-700">9mm Pistol</span>
                                        <p class="text-sm text-gray-400 font-mono mt-0.5">Semi-Automatic • 9×19mm Parabellum</p>
                                    </div>
                                </div>
                                <div class="carousel-slide">
                                    <img src="/images/assets/.45.png" alt=".45 Pistol" class="w-full h-96 object-contain gun-img" loading="lazy">
                                    <div class="text-center mt-4">
                                        <span class="text-lg font-mono font-bold text-violet-700">.45 Pistol</span>
                                        <p class="text-sm text-gray-400 font-mono mt-0.5">1911 Style • .45 ACP</p>
                                    </div>
                                </div>
                                <div class="carousel-slide">
<img src="/images/assets/38.png" alt=".38 Pistol Revolver" class="w-full h-96 object-contain gun-img" loading="lazy">
                                    <div class="text-center mt-4">
                                        <span class="text-lg font-mono font-bold text-violet-700">.38 Pistol Revolver</span>
                                        <p class="text-sm text-gray-400 font-mono mt-0.5">Revolver • .38 Special</p>
                                    </div>
                                </div>
                                <div class="carousel-slide">
                                    <img src="/images/assets/shotgun.png" alt="12-Gauge Shotgun" class="w-full h-96 object-contain gun-img" loading="lazy">
                                    <div class="text-center mt-4">
                                        <span class="text-lg font-mono font-bold text-amber-700">12-Gauge Shotgun</span>
                                        <p class="text-sm text-gray-400 font-mono mt-0.5">Pump-Action • 12-Gauge</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-indicator absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2">
            <span class="text-[10px] uppercase tracking-[.3em] text-violet-600 font-semibold">Scroll</span>
            <div class="w-6 h-10 border-2 border-violet-500 rounded-full flex justify-center pt-2">
                <div class="scroll-dot w-1.5 h-3 bg-violet-600 rounded-full"></div>
            </div>
        </div>
    </section>

    <!-- MARQUEE -->
    <div class="relative border-y border-violet-100 bg-violet-50/50 py-4 overflow-hidden">
        <div class="marquee-track flex items-center gap-12 whitespace-nowrap">
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-gun text-violet-600"></i> 9mm Pistol</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-gun text-violet-600"></i> .45 Pistol</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-gun text-violet-600"></i> .38 Pistol Revolver</span>
            <span class="text-xs uppercase tracking-[.4em] text-amber-500 flex items-center gap-3"><i class="fas fa-gun text-amber-600"></i> 12-Gauge Shotgun</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-wrench text-violet-600"></i> Assembly Training</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-bullseye text-violet-600"></i> Firing Simulation</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-graduation-cap text-violet-600"></i> SPC Criminology</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-gun text-violet-600"></i> 9mm Pistol</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-gun text-violet-600"></i> .45 Pistol</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-gun text-violet-600"></i> .38 Pistol Revolver</span>
            <span class="text-xs uppercase tracking-[.4em] text-amber-500 flex items-center gap-3"><i class="fas fa-gun text-amber-600"></i> 12-Gauge Shotgun</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-wrench text-violet-600"></i> Assembly Training</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-bullseye text-violet-600"></i> Firing Simulation</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-graduation-cap text-violet-600"></i> SPC Criminology</span>
        </div>
    </div>

    <!-- 3 CORE MODULES -->
    <section id="modules" class="relative py-24 md:py-32 overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-20 fade-in-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-violet-200 bg-violet-50 mb-6"><i class="fas fa-layer-group text-violet-600 text-[10px]"></i><span class="text-[10px] font-mono font-medium tracking-[.3em] uppercase text-violet-700">Three Core Modules</span></div>
                <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl tracking-tight text-black">Learn. Build. <span class="text-highlight">Fire.</span></h2>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="feature-card group p-8 md:p-10 rounded-2xl bg-white glow-border hover:shadow-xl transition-all duration-500 fade-in-up relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-violet-600 to-violet-300 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                    <div class="w-14 h-14 rounded-xl bg-violet-100 border border-violet-200 flex items-center justify-center mb-6 group-hover:bg-violet-200 transition-colors"><i class="fas fa-book-open text-violet-700 text-xl"></i></div>
                    <span class="text-[10px] font-mono font-bold text-violet-500 tracking-widest">MODULE 01</span>
                    <h3 class="font-display font-bold text-2xl text-black mt-2 mb-3">Firearm Parts Education</h3>
                    <div class="feature-line h-0.5 bg-gradient-to-r from-violet-600 to-transparent mb-4"></div>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">Explore detailed descriptions of every component of the <strong class="text-black">9mm Pistol</strong>, <strong class="text-black">.45 Pistol</strong>, <strong class="text-black">.38 Pistol Revolver</strong>, and <strong class="text-black">12-Gauge Shotgun</strong> with actual images and interactive diagrams.</p>
                </div>
                <div class="feature-card group p-8 md:p-10 rounded-2xl bg-white glow-border hover:shadow-xl transition-all duration-500 fade-in-up relative overflow-hidden" style="transition-delay:.15s">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-violet-600 to-violet-300 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                    <div class="w-14 h-14 rounded-xl bg-violet-100 border border-violet-200 flex items-center justify-center mb-6 group-hover:bg-violet-200 transition-colors"><i class="fas fa-wrench text-violet-700 text-xl"></i></div>
                    <span class="text-[10px] font-mono font-bold text-violet-500 tracking-widest">MODULE 02</span>
                    <h3 class="font-display font-bold text-2xl text-black mt-2 mb-3">Assembly & Disassembly</h3>
                    <div class="feature-line h-0.5 bg-gradient-to-r from-violet-600 to-transparent mb-4"></div>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">Learn step-by-step field-stripping and reassembling the <strong class="text-black">9mm</strong>, <strong class="text-black">.45</strong>, <strong class="text-black">.38</strong>, and <strong class="text-black">12-Gauge Shotgun</strong>.</p>
                </div>
                <div class="feature-card group p-8 md:p-10 rounded-2xl bg-white glow-border hover:shadow-xl transition-all duration-500 fade-in-up relative overflow-hidden" style="transition-delay:.3s">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-violet-600 to-violet-300 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                    <div class="w-14 h-14 rounded-xl bg-violet-100 border border-violet-200 flex items-center justify-center mb-6 group-hover:bg-violet-200 transition-colors"><i class="fas fa-crosshairs text-violet-700 text-xl"></i></div>
                    <span class="text-[10px] font-mono font-bold text-violet-500 tracking-widest">MODULE 03</span>
                    <h3 class="font-display font-bold text-2xl text-black mt-2 mb-3">Firing Range Simulation</h3>
                    <div class="feature-line h-0.5 bg-gradient-to-r from-violet-600 to-transparent mb-4"></div>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">After assembling your firearm, the virtual range appears. Aim, fire, and receive real-time scoring with your chosen weapon.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- MODULE 1: GUN PARTS -->
    <section id="parts" class="relative py-24 md:py-32 overflow-hidden bg-light-50">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row items-start gap-16">
                <div class="lg:w-2/5 lg:sticky lg:top-28 fade-in-up">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-violet-200 bg-violet-50 mb-6"><i class="fas fa-book-open text-violet-600 text-[10px]"></i><span class="text-[10px] font-mono font-medium tracking-[.3em] uppercase text-violet-700">Module 01</span></div>
                    <h2 class="font-display font-bold text-3xl md:text-4xl lg:text-5xl tracking-tight text-black mb-6">Firearm Parts <span class="text-highlight">Education</span></h2>
                    <p class="text-gray-500 text-lg font-light leading-relaxed mb-8">Before holding a firearm, criminology students must understand every component - its name, location, purpose, and how it contributes to the weapon's operation.</p>
                </div>
                <div class="lg:w-3/5 fade-in-up" style="transition-delay:.2s">
                    <div class="flex gap-3 mb-8 flex-wrap">
                        <button class="tab-btn active px-5 py-2.5 rounded-lg border border-violet-200 text-xs font-bold tracking-wider uppercase transition-all" data-tab="pistol9">9mm Pistol</button>
                        <button class="tab-btn px-5 py-2.5 rounded-lg border border-violet-200 text-xs font-bold tracking-wider uppercase text-gray-500 hover:text-violet-700 transition-all" data-tab="pistol45">.45 Pistol</button>
                        <button class="tab-btn px-5 py-2.5 rounded-lg border border-violet-200 text-xs font-bold tracking-wider uppercase text-gray-500 hover:text-violet-700 transition-all" data-tab="38">.38 Pistol Revolver</button>
                        <button class="tab-btn px-5 py-2.5 rounded-lg border border-amber-200 text-xs font-bold tracking-wider uppercase text-gray-500 hover:text-amber-700 transition-all" data-tab="shotgun">12-Gauge Shotgun</button>
                    </div>
                    <!-- 9mm Pistol -->
                    <div id="tab-pistol9" class="tab-content active">
                        <div class="p-8 rounded-2xl bg-white glow-border shadow-sm">
                            <div class="flex items-center gap-3 mb-6"><span class="px-3 py-1 rounded bg-violet-100 text-violet-700 text-[10px] font-mono font-bold tracking-wider">9×19mm PARABELLUM</span><span class="px-3 py-1 rounded bg-gray-100 text-gray-600 text-[10px] font-mono font-bold tracking-wider">SEMI-AUTOMATIC</span></div>
                            <div class="relative bg-gradient-to-br from-gray-50 to-violet-50 rounded-xl p-6 border border-violet-100 mb-6">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/46/Glock_17_%286938849536%29.jpg/640px-Glock_17_%286938849536%29.jpg" alt="9mm Pistol" class="w-full h-56 object-contain gun-img" loading="lazy">
                                <p class="text-center text-[10px] text-gray-400 mt-3 font-mono">9mm Semi-Automatic Pistol (Glock 17)</p>
                            </div>
                            <div class="relative bg-gradient-to-br from-gray-50 to-violet-50 rounded-xl p-6 border border-violet-100 mb-6">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3a/Glock_17_gen4_box_disassembled.jpg/640px-Glock_17_gen4_box_disassembled.jpg" alt="9mm Pistol Field Stripped" class="w-full h-48 object-contain gun-img" loading="lazy">
                                <p class="text-center text-[10px] text-gray-400 mt-3 font-mono">Field-stripped: Slide, Barrel, Recoil Spring, Frame, Magazine</p>
                            </div>
                            <h4 class="font-display font-semibold text-black text-sm mb-4 flex items-center gap-2"><i class="fas fa-list text-violet-500 text-xs"></i> Components</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[280px] overflow-y-auto pr-2">
                                <div class="p-3 rounded-lg bg-light-50 border border-violet-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-violet-600"></div><h5 class="font-semibold text-black text-xs">Slide</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Houses barrel, firing pin, extractor. Moves rearward during recoil.</p></div>
                                <div class="p-3 rounded-lg bg-light-50 border border-violet-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-violet-600"></div><h5 class="font-semibold text-black text-xs">Barrel</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Metal tube for 9mm bullet travel with rifling for stability.</p></div>
                                <div class="p-3 rounded-lg bg-light-50 border border-violet-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-violet-600"></div><h5 class="font-semibold text-black text-xs">Frame</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Structural foundation with trigger mechanism and magazine well.</p></div>
                                <div class="p-3 rounded-lg bg-light-50 border border-violet-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-violet-600"></div><h5 class="font-semibold text-black text-xs">Magazine</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Removable, holds 15–17 rounds, spring-loaded.</p></div>
                            </div>
                        </div>
                    </div>
                    <!-- .45 Pistol -->
                    <div id="tab-pistol45" class="tab-content">
                        <div class="p-8 rounded-2xl bg-white glow-border shadow-sm">
                            <div class="flex items-center gap-3 mb-6"><span class="px-3 py-1 rounded bg-violet-100 text-violet-700 text-[10px] font-mono font-bold tracking-wider">.45 ACP</span><span class="px-3 py-1 rounded bg-gray-100 text-gray-600 text-[10px] font-mono font-bold tracking-wider">1911 STYLE</span></div>
                            <div class="relative bg-gradient-to-br from-gray-50 to-violet-50 rounded-xl p-6 border border-violet-100 mb-6">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e4/Colt_M1911A1.jpg/640px-Colt_M1911A1.jpg" alt=".45 Pistol" class="w-full h-56 object-contain gun-img" loading="lazy">
                                <p class="text-center text-[10px] text-gray-400 mt-3 font-mono">.45 ACP M1911A1 Semi-Automatic Pistol</p>
                            </div>
                            <div class="relative bg-gradient-to-br from-gray-50 to-violet-50 rounded-xl p-6 border border-violet-100 mb-6">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/M1911_exploded_diagram.svg/640px-M1911_exploded_diagram.svg.png" alt="1911 Exploded" class="w-full h-56 object-contain gun-img" loading="lazy">
                                <p class="text-center text-[10px] text-gray-400 mt-3 font-mono">M1911 Exploded View</p>
                            </div>
                            <h4 class="font-display font-semibold text-black text-sm mb-4 flex items-center gap-2"><i class="fas fa-list text-violet-500 text-xs"></i> Components</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[280px] overflow-y-auto pr-2">
                                <div class="p-3 rounded-lg bg-light-50 border border-violet-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-violet-600"></div><h5 class="font-semibold text-black text-xs">Grip Safety</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">1911-specific passive safety, must be depressed to fire.</p></div>
                                <div class="p-3 rounded-lg bg-light-50 border border-violet-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-violet-600"></div><h5 class="font-semibold text-black text-xs">Thumb Safety</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Manual lever blocking hammer and sear when engaged.</p></div>
                                <div class="p-3 rounded-lg bg-light-50 border border-violet-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-violet-600"></div><h5 class="font-semibold text-black text-xs">Hammer</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Exposed external hammer for single-action firing.</p></div>
                                <div class="p-3 rounded-lg bg-light-50 border border-violet-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-violet-600"></div><h5 class="font-semibold text-black text-xs">Magazine</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Single-stack, 7 rounds of .45 ACP.</p></div>
                            </div>
                        </div>
                    </div>
                    <!-- .38 Revolver -->
                    <div id="tab-38" class="tab-content">
                        <div class="p-8 rounded-2xl bg-white glow-border shadow-sm">
                            <div class="flex items-center gap-3 mb-6"><span class="px-3 py-1 rounded bg-violet-100 text-violet-700 text-[10px] font-mono font-bold tracking-wider">5.56×45mm NATO</span><span class="px-3 py-1 rounded bg-gray-100 text-gray-600 text-[10px] font-mono font-bold tracking-wider">GAS-OPERATED</span></div>
                            <div class="relative bg-gradient-to-br from-gray-50 to-violet-50 rounded-xl p-6 border border-violet-100 mb-6">
                                <img src="/images/assets/38.png" alt=".38 Pistol Revolver" class="w-full h-48 object-contain gun-img" loading="lazy">
                                <p class="text-center text-[10px] text-gray-400 mt-3 font-mono">.38 Pistol Revolver</p>
                            </div>
                            <div class="relative bg-gradient-to-br from-gray-50 to-violet-50 rounded-xl p-6 border border-violet-100 mb-6">
                                <img src="/images/assets/38.png" alt=".38 Parts" class="w-full h-56 object-contain gun-img" loading="lazy">
                                <p class="text-center text-[10px] text-gray-400 mt-3 font-mono">.38 Revolver Parts Diagram</p>
                            </div>
                            <h4 class="font-display font-semibold text-black text-sm mb-4 flex items-center gap-2"><i class="fas fa-list text-violet-500 text-xs"></i> Components</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[280px] overflow-y-auto pr-2">
                                <div class="p-3 rounded-lg bg-light-50 border border-violet-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-violet-600"></div><h5 class="font-semibold text-black text-xs">Cylinder & Barrel</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Heart of the .38 Revolver — chambers, cylinder rotation, firing, extraction.</p></div>
                                <div class="p-3 rounded-lg bg-light-50 border border-violet-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-violet-600"></div><h5 class="font-semibold text-black text-xs">Gas System</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Direct impingement cycles bolt carrier via redirected gas.</p></div>
                                <div class="p-3 rounded-lg bg-light-50 border border-violet-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-violet-600"></div><h5 class="font-semibold text-black text-xs">Forward Assist</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Manually pushes bolt into battery if it fails to close.</p></div>
                                <div class="p-3 rounded-lg bg-light-50 border border-violet-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-violet-600"></div><h5 class="font-semibold text-black text-xs">Fire Selector</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">SAFE / SEMI / AUTO (or BURST) mode switch.</p></div>
                            </div>
                        </div>
                    </div>
                    <!-- 12-Gauge Shotgun -->
                    <div id="tab-shotgun" class="tab-content">
                        <div class="p-8 rounded-2xl bg-white glow-border shadow-sm">
                            <div class="flex items-center gap-3 mb-6"><span class="px-3 py-1 rounded bg-amber-100 text-amber-700 text-[10px] font-mono font-bold tracking-wider">12-GAUGE</span><span class="px-3 py-1 rounded bg-gray-100 text-gray-600 text-[10px] font-mono font-bold tracking-wider">PUMP-ACTION</span></div>
                            <div class="relative bg-gradient-to-br from-gray-50 to-amber-50 rounded-xl p-6 border border-amber-100 mb-6">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Remington_870_Wingmaster_12_Gauge.jpg/640px-Remington_870_Wingmaster_12_Gauge.jpg" alt="12-Gauge Shotgun" class="w-full h-48 object-contain gun-img" loading="lazy">
                                <p class="text-center text-[10px] text-gray-400 mt-3 font-mono">12-Gauge Pump-Action Shotgun (Remington 870)</p>
                            </div>
                            <h4 class="font-display font-semibold text-black text-sm mb-4 flex items-center gap-2"><i class="fas fa-list text-violet-500 text-xs"></i> Components</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[280px] overflow-y-auto pr-2">
                                <div class="p-3 rounded-lg bg-light-50 border border-amber-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-amber-600"></div><h5 class="font-semibold text-black text-xs">Barrel & Magazine Tube</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Smoothbore barrel underlaid by tubular magazine holding multiple shells.</p></div>
                                <div class="p-3 rounded-lg bg-light-50 border border-amber-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-amber-600"></div><h5 class="font-semibold text-black text-xs">Forend / Pump</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Sliding grip that cycles the action — ejects spent shell, loads fresh round.</p></div>
                                <div class="p-3 rounded-lg bg-light-50 border border-amber-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-amber-600"></div><h5 class="font-semibold text-black text-xs">Stock</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Shoulder mount providing stability and recoil absorption.</p></div>
                                <div class="p-3 rounded-lg bg-light-50 border border-amber-100"><div class="flex items-center gap-2 mb-1"><div class="w-2 h-2 rounded-full bg-amber-600"></div><h5 class="font-semibold text-black text-xs">Trigger Group & Safety</h5></div><p class="text-gray-400 text-[11px] leading-relaxed pl-4">Cross-bolt safety blocks trigger; simple, reliable fire control system.</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MODULE 2: ASSEMBLY (Shortened for brevity in this diff, same as before) -->
    <section id="assembly" class="relative py-24 md:py-32 overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-20 fade-in-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-violet-200 bg-violet-50 mb-6"><i class="fas fa-wrench text-violet-600 text-[10px]"></i><span class="text-[10px] font-mono font-medium tracking-[.3em] uppercase text-violet-700">Module 02</span></div>
                <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl tracking-tight text-black">Assembly & <span class="text-highlight">Disassembly</span></h2>
            </div>
            <div class="max-w-5xl mx-auto fade-in-up">
                <div class="mb-12">
                    <div class="flex items-center gap-3 mb-6"><div class="px-3 py-1.5 rounded bg-violet-700 text-white text-[10px] font-mono font-bold tracking-widest">PISTOL</div><div class="h-px flex-1 bg-violet-100"></div><span class="text-xs text-gray-400 font-mono">9mm & .45</span></div>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        <div class="p-3 rounded-xl bg-light-50 border border-violet-100 text-center"><div class="w-8 h-8 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center mx-auto mb-2"><span class="font-display font-bold text-violet-700 text-xs">1</span></div><h5 class="font-semibold text-black text-[10px]">Remove Magazine</h5></div>
                        <div class="p-3 rounded-xl bg-light-50 border border-violet-100 text-center"><div class="w-8 h-8 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center mx-auto mb-2"><span class="font-display font-bold text-violet-700 text-xs">2</span></div><h5 class="font-semibold text-black text-[10px]">Retract & Clear</h5></div>
                        <div class="p-3 rounded-xl bg-light-50 border border-violet-100 text-center"><div class="w-8 h-8 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center mx-auto mb-2"><span class="font-display font-bold text-violet-700 text-xs">3</span></div><h5 class="font-semibold text-black text-[10px]">Release Slide Lock</h5></div>
                        <div class="p-3 rounded-xl bg-light-50 border border-violet-100 text-center"><div class="w-8 h-8 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center mx-auto mb-2"><span class="font-display font-bold text-violet-700 text-xs">4</span></div><h5 class="font-semibold text-black text-[10px]">Remove Slide</h5></div>
                        <div class="p-3 rounded-xl bg-light-50 border border-violet-100 text-center"><div class="w-8 h-8 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center mx-auto mb-2"><span class="font-display font-bold text-violet-700 text-xs">5</span></div><h5 class="font-semibold text-black text-[10px]">Barrel & Spring</h5></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-3 mb-6"><div class="px-3 py-1.5 rounded bg-violet-700 text-white text-[10px] font-mono font-bold tracking-widest">.38</div><div class="h-px flex-1 bg-violet-100"></div><span class="text-xs text-gray-400 font-mono">FIELD STRIP</span></div>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        <div class="p-3 rounded-xl bg-light-50 border border-violet-100 text-center"><div class="w-8 h-8 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center mx-auto mb-2"><span class="font-display font-bold text-violet-700 text-xs">1</span></div><h5 class="font-semibold text-black text-[10px]">Remove Magazine</h5></div>
                        <div class="p-3 rounded-xl bg-light-50 border border-violet-100 text-center"><div class="w-8 h-8 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center mx-auto mb-2"><span class="font-display font-bold text-violet-700 text-xs">2</span></div><h5 class="font-semibold text-black text-[10px]">Charge & Clear</h5></div>
                        <div class="p-3 rounded-xl bg-light-50 border border-violet-100 text-center"><div class="w-8 h-8 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center mx-auto mb-2"><span class="font-display font-bold text-violet-700 text-xs">3</span></div><h5 class="font-semibold text-black text-[10px]">Separate Receivers</h5></div>
                        <div class="p-3 rounded-xl bg-light-50 border border-violet-100 text-center"><div class="w-8 h-8 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center mx-auto mb-2"><span class="font-display font-bold text-violet-700 text-xs">4</span></div><h5 class="font-semibold text-black text-[10px]">Remove Bolt Carrier</h5></div>
                        <div class="p-3 rounded-xl bg-light-50 border border-violet-100 text-center"><div class="w-8 h-8 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center mx-auto mb-2"><span class="font-display font-bold text-violet-700 text-xs">5</span></div><h5 class="font-semibold text-black text-[10px]">Remove Handguard</h5></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MODULE 3: FIRING RANGE -->
    <section id="firing" class="relative py-24 md:py-32 overflow-hidden bg-light-50 grid-pattern">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-20 fade-in-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-violet-200 bg-violet-50 mb-6"><i class="fas fa-crosshairs text-violet-600 text-[10px]"></i><span class="text-[10px] font-mono font-medium tracking-[.3em] uppercase text-violet-700">Module 03</span></div>
                <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl tracking-tight text-black">Firing Range <span class="text-highlight">Simulation</span></h2>
            </div>
            <div class="max-w-7xl mx-auto fade-in-up">
                <div class="flex items-center justify-center gap-3 mb-6 flex-wrap">
                    <button class="weapon-btn active px-4 py-2 rounded-lg border-2 border-violet-600 bg-violet-700 text-white text-xs font-bold tracking-wider uppercase transition-all" data-weapon="9mm">9mm</button>
                    <button class="weapon-btn px-4 py-2 rounded-lg border-2 border-violet-200 bg-white text-gray-500 text-xs font-bold tracking-wider uppercase transition-all hover:border-violet-400" data-weapon="45">.45</button>
                    <button class="weapon-btn px-4 py-2 rounded-lg border-2 border-violet-200 bg-white text-gray-500 text-xs font-bold tracking-wider uppercase transition-all hover:border-violet-400" data-weapon="38">.38</button>
                    <button class="weapon-btn px-4 py-2 rounded-lg border-2 border-amber-200 bg-white text-gray-500 text-xs font-bold tracking-wider uppercase transition-all hover:border-amber-400" data-weapon="shotgun">Shotgun</button>
                </div>

                <input type="hidden" id="fr-config-weapon" value="9mm">
                <input type="hidden" id="fr-config-time" value="30">
                <input type="hidden" id="fr-config-mode" value="steady">

                <div id="game-area" class="relative rounded-2xl overflow-hidden glow-border bg-white shadow-xl" style="position:relative;background:#f8fafc;min-height:520px">
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
                                <p id="hud-timer" style="font-size:30px;font-weight:700;color:#fff;font-family:'Space Grotesk',sans-serif">30</p>
                            </div>
                            <div id="timer-controls" style="display:flex;align-items:center;gap:6px;background:rgba(3,3,7,0.8);backdrop-filter:blur(4px);border:1px solid rgba(124,58,237,0.5);border-radius:999px;padding:6px;pointer-events:auto;transition:opacity 0.25s ease, transform 0.25s ease">
                                <button onclick="openStartModal()" style="padding:5px 12px;background:#7C3AED;border:none;color:#fff;font-size:10px;font-weight:700;border-radius:999px;cursor:pointer;text-transform:uppercase;letter-spacing:0.05em;transition:background 0.2s"><i class="fas fa-play" style="margin-right:3px;font-size:8px"></i> Start</button>
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
                                <button onclick="closeEndOverlay()" style="flex:1;border:none;cursor:pointer;font-family:'Inter',sans-serif;font-weight:700;font-size:13px;padding:12px 24px;border-radius:12px;color:#7c3aed;border:1px solid #ede9fe;background:transparent;display:inline-flex;align-items:center;justify-content:center;gap:6px"><i class="fas fa-times" style="font-size:12px"></i> Close</button>
                            </div>
                        </div>
                    </div>

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
                                        <p class="text-[10px] text-violet-500" id="start-modal-time">30s &middot; Steady</p>
                                    </div>
                                    <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse flex-shrink-0"></div>
                                </div>
                            </div>
                            <div class="px-8 pb-8 flex items-center gap-3">
                                <button class="start-btn start-btn-cancel flex-1" type="button" onclick="closeStartModal()"><i class="fas fa-times text-xs"></i> Cancel</button>
                                <button class="start-btn start-btn-start flex-1" type="button" onclick="confirmStart()"><i class="fas fa-play text-xs"></i> Start Simulation</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-6 max-w-lg mx-auto">
                    <div class="p-4 rounded-xl bg-white glow-border text-center"><div class="text-xl font-display font-bold stat-number" id="totalShots">0</div><div class="text-[10px] text-gray-400 uppercase mt-1">Shots</div></div>
                    <div class="p-4 rounded-xl bg-white glow-border text-center"><div class="text-xl font-display font-bold stat-number" id="totalScore">0</div><div class="text-[10px] text-gray-400 uppercase mt-1">Score</div></div>
                    <div class="p-4 rounded-xl bg-white glow-border text-center"><div class="text-xl font-display font-bold stat-number" id="accuracy">0%</div><div class="text-[10px] text-gray-400 uppercase mt-1">Accuracy</div></div>
                </div>
                <div class="mt-4 text-center"><button onclick="resetFiringRange();openStartModal();" class="px-5 py-2 border-2 border-violet-200 text-violet-700 font-bold text-xs uppercase rounded hover:bg-violet-50 transition-all"><i class="fas fa-redo text-[10px] mr-1"></i> Reset</button></div>
            </div>
        </div>
    </section>

    <!-- ABOUT -->
    <section id="about" class="relative py-24 md:py-32 overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2 fade-in-up">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-violet-200 bg-violet-50 mb-6"><i class="fas fa-graduation-cap text-violet-600 text-[10px]"></i><span class="text-[10px] font-mono font-medium tracking-[.3em] uppercase text-violet-700">About</span></div>
                    <h2 class="font-display font-bold text-3xl md:text-4xl lg:text-5xl tracking-tight text-black mb-6">Built for <span class="text-highlight">Criminology</span> Students</h2>
                    <p class="text-gray-500 text-lg font-light leading-relaxed mb-6">IOT-BASED MARKSMANSHIP TRAINING SIMULATOR FOR SPC CRIMINOLOGY is an interactive marksmanship simulator designed for <span class="text-black font-medium">Southern de Oro Philippines College</span> criminology students who need firearms training without live-fire risks. The system covers the <strong class="text-black">9mm Pistol</strong>, <strong class="text-black">.45 Pistol</strong>, <strong class="text-black">.38 Pistol Revolver</strong>, and <strong class="text-black">12-Gauge Shotgun</strong>.</p>
                </div>
                <div class="lg:w-1/2 fade-in-up" style="transition-delay:.2s">
                    <div class="rounded-2xl overflow-hidden glow-border shadow-lg bg-gradient-to-br from-violet-50 to-white p-8 flex flex-col items-center">
                        <img src="{{ asset('images/assets/logo.png') }}" alt="SPC Logo" class="h-48 w-auto mb-6">
                        <h3 class="font-display font-bold text-xl text-black text-center">Southern de Oro Philippines College</h3>
                        <p class="text-sm text-violet-700 font-medium mt-1">SPC Criminology</p>
                        <p class="text-xs text-gray-400 mt-3 text-center">Julio Pacana St., Licuan, Cagayan de Oro City<br>Misamis Oriental, 9000</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- QUOTE -->
    <section class="relative py-20 overflow-hidden"><div class="absolute inset-0 animated-gradient"></div>
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10 fade-in-up">
            <i class="fas fa-quote-left text-white/20 text-4xl mb-6"></i>
            <blockquote class="font-display text-2xl md:text-3xl lg:text-4xl font-light text-white leading-snug tracking-tight mb-8">"Before you fire a weapon, you must <span class="text-violet-200 font-medium">know its parts</span>, <span class="text-violet-200 font-medium">build it properly</span>, and <span class="text-violet-200 font-medium">understand its power</span>. This simulator teaches all three."</blockquote>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="relative border-t border-violet-100 bg-white py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <a href="#" class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/assets/Marksmanship innovatech.png') }}" alt="SPC Logo" class="h-14 w-auto">
                        <div class="flex flex-col">
                            <span class="font-display font-bold text-lg text-black leading-none">IOT-BASED MARKSMANSHIP<span class="text-violet-700"> TRAINING SIMULATOR</span></span>
                            <span class="text-[9px] text-gray-400 tracking-wider uppercase font-medium">SPC CRIMINOLOGY</span>
                        </div>
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-sm">IOT-BASED MARKSMANSHIP TRAINING SIMULATOR FOR SPC CRIMINOLOGY — teaching 9mm Pistol, .45 Pistol, .38 Pistol Revolver, and 12-Gauge Shotgun parts, assembly, and marksmanship.</p>
                </div>
                <div>
                    <h4 class="font-display font-semibold text-black text-sm mb-4">Modules</h4>
                    <ul class="space-y-2">
                        <li><a href="#parts" class="text-gray-400 hover:text-violet-700 text-sm transition-colors">Gun Parts Education</a></li>
                        <li><a href="#assembly" class="text-gray-400 hover:text-violet-700 text-sm transition-colors">Assembly & Disassembly</a></li>
                        <li><a href="#firing" class="text-gray-400 hover:text-violet-700 text-sm transition-colors">Firing Range Simulation</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-display font-semibold text-black text-sm mb-4">Contact SPC</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2 text-gray-400 text-sm"><i class="fas fa-map-marker-alt text-violet-400 text-xs mt-1"></i><span>Julio Pacana St., Licuan, Cagayan de Oro City, 9000</span></li>
                        <li class="flex items-center gap-2 text-gray-400 text-sm"><i class="fas fa-phone text-violet-400 text-xs"></i>(088) 856 2609</li>
                        <li class="flex items-center gap-2 text-gray-400 text-sm"><i class="fas fa-envelope text-violet-400 text-xs"></i>registrar@spccdo.edu.ph</li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-violet-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-400 text-xs">&copy; 2025 IOT-BASED MARKSMANSHIP TRAINING SIMULATOR — Southern Philippines College. All rights reserved.</p>
                <div class="flex items-center gap-1 text-gray-400 text-xs"><span>SPC Criminology</span><span class="text-violet-400">•</span><span>SY 2024-2025</span></div>
            </div>
        </div>
    </footer>

    <script>
    // NAVBAR
    const navbar=document.getElementById('navbar');window.addEventListener('scroll',()=>{navbar.classList.toggle('nav-glass',window.pageYOffset>50)});
    // MOBILE MENU
    const menuToggle=document.getElementById('menuToggle'),mobileMenu=document.getElementById('mobileMenu'),menuLines=document.querySelectorAll('.menu-line');let menuOpen=false;menuToggle.addEventListener('click',()=>{menuOpen=!menuOpen;mobileMenu.classList.toggle('open');if(menuOpen){menuLines[0].style.transform='rotate(45deg) translate(4px,4px)';menuLines[1].style.opacity='0';menuLines[2].style.transform='rotate(-45deg) translate(4px,-4px)';menuLines[2].style.width='24px';document.body.style.overflow='hidden'}else{menuLines[0].style.transform='';menuLines[1].style.opacity='1';menuLines[2].style.transform='';menuLines[2].style.width='16px';document.body.style.overflow=''}});document.querySelectorAll('.mobile-link').forEach(l=>{l.addEventListener('click',()=>{menuOpen=false;mobileMenu.classList.remove('open');menuLines[0].style.transform='';menuLines[1].style.opacity='1';menuLines[2].style.transform='';menuLines[2].style.width='16px';document.body.style.overflow=''})});
    // SCROLL ANIM
    const obs=new IntersectionObserver(e=>{e.forEach(en=>{if(en.isIntersecting){en.target.classList.add('visible');obs.unobserve(en.target)}})},{rootMargin:'0px 0px -80px 0px',threshold:.1});document.querySelectorAll('.fade-in-up').forEach(el=>obs.observe(el));
    // SMOOTH SCROLL
    document.querySelectorAll('a[href^="#"]').forEach(a=>{a.addEventListener('click',function(e){e.preventDefault();const t=document.querySelector(this.getAttribute('href'));if(t)t.scrollIntoView({behavior:'smooth',block:'start'})})});
    // GUN PARTS TABS
    document.querySelectorAll('.tab-btn').forEach(btn=>{btn.addEventListener('click',()=>{document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));btn.classList.add('active');document.getElementById('tab-'+btn.dataset.tab).classList.add('active')})});
    // WEAPON SELECTOR + FIRING RANGE
    const weaponBtns = document.querySelectorAll('.weapon-btn');
    const weaponMeta = {'9mm':'9mm Pistol','45':'.45 Caliber','38':'.38 Pistol Revolver','shotgun':'12-Gauge Shotgun'};
    weaponBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            weaponBtns.forEach(b => {
                b.classList.remove('active','bg-violet-700','text-white','border-violet-600');
                b.classList.add('bg-white','text-gray-500','border-violet-200');
                if (b.dataset.weapon === 'shotgun') {
                    b.classList.remove('border-violet-200'); b.classList.add('border-amber-200');
                }
            });
            btn.classList.add('active','bg-violet-700','text-white','border-violet-600');
            btn.classList.remove('bg-white','text-gray-500','border-violet-200','border-amber-200');
            const w = btn.dataset.weapon;
            document.getElementById('fr-config-weapon').value = w;
            state.selectedWeapon = w;
            const wStats = weaponStats[w];
            state.currentAmmo = wStats.magSize;
            state.reserveAmmo = wStats.totalAmmo;
            document.getElementById('hud-weapon').innerText = wStats.name;
            document.getElementById('hud-ammo').innerText = wStats.magSize;
            document.getElementById('hud-reserve').innerText = '/ ' + wStats.totalAmmo;
            if (state.hasStarted) resetFiringRange();
            if (!state.hasStarted && !startModalOpen) openStartModal();
        });
    });

    const weaponStats = {
        '9mm': { name: '9mm Pistol', magSize: 15, totalAmmo: 45, reloadTime: 1500, recoil: 8, flashColor: 'rgba(255,200,50,0.4)', flashSize: '40%' },
        '45': { name: '.45 Caliber', magSize: 7, totalAmmo: 28, reloadTime: 2200, recoil: 15, flashColor: 'rgba(255,150,50,0.5)', flashSize: '50%' },
        '38': { name: '.38 Pistol Revolver', magSize: 6, totalAmmo: 24, reloadTime: 1800, recoil: 12, flashColor: 'rgba(255,215,140,0.55)', flashSize: '45%' },
        'shotgun': { name: '12-Gauge Shotgun', magSize: 5, totalAmmo: 20, reloadTime: 2500, recoil: 20, flashColor: 'rgba(255,180,50,0.6)', flashSize: '55%' }
    };
    const configWeapon = document.getElementById('fr-config-weapon').value;
    const configTime = parseInt(document.getElementById('fr-config-time').value, 10);
    const configMode = document.getElementById('fr-config-mode').value;

    const state = {
        selectedWeapon: configWeapon, targetMode: configMode, selectedTime: configTime,
        score: 0, timeLeft: configTime, currentAmmo: weaponStats[configWeapon].magSize,
        reserveAmmo: weaponStats[configWeapon].totalAmmo, isReloading: false,
        isPlaying: false, hasStarted: false, totalShots: 0, hits: 0, bullseyes: 0
    };
    let gameTimer = null, countdownTimer = null, activeTarget = null, lastFrameTime = performance.now();
    const gameArea = document.getElementById('game-area');
    const targetContainer = document.getElementById('target-container');
    const crosshair = document.getElementById('crosshair');
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
    function createNoiseBuffer(d) { const size = audioCtx.sampleRate * d; const buf = audioCtx.createBuffer(1, size, audioCtx.sampleRate); const data = buf.getChannelData(0); for (let i = 0; i < size; i++) data[i] = Math.random() * 2 - 1; return buf; }
    const noiseBuffer = createNoiseBuffer(0.5);
    function playSound(filterType, freq, q, dur, vol) {
        if (audioCtx.state === 'suspended') audioCtx.resume();
        const s = audioCtx.createBufferSource(); s.buffer = noiseBuffer;
        const f = audioCtx.createBiquadFilter(); f.type = filterType; f.frequency.setValueAtTime(freq, audioCtx.currentTime); f.Q.setValueAtTime(q, audioCtx.currentTime);
        const g = audioCtx.createGain(); g.gain.setValueAtTime(vol, audioCtx.currentTime); g.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + dur);
        s.connect(f); f.connect(g); g.connect(audioCtx.destination); s.start(); s.stop(audioCtx.currentTime + dur);
    }
    function addLowThump(freq, dur, vol) {
        const o = audioCtx.createOscillator(); const g = audioCtx.createGain();
        o.type = 'sine'; o.frequency.setValueAtTime(freq, audioCtx.currentTime); o.frequency.exponentialRampToValueAtTime(20, audioCtx.currentTime + dur);
        g.gain.setValueAtTime(vol, audioCtx.currentTime); g.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + dur);
        o.connect(g); g.connect(audioCtx.destination); o.start(); o.stop(audioCtx.currentTime + dur);
    }
    function playGunshot() {
        const w = state.selectedWeapon;
        if (w === '9mm') { playSound('bandpass',2000,1,0.12,0.6); addLowThump(150,0.08,0.8); }
        else if (w === '45') { playSound('lowpass',800,2,0.25,1.0); addLowThump(80,0.15,1.2); }
        else if (w === '38') { playSound('bandpass',1300,1.4,0.18,0.7); addLowThump(110,0.11,0.95); }
        else if (w === 'shotgun') { playSound('lowpass',400,3,0.35,1.2); addLowThump(50,0.2,1.4); }
    }
    function playHitSound() {
        const o = audioCtx.createOscillator(); const g = audioCtx.createGain();
        o.type = 'sine'; o.frequency.setValueAtTime(1200, audioCtx.currentTime); o.frequency.exponentialRampToValueAtTime(600, audioCtx.currentTime + 0.1);
        g.gain.setValueAtTime(0.2, audioCtx.currentTime); g.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1);
        o.connect(g); g.connect(audioCtx.destination); o.start(); o.stop(audioCtx.currentTime + 0.1);
    }
    function playEmptyClickSound() { playSound('highpass',4000,1,0.02,0.3); }

    let startModalOpen = false;
    function openStartModal() {
        if (state.hasStarted || startModalOpen) return;
        const overlay = document.getElementById('start-modal-overlay');
        document.getElementById('start-modal-weapon').innerText = weaponStats[state.selectedWeapon].name;
        document.getElementById('start-modal-time').innerText = state.selectedTime + 's \u00b7 ' + (state.targetMode.charAt(0).toUpperCase() + state.targetMode.slice(1));
        overlay.classList.remove('closing'); overlay.classList.add('active');
        startModalOpen = true;
    }
    function closeStartModal() {
        if (!startModalOpen) return;
        const overlay = document.getElementById('start-modal-overlay');
        overlay.classList.add('closing'); overlay.classList.remove('active');
        startModalOpen = false;
        setTimeout(() => overlay.classList.remove('closing'), 280);
    }
    function confirmStart() { closeStartModal(); startFiringRangeFromControl(); }

    function showCountInOverlay(val) { countInNumber.innerText = val; countInOverlay.style.opacity = '1'; countInOverlay.style.visibility = 'visible'; }
    function hideCountInOverlay() { countInOverlay.style.opacity = '0'; countInOverlay.style.visibility = 'hidden'; }

    function startFiringRangeFromControl() {
        if (state.hasStarted || countdownTimer || state.isPlaying) return;
        hideEndOverlay(); beginCountdown();
    }
    function beginCountdown() {
        const steps = ['3','2','1','Start!']; let idx = 0;
        showCountInOverlay(steps[idx]); setTimerControlsVisible(false);
        countdownTimer = setInterval(() => {
            idx++;
            if (idx < steps.length) { showCountInOverlay(steps[idx]); return; }
            clearInterval(countdownTimer); countdownTimer = null;
            hideCountInOverlay(); startFiring();
        }, 1000);
    }
    function startFiring() {
        state.isPlaying = true; state.hasStarted = true; state.timeLeft = state.selectedTime;
        gameArea.classList.add('cursor-hidden'); crosshair.style.display = 'block';
        timerControls.innerHTML = '<button onclick="endGame(\'Stopped manually\')" style="padding:5px 12px;background:#ef4444;border:none;color:#fff;font-size:10px;font-weight:700;border-radius:999px;cursor:pointer;text-transform:uppercase;letter-spacing:0.05em;transition:background 0.2s"><i class="fas fa-stop" style="margin-right:3px;font-size:8px"></i> Stop</button>';
        updateHUD(); setTimerControlsVisible(true);
        gameTimer = setInterval(() => {
            state.timeLeft--; updateHUD();
            if (state.timeLeft <= 0) endGame("Time's up!");
        }, 1000);
        spawnTarget(); lastFrameTime = performance.now();
        requestAnimationFrame(moveTargets);
    }
    function endGame(reason) {
        state.isPlaying = false;
        clearInterval(gameTimer); gameTimer = null;
        targetContainer.innerHTML = ''; activeTarget = null;
        gameArea.classList.remove('cursor-hidden'); crosshair.style.display = 'none';
        setTimerControlsVisible(true);
        document.getElementById('res-reason').innerText = reason || 'Simulation ended';
        document.getElementById('res-score').innerText = state.score;
        document.getElementById('res-accuracy').innerText = state.totalShots > 0 ? Math.round((state.hits / state.totalShots) * 100) + '%' : '0%';
        document.getElementById('res-bullseyes').innerText = state.bullseyes;
        setTimeout(() => {
            endOverlay.style.opacity = '1'; endOverlay.style.visibility = 'visible';
            endOverlay.querySelector('div:first-child').style.transform = 'scale(1) translateY(0)';
        }, 500);
        updateStatsGrid();
    }
    function hideEndOverlay() {
        endOverlay.style.opacity = '0'; endOverlay.style.visibility = 'hidden';
        const inner = endOverlay.querySelector('div:first-child');
        if (inner) inner.style.transform = 'scale(0.85) translateY(20px)';
    }
    function closeEndOverlay() { hideEndOverlay(); resetFiringRange(); }
    function restartGame() { resetFiringRange(); beginCountdown(); }
    function resetFiringRange() {
        clearInterval(gameTimer); gameTimer = null;
        clearInterval(countdownTimer); countdownTimer = null;
        hideCountInOverlay(); hideEndOverlay();
        const wStats = weaponStats[state.selectedWeapon];
        state.score = 0; state.timeLeft = state.selectedTime;
        state.currentAmmo = wStats.magSize; state.reserveAmmo = wStats.totalAmmo;
        state.isReloading = false; state.totalShots = 0; state.hits = 0; state.bullseyes = 0;
        state.isPlaying = false; state.hasStarted = false;
        targetContainer.innerHTML = ''; activeTarget = null;
        gameArea.classList.remove('cursor-hidden'); crosshair.style.display = 'none';
        timerControls.innerHTML = '<button onclick="openStartModal()" style="padding:5px 12px;background:#7C3AED;border:none;color:#fff;font-size:10px;font-weight:700;border-radius:999px;cursor:pointer;text-transform:uppercase;letter-spacing:0.05em;transition:background 0.2s"><i class="fas fa-play" style="margin-right:3px;font-size:8px"></i> Start</button>';
        setTimerControlsVisible(true); updateHUD(); updateStatsGrid();
    }
    function setTimerControlsVisible(vis) {
        timerControls.style.opacity = vis ? '1' : '0';
        timerControls.style.transform = vis ? 'scale(1)' : 'scale(0.85)';
        timerControls.style.pointerEvents = vis ? 'auto' : 'none';
    }
    function spawnTarget() {
        if (!state.isPlaying) return;
        targetContainer.innerHTML = ''; activeTarget = null;
        const targetEl = document.createElement('div');
        targetEl.classList.add('target-container');
        targetEl.innerHTML = '<div class="target" data-points="2"><div class="target-board"></div><div class="target-rings"><div class="target-ring delta" data-zone="delta" data-points="1"></div><div class="target-ring charlie" data-zone="charlie" data-points="3"></div><div class="target-ring bravo" data-zone="bravo" data-points="5"></div><div class="target-ring alpha" data-zone="alpha" data-points="10"></div><div class="target-bullseye" data-zone="bullseye" data-points="20"></div></div></div>';
        const bounds = gameArea.getBoundingClientRect();
        const targetWidth = 160, targetHeight = 160;
        if (state.targetMode === 'steady') {
            targetEl.style.left = (bounds.width / 2 - targetWidth / 2) + 'px';
            targetEl.style.top = (bounds.height / 2 - targetHeight / 2) + 'px';
            targetEl.timeoutId = setTimeout(() => removeTarget(targetEl, false), 4000);
        } else if (state.targetMode === 'sideways') {
            const y = (bounds.height / 2) - (targetHeight / 2);
            const startX = Math.random() > 0.5 ? -targetWidth : bounds.width;
            targetEl.style.left = startX + 'px'; targetEl.style.top = y + 'px';
            targetEl.dataset.vx = (3 + Math.random() * 4) * (startX < 0 ? 1 : -1);
            targetEl.dataset.vy = 0;
        } else if (state.targetMode === 'around') {
            targetEl.style.left = (Math.random() * (bounds.width - targetWidth - 200) + 100) + 'px';
            targetEl.style.top = (Math.random() * (bounds.height - targetHeight - 200) + 80) + 'px';
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
        const delta = (currentTime - lastFrameTime) / 16.667;
        lastFrameTime = currentTime;
        if (activeTarget && state.targetMode !== 'steady') {
            const bounds = gameArea.getBoundingClientRect();
            const tw = 160, th = 160;
            let vx = parseFloat(activeTarget.dataset.vx);
            let vy = parseFloat(activeTarget.dataset.vy);
            let left = parseFloat(activeTarget.style.left);
            let top = parseFloat(activeTarget.style.top);
            left += vx * delta; top += vy * delta;
            if (state.targetMode === 'sideways') {
                top = (bounds.height / 2) - (th / 2);
                if (left <= 0) { left = 0; activeTarget.dataset.vx = Math.abs(vx); }
                else if (left >= bounds.width - tw) { left = bounds.width - tw; activeTarget.dataset.vx = -Math.abs(vx); }
            } else if (state.targetMode === 'around') {
                if (left <= 0) { left = 0; activeTarget.dataset.vx = Math.abs(vx); }
                else if (left >= bounds.width - tw) { left = bounds.width - tw; activeTarget.dataset.vx = -Math.abs(vx); }
                if (top <= 0) { top = 0; activeTarget.dataset.vy = Math.abs(vy); }
                else if (top >= bounds.height - th - 120) { top = bounds.height - th - 120; activeTarget.dataset.vy = -Math.abs(vy); }
            }
            activeTarget.style.left = left + 'px';
            activeTarget.style.top = top + 'px';
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
            const hitEl = e.target.closest('[data-zone]');
            if (hitEl) {
                points = parseInt(hitEl.dataset.points || '2', 10);
                if (hitEl.dataset.zone === 'bullseye') state.bullseyes++;
            } else if (e.target.classList.contains('target-bullseye')) {
                points = 20; state.bullseyes++;
            }
            state.score += points; playHitSound();
            hitMarker.classList.remove('show'); void hitMarker.offsetWidth;
            hitMarker.classList.add('show');
            removeTarget(targetCheck.parentElement, true);
        } else { createBulletHole(e.clientX, e.clientY); }
        updateHUD(); updateStatsGrid();
        if (state.currentAmmo <= 0 && state.reserveAmmo <= 0) { setTimeout(() => endGame("Out of ammunition!"), 1000); }
        else if (state.currentAmmo <= 0) { initiateReload(); }
    }
    function createBulletHole(x, y) {
        const h = document.createElement('div'); h.classList.add('bullet-hole');
        h.style.left = x + 'px'; h.style.top = y + 'px'; document.body.appendChild(h);
        setTimeout(() => { h.style.transition = 'opacity 1s'; h.style.opacity = '0'; setTimeout(() => h.remove(), 1000); }, 3000);
    }
    function initiateReload() {
        const wStats = weaponStats[state.selectedWeapon];
        if (state.isReloading || state.currentAmmo === wStats.magSize || state.reserveAmmo <= 0 || !state.isPlaying) return;
        state.isReloading = true; btnReload.disabled = true; reloadUI.style.display = 'block';
        reloadFill.style.transition = 'width ' + wStats.reloadTime + 'ms linear';
        setTimeout(() => reloadFill.style.width = '100%', 50);
        setTimeout(() => {
            const needed = wStats.magSize - state.currentAmmo;
            const available = Math.min(needed, state.reserveAmmo);
            state.currentAmmo += available; state.reserveAmmo -= available;
            state.isReloading = false; btnReload.disabled = false;
            reloadFill.style.width = '0%';
            setTimeout(() => reloadUI.style.display = 'none', 300); updateHUD();
        }, wStats.reloadTime);
    }
    function updateHUD() {
        hudScore.innerText = state.score;
        hudTimer.innerText = state.timeLeft;
        hudAmmo.innerText = state.currentAmmo;
        hudReserve.innerText = '/ ' + state.reserveAmmo;
        const acc = state.totalShots > 0 ? Math.round((state.hits / state.totalShots) * 100) : 0;
        hudAccuracy.innerText = acc + '%';
        hudTimer.style.color = state.timeLeft <= 10 ? '#ef4444' : '#fff';
        hudAmmo.style.color = state.currentAmmo <= 3 && state.currentAmmo > 0 ? '#f59e0b' : (state.currentAmmo === 0 ? '#ef4444' : '#fff');
        btnReload.disabled = state.isReloading || state.currentAmmo === weaponStats[state.selectedWeapon].magSize || state.reserveAmmo <= 0;
    }
    function updateStatsGrid() {
        document.getElementById('totalShots').innerText = state.totalShots;
        document.getElementById('totalScore').innerText = state.score;
        const acc = state.totalShots > 0 ? Math.round((state.hits / state.totalShots) * 100) : 0;
        document.getElementById('accuracy').innerText = acc + '%';
    }

    document.addEventListener('mousemove', (e) => { crosshair.style.left = e.clientX + 'px'; crosshair.style.top = e.clientY + 'px'; });
    gameArea.addEventListener('mouseenter', () => { if (state.isPlaying) crosshair.style.display = 'block'; });
    gameArea.addEventListener('mouseleave', () => { crosshair.style.display = 'none'; });
    gameArea.addEventListener('mousedown', handleShot);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'r' || e.key === 'R') initiateReload();
        if (e.key === 'Escape' && startModalOpen) closeStartModal();
    });
    gameArea.addEventListener('contextmenu', e => e.preventDefault());

    hudWeapon.innerText = weaponStats[configWeapon].name;
    const iniW = weaponStats[configWeapon];
    if (iniW) {
        muzzleFlash.style.background = 'radial-gradient(ellipse at bottom, ' + iniW.flashColor + ' 0%, transparent 70%)';
        muzzleFlash.style.height = iniW.flashSize;
    }
    updateHUD(); updateStatsGrid();
    
    // CAROUSEL
    (function(){const c=document.getElementById('gunCarousel'),slides=c.querySelectorAll('.carousel-slide');let i=0;setInterval(()=>{slides[i].classList.remove('active');i=(i+1)%slides.length;slides[i].classList.add('active')},4000)})();
    // HERO PARALLAX
    const hv=document.getElementById('heroVisual');window.addEventListener('mousemove',e=>{if(window.innerWidth<768)return;hv.style.transform=`translate(${(e.clientX/window.innerWidth-.5)*15}px,${(e.clientY/window.innerHeight-.5)*15}px)`});
    </script>
</body>
</html>