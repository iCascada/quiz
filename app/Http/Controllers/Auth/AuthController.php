<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Department;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\User;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Auth\Events\PasswordReset as EventPasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Login page
     *
     * @return View
     */
    public function loginPage(): View
    {
        return view('auth.login', [
            'title' => __('pages.login')
        ]);
    }

    /**
     * Register page
     *
     * @return View
     */
    public function registerPage(): View
    {
        return view('auth.register', [
            'departments' => Department::all(),
            'title' => __('pages.register')
        ]);
    }

    /**
     * Notice mail template
     *
     * @return View
     */
    public function noticeEmail(): View
    {
        return view('auth.verify-email');
    }

    /**
     * Verify user email
     *
     * @param EmailVerificationRequest $request
     * @return RedirectResponse
     */
    public function verifyEmail(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();
        $request->session()->flash('status', __('custom_session_message.auth.email_verified'));
        return redirect()->route('dashboard-page');
    }

    /**
     * Login user
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $remember = isset($validated['remember']);

        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            $user = Auth::user();

            if ($user->is_blocked) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withErrors(
                        [
                            'error' => __('custom_session_message.flash.is_blocked')
                        ]
                    )
                    ->withInput();
            }

            $request->session()->flash('status', __('custom_session_message.auth.success'));
            return redirect()->route('dashboard-page');
        }

        return back()
            ->withErrors(
                [
                    'error' => __('custom_validation.error.failed_auth')
                ]
            )
            ->withInput();
    }

    /**
     * User register
     *
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['role_id'] = Role::USER;
        $validated['password'] = Hash::make($validated['password']);

        try {
            $user = User::create($validated);
        } catch (Exception $e) {

            if ($e->getCode()) {

                return back()
                    ->withErrors(
                        [
                            'email' => __('custom_validation.email.used')
                        ]
                    )
                    ->withInput();
            }

            $message = env('app_debug') ? ': ' . $e->getMessage() : '';

            return back()
                ->withErrors(
                    [
                        'error' => __('custom_validation.error.internal') . $message
                    ]
                );
        }

        Auth::login($user);
        $request->session()->flash('status', __('custom_session_message.auth.success'));
        event(new Registered($user));

        return redirect()->route('dashboard-page');
    }

    /**
     * User logout
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login-page');
    }

    /**
     * Reset password page
     *
     * @return View
     */
    public function forgotPasswordPage(): View
    {
        return view('auth.forgot-password', [
            'title' => __('pages.forgot-password')
        ]);
    }

    /**
     * Send email for restore password
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function forgotPasswordRequest(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? redirect()->route('login-page')->with('status', __($status))
            : back()->withErrors(['error' => __($status)]);
    }

    /**
     * Check token and redirect to update form
     *
     * @param string $email
     * @param string $token
     * @param Request $request
     * @return View|RedirectResponse
     * @throws Exception
     */
    public function forgotPasswordReset(string $email, string $token, Request $request): View|RedirectResponse
    {
        $resetRecord = PasswordReset::where('email', $email)->first();

        if (!$resetRecord) {
            return redirect(null, Response::HTTP_NOT_FOUND)
                ->route('login-page')
                ->withErrors(['error' => __('auth.password_token_expired')]);
        }

        $createdTokenTimestamp = (new DateTime($resetRecord->created_at))
            ->getTimestamp();

        $expiredTimestamp = (new DateTime())
            ->add(new DateInterval('PT60M'))
            ->getTimestamp();

        if (
            !Hash::check($token, $resetRecord->token) ||
            $createdTokenTimestamp > $expiredTimestamp
        ) {
            return redirect(null, 419)
                ->route('login-page')
                ->withErrors(['error' => __('auth.password_token_expired')]);
        }

        return view('auth.change-password', [
            'email' => $email,
            'token' => $token,
            'title' => __('pages.forgot-password')
        ]);
    }

    /**
     * Update password
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function forgotPasswordUpdate(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new EventPasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login-page')->with('status', __('auth.password_update'))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
