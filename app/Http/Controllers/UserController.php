<?php
/**
crowdCuratio - Curating together virtually
Copyright (C)2022 - berlinHistory e.V.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program in the file LICENSE.

If not, see <https://www.gnu.org/licenses/>.
 */
namespace App\Http\Controllers;

use App\Models\MailSetting;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only(['index', 'edit', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = DB::table('users')
            ->join('model_has_roles', 'model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('users.*', 'roles.name as role')
            ->whereNull('deleted_at')
            ->get();

        return view('users.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        if (isset($request['old_password']) && $request['old_password'] != '') {
            $request->validate(
                [
                    'old_password' => [
                        'required',

                        function ($attribute, $value, $fail) use ($user) {
                            if (!Hash::check($value, $user->password)) {
                                $fail('__("message_old_password_incorrect")');
                            }
                        }
                    ],
                    'new_password' => 'required|min:8',
                    'confirm_password' => 'required|same:new_password',
                ]
            );

            $request->validate(
                [
                    'firstName' => 'required|string|max:255',
                    'lastName' => 'required|string|max:255',
                ]
            );

            $user->name = $request['firstName'];
            $user->last_name = $request['lastName'];
            $user->password = Hash::make($request['new_password']);
            $user->save();

            if (isset($request['roles'])) {
                $user->syncRoles($request->input('roles'));
            }

            return redirect()->back()->with('success', __("message_edit_profile_success"));
        } else {
            $request->validate(
                [
                    'firstName' => 'required|string|max:255',
                    'lastName' => 'required|string|max:255',

                ]
            );

            $user->name = $request['firstName'];
            $user->last_name = $request['lastName'];
            $user->is_admin = isset($request['adminUser']) ? $request['adminUser'] : 0;
            $user->create_project = isset($request['createProject']) ? $request['createProject'] : 0;
            $user->save();

            if (isset($request['roles'])) {
                $user->syncRoles($request->input('roles'));
            }

            return redirect()->back()->with('success', __("message_edit_user_success"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', __("message_delete_user_success"));
    }

    /**
     * Get own profile
     *
     * @return Application|Factory|View
     */
    public function profile()
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('users.profile', compact('roles'));
    }

    /**
     * Resend invitation
     *
     * @param $id
     * @return $this
     */
    public function resendInvitation($id){

        $mail = !empty(MailSetting::first()) ? MailSetting::first() : null;

        $expiresAt = now()->addDay(3);
        $invitation = (isset($mail['invitation']) && !empty(strip_tags($mail['invitation']))) ? strip_tags(
            $mail['invitation']
        ) : config('project.mail.default');

         User::where('id',$id)
        ->update(['welcome_valid_until' => $expiresAt,
                     'updated_at' => now()]);

        $user = User::findOrFail($id);
        $user->sendWelcomeNotification($expiresAt, $user->last_name, $invitation);

        return redirect()->back()->with('success', __("invitation_resent"));
    }

}
