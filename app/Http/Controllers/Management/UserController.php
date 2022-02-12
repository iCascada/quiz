<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends AuthController
{
    public function accountManagementPage()
    {
        return view('common.user', [
            'title' => __('pages.user-management'),
            'departments' => Department::all(),
            'roles' => Role::all()
        ]);
    }

    public function verifyAuthUserEmail(Request $request)
    {
        $request->session()->flash('status', __('custom_session_message.auth.email_verify_send'));
        event(new Registered(Auth::user()));

        return back();
    }

    public function resetPassword(): RedirectResponse|View
    {
        if (!Auth::user()->email_verified_at) {
            return back()->withErrors(['error' => __('custom_session_message.flash.email_not_verified')]);
        }

        return view('common.reset-password', [
            'title' => __('pages.forgot-password')
        ]);
    }

    public function resetPasswordConfirm(ResetPasswordRequest $request)
    {
        $currentPassword = $request->get('current_password');
        $user = Auth::user();

        if (!Hash::check($currentPassword, $user->password)) {
            return back()->withErrors(['error' => __('custom_session_message.auth.password_check_fail')]);
        }

        $user->password = Hash::make($request->get('password'));
        $user->save();

        $request->session()->flash('status', __('custom_session_message.flash.apply'));
        return redirect()->route('account-page');
    }

    public function updateUserInfo(UpdateUserInfoRequest $request)
    {
        $user = Auth::user();

        if ($user->email !== $request->get('email')) {
            $user->email = $request->get('email');
            $user->email_verified_at = null;
        }

        if ($user->name !== $request->get('name')) {
            $user->name = $request->get('name');
        }

        if ($user->last_name !== $request->get('last_name')) {
            $user->last_name = $request->get('last_name');
        }

        if ($user->role_id !== $request->get('role_id')) {
            $user->role_id = $request->get('role_id');
        }

        if ($user->department_id !== $request->get('department_id')) {
            $user->department_id = $request->get('department_id');
        }

        $user->save();

        $request->session()->flash('status', __('custom_session_message.flash.apply'));
        return back();

    }
}
