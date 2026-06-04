<?php

namespace App\Http\Middleware;

use App\Models\ManagedStudent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\ModuleAccessControl;

class EnsureModuleUnlocked
{
    /**
     * Handle an incoming request.
     * Middleware expects parameter: module key (e.g., module-1)
     */
    public function handle(Request $request, Closure $next, $moduleKey = null)
    {
        if (!$moduleKey) {
            // If no module key provided, allow by default
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

        $state = ModuleAccessControl::where('module_key', $moduleKey)->first();

        if ($state && $state->is_unlocked) {
            return $next($request);
        }

        // Module locked: respond appropriately
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['error' => 'module_locked', 'message' => 'Module is locked'], 403);
        }

        // Redirect students back to dashboard with a flash message
        return Redirect::route('student.dashboard')->with('module_access_flash', [
            'title' => 'Module Locked',
            'message' => 'This module is currently locked. Please contact your instructor to request access.'
        ]);
    }
}
