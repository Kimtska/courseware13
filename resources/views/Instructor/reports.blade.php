@extends('Instructor.layout')

@section('title', 'Reports')
@section('pageTitle', 'Reports')
@section('pageSubtitle', 'View training performance and analytics')

@section('content')
    <style>
        .rank-badge{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800}
        .rank-1{background:linear-gradient(135deg,#f59e0b,#fbbf24);color:#fff;box-shadow:0 2px 8px -2px rgba(245,158,11,.4)}
        .rank-2{background:linear-gradient(135deg,#94a3b8,#cbd5e1);color:#fff;box-shadow:0 2px 8px -2px rgba(148,163,184,.4)}
        .rank-3{background:linear-gradient(135deg,#d97706,#f59e0b);color:#fff;box-shadow:0 2px 8px -2px rgba(217,119,6,.4)}
        .rank-default{background:#f1f5f9;color:#64748b}
        .score-cell{font-variant-numeric:tabular-nums}
    </style>

    {{-- Leaderboard + Data Table --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 mb-6">
        {{-- Leaderboard --}}
        <div class="xl:col-span-1 glass-card rounded-3xl overflow-hidden bg-white border border-gray-200">
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100">
                <h2 class="font-display font-bold text-lg text-gray-900 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-violet-500 text-sm"></i>
                    Analytics Report
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Student performance metrics across all modules</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($leaderboard as $rank => $entry)
                    @php
                        $student = $entry['student'];
                                $fullName = $student->full_name;
                        $studentId = $student->student_id_number ?? '';
                        $rankClass = $rank === 0 ? 'rank-1' : ($rank === 1 ? 'rank-2' : ($rank === 2 ? 'rank-3' : 'rank-default'));
                    @endphp
                    <div class="px-5 sm:px-6 py-3.5 flex items-center gap-3 hover:bg-violet-50/50 transition-colors">
                        <span class="rank-badge {{ $rankClass }} shrink-0">{{ $rank + 1 }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $fullName }}</p>
                            <p class="text-[10px] text-gray-500">{{ $studentId }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-bold text-violet-700">{{ $entry['average'] }}%</p>
                            <p class="text-[10px] text-gray-400">avg</p>
                        </div>
                    </div>
                @empty
                    <div class="px-5 sm:px-6 py-8 text-center text-gray-500 text-sm">
                        <i class="fas fa-trophy text-2xl text-gray-300 mb-2 block"></i>
                        <p class="font-semibold text-gray-900">No data yet</p>
                        <p class="text-xs mt-1">Complete all 4 modules to appear on the leaderboard.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Data Table --}}
        <div class="xl:col-span-3 glass-card rounded-3xl overflow-hidden bg-white border border-gray-200">
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h2 class="font-display font-bold text-xl text-gray-900">Student Performance Report</h2>
                    <p class="text-sm text-gray-500">Scores per module and final marksmanship assessment.</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs">
                    <span class="chip bg-emerald-100 text-emerald-700"><i class="fas fa-circle-check"></i> Completed</span>
                    <span class="chip bg-gray-100 text-gray-500"><i class="fas fa-minus"></i> Not yet taken</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[1000px]">
                    <thead class="bg-violet-950 text-xs text-violet-100 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 sm:px-6 py-4 font-semibold">Student</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold text-center">Module 1</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold text-center">Module 2</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold text-center">Module 3</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold text-center">Final Marksmanship</th>
                            <th class="px-5 sm:px-6 py-4 font-semibold text-center">Overall</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($studentReports as $report)
                            @php
                                $student = $report['student'];
                        $fullName = $student->full_name;
                                $studentId = $student->student_id_number ?? '';
                                $section = $student->section ?? '—';

                                $m1 = $report['module_1'];
                                $m2 = $report['module_2'];
                                $m3 = $report['module_3'];
                                $ms = $report['marksmanship'];

                                $completedModules = collect([$m1, $m2, $m3, $ms])->filter(fn($m) => $m !== null);
                                $overallAvg = $completedModules->count() > 0
                                    ? round($completedModules->sum('percentage') / $completedModules->count())
                                    : null;
                            @endphp
                            <tr class="hover:bg-violet-50/50 transition-colors">
                                <td class="px-5 sm:px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $fullName }}</div>
                                    <div class="text-xs text-gray-500">{{ $studentId }} &middot; Section {{ $section }}</div>
                                </td>
                                <td class="px-5 sm:px-6 py-4 text-center">
                                    @if ($m1)
                                        <span class="score-cell font-bold {{ $m1['percentage'] >= 70 ? 'text-emerald-600' : 'text-red-500' }}">{{ $m1['percentage'] }}%</span>
                                        <div class="text-[10px] text-gray-400">{{ $m1['score'] }}/{{ $m1['max_score'] }}</div>
                                    @else
                                        <span class="text-gray-300 text-xs"><i class="fas fa-minus"></i></span>
                                    @endif
                                </td>
                                <td class="px-5 sm:px-6 py-4 text-center">
                                    @if ($m2)
                                        <span class="score-cell font-bold {{ $m2['percentage'] >= 70 ? 'text-emerald-600' : 'text-red-500' }}">{{ $m2['percentage'] }}%</span>
                                        <div class="text-[10px] text-gray-400">{{ $m2['score'] }}/{{ $m2['max_score'] }}</div>
                                    @else
                                        <span class="text-gray-300 text-xs"><i class="fas fa-minus"></i></span>
                                    @endif
                                </td>
                                <td class="px-5 sm:px-6 py-4 text-center">
                                    @if ($m3)
                                        <span class="score-cell font-bold {{ $m3['percentage'] >= 70 ? 'text-emerald-600' : 'text-red-500' }}">{{ $m3['percentage'] }}%</span>
                                        <div class="text-[10px] text-gray-400">{{ $m3['score'] }}/{{ $m3['max_score'] }}</div>
                                    @else
                                        <span class="text-gray-300 text-xs"><i class="fas fa-minus"></i></span>
                                    @endif
                                </td>
                                <td class="px-5 sm:px-6 py-4 text-center">
                                    @if ($ms)
                                        <span class="score-cell font-bold {{ $ms['percentage'] >= 70 ? 'text-violet-600' : 'text-red-500' }}">{{ $ms['percentage'] }}%</span>
                                        <div class="text-[10px] text-gray-400">{{ $ms['score'] }}/{{ $ms['max_score'] }}</div>
                                    @else
                                        <span class="text-gray-300 text-xs"><i class="fas fa-minus"></i></span>
                                    @endif
                                </td>
                                <td class="px-5 sm:px-6 py-4 text-center">
                                    @if ($overallAvg !== null)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $overallAvg >= 70 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $overallAvg }}%
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs"><i class="fas fa-minus"></i></span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="max-w-md mx-auto">
                                        <i class="fas fa-chart-simple text-3xl text-gray-300 mb-3 block"></i>
                                        <p class="font-semibold text-gray-900 mb-1">No students enrolled</p>
                                        <p class="text-sm">Import or add students to start tracking their performance.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 sm:px-6 py-3 border-t border-gray-100 bg-gray-50/50">
                <p class="text-xs text-gray-500">
                    Showing {{ $studentReports->count() }} student{{ $studentReports->count() !== 1 ? 's' : '' }}.
                    Scores are displayed as percentage with raw score below.
                </p>
            </div>
        </div>
    </div>
@endsection
