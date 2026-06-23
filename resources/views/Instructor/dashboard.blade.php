@extends('Instructor.layout')

@section('title', 'Instructor Dashboard')
@section('pageTitle', 'Instructor Dashboard')
@section('pageSubtitle', 'Manage classes and monitor student progress')

@section('content')
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <section class="glass-card rounded-2xl p-5 bg-white border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                    <i class="fas fa-users text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">Total Students</p>
                    <p class="text-xl font-display font-bold text-gray-900 leading-tight">{{ isset($stats['total_students']) ? $stats['total_students'] : 0 }}</p>
                    <p class="text-[10px] text-violet-600 font-medium mt-0.5">{{ isset($stats['sections']) ? $stats['sections'] : 0 }} Sections</p>
                </div>
            </div>
        </section>
        <section class="glass-card rounded-2xl p-5 bg-white border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <i class="fas fa-clock text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">Pending Evaluations</p>
                    <p class="text-xl font-display font-bold text-gray-900 leading-tight">{{ isset($stats['pending']) ? $stats['pending'] : 0 }}</p>
                    <p class="text-[10px] text-amber-600 font-medium mt-0.5">{{ isset($stats['urgent']) ? $stats['urgent'] : 0 }} Urgent</p>
                </div>
            </div>
        </section>
        <section class="glass-card rounded-2xl p-5 bg-white border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <i class="fas fa-check-double text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">Completed Activities</p>
                    <p class="text-xl font-display font-bold text-gray-900 leading-tight">{{ isset($stats['completed_activities']) ? $stats['completed_activities'] : 0 }}</p>
                    <p class="text-[10px] text-emerald-600 font-medium mt-0.5">total students with activity</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Student List & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 dash-card p-0 overflow-hidden">   
            <div class="p-5 border-b border-gray-100 flex justify-between items-center"><h3 class="font-display font-bold text-black">Student Performance Overview</h3><select class="text-xs border border-gray-200 rounded-lg px-3 py-1.5 bg-white text-gray-600 focus:outline-none"><option>All Sections</option><option>CRIM 1-1</option><option>CRIM 2-1</option><option>CRIM 3-1</option></select></div>
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wider"><tr><th class="p-4">Student</th><th class="p-4">Year</th><th class="p-4">Parts</th><th class="p-4">Assembly</th><th class="p-4">Firing</th><th class="p-4">Status</th></tr></thead>
                <tbody class="divide-y divide-gray-50">
                    <tr class="hover:bg-gray-50 transition-colors"><td class="p-4 font-medium text-black">Dela Cruz, Juan</td><td class="p-4 text-gray-500">3rd</td><td class="p-4 text-green-500 font-semibold">98%</td><td class="p-4 text-green-500 font-semibold">95%</td><td class="p-4 text-violet-600 font-semibold">87%</td><td class="p-4"><span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded">Passing</span></td></tr>
                    <tr class="hover:bg-gray-50 transition-colors"><td class="p-4 font-medium text-black">Santos, Ana</td><td class="p-4 text-gray-500">2nd</td><td class="p-4 text-green-500 font-semibold">100%</td><td class="p-4 text-green-500 font-semibold">92%</td><td class="p-4 text-violet-600 font-semibold">91%</td><td class="p-4"><span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded">Passing</span></td></tr>
                    <tr class="hover:bg-gray-50 transition-colors bg-red-50/30"><td class="p-4 font-medium text-black">Garcia, Pedro</td><td class="p-4 text-gray-500">1st</td><td class="p-4 text-orange-500 font-semibold">75%</td><td class="p-4 text-red-500 font-semibold">68%</td><td class="p-4 text-red-500 font-semibold">54%</td><td class="p-4"><span class="px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded">Needs Attention</span></td></tr>
                </tbody>
            </table>
            <div class="p-4 border-t border-gray-100 text-center"><a href="#" class="text-xs text-violet-600 font-semibold hover:underline">View All Students →</a></div>
        </div>
        <div class="dash-card p-6">
            <h3 class="font-display font-bold text-black mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('instructor.manage-marksmanship') }}" class="block w-full p-4 rounded-xl border-2 border-dashed border-violet-200 hover:border-violet-400 hover:bg-violet-50 transition-all text-left group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-600"><i class="fas fa-sliders"></i></div><div><div class="text-sm font-semibold text-black">Marksmanship Setup</div><div class="text-[10px] text-gray-400">Configure timer, firearm, and target</div></div></div></a>
                <a href="{{ route('instructor.reports') }}" class="block w-full p-4 rounded-xl border-2 border-dashed border-violet-200 hover:border-violet-400 hover:bg-violet-50 transition-all text-left group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-600"><i class="fas fa-chart-simple"></i></div><div><div class="text-sm font-semibold text-black">View Reports</div><div class="text-[10px] text-gray-400">Student performance and analytics</div></div></div></a>
            </div>
        </div>
    </div>
@endsection
