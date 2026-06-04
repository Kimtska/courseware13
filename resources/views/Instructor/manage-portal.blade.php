@extends('Instructor.layout')

@section('title', 'Manage Portal')
@section('pageTitle', 'Manage Portal')
@section('pageSubtitle', 'Instructor-granted access to unlocked training modules')

@section('content')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <section class="lg:col-span-5 portal-card p-6 sm:p-7">
                <div class="chip bg-emerald-100 text-emerald-700 mb-4"><i class="fas fa-unlock"></i> Access granted by instructor</div>
                <h1 class="font-display font-bold text-3xl text-gray-900 mb-2">Student Module Portal</h1>
                <p class="text-sm text-gray-500 mb-6">Open any module to unlock access for all verified students. Module access is managed globally from this portal.</p>

                <div class="portal-card mt-4 p-4 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-600">
                    <h3 class="font-display font-bold text-lg text-gray-900 mb-3">Portal flow</h3>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="rounded-xl bg-white border border-gray-100 p-4"><span class="chip bg-violet-100 text-violet-700 mb-3">1</span><p>The instructor opens a module from this portal.</p></div>
                        <div class="rounded-xl bg-white border border-gray-100 p-4"><span class="chip bg-violet-100 text-violet-700 mb-3">2</span><p>All verified students with locked access are upgraded automatically.</p></div>
                        <div class="rounded-xl bg-white border border-gray-100 p-4"><span class="chip bg-violet-100 text-violet-700 mb-3">3</span><p>The module stays available without selecting a specific student first.</p></div>
                    </div>
                </div>
            </section>

            <section class="lg:col-span-7 space-y-6">
                <div class="portal-card p-6 sm:p-7">
                    <div class="flex items-center justify-between gap-4 mb-5">
                        <div>
                            <h2 class="font-display font-bold text-2xl text-gray-900">Training Modules</h2>
                            <p class="text-sm text-gray-500">These modules are unlocked by the instructor when your session is active.</p>
                        </div>
                        <span class="chip bg-emerald-100 text-emerald-700"><i class="fas fa-shield-halved"></i> SPC verified</span>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        @foreach($modules as $module)
                            @php
                                $state = \App\Models\ModuleAccessControl::where('module_key', $module['key'])->first();
                                $isUnlocked = $state && $state->is_unlocked;
                            @endphp
                            <article class="module-card rounded-2xl p-5 bg-white">
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div>
                                        <p class="text-[10px] uppercase tracking-[0.28em] text-violet-500 font-bold">{{ $module['title'] }}</p>
                                        <h3 class="text-lg font-bold text-gray-900 mt-1">{{ $module['description'] }}</h3>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        <span class="text-xs font-bold px-3 py-1 rounded-full {{ $isUnlocked ? 'bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm' : 'bg-rose-100 text-rose-800 border border-rose-200 shadow-sm' }}">{{ $isUnlocked ? 'Unlocked' : 'Locked' }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <a href="{{ $module['route'] }}" class="module-launch inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-violet-700 text-white text-sm font-bold hover:bg-violet-800 transition-colors">
                                        <i class="fas fa-door-open"></i> Open Module
                                    </a>
                                    <span class="text-xs text-gray-500">{{ $isUnlocked ? 'Unlocked by instructor access' : 'Locked — open to unlock for students' }}</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
@endsection