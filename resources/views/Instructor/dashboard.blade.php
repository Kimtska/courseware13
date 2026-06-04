@extends('Instructor.layout')

@section('title', 'Instructor Dashboard')
@section('pageTitle', 'Instructor Dashboard')
@section('pageSubtitle', 'Manage classes and monitor student progress')

@section('content')
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="dash-card p-5"><p class="text-xs text-gray-400 uppercase tracking-wider">Total Students</p><p class="text-2xl font-bold text-black mt-1">{{ isset($stats['total_students']) ? $stats['total_students'] : 0 }}</p><p class="text-[10px] text-violet-500 mt-1 font-medium">{{ isset($stats['sections']) ? $stats['sections'] : 0 }} Sections</p></div>
        <div class="dash-card p-5"><p class="text-xs text-gray-400 uppercase tracking-wider">Avg. Firing Score</p><p class="text-2xl font-bold text-black mt-1">{{ isset($stats['avg_score']) ? $stats['avg_score'] : 0 }}%</p><p class="text-[10px] text-green-500 mt-1 font-medium">▲ {{ isset($stats['score_change']) ? $stats['score_change'] : 0 }}% this month</p></div>
        <div class="dash-card p-5"><p class="text-xs text-gray-400 uppercase tracking-wider">Pending Evaluations</p><p class="text-2xl font-bold text-black mt-1">{{ isset($stats['pending']) ? $stats['pending'] : 0 }}</p><p class="text-[10px] text-orange-500 mt-1 font-medium">{{ isset($stats['urgent']) ? $stats['urgent'] : 0 }} Urgent</p></div>
        <div class="dash-card p-5"><p class="text-xs text-gray-400 uppercase tracking-wider">Live Sessions</p><p class="text-2xl font-bold text-black mt-1 flex items-center gap-2">{{ isset($live_sessions) ? $live_sessions : 0 }} <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span></p><p class="text-[10px] text-gray-400 mt-1">CRIM 3-1 in progress</p></div>
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
                <button class="w-full p-4 rounded-xl border-2 border-dashed border-violet-200 hover:border-violet-400 hover:bg-violet-50 transition-all text-left group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-600"><i class="fas fa-play"></i></div><div><div class="text-sm font-semibold text-black">Start Live Session</div><div class="text-[10px] text-gray-400">Monitor range real-time</div></div></div></button>
                <button class="w-full p-4 rounded-xl border-2 border-dashed border-violet-200 hover:border-violet-400 hover:bg-violet-50 transition-all text-left group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-600"><i class="fas fa-file-alt"></i></div><div><div class="text-sm font-semibold text-black">Generate Report</div><div class="text-[10px] text-gray-400">Export class PDF</div></div></div></button>
                <button class="w-full p-4 rounded-xl border-2 border-dashed border-violet-200 hover:border-violet-400 hover:bg-violet-50 transition-all text-left group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-600"><i class="fas fa-edit"></i></div><div><div class="text-sm font-semibold text-black">Create Quiz</div><div class="text-[10px] text-gray-400">Parts identification test</div></div></div></button>
            </div>
        </div>
    </div>
@endsection
