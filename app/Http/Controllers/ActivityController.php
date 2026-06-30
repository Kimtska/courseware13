<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Module;
use App\Models\LessonPage;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index($moduleNumber)
    {
        $module = Module::where('module_key', "module-{$moduleNumber}")->firstOrFail();

        $lessonDetailIds = LessonPage::whereHas('lesson', function ($q) use ($module) {
            $q->where('module_id', $module->id);
        })->pluck('id');

        $activities = Activity::whereIn('lesson_detail_id', $lessonDetailIds)
            ->orderBy('question_number')
            ->get()
            ->map(function ($q) use ($moduleNumber) {
                $q->setAttribute('module', (int) $moduleNumber);
                return $q;
            });

        return response()->json($activities);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'module' => 'required|integer|min:1|max:99',
            'question_text' => 'required|string',
            'options' => 'required|array|size:4',
            'options.*' => 'required|string',
            'correct_answer' => 'required|integer|min:0|max:3',
        ]);

        $module = Module::where('module_key', 'module-'.$data['module'])->firstOrFail();

        $firstLessonDetail = LessonPage::whereHas('lesson', function ($q) use ($module) {
            $q->where('module_id', $module->id);
        })->orderBy('id')->firstOrFail();

        $maxQn = Activity::whereIn('lesson_detail_id', function ($q) use ($module) {
            $q->select('lesson_details.id')
              ->from('lesson_details')
              ->join('lessons', 'lesson_details.lesson_id', '=', 'lessons.id')
              ->where('lessons.module_id', $module->id);
        })->max('question_number');

        $activity = Activity::create([
            'lesson_detail_id' => $firstLessonDetail->id,
            'question_number' => ($maxQn ?? 0) + 1,
            'question_text' => $data['question_text'],
            'options' => $data['options'],
            'correct_answer' => $data['correct_answer'],
        ]);

        return response()->json($activity, 201);
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $data = $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|size:4',
            'options.*' => 'required|string',
            'correct_answer' => 'required|integer|min:0|max:3',
        ]);

        $activity->update($data);
        return response()->json($activity);
    }

    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'module' => 'required|integer|min:1|max:99',
            'activities' => 'required|array',
            'activities.*.id' => 'required|exists:activity,id',
            'activities.*.question_number' => 'required|integer|min:1',
        ]);

        $module = Module::where('module_key', 'module-'.$request->module)->firstOrFail();

        $lessonDetailIds = LessonPage::whereHas('lesson', function ($q) use ($module) {
            $q->where('module_id', $module->id);
        })->pluck('id');

        foreach ($request->activities as $q) {
            Activity::where('id', $q['id'])
                ->whereIn('lesson_detail_id', $lessonDetailIds)
                ->update(['question_number' => $q['question_number']]);
        }

        return response()->json(['message' => 'Reordered']);
    }
}
