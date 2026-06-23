<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VirtualArm — Interactive Marksmanship Simulator</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- NAVBAR -->
    <nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="#" class="flex items-center gap-3">
                <img src="{{ asset('images/assets/logo.png') }}" alt="SPC College of Criminology" class="h-12 w-auto">
                <div class="flex flex-col">
                    <span class="font-display font-bold text-lg tracking-tight text-black leading-none">Virtual<span class="text-violet-700">Arm</span></span>
                    <span class="text-[9px] text-gray-400 tracking-wider uppercase font-medium">SPC College of Criminology</span>
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
        <img src="{{ asset('images/assets/logo.png') }}" alt="SPC Logo" class="h-20 w-auto mb-4">
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
        <div class="relative z-10 max-w-7xl mx-auto px-6 pt-32 pb-20 w-full">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-3/5 text-center lg:text-left">
                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full border border-violet-200 bg-violet-50 mb-8">
                        <img src="{{ asset('images/assets/logo.png') }}" alt="SPC" class="h-7 w-auto">
                        <span class="text-xs font-mono font-medium tracking-wider uppercase text-violet-700">College of Criminology</span>
                    </div>
                    <h1 class="font-display font-bold tracking-tight leading-[.95]">
                        <span class="block text-5xl md:text-7xl lg:text-8xl text-black">Virtual</span>
                        <span class="block text-5xl md:text-7xl lg:text-8xl text-highlight">Arm</span>
                    </h1>
                    <p class="mt-4 text-base md:text-lg font-medium text-violet-700 tracking-wide">Interactive Marksmanship Simulator for Criminology Students</p>
                    <p class="mt-4 text-lg md:text-xl font-light text-gray-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Master firearm knowledge — from <span class="text-black font-medium">parts identification</span> to <span class="text-black font-medium">assembly & disassembly</span>, then test your aim in a <span class="text-black font-medium">virtual firing range</span>.
                    </p>
                    <div class="flex flex-wrap items-center gap-3 mt-8 justify-center lg:justify-start">
                        <span class="px-4 py-2 rounded-full bg-violet-100 border border-violet-200 text-xs font-semibold text-violet-700 flex items-center gap-2"><i class="fas fa-gun text-[10px]"></i> 9mm Pistol</span>
                        <span class="px-4 py-2 rounded-full bg-violet-100 border border-violet-200 text-xs font-semibold text-violet-700 flex items-center gap-2"><i class="fas fa-gun text-[10px]"></i> .45 Pistol</span>
                        <span class="px-4 py-2 rounded-full bg-violet-100 border border-violet-200 text-xs font-semibold text-violet-700 flex items-center gap-2"><i class="fas fa-gun text-[10px]"></i> .38 Pistol Revolver</span>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-4 mt-10 justify-center lg:justify-start">
                        <a href="/login" class="btn-shine group px-8 py-3.5 bg-violet-700 hover:bg-violet-800 text-white font-bold text-sm tracking-wider uppercase rounded transition-all duration-300 hover:shadow-[0_4px_25px_rgba(91,33,182,.4)] flex items-center gap-3"><span>Get Started</span><i class="fas fa-arrow-right text-violet-300 group-hover:translate-x-1 transition-transform"></i></a>
                        <a href="#modules" class="group px-8 py-3.5 border-2 border-violet-200 hover:border-violet-400 text-violet-700 hover:text-violet-800 font-bold text-sm tracking-wider uppercase rounded transition-all duration-300 hover:bg-violet-50 flex items-center gap-3"><span>Explore Modules</span></a>
                    </div>
                </div>
                <div class="lg:w-2/5 relative flex items-center justify-center">
                    <div class="relative w-full max-w-md float-animation" id="heroVisual">
                        <div class="relative rounded-2xl overflow-hidden glow-border bg-gradient-to-br from-violet-50 to-violet-100 p-6">
                            <img src="/images/assets/9mm.png" alt="9mm Pistol" class="w-full h-48 object-contain gun-img mb-4" loading="lazy">
                            <img src="/images/assets/.45.png" alt=".45 Pistol" class="w-full h-48 object-contain gun-img mb-4" loading="lazy">
                            <img src="/images/assets/38.png" alt=".38 Pistol Revolver" class="w-full h-48 object-contain gun-img mb-4" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2">
            <span class="text-[10px] uppercase tracking-[.3em] text-gray-400">Scroll</span>
            <div class="w-5 h-8 border-2 border-violet-300 rounded-full flex justify-center pt-1.5"><div class="w-1 h-2 bg-violet-600 rounded-full animate-bounce"></div></div>
        </div>
    </section>

    <!-- MARQUEE -->
    <div class="relative border-y border-violet-100 bg-violet-50/50 py-4 overflow-hidden">
        <div class="marquee-track flex items-center gap-12 whitespace-nowrap">
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-gun text-violet-600"></i> 9mm Pistol</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-gun text-violet-600"></i> .45 Pistol</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-gun text-violet-600"></i> .38 Pistol Revolver</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-wrench text-violet-600"></i> Assembly Training</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-bullseye text-violet-600"></i> Firing Simulation</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-graduation-cap text-violet-600"></i> SPC Criminology</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-gun text-violet-600"></i> 9mm Pistol</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-gun text-violet-600"></i> .45 Pistol</span>
            <span class="text-xs uppercase tracking-[.4em] text-violet-400 flex items-center gap-3"><i class="fas fa-gun text-violet-600"></i> .38 Pistol Revolver</span>
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
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">Explore detailed descriptions of every component of the <strong class="text-black">9mm Pistol</strong>, <strong class="text-black">.45 Pistol</strong>, and <strong class="text-black">.38 Pistol Revolver</strong> with actual images and interactive diagrams.</p>
                </div>
                <div class="feature-card group p-8 md:p-10 rounded-2xl bg-white glow-border hover:shadow-xl transition-all duration-500 fade-in-up relative overflow-hidden" style="transition-delay:.15s">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-violet-600 to-violet-300 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                    <div class="w-14 h-14 rounded-xl bg-violet-100 border border-violet-200 flex items-center justify-center mb-6 group-hover:bg-violet-200 transition-colors"><i class="fas fa-wrench text-violet-700 text-xl"></i></div>
                    <span class="text-[10px] font-mono font-bold text-violet-500 tracking-widest">MODULE 02</span>
                    <h3 class="font-display font-bold text-2xl text-black mt-2 mb-3">Assembly & Disassembly</h3>
                    <div class="feature-line h-0.5 bg-gradient-to-r from-violet-600 to-transparent mb-4"></div>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">Learn step-by-step field-stripping and reassembling the <strong class="text-black">9mm</strong>, <strong class="text-black">.45</strong>, and <strong class="text-black">.38</strong>.</p>
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
                    <p class="text-gray-500 text-lg font-light leading-relaxed mb-8">Before holding a firearm, criminology students must understand every component — its name, location, purpose, and how it contributes to the weapon's operation.</p>
                </div>
                <div class="lg:w-3/5 fade-in-up" style="transition-delay:.2s">
                    <div class="flex gap-3 mb-8 flex-wrap">
                        <button class="tab-btn active px-5 py-2.5 rounded-lg border border-violet-200 text-xs font-bold tracking-wider uppercase transition-all" data-tab="pistol9">9mm Pistol</button>
                        <button class="tab-btn px-5 py-2.5 rounded-lg border border-violet-200 text-xs font-bold tracking-wider uppercase text-gray-500 hover:text-violet-700 transition-all" data-tab="pistol45">.45 Pistol</button>
                        <button class="tab-btn px-5 py-2.5 rounded-lg border border-violet-200 text-xs font-bold tracking-wider uppercase text-gray-500 hover:text-violet-700 transition-all" data-tab="38">.38 Pistol Revolver</button>
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

    <!-- MODULE 3: FIRING RANGE (Same as before) -->
    <section id="firing" class="relative py-24 md:py-32 overflow-hidden bg-light-50 grid-pattern">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-20 fade-in-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-violet-200 bg-violet-50 mb-6"><i class="fas fa-crosshairs text-violet-600 text-[10px]"></i><span class="text-[10px] font-mono font-medium tracking-[.3em] uppercase text-violet-700">Module 03</span></div>
                <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl tracking-tight text-black">Firing Range <span class="text-highlight">Simulation</span></h2>
            </div>
            <div class="max-w-5xl mx-auto fade-in-up">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <button class="weapon-btn active px-4 py-2 rounded-lg border-2 border-violet-600 bg-violet-700 text-white text-xs font-bold tracking-wider uppercase transition-all" data-weapon="9mm">9mm</button>
                    <button class="weapon-btn px-4 py-2 rounded-lg border-2 border-violet-200 bg-white text-gray-500 text-xs font-bold tracking-wider uppercase transition-all hover:border-violet-400" data-weapon="45">.45</button>
                    <button class="weapon-btn px-4 py-2 rounded-lg border-2 border-violet-200 bg-white text-gray-500 text-xs font-bold tracking-wider uppercase transition-all hover:border-violet-400" data-weapon="38">.38</button>
                </div>
                <div class="relative rounded-2xl overflow-hidden glow-border bg-white shadow-xl sim-area" id="simScreen">
                    <div class="absolute top-0 left-0 right-0 z-20 px-6 py-3 bg-black/60 backdrop-blur-sm flex items-center justify-between">
                        <div class="flex items-center gap-4"><span class="font-mono text-[10px] text-violet-300 tracking-widest">VIRTUALRANGE</span><span class="font-mono text-[10px] text-gray-400" id="weaponLabel">9mm PISTOL</span><span class="font-mono text-[10px] text-gray-400" id="distLabel">15m</span></div>
                        <div class="flex items-center gap-4"><span class="font-mono text-[10px] text-gray-300" id="shotCount">SHOTS: 0</span><span class="font-mono text-[10px] text-gray-300" id="scoreDisplay">SCORE: 0</span></div>
                    </div>
                    <div class="relative bg-gradient-to-b from-gray-100 to-gray-200 aspect-video flex items-center justify-center">
                        <div class="relative" id="targetArea">
                            <svg viewBox="0 0 300 300" class="w-48 h-48 md:w-64 md:h-64" id="targetSVG">
                                <circle cx="150" cy="150" r="140" fill="white" stroke="#ccc"/><circle cx="150" cy="150" r="100" fill="white" stroke="#ccc"/><circle cx="150" cy="150" r="60" fill="#3B0F8F" stroke="#5B21B6" opacity=".8"/><circle cx="150" cy="150" r="25" fill="#5B21B6"/><circle cx="150" cy="150" r="4" fill="#5B21B6"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none"><svg viewBox="0 0 100 100" class="w-20 h-20 opacity-0 transition-opacity duration-300" id="crosshair"><line x1="50" y1="10" x2="50" y2="40" stroke="#EF4444" stroke-width="1.5"/><line x1="50" y1="60" x2="50" y2="90" stroke="#EF4444" stroke-width="1.5"/><line x1="10" y1="50" x2="40" y2="50" stroke="#EF4444" stroke-width="1.5"/><line x1="60" y1="50" x2="90" y2="50" stroke="#EF4444" stroke-width="1.5"/><circle cx="50" cy="50" r="3" fill="none" stroke="#EF4444" stroke-width="1"/></svg></div>
                        </div>
                        <div id="muzzleFlash" class="absolute bottom-8 left-1/2 -translate-x-1/2 w-16 h-16 rounded-full bg-yellow-400/0 pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 right-0 z-20 px-6 py-3 bg-black/60 backdrop-blur-sm"><span class="font-mono text-[10px] text-gray-400">CLICK TARGET TO FIRE</span></div>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 mt-6">
                    <div class="p-4 rounded-xl bg-white glow-border text-center"><div class="text-xl font-display font-bold stat-number" id="totalShots">0</div><div class="text-[10px] text-gray-400 uppercase mt-1">Shots</div></div>
                    <div class="p-4 rounded-xl bg-white glow-border text-center"><div class="text-xl font-display font-bold stat-number" id="totalScore">0</div><div class="text-[10px] text-gray-400 uppercase mt-1">Score</div></div>
                    <div class="p-4 rounded-xl bg-white glow-border text-center"><div class="text-xl font-display font-bold stat-number" id="avgScore">0</div><div class="text-[10px] text-gray-400 uppercase mt-1">Avg</div></div>
                    <div class="p-4 rounded-xl bg-white glow-border text-center"><div class="text-xl font-display font-bold stat-number" id="accuracy">0%</div><div class="text-[10px] text-gray-400 uppercase mt-1">Accuracy</div></div>
                </div>
                <div class="mt-4 text-center"><button id="resetBtn" class="px-5 py-2 border-2 border-violet-200 text-violet-700 font-bold text-xs uppercase rounded hover:bg-violet-50 transition-all"><i class="fas fa-redo text-[10px] mr-1"></i> Reset</button></div>
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
                    <p class="text-gray-500 text-lg font-light leading-relaxed mb-6">VirtualArm is an interactive marksmanship simulator designed for <span class="text-black font-medium">Southern Philippines College</span> criminology students who need firearms training without live-fire risks. The system covers the <strong class="text-black">9mm Pistol</strong>, <strong class="text-black">.45 Pistol</strong>, and <strong class="text-black">.38 Pistol Revolver</strong>.</p>
                </div>
                <div class="lg:w-1/2 fade-in-up" style="transition-delay:.2s">
                    <div class="rounded-2xl overflow-hidden glow-border shadow-lg bg-gradient-to-br from-violet-50 to-white p-8 flex flex-col items-center">
                        <img src="{{ asset('images/assets/logo.png') }}" alt="SPC Logo" class="h-48 w-auto mb-6">
                        <h3 class="font-display font-bold text-xl text-black text-center">Southern Philippines College</h3>
                        <p class="text-sm text-violet-700 font-medium mt-1">College of Criminology</p>
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
            <blockquote class="font-display text-2xl md:text-3xl lg:text-4xl font-light text-white leading-snug tracking-tight mb-8">"Before you fire a weapon, you must <span class="text-violet-200 font-medium">know its parts</span>, <span class="text-violet-200 font-medium">build it properly</span>, and <span class="text-violet-200 font-medium">understand its power</span>. VirtualArm teaches all three."</blockquote>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="relative border-t border-violet-100 bg-white py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <a href="#" class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/assets/logo.png') }}" alt="SPC Logo" class="h-14 w-auto">
                        <div class="flex flex-col">
                            <span class="font-display font-bold text-lg text-black leading-none">Virtual<span class="text-violet-700">Arm</span></span>
                            <span class="text-[9px] text-gray-400 tracking-wider uppercase font-medium">College of Criminology</span>
                        </div>
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-sm">Interactive Marksmanship Simulator for Criminology Students — teaching 9mm Pistol, .45 Pistol, and .38 Pistol Revolver parts, assembly, and marksmanship.</p>
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
                <p class="text-gray-400 text-xs">&copy; 2025 VirtualArm — Southern Philippines College. All rights reserved.</p>
                <div class="flex items-center gap-1 text-gray-400 text-xs"><span>College of Criminology</span><span class="text-violet-400">•</span><span>SY 2024-2025</span></div>
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
    // WEAPON SELECTOR
    const weaponBtns=document.querySelectorAll('.weapon-btn'),weaponLabel=document.getElementById('weaponLabel'),distLabel=document.getElementById('distLabel'),weaponNames={'9mm':'9mm PISTOL','45':'.45 PISTOL','38':'.38 PISTOL'},weaponDist={'9mm':'15m','45':'15m','38':'15m'};weaponBtns.forEach(btn=>{btn.addEventListener('click',()=>{weaponBtns.forEach(b=>{b.classList.remove('active','bg-violet-700','text-white','border-violet-600');b.classList.add('bg-white','text-gray-500','border-violet-200')});btn.classList.add('active','bg-violet-700','text-white','border-violet-600');btn.classList.remove('bg-white','text-gray-500','border-violet-200');weaponLabel.textContent=weaponNames[btn.dataset.weapon];distLabel.textContent=weaponDist[btn.dataset.weapon]})});
    // FIRING RANGE
    const simScreen=document.getElementById('simScreen'),targetArea=document.getElementById('targetArea'),crosshair=document.getElementById('crosshair'),targetSVG=document.getElementById('targetSVG'),muzzleFlash=document.getElementById('muzzleFlash');let shots=0,totalScoreVal=0;simScreen.addEventListener('mousemove',e=>{const r=targetArea.getBoundingClientRect();crosshair.style.opacity=(e.clientX>=r.left&&e.clientX<=r.right&&e.clientY>=r.top&&e.clientY<=r.bottom)?'1':'0'});simScreen.addEventListener('mouseleave',()=>{crosshair.style.opacity='0'});simScreen.addEventListener('click',e=>{const r=targetArea.getBoundingClientRect(),x=e.clientX-r.left,y=e.clientY-r.top;if(x<0||x>r.width||y<0||y>r.height)return;const cx=r.width/2,cy=r.height/2,dist=Math.sqrt((x-cx)**2+(y-cy)**2),max=r.width/2;let score=0;if(dist<max*.05)score=10;else if(dist<max*.12)score=9;else if(dist<max*.22)score=7;else if(dist<max*.35)score=5;else if(dist<max*.52)score=3;else if(dist<max*.75)score=1;shots++;totalScoreVal+=score;const ns="http://www.w3.org/2000/svg",sx=(x/r.width)*300,sy=(y/r.height)*300;const hole=document.createElementNS(ns,"circle");hole.setAttribute("cx",sx);hole.setAttribute("cy",sy);hole.setAttribute("r","3");hole.setAttribute("fill","#1a1a1a");hole.setAttribute("stroke","#333");hole.setAttribute("stroke-width",".5");targetSVG.appendChild(hole);muzzleFlash.style.background='radial-gradient(circle,rgba(255,200,50,.6) 0%,transparent 70%)';setTimeout(()=>{muzzleFlash.style.background='rgba(255,200,50,0)'},150);document.getElementById('shotCount').textContent='SHOTS: '+shots;document.getElementById('scoreDisplay').textContent='SCORE: '+totalScoreVal;document.getElementById('totalShots').textContent=shots;document.getElementById('totalScore').textContent=totalScoreVal;document.getElementById('avgScore').textContent=shots>0?(totalScoreVal/shots).toFixed(1):'0';document.getElementById('accuracy').textContent=shots>0?Math.round((totalScoreVal/(shots*10))*100)+'%':'0%'});document.getElementById('resetBtn').addEventListener('click',()=>{shots=0;totalScoreVal=0;targetSVG.querySelectorAll('circle[fill="#1a1a1a"]').forEach(el=>el.remove());document.getElementById('shotCount').textContent='SHOTS: 0';document.getElementById('scoreDisplay').textContent='SCORE: 0';document.getElementById('totalShots').textContent='0';document.getElementById('totalScore').textContent='0';document.getElementById('avgScore').textContent='0';document.getElementById('accuracy').textContent='0%'});
    
    // HERO PARALLAX
    const hv=document.getElementById('heroVisual');window.addEventListener('mousemove',e=>{if(window.innerWidth<768)return;hv.style.transform=`translate(${(e.clientX/window.innerWidth-.5)*15}px,${(e.clientY/window.innerHeight-.5)*15}px)`});
    </script>
</body>
</html>