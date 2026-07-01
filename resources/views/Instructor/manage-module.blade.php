@extends('Instructor.layout')

@section('title', 'Manage Lessons')
@section('pageTitle', 'List of Modules')
@section('pageSubtitle', 'Training modules available for instructor-led sessions')

@section('content')
    <style>
        .portal-card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;box-shadow:0 4px 14px -8px rgba(30,5,82,.1)}
        .chip{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:9999px;font-size:11px;font-weight:700;letter-spacing:.04em}
        .module-launch{box-shadow:0 4px 12px -4px rgba(124,58,237,.45)}
        .ml-pulse-dot{display:inline-block;width:8px;height:8px;border-radius:50%;background:#22c55e;box-shadow:0 0 0 0 rgba(34,197,94,.7);animation:mlPulse 1.6s infinite}
        @keyframes mlPulse{0%{box-shadow:0 0 0 0 rgba(34,197,94,.7)}70%{box-shadow:0 0 0 10px rgba(34,197,94,0)}100%{box-shadow:0 0 0 0 rgba(34,197,94,0)}}
        .presentation-shell{background:linear-gradient(180deg,rgba(255,255,255,.96),rgba(248,250,252,.98));border:1px solid #ddd6fe;border-radius:28px;box-shadow:0 26px 70px -28px rgba(30,5,82,.2);overflow:hidden;min-height:clamp(760px,86vh,980px);display:flex;flex-direction:column}
        .presentation-stage{position:relative;flex:1;min-height:0;height:100%}
        .presentation-page{position:absolute;inset:0;padding:24px 28px;opacity:0;transform:translateX(18px);pointer-events:none;transition:opacity .28s ease,transform .28s ease;display:flex;flex-direction:column;min-height:0;overflow:hidden}
        .presentation-page.active{opacity:1;transform:translateX(0);pointer-events:auto}
        .presentation-content{display:flex;flex-direction:column;justify-content:flex-start;gap:1rem;min-height:0;flex:1;overflow:hidden;position:relative}
        .presentation-content .layer-delete-zone,
        .presentation-content .layer-handle{display:none!important}
        section.presentation-page[data-lesson="result"]{overflow:hidden auto!important}
        section.presentation-page[data-lesson="result"] .presentation-content{overflow:hidden auto!important}
        .presentation-kicker{font-size:11px;letter-spacing:.28em;text-transform:uppercase;font-weight:800;color:#7c3aed}
        .presentation-nav{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
        .presentation-btn{display:inline-flex;align-items:center;gap:.5rem;border-radius:14px;border:1px solid #ddd6fe;background:#fff;padding:12px 16px;font-size:14px;font-weight:800;color:#6d28d9;transition:all .2s}
        .presentation-btn:hover{background:#f5f3ff;border-color:#c4b5fd;transform:translateY(-1px)}
        .presentation-btn:disabled{opacity:.45;cursor:not-allowed;transform:none}
        .presentation-page-counter{font-size:13px;font-weight:700;color:#6d28d9}
        .presentation-wrapper{display:flex;gap:24px;align-items:flex-start;width:100%}
        .presentation-wrapper .presentation-shell{flex:1;min-width:0}
        @media(max-width:1023px){.presentation-wrapper{flex-direction:column;gap:16px}}
        .cp-sidebar{flex:0 0 380px;position:relative;padding:20px 0 20px 18px;display:flex;flex-direction:column;gap:24px;overflow:hidden;border-radius:16px}
        @media(max-width:1023px){.cp-sidebar{flex:1 1 auto;width:100%;padding:20px 0 20px 36px;margin-bottom:0;gap:16px}}
        .cp-track{position:absolute;top:0;bottom:0;left:37px;width:2.5px;border-radius:4px;background:#e9d5ff;overflow:hidden;z-index:0;transform:translateX(-50%)}
        .cp-track-fill{position:absolute;top:0;left:0;width:100%;height:0%;background:linear-gradient(180deg,#a78bfa,#7c3aed);border-radius:4px;transition:height .6s cubic-bezier(.4,0,.2,1)}
        .cp-module{position:relative;z-index:1;display:flex;align-items:flex-start;gap:14px;flex-shrink:0}
        .cp-node{width:38px;height:38px;min-width:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;font-family:'Rajdhani','Inter',sans-serif;border:2.5px solid;position:relative;z-index:2;background:#fff;transition:all .35s cubic-bezier(.4,0,.2,1);flex-shrink:0}
        .cp-node.completed{background:#7c3aed;border-color:#7c3aed;color:#fff;box-shadow:0 0 0 4px rgba(124,58,237,.12),0 4px 18px -6px rgba(124,58,237,.45)}
        .cp-node.completed .cp-node-check{width:16px;height:16px}
        .cp-node.completed .cp-node-glow{position:absolute;inset:-6px;border-radius:50%;background:radial-gradient(circle,rgba(124,58,237,.25) 0%,transparent 70%);animation:cpGlowPulse 2.5s ease-in-out infinite;pointer-events:none}
        .cp-node.in-progress{background:#fff;border-color:#7c3aed;color:#7c3aed;box-shadow:0 0 0 4px rgba(124,58,237,.10),0 4px 16px -6px rgba(124,58,237,.35)}
        .cp-node.in-progress .cp-node-pulse{position:absolute;inset:-8px;border-radius:50%;border:2px solid rgba(124,58,237,.2);animation:cpPulseRing 2s ease-in-out infinite;pointer-events:none}
        @keyframes cpGlowPulse{0%,100%{opacity:.6;transform:scale(1)}50%{opacity:1;transform:scale(1.15)}}
        @keyframes cpPulseRing{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.25);opacity:0}}
        .cp-card{flex:1;background:#fff;border:1px solid #e9e3f5;border-radius:16px;overflow:hidden;box-shadow:0 2px 10px -4px rgba(90,30,150,.08);transition:box-shadow .25s}
        .cp-card:hover{box-shadow:0 6px 22px -8px rgba(90,30,150,.12)}
        .cp-card-header{display:flex;align-items:center;gap:14px;padding:14px 16px;cursor:pointer;user-select:none;transition:background .2s;position:relative}
        .cp-card-header:hover{background:#faf9ff}
        .cp-icon-box{width:40px;height:40px;min-width:40px;border-radius:12px;background:linear-gradient(135deg,#f5f0ff,#ede5ff);display:flex;align-items:center;justify-content:center;color:#7c3aed;font-size:17px;flex-shrink:0}
        .cp-module.completed .cp-icon-box{background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff}
        .cp-card-info{flex:1;min-width:0}
        .cp-card-title{font-family:'Rajdhani','Inter',sans-serif;font-size:17px;font-weight:700;color:#2d1b69;line-height:1.25;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .cp-card-meta{display:flex;align-items:center;gap:6px;font-size:12px;color:#a78bfa;margin-top:2px}
        .cp-card-meta .dot{color:#d8b4fe}
        .cp-progress-bar{width:64px;min-width:64px;height:3px;border-radius:4px;background:#ede9f7;overflow:hidden}
        .cp-progress-fill{height:100%;border-radius:4px;background:linear-gradient(90deg,#a78bfa,#7c3aed);transition:width .6s cubic-bezier(.4,0,.2,1)}
        .cp-chevron{width:28px;height:28px;border:none;background:transparent;color:#c4b5fd;cursor:pointer;display:flex;align-items:center;justify-content:center;border-radius:8px;transition:all .3s ease;font-size:14px;flex-shrink:0}
        .cp-chevron:hover{background:#f5f0ff;color:#7c3aed}
        .cp-chevron.rotated{transform:rotate(180deg)}
        .cp-card-body{max-height:0;overflow:hidden;transition:max-height .35s cubic-bezier(.16,1,.3,1),opacity .3s;opacity:0;border-top:0 solid #e9e3f5}
        .cp-card-body.open{max-height:600px;opacity:1;border-top-width:1px;border-style:solid}
        .cp-lesson{display:flex;align-items:center;gap:10px;padding:10px 16px;transition:all .2s;border-left:3px solid transparent;cursor:pointer}
        .cp-lesson:hover{background:#faf9ff}
        .cp-lesson.active{border-left-color:#7c3aed;background:linear-gradient(90deg,rgba(124,58,237,.05),transparent)}
        .cp-lesson-icon{width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;color:#9188a5}
        .cp-lesson.completed .cp-lesson-icon{color:#22c55e}
        .cp-lesson.active .cp-lesson-icon{color:#7c3aed}
        .cp-lesson-title{flex:1;font-size:13px;font-weight:500;color:#3d2a6b;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .cp-badge{font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:.02em;text-transform:uppercase;flex-shrink:0;line-height:1.4}
        .cp-badge-lavender{background:#ede5ff;color:#7c3aed}
        .cp-badge-yellow{background:#fef7e6;color:#d97706}
        .cp-badge-green{background:#dcfce7;color:#16a34a}
        @media(max-width:1023px){.cp-track{left:55px}}
        @media(max-width:767px){.cp-sidebar{padding:16px 0 16px 34px}.cp-track{left:49px;top:38px;bottom:38px}.cp-node{width:30px;height:30px;min-width:30px;font-size:12px}.cp-card-header{padding:12px 14px;gap:10px;flex-wrap:wrap}.cp-icon-box{width:34px;height:34px;min-width:34px;font-size:15px}.cp-card-title{font-size:15px}.cp-progress-bar{width:48px;min-width:48px}.cp-card-body.open{max-height:900px}.cp-lesson{padding:9px 14px}}
    </style>

    <div class="flex items-center gap-2 mb-6 border-b border-gray-200 pb-0">
        <button type="button" class="ml-tab-btn px-5 py-3 text-sm font-bold rounded-t-xl transition-all bg-violet-700 text-white" data-tab="lessons">
            <i class="fas fa-book-open mr-2"></i> View Lessons and Activity
        </button>
        <button type="button" class="ml-tab-btn px-5 py-3 text-sm font-bold rounded-t-xl transition-all text-gray-600 hover:text-violet-700 hover:bg-violet-50" data-tab="progress">
            <i class="fas fa-chart-line mr-2"></i> View Student Progress Tracking
        </button>
        <button type="button" class="ml-tab-btn px-5 py-3 text-sm font-bold rounded-t-xl transition-all text-gray-600 hover:text-violet-700 hover:bg-violet-50" data-tab="questions">
            <i class="fas fa-pen-to-square mr-2"></i> Manage Activity
        </button>
    </div>

    <div id="ml-tab-lessons" class="ml-tab-content">
        <div class="presentation-wrapper">
            <aside class="cp-sidebar" aria-label="Course progress">
                <div class="cp-track"><div class="cp-track-fill" id="track-fill"></div></div>

                <div class="cp-module completed" data-cp="0">
                    <div class="cp-node completed" data-state="completed">
                        <svg class="cp-node-check" viewBox="0 0 16 16" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13.5 4.5L6 12l-3.5-3.5"/></svg>
                        <div class="cp-node-glow"></div>
                    </div>
                    <div class="cp-card">
                        <div class="cp-card-header" data-toggle="cp-body-0">
                            <div class="cp-icon-box"><i class="fa-solid fa-book-open"></i></div>
                            <div class="cp-card-info">
                                <div class="cp-card-title">Module 1: Gun Parts</div>
                                <div class="cp-card-meta"><i class="fa-regular fa-clock"></i> 30 mins <span class="dot">·</span> 6 lessons</div>
                            </div>
                            <div class="cp-progress-bar"><div class="cp-progress-fill" style="width:100%"></div></div>
                            <button class="cp-chevron" type="button"><i class="fa-solid fa-chevron-down"></i></button>
                        </div>
                        <div class="cp-card-body" id="cp-body-0">
                            <div class="cp-lesson" data-page="2"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 1.1: Firearm Overview</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="3"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 1.2: Weapon Types</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="4"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 1.3: Parts ID</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="5"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 1.4: Safety &amp; Handling</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="6"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 1.5: Ammunition</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="7"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Module 1 Assessment</span><span class="cp-badge cp-badge-yellow">Assessment</span></div>
                            <div class="cp-lesson" data-page="27"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Assessment Result</span><span class="cp-badge cp-badge-green">Score</span></div>
                        </div>
                    </div>
                </div>

                <div class="cp-module completed" data-cp="1">
                    <div class="cp-node completed" data-state="completed">
                        <svg class="cp-node-check" viewBox="0 0 16 16" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13.5 4.5L6 12l-3.5-3.5"/></svg>
                        <div class="cp-node-glow"></div>
                    </div>
                    <div class="cp-card">
                        <div class="cp-card-header" data-toggle="cp-body-1">
                            <div class="cp-icon-box"><i class="fa-solid fa-wrench"></i></div>
                            <div class="cp-card-info">
                                <div class="cp-card-title">Module 2: Disassembly</div>
                                <div class="cp-card-meta"><i class="fa-regular fa-clock"></i> 45 mins <span class="dot">·</span> 8 lessons</div>
                            </div>
                            <div class="cp-progress-bar"><div class="cp-progress-fill" style="width:100%"></div></div>
                            <button class="cp-chevron" type="button"><i class="fa-solid fa-chevron-down"></i></button>
                        </div>
                        <div class="cp-card-body" id="cp-body-1">
                            <div class="cp-lesson" data-page="29"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 2.1: Firing Principles</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="30"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 2.2: Stance &amp; Grip</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="31"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 2.3: Frame Separation</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="32"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 2.4: Slide Removal</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="33"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 2.5: Barrel Inspection</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="34"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Module 2 Assessment</span><span class="cp-badge cp-badge-yellow">Assessment</span></div>
                            <div class="cp-lesson" data-page="56"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Assemble &amp; Disassemble</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                        </div>
                    </div>
                </div>

                <div class="cp-module completed" data-cp="2">
                    <div class="cp-node completed" data-state="completed">
                        <svg class="cp-node-check" viewBox="0 0 16 16" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13.5 4.5L6 12l-3.5-3.5"/></svg>
                        <div class="cp-node-glow"></div>
                    </div>
                    <div class="cp-card">
                        <div class="cp-card-header" data-toggle="cp-body-2">
                            <div class="cp-icon-box"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                            <div class="cp-card-info">
                                <div class="cp-card-title">Module 3: Maintenance</div>
                                <div class="cp-card-meta"><i class="fa-regular fa-clock"></i> 35 mins <span class="dot">·</span> 6 lessons</div>
                            </div>
                            <div class="cp-progress-bar"><div class="cp-progress-fill" style="width:100%"></div></div>
                            <button class="cp-chevron" type="button"><i class="fa-solid fa-chevron-down"></i></button>
                        </div>
                        <div class="cp-card-body" id="cp-body-2">
                            <div class="cp-lesson" data-page="58"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 3.1: Cleaning</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="59"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 3.2: Lubrication</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="60"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 3.3: Spring Replacement</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="61"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 3.4: Part Inspection</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="62"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Lesson 3.5: Troubleshooting</span><span class="cp-badge cp-badge-lavender">Lesson</span></div>
                            <div class="cp-lesson" data-page="63"><span class="cp-lesson-icon"><i class="fa-solid fa-circle-check"></i></span><span class="cp-lesson-title">Module 3 Assessment</span><span class="cp-badge cp-badge-yellow">Assessment</span></div>
                        </div>
                    </div>
                </div>
            </aside>

            @include('Students.partials.lesson-presentation-shell')
        </div>
    </div>

    <div id="ml-tab-progress" class="ml-tab-content hidden">
        <section class="glass-card rounded-3xl p-5 sm:p-6 mb-6">
            <form id="ml-filter-form" class="grid grid-cols-1 md:grid-cols-3 gap-4" onsubmit="return false;">
                <div class="relative">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Live Search</label>
                    <input type="text" name="q" id="ml-search" placeholder="Search student ID or full name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                    <i class="fas fa-magnifying-glass absolute right-4 top-10 text-gray-400"></i>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Section</label>
                    <select name="section" id="ml-filter-section" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                        <option value="">All Sections</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.28em] text-gray-500 mb-2">Status</label>
                    <select name="status" id="ml-filter-status" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="idle">Idle (over 15s)</option>
                    </select>
                </div>
            </form>
        </section>

        <section class="glass-card rounded-3xl overflow-hidden mb-6">
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h2 class="font-display font-bold text-xl text-gray-900">Student Lesson Tracking</h2>
                    <p class="text-sm text-gray-500">Students currently opening the lesson. Auto-refreshes every 5 seconds.</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs">
                    <span class="chip bg-emerald-100 text-emerald-700"><span class="ml-pulse-dot"></span> Active</span>
                    <span class="chip bg-violet-100 text-violet-700"><i class="fas fa-book-open"></i> Gun Parts Lesson</span>
                    <span class="chip bg-cyan-100 text-cyan-700"><i class="fas fa-file-lines"></i> Page-aware</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[1100px]">
                    <thead class="bg-violet-950 text-xs text-violet-100 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 sm:px-6 py-4 font-semibold">Student ID</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold">Full Name</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold">Section</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold">Current Page</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold">Last Active</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white" id="ml-tbody">
                        <tr id="ml-empty-row">
                            <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                <div class="max-w-md mx-auto">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-2xl">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <p class="font-semibold text-gray-900 mb-2">No students are currently opening the lesson</p>
                                    <p class="text-sm">When a student opens the gun parts lesson, they will appear here .</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-5 sm:px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-500" id="ml-summary-text">Showing 0 active students</div>
                <div class="text-xs text-gray-500 flex items-center gap-1.5">
                    <span class="ml-pulse-dot"></span> Updates every 5 seconds
                </div>
            </div>
        </section>

    </div>

    <div id="ml-tab-questions" class="ml-tab-content hidden">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Manage Assessment Questions</h2>
                <p class="text-sm text-gray-500">Add, edit, or remove questions for each module assessment.</p>
            </div>

        </div>

        <div class="flex gap-2 mb-6">
            <button type="button" class="qm-tab-btn px-4 py-2 text-sm font-bold rounded-lg transition-all bg-violet-700 text-white" data-qm="1">Module 1</button>
            <button type="button" class="qm-tab-btn px-4 py-2 text-sm font-bold rounded-lg transition-all bg-white text-gray-600 border border-gray-200 hover:bg-violet-50" data-qm="2">Module 2</button>
            <button type="button" class="qm-tab-btn px-4 py-2 text-sm font-bold rounded-lg transition-all bg-white text-gray-600 border border-gray-200 hover:bg-violet-50" data-qm="3">Module 3</button>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 font-semibold w-12">#</th>
                            <th class="px-4 py-3 font-semibold">Question</th>
                            <th class="px-4 py-3 font-semibold">Options</th>
                            <th class="px-4 py-3 font-semibold w-24">Correct</th>
                            <th class="px-4 py-3 font-semibold w-28">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="qm-tbody" class="divide-y divide-gray-100">
                        <tr id="qm-empty-row">
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                <div class="max-w-md mx-auto">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-2xl"><i class="fas fa-file-pen"></i></div>
                                    <p class="font-semibold text-gray-900 mb-2">No questions yet</p>
                                    <p class="text-sm">Select a module above or click "Add Question" to create the first one.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Question Modal -->
        <div id="qm-modal-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm" style="display:none">
            <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 shadow-2xl overflow-hidden" style="transform:scale(0.95);transition:transform 0.2s">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 id="qm-modal-title" class="text-lg font-bold text-gray-900">Add Question</h3>
                    <button type="button" onclick="closeQmModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400"><i class="fas fa-times"></i></button>
                </div>
                <form id="qm-form" class="p-6 space-y-4" onsubmit="return saveQuestion(event)">
                    <input type="hidden" id="qm-edit-id" value="">
                    <input type="hidden" id="qm-module" value="1">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Question Text</label>
                        <textarea id="qm-question-text" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @for ($i = 0; $i < 4; $i++)
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Option {{ chr(65 + $i) }}</label>
                            <div class="flex items-center gap-2">
                                <input type="radio" name="qm-correct" value="{{ $i }}" class="accent-violet-600" {{ $i === 0 ? 'checked' : '' }}>
                                <input type="text" id="qm-option-{{ $i }}" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                            </div>
                        </div>
                        @endfor
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" onclick="closeQmModal()" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-gray-800 transition-all">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-violet-700 text-white text-sm font-bold rounded-lg hover:bg-violet-800 transition-all flex items-center gap-2"><i class="fas fa-check"></i> <span id="qm-submit-text">Add Question</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const apiUrl = @json(route('api.lesson.active-students')) + '?lesson=gun-parts-presentation';
            const tbody = document.getElementById('ml-tbody');
            const summaryEl = document.getElementById('ml-summary-text');
            const searchInput = document.getElementById('ml-search');
            const filterSection = document.getElementById('ml-filter-section');
            const filterStatus = document.getElementById('ml-filter-status');

            let timer = null;
            let inFlight = false;
            let allRows = [];
            let sectionOptions = new Set();
            let rowsVersion = 0;

            function escapeHtml(s) {
                if (s == null) return '';
                return String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
            }

            function relativeTime(ts) {
                const diff = Math.max(0, Math.floor(Date.now() / 1000) - ts);
                if (diff < 5) return 'just now';
                if (diff < 60) return diff + 's ago';
                if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
                return Math.floor(diff / 3600) + 'h ago';
            }

            function statusForRow(r) {
                const idle = Math.max(0, Math.floor(Date.now() / 1000) - r.last_active_at);
                return idle > 15 ? 'idle' : 'active';
            }

            function matchesFilters(r) {
                const q = (searchInput.value || '').trim().toLowerCase();
                const section = filterSection.value || '';
                const status = filterStatus.value || '';

                if (q) {
                    const hay = ((r.full_name || '') + ' ' + (r.student_id || '')).toLowerCase();
                    if (hay.indexOf(q) === -1) return false;
                }
                if (section && (r.section || '') !== section) return false;
                if (status && statusForRow(r) !== status) return false;
                return true;
            }

            function rowHtml(r) {
                const page = Math.max(1, (parseInt(r.current_page, 10) || 0) + 1);
                const last = escapeHtml(relativeTime(r.last_active_at));
                const meta = (r.section || '—').split(' / ').pop();
                const idle = Math.max(0, Math.floor(Date.now() / 1000) - r.last_active_at);
                const statusKey = idle > 15 ? 'idle' : 'active';
                const statusPill = statusKey === 'idle'
                    ? '<span class="status-pill bg-amber-100 text-amber-700"><i class="fas fa-circle-pause"></i> idle</span>'
                    : '<span class="status-pill bg-emerald-100 text-emerald-700"><span class="ml-pulse-dot"></span> active</span>';
                return '<tr class="hover:bg-violet-50/50 transition-colors" data-last-active="' + r.last_active_at + '" data-status="' + statusKey + '">' +
                    '<td class="px-5 sm:px-6 py-4 font-semibold text-gray-900">' + escapeHtml(r.student_id) + '</td>' +
                    '<td class="px-5 sm:px-6 py-4">' +
                        '<div class="font-medium text-gray-900">' + escapeHtml(r.full_name || '—') + '</div>' +
                        '<div class="text-xs text-gray-500">Viewing: Gun Parts Lesson</div>' +
                    '</td>' +
                    '<td class="px-5 sm:px-6 py-4 text-sm text-gray-600">' + escapeHtml(meta) + '</td>' +
                    '<td class="px-5 sm:px-6 py-4"><span class="status-pill bg-violet-100 text-violet-700"><i class="fas fa-file-lines"></i> Page ' + page + '</span></td>' +
                    '<td class="px-5 sm:px-6 py-4 text-sm text-gray-700" data-ts="' + r.last_active_at + '">' + last + '</td>' +
                    '<td class="px-5 sm:px-6 py-4">' + statusPill + '</td>' +
                '</tr>';
            }

            let rebuildVersion = -1;
            function rebuildSectionOptions() {
                if (rebuildVersion === rowsVersion) return;
                rebuildVersion = rowsVersion;
                const current = filterSection.value || '';
                const next = new Set();
                allRows.forEach(r => { if (r.section) next.add(r.section); });
                if (next.size === sectionOptions.size) {
                    let same = true;
                    next.forEach(v => { if (!sectionOptions.has(v)) same = false; });
                    if (same) return;
                }
                sectionOptions = next;
                const opts = ['<option value="">All Sections</option>']
                    .concat(Array.from(sectionOptions).sort().map(c => '<option value="' + escapeHtml(c) + '"' + (c === current ? ' selected' : '') + '>' + escapeHtml(c) + '</option>'));
                filterSection.innerHTML = opts.join('');
            }

            function render() {
                const filtered = allRows.filter(matchesFilters);
                summaryEl.textContent = 'Showing ' + filtered.length + ' of ' + allRows.length + ' active student' + (allRows.length === 1 ? '' : 's');
                if (filtered.length === 0) {
                    const isFiltering = !!(searchInput.value || filterSection.value || filterStatus.value);
                    const emptyMsg = isFiltering
                        ? '<p class="font-semibold text-gray-900 mb-2">No students match the current filters.</p><p class="text-sm">Try adjusting your search or filter selections.</p>'
                        : '<p class="font-semibold text-gray-900 mb-2">No students are currently opening the lesson</p><p class="text-sm">When a student opens the gun parts lesson, they will appear here</p>';
                    tbody.innerHTML = '<tr id="ml-empty-row"><td colspan="6" class="px-6 py-16 text-center text-gray-500"><div class="max-w-md mx-auto"><div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-2xl"><i class="fas fa-user-graduate"></i></div>' + emptyMsg + '</div></td></tr>';
                    return;
                }
                tbody.innerHTML = filtered.map(rowHtml).join('');
            }

            function refreshRelativeTimes() {
                const now = Math.floor(Date.now() / 1000);
                tbody.querySelectorAll('tr[data-last-active]').forEach(tr => {
                    const ts = parseInt(tr.getAttribute('data-last-active'), 10);
                    if (!Number.isFinite(ts)) return;
                    const cell = tr.querySelector('td[data-ts]');
                    if (cell) cell.textContent = relativeTime(ts);
                    const diff = Math.max(0, now - ts);
                    const statusKey = diff > 15 ? 'idle' : 'active';
                    const prev = tr.getAttribute('data-status');
                    if (prev !== statusKey) {
                        tr.setAttribute('data-status', statusKey);
                        const statusCell = tr.querySelector('td:last-child');
                        if (statusCell) {
                            statusCell.innerHTML = statusKey === 'idle'
                                ? '<span class="status-pill bg-amber-100 text-amber-700"><i class="fas fa-circle-pause"></i> idle</span>'
                                : '<span class="status-pill bg-emerald-100 text-emerald-700"><span class="ml-pulse-dot"></span> active</span>';
                        }
                    }
                });
            }

            async function tick() {
                if (inFlight) return;
                const progressTab = document.getElementById('ml-tab-progress');
                if (progressTab && progressTab.classList.contains('hidden')) return;
                inFlight = true;
                try {
                    const res = await fetch(apiUrl, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
                    if (!res.ok) throw new Error('http ' + res.status);
                    const data = await res.json();
                    const next = data.students || [];
                    if (next.length !== allRows.length || next.some((r, i) => r.section !== allRows[i].section || r.last_active_at !== allRows[i].last_active_at || r.full_name !== allRows[i].full_name)) {
                        rowsVersion++;
                    }
                    allRows = next;
                    rebuildSectionOptions();
                    render();
                } catch (err) {
                    console.error('Activity refresh failed', err);
                } finally {
                    inFlight = false;
                }
            }

            function start() {
                if (timer) clearInterval(timer);
                timer = setInterval(() => { tick(); refreshRelativeTimes(); }, 5000);
            }

            searchInput.addEventListener('input', render);
            filterSection.addEventListener('change', render);
            filterStatus.addEventListener('change', render);

            tick();
            start();
        })();

        (function() {
            const tabLessons = document.getElementById('ml-tab-lessons');
            if (!tabLessons) return;
            let pages, prevButton, nextButton, counterEl, trackFill, cpModules;
            let currentPage = 0;
            let cpBound = false;

            function getModuleIndex(pageIndex) {
                const page = pages[pageIndex];
                const raw = page?.dataset?.lesson;
                const num = parseInt(raw, 10);
                if (isNaN(num)) return 0;
                return Math.max(0, num - 1);
            }

            function firstPageForModule(moduleIndex) {
                if (moduleIndex === 0) return 0;
                const target = moduleIndex + 1;
                const match = pages.findIndex(p => parseInt(p.dataset.lesson, 10) === target);
                return match === -1 ? 0 : match;
            }

            function updateCheckpoints() {
                const currentMod = getModuleIndex(currentPage);
                cpModules.forEach((m, i) => {
                    m.classList.remove('completed', 'active');
                    if (i < currentMod) m.classList.add('completed');
                    else if (i === currentMod) m.classList.add('active');
                    const node = m.querySelector('.cp-node');
                    if (!node) return;
                    if (i < currentMod) {
                        node.className = 'cp-node completed';
                        node.innerHTML = '<svg class="cp-node-check" viewBox="0 0 16 16" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13.5 4.5L6 12l-3.5-3.5"/></svg><div class="cp-node-glow"></div>';
                    } else if (i === currentMod) {
                        node.className = 'cp-node in-progress';
                        node.innerHTML = '<span>' + (i + 1) + '</span><div class="cp-node-pulse"></div>';
                    }
                    const card = m.querySelector('.cp-card');
                    if (card) card.style.borderColor = i === currentMod ? '#c4b5fd' : '';
                });
                if (trackFill) {
                    const totalModules = cpModules.length;
                    const pct = totalModules > 1 ? (currentMod / (totalModules - 1)) * 100 : 0;
                    trackFill.style.height = pct + '%';
                }
            }

            function updateLessonStates() {
                const currentMod = getModuleIndex(currentPage);
                tabLessons.querySelectorAll('.cp-lesson').forEach(lesson => {
                    const mod = lesson.closest('.cp-module');
                    if (!mod) return;
                    const modIdx = cpModules.indexOf(mod);
                    if (modIdx !== currentMod) return;
                    const page = parseInt(lesson.dataset.page, 10);
                    if (isNaN(page)) return;
                    const icon = lesson.querySelector('.cp-lesson-icon i');
                    lesson.classList.remove('active', 'completed');
                    if (page === currentPage) {
                        lesson.classList.add('active');
                        if (icon) icon.className = 'fa-solid fa-circle-play';
                    } else {
                        lesson.classList.add('completed');
                        if (icon) icon.className = 'fa-solid fa-circle-check';
                    }
                });
            }

            function updatePresentationPage() {
                const totalPages = pages.length;
                pages.forEach((p, i) => p.classList.toggle('active', i === currentPage));
                if (counterEl) counterEl.textContent = (currentPage + 1) + ' / ' + totalPages;
                if (prevButton) prevButton.disabled = currentPage === 0;
                if (nextButton) nextButton.disabled = currentPage === totalPages - 1;
                updateCheckpoints();
                updateLessonStates();
            }

            function goToPage(idx) {
                currentPage = Math.max(0, Math.min(idx, pages.length - 1));
                updatePresentationPage();
            }

            function refreshCp() {
                pages = Array.from(tabLessons.querySelectorAll('.presentation-page'));
                if (!pages.length) return;
                prevButton = tabLessons.querySelector('#presentation-prev');
                nextButton = tabLessons.querySelector('#presentation-next');
                counterEl = tabLessons.querySelector('#page-counter');
                trackFill = tabLessons.querySelector('#track-fill');
                cpModules = Array.from(tabLessons.querySelectorAll('.cp-module'));
                if (!cpBound) {
                    cpBound = true;
                    prevButton?.addEventListener('click', () => goToPage(currentPage - 1));
                    nextButton?.addEventListener('click', () => goToPage(currentPage + 1));
                    cpModules.forEach(m => {
                        const header = m.querySelector('.cp-card-header');
                        if (!header) return;
                        header.addEventListener('click', () => {
                            const body = m.querySelector('.cp-card-body');
                            const chevron = header.querySelector('.cp-chevron');
                            if (!body) return;
                            const wasOpen = body.classList.contains('open');
                            tabLessons.querySelectorAll('.cp-card-body.open').forEach(b => {
                                if (b !== body) {
                                    b.classList.remove('open');
                                    const ch = b.closest('.cp-module')?.querySelector('.cp-chevron');
                                    if (ch) ch.classList.remove('rotated');
                                }
                            });
                            body.classList.toggle('open');
                            if (chevron) chevron.classList.toggle('rotated', !wasOpen);
                            if (!wasOpen) {
                                const modIdx = parseInt(m.dataset.cp, 10) || 0;
                                goToPage(firstPageForModule(modIdx));
                            }
                        });
                    });
                    tabLessons.querySelector('.cp-sidebar')?.addEventListener('click', (e) => {
                        const lesson = e.target.closest('.cp-lesson');
                        if (!lesson) return;
                        e.stopPropagation();
                        const page = parseInt(lesson.dataset.page, 10);
                        if (!isNaN(page)) goToPage(page);
                    });
                }
                updatePresentationPage();
            }

            const tabBtns = document.querySelectorAll('.ml-tab-btn');
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    tabBtns.forEach(b => {
                        b.classList.remove('bg-violet-700', 'text-white');
                        b.classList.add('text-gray-600', 'hover:text-violet-700', 'hover:bg-violet-50');
                    });
                    this.classList.add('bg-violet-700', 'text-white');
                    this.classList.remove('text-gray-600', 'hover:text-violet-700', 'hover:bg-violet-50');
                    document.querySelectorAll('.ml-tab-content').forEach(tc => tc.classList.add('hidden'));
                    const tabId = 'ml-tab-' + this.dataset.tab;
                    const target = document.getElementById(tabId);
                    if (target) target.classList.remove('hidden');
                    if (this.dataset.tab === 'lessons') {
                        setTimeout(refreshCp, 50);
                    }
                });
            });

            refreshCp();
        })();

        (function() {
            const tbody = document.getElementById('qm-tbody');
            const modalOverlay = document.getElementById('qm-modal-overlay');
            const modalTitle = document.getElementById('qm-modal-title');
            const submitText = document.getElementById('qm-submit-text');
            const editId = document.getElementById('qm-edit-id');
            const qmModule = document.getElementById('qm-module');
            const qText = document.getElementById('qm-question-text');
            const optionInputs = [0,1,2,3].map(i => document.getElementById('qm-option-'+i));
            const correctRadios = document.querySelectorAll('input[name="qm-correct"]');
            let currentModule = 1;

            function loadQuestions(module) {
                currentModule = module;
                qmModule.value = module;
                document.querySelectorAll('.qm-tab-btn').forEach(b => {
                    b.classList.remove('bg-violet-700', 'text-white');
                    b.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-200', 'hover:bg-violet-50');
                });
                const activeBtn = document.querySelector('.qm-tab-btn[data-qm="'+module+'"]');
                if (activeBtn) {
                    activeBtn.classList.add('bg-violet-700', 'text-white');
                    activeBtn.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-200', 'hover:bg-violet-50');
                }
                fetch('/instructor/activity/' + module, { headers: { 'Accept': 'application/json' } })
                    .then(r => r.json())
                    .then(questions => renderQuestions(questions))
                    .catch(err => console.error('Failed to load questions', err));
            }

            function renderQuestions(questions) {
                if (!questions.length) {
                    tbody.innerHTML = '<tr id="qm-empty-row"><td colspan="5" class="px-6 py-16 text-center text-gray-500"><div class="max-w-md mx-auto"><div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center text-2xl"><i class="fas fa-file-pen"></i></div><p class="font-semibold text-gray-900 mb-2">No questions yet</p><p class="text-sm">Click "Add Question" to create the first one.</p></div></td></tr>';
                    return;
                }
                tbody.innerHTML = questions.map((q, i) => {
                    const opts = q.options || [];
                    const labels = ['A','B','C','D'];
                    const optHtml = opts.map((o, j) =>
                        '<span class="' + (j === q.correct_answer ? 'text-emerald-600 font-semibold' : 'text-gray-600') + '">' +
                        labels[j] + '. ' + escapeHtml(o) +
                        (j === q.correct_answer ? ' <i class="fas fa-check text-emerald-500 text-xs"></i>' : '') +
                        '</span>'
                    ).join('<br>');
                    const qData = { id: q.id, module: q.module, question_text: q.question_text, options: q.options, correct_answer: q.correct_answer };
                    return '<tr class="hover:bg-violet-50/50 transition-colors" data-qid="' + q.id + '" data-question=\'' + JSON.stringify(qData) + '\'>' +
                        '<td class="px-4 py-3 text-sm text-gray-500 font-mono">' + q.question_number + '</td>' +
                        '<td class="px-4 py-3 text-sm text-gray-900 font-medium">' + escapeHtml(q.question_text) + '</td>' +
                        '<td class="px-4 py-3 text-xs">' + optHtml + '</td>' +
                        '<td class="px-4 py-3 text-sm"><span class="px-2 py-1 rounded-full text-xs font-bold ' + (q.correct_answer === 0 ? 'bg-emerald-100 text-emerald-700' : q.correct_answer === 1 ? 'bg-blue-100 text-blue-700' : q.correct_answer === 2 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') + '">' + labels[q.correct_answer] + '</span></td>' +
                        '<td class="px-4 py-3"><div class="flex items-center gap-2">' +
                        '<button onclick="editQuestion(' + q.id + ')" class="px-4 py-2 text-sm font-bold bg-violet-700 text-white rounded-lg hover:bg-violet-800 transition-all flex items-center gap-2"><i class="fas fa-edit"></i> Edit</button>' +
                        '</div></td></tr>';
                }).join('');
            }

            window.openQmModal = function(module) {
                editId.value = '';
                modalTitle.textContent = 'Add Question';
                submitText.textContent = 'Add Question';
                qText.value = '';
                optionInputs.forEach(inp => inp.value = '');
                correctRadios[0].checked = true;
                qmModule.value = module || currentModule;
                modalOverlay.style.display = 'flex';
                setTimeout(() => { modalOverlay.querySelector('div:first-child').style.transform = 'scale(1)'; }, 50);
            };

            window.closeQmModal = function() {
                modalOverlay.querySelector('div:first-child').style.transform = 'scale(0.95)';
                setTimeout(() => { modalOverlay.style.display = 'none'; }, 150);
            };

            window.saveQuestion = function(e) {
                e.preventDefault();
                const id = editId.value;
                const module = parseInt(qmModule.value, 10);
                const q = qText.value.trim();
                const opts = optionInputs.map(inp => inp.value.trim());
                let correct = 0;
                correctRadios.forEach((r, i) => { if (r.checked) correct = i; });

                if (!q || opts.some(o => !o)) { alert('Please fill in all fields.'); return; }

                const payload = {
                    module: module,
                    question_text: q,
                    options: opts,
                    correct_answer: correct,
                    _token: '{{ csrf_token() }}'
                };

                const url = id ? '/instructor/activity/' + id : '/instructor/activity';
                const method = id ? 'PUT' : 'POST';

                fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(payload)
                })
                .then(r => {
                    if (!r.ok) throw new Error('Save failed');
                    return r.json();
                })
                .then(() => {
                    closeQmModal();
                    loadQuestions(currentModule);
                })
                .catch(err => { alert('Failed to save question: ' + err.message); });
                return false;
            };

            window.editQuestion = function(id) {
                const row = tbody.querySelector('tr[data-qid="' + id + '"]');
                if (!row) return;
                const raw = row.getAttribute('data-question');
                if (!raw) return;
                try {
                    const q = JSON.parse(raw);
                    editId.value = q.id;
                    modalTitle.textContent = 'Edit Question';
                    submitText.textContent = 'Save Changes';
                    qmModule.value = q.module;
                    qText.value = q.question_text;
                    (q.options || []).forEach((o, i) => { if (optionInputs[i]) optionInputs[i].value = o; });
                    correctRadios.forEach((r, i) => { r.checked = i === q.correct_answer; });
                    modalOverlay.style.display = 'flex';
                    setTimeout(() => { modalOverlay.querySelector('div:first-child').style.transform = 'scale(1)'; }, 50);
                } catch (e) {
                    console.error('Failed to parse question data', e);
                }
            };

            function escapeHtml(s) {
                if (s == null) return '';
                return String(s).replace(/[&<>"']/g, c => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
            }

            document.querySelectorAll('.qm-tab-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    loadQuestions(parseInt(this.dataset.qm, 10));
                });
            });

            loadQuestions(1);
        })();
    </script>
@endsection
