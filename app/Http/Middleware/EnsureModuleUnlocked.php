<?php

namespace App\Http\Middleware;

use App\Models\ManagedStudent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class EnsureModuleUnlocked
{
    public function handle(Request $request, Closure $next, $moduleKey = null)
    {
        if (!$moduleKey) {
            return $next($request);
        }

        $student = Auth::guard('student')->user();

        if (! $student && Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if ($user && $user->role === 'student') {
                $student = ManagedStudent::withArchived()->where('student_id_number', $user->email)->first();
            }
        }

        if (! $student || $student->status !== 'active') {
            return Redirect::route('login')->withErrors([
                'email' => 'Your Marksmanship access is not active.',
            ]);
        }

        if ($student->isModuleUnlocked($moduleKey)) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['error' => 'module_locked', 'message' => 'Module is locked'], 403);
        }

        return Redirect::route('student.dashboard')->with('module_access_flash', [
            'title' => 'Module Locked',
            'message' => 'Complete the previous module first to unlock this one.'
        ]);
    }
}
