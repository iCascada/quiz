<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UsersController extends Controller
{
    /**
     * Users management page
     *
     * @return View
     */
    public function UsersManagementPage()
    {
        $currentUser = Auth::user();
        $users = User::where('id', '<>', $currentUser->id)->get();

        return view('private.admin.users.index', [
            'title' => __('pages.users-management'),
            'users' => $users
        ]);
    }

    /**
     * Edit modal
     *
     * @param Request $request
     * @return View
     */
    public function editPage(Request $request): View
    {
        $id = (int) $request->get('id');
        $user = User::find($id);
        $departments = Department::all();
        $roles = Role::all();

        return view('private.admin.users.edit', [
            'user' => $user,
            'departments' => $departments,
            'roles' => $roles
        ]);
    }

    /**
     * Update user info
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $id = $request->get('id');

        $user = User::find($id);

        if ($user->email !== $request->get('email')) {
            $user->email = $request->get('email');
            $user->email_verified_at = null;
        }

        $user->name = $request->get('name');
        $user->last_name = $request->get('last_name');
        $user->department_id = $request->get('department_id');
        $user->role_id = $request->get('role_id');

        $user->save();
        $request->session()->flash('status', __('custom_session_message.flash.apply'));

        return response()->json();
    }

    /**
     * Grants management
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function accessManagement(Request $request)
    {
        $action = $request->get('action');
        $user = User::find($request->get('id'));

        if ($action === User::LOCK) {
            $user->is_blocked = true;
        }

        if ($action === User::UNLOCK) {
            $user->is_blocked = false;
        }

        $user->save();
        $request->session()->flash('status', __('custom_session_message.flash.apply'));

        return response()->json();
    }
}
