<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ManagedStudent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $identifier = trim($credentials['email']);
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        // Log login attempt (don't log passwords)
        Log::info('Login attempt', ['email' => $identifier]);

        // Attempt to authenticate
        $attempt = Auth::attempt([
            'email' => $identifier,
            'password' => $password,
        ], $remember);

        $authenticatedGuard = 'web';

        if (! $attempt) {
            $student = ManagedStudent::withArchived()->active()->where('student_id_number', $identifier)->first();

            $storedStudentPassword = $student ? (string) $student->password : '';
            $studentPasswordMatches = $student && (
                Hash::check($password, $storedStudentPassword)
                || hash_equals($storedStudentPassword, $password)
            );

            if ($studentPasswordMatches) {
                if (hash_equals($storedStudentPassword, $password)) {
                    $student->password = Hash::make($password);
                    $student->save();
                }

                Auth::guard('student')->login($student, $remember);
                $attempt = true;
                $authenticatedGuard = 'student';
            }
        }

        Log::info('Auth::attempt result', ['email' => $identifier, 'success' => $attempt]);

        if ($attempt) {
            $request->session()->regenerate();

            // Log successful login for debugging
            $user = $authenticatedGuard === 'student' ? Auth::guard('student')->user() : Auth::user();
            $role = $authenticatedGuard === 'student' ? 'student' : $user->role;
            Log::info('User logged in', [
                'id' => $user->id,
                'email' => $authenticatedGuard === 'student' ? $user->student_id_number : $user->email,
                'role' => $role,
            ]);

            // Pull the intended URL (if any) and validate it belongs to a route
            // the authenticated role is allowed to access. If not — or if no
            // intended URL is stored — fall back to the role-specific dashboard.
            $intended = $request->session()->pull('url.intended');
            if (is_string($intended) && $this->isIntendedUrlAllowedForRole($intended, $role)) {
                return redirect()->to($intended);
            }

            $dashboardRoute = $this->dashboardRouteForRole($role);
            if ($dashboardRoute === '/') {
                return redirect('/');
            }

            return redirect()->route($dashboardRoute);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('student')->logout();
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        $request->session()->forget('url.intended');

        return redirect('/login');
    }

    /**
     * Resolve the role-specific dashboard route for a given role.
     */
    private function dashboardRouteForRole(string $role): string
    {
        return match ($role) {
            'student' => 'student.dashboard',
            'instructor' => 'instructor.dashboard',
            'department_head' => 'department-head.dashboard',
            default => '/',
        };
    }

    /**
     * Determine whether the stored intended URL is safe to redirect to
     * for the given role. Rejects empty, external, or cross-role URLs so
     * a stale session key can never bounce the user to the wrong panel
     * (or to the landing page).
     */
    private function isIntendedUrlAllowedForRole(?string $url, string $role): bool
    {
        if (! is_string($url) || $url === '') {
            return false;
        }

        // Reject anything that isn't a same-origin relative path
        if (preg_match('#^(?:[a-z][a-z0-9+\-.]*:)?//#i', $url) === 1) {
            return false;
        }
        if (str_starts_with($url, '//')) {
            return false;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (! is_string($path) || $path === '') {
            return false;
        }

        $allowedPrefixes = match ($role) {
            'student' => ['/student', '/api/lesson/heartbeat', '/api/lesson/leave', '/api/lesson/active-students'],
            'instructor' => ['/instructor', '/api/lesson/active-students'],
            'department_head' => ['/department-head', '/instructor', '/api/lesson/active-students'],
            default => [],
        };

        foreach ($allowedPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/') || str_starts_with($path, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
