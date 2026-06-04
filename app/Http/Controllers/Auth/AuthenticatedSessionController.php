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
            Log::info('User logged in', [
                'id' => $user->id,
                'email' => $authenticatedGuard === 'student' ? $user->student_id_number : $user->email,
                'role' => $authenticatedGuard === 'student' ? 'student' : $user->role,
            ]);

            if ($authenticatedGuard === 'student') {
                return redirect()->intended(route('student.dashboard'));
            }

            // Redirect based on user role, prefer intended URL when present
            if ($user->role === 'instructor') {
                return redirect()->intended(route('instructor.dashboard'));
            } elseif ($user->role === 'department_head') {
                return redirect()->intended(route('department-head.dashboard'));
            }

            // Fallback: go to home
            return redirect()->intended('/');
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

        return redirect('/login');
    }
}
