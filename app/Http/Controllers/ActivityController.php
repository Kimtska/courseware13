<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index($module)
    {
        $activities = Activity::where('module', $module)
            ->orderBy('question_number')
            ->get();
        return response()->json($activities);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'module' => 'required|integer|min:1|max:3',
            'question_number' => 'required|integer|min:1|max:99',
            'question_text' => 'required|string',
            'options' => 'required|array|size:4',
            'options.*' => 'required|string',
            'correct_answer' => 'required|integer|min:0|max:3',
        ]);

        $activity = Activity::create($data);
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
            'module' => 'required|integer|min:1|max:3',
            'activities' => 'required|array',
            'activities.*.id' => 'required|exists:activity,id',
            'activities.*.question_number' => 'required|integer|min:1',
        ]);

        foreach ($request->activities as $q) {
            Activity::where('id', $q['id'])->update(['question_number' => $q['question_number']]);
        }

        return response()->json(['message' => 'Reordered']);
    }
}
