<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect($this->redirectPathFor($guard));
            }
        }

        return $next($request);
    }

    /**
     * Resolve where to send an already-authenticated user based on the
     * guard they are signed in with. Falls back to RouteServiceProvider::HOME
     * (the landing page) when the role cannot be determined.
     */
    protected function redirectPathFor(?string $guard): string
    {
        if ($guard === 'student') {
            return route('student.dashboard');
        }

        $user = Auth::guard($guard)->user();
        $role = $user?->role;

        return match ($role) {
            'instructor' => route('instructor.dashboard'),
            'department_head' => route('department-head.dashboard'),
            default => RouteServiceProvider::HOME,
        };
    }
}
