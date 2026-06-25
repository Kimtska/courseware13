<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\LoginVerificationCode as LoginVerificationCodeMailable;
use App\Models\LoginVerificationCode;
use App\Models\ManagedStudent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    private const MAX_ATTEMPTS = 6;

    public function create(): View|RedirectResponse
    {
        if (session()->has('login_verify_student_id')) {
            return redirect()->route('login.verify');
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $identifier = trim($credentials['email']);
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        Log::info('Login attempt', ['email' => $identifier]);

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
                cache()->forget($this->attemptCacheKey($identifier));

                if (hash_equals($storedStudentPassword, $password)) {
                    $student->password = Hash::make($password);
                    $student->save();
                }

                Auth::guard('student')->login($student, $remember);
                $attempt = true;
                $authenticatedGuard = 'student';
            }
        }

        if ($attempt) {
            cache()->forget($this->attemptCacheKey($identifier));

            $request->session()->regenerate();

            $user = $authenticatedGuard === 'student' ? Auth::guard('student')->user() : Auth::user();
            $role = $authenticatedGuard === 'student' ? 'student' : $user->role;
            Log::info('User logged in', [
                'id' => $user->id,
                'email' => $authenticatedGuard === 'student' ? $user->student_id_number : $user->email,
                'role' => $role,
            ]);

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

        $this->incrementAttempt($identifier, $student ?? null);

        if (session()->has('login_verify_student_id')) {
            return redirect()->route('login.verify');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showVerifyForm(): View|RedirectResponse
    {
        $studentId = session('login_verify_student_id');

        if (! $studentId) {
            return redirect()->route('login');
        }

        $student = ManagedStudent::find($studentId);

        if (! $student || ! $student->email) {
            return redirect()->route('login')->withErrors(['email' => 'No email on record for verification.']);
        }

        return view('auth.verify-code', [
            'maskedEmail' => $this->maskEmail($student->email),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $studentId = session('login_verify_student_id');

        if (! $studentId) {
            return redirect()->route('login');
        }

        $code = LoginVerificationCode::where('student_id', $studentId)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $code) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.'])->onlyInput('code');
        }

        $code->update(['used_at' => now()]);

        $student = $code->student;

        Auth::guard('student')->login($student);
        $request->session()->regenerate();
        session()->forget('login_verify_student_id');

        cache()->forget($this->attemptCacheKey($student->student_id_number));

        Log::info('Student logged in via verification code', ['student_id' => $student->id]);

        return redirect()->route('student.dashboard');
    }

    public function destroy(Request $request): JsonResponse|RedirectResponse
    {
        Log::info('Logout initiated', [
            'has_student_guard' => Auth::guard('student')->check(),
            'has_web_guard' => Auth::guard('web')->check(),
            'session_id_before' => $request->session()->getId(),
        ]);

        $student = Auth::guard('student')->user();
        $user = Auth::user();

        if ($student) {
            $student->setRememberToken(null);
            $student->save();
            Log::info('Student remember token cleared', ['student_id' => $student->id]);
        }

        if ($user) {
            $user->setRememberToken(null);
            $user->save();
        }

        Auth::guard('student')->logout();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Logout completed', ['new_session_id' => $request->session()->getId()]);

        if ($request->expectsJson()) {
            return response()->json(['redirect' => url('/login')]);
        }

        return redirect('/login');
    }

    private function incrementAttempt(string $identifier, ?ManagedStudent $student): void
    {
        $cacheKey = $this->attemptCacheKey($identifier);
        $attempts = (int) cache()->get($cacheKey, 0) + 1;
        cache()->put($cacheKey, $attempts, now()->addMinutes(30));

        Log::info('Login attempt count', ['email' => $identifier, 'attempts' => $attempts]);

        if ($attempts >= self::MAX_ATTEMPTS && $student && $student->email) {
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            LoginVerificationCode::create([
                'student_id' => $student->id,
                'code' => $code,
                'expires_at' => now()->addMinutes(5),
            ]);

            try {
                Mail::to($student->email)->send(new LoginVerificationCodeMailable($code));
            } catch (\Throwable $e) {
                Log::error('Failed to send verification email', [
                    'student_id' => $student->id,
                    'error' => $e->getMessage(),
                ]);
            }

            session(['login_verify_student_id' => $student->id]);
        }
    }

    private function attemptCacheKey(string $identifier): string
    {
        return 'login_attempts_' . md5(strtolower(trim($identifier)));
    }

    private function maskEmail(?string $email): string
    {
        if (! $email) {
            return '';
        }

        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';

        $maskedName = strlen($name) > 2
            ? substr($name, 0, 2) . str_repeat('*', max(0, strlen($name) - 2))
            : $name[0] . '*';

        return $maskedName . '@' . $domain;
    }

    private function dashboardRouteForRole(string $role): string
    {
        return match ($role) {
            'student' => 'student.dashboard',
            'instructor' => 'instructor.dashboard',
            'department_head' => 'department-head.dashboard',
            default => '/',
        };
    }

    private function isIntendedUrlAllowedForRole(?string $url, string $role): bool
    {
        if (! is_string($url) || $url === '') {
            return false;
        }

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
