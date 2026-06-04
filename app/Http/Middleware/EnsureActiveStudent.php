<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureActiveStudent
{
    public function handle(Request $request, Closure $next)
    {
        $student = Auth::guard('student')->user();

        if (! $student && Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if ($user && $user->role === 'student') {
                $student = \App\Models\ManagedStudent::withArchived()
                    ->where('student_id_number', $user->email)
                    ->first();
            }
        }

        if (! $student) {
            return redirect()->route('login');
        }

        if ($student->status !== 'active') {
            Auth::guard('student')->logout();
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your Marksmanship access has been archived.',
            ]);
        }

        return $next($request);
    }
}