@extends('Instructor.layout')

@section('title', 'Firing Range')
@section('pageTitle', 'Firing Range')
@section('pageSubtitle', 'Instructor module view with persistent navigation')

@section('headerActions')
    <form method="POST" action="{{ route('instructor.manage-portal.unlock', $moduleKey) }}" class="flex items-center gap-3" data-module-access-form>
        @csrf
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg {{ ($moduleState->is_unlocked ?? false) ? 'bg-rose-600 hover:bg-rose-700' : 'bg-violet-700 hover:bg-violet-800' }} text-white text-xs font-bold uppercase transition-colors" data-module-access-button>
            <i class="fas {{ ($moduleState->is_unlocked ?? false) ? 'fa-lock' : 'fa-lock-open' }}"></i>
            <span>{{ ($moduleState->is_unlocked ?? false) ? 'Lock Module Access' : 'Unlock Module Access?' }}</span>
        </button>
    </form>
@endsection

@section('content')
    <div class="grid gap-6">
        <div class="glass-card rounded-3xl p-6 sm:p-8">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.28em] text-violet-500 font-bold">Global module access</p>
                    <h1 class="font-display font-bold text-3xl text-gray-900">{{ $moduleTitle }}</h1>
                    <p class="text-sm text-gray-500">{{ $moduleDescription }}</p>
                </div>
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wider">
                    <i class="fas fa-shield-halved"></i> Unlocked for all verified students
                </span>
            </div>
            <p class="text-gray-600">The firing range simulation is loaded below inside the instructor dashboard layout so the sidebar remains visible at all times.</p>
        </div>

        <div class="glass-card rounded-3xl overflow-hidden min-h-[70vh]">
            <iframe src="{{ $contentUrl }}" title="Firing Range Content" class="w-full h-[75vh] border-0 bg-white"></iframe>
        </div>
    </div>

    @include('shared.sweet-alerts.module-access', [
        'moduleTitle' => $moduleTitle,
        'moduleState' => $moduleState,
    ])
@endsection