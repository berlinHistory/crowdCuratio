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
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\MailSetting;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\User;
use App\Models\UserHasPermission;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{

    /**
     * Instantiate a new ProjectController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the registration view.
     *
     * @return View
     */
    public function create()
    {
        $roles = Role::where('id', 'not like', '1')->pluck('name', 'name')->all();
        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return RedirectResponse
     *
     */
    public function store(Request $request)
    {
        $mail = !empty(MailSetting::first()) ? MailSetting::first() : null;

        $request->validate(
            [
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'roles' => 'required',
                'policy' => 'required'
            ]
        );


        $ifUserExists = DB::table('users')->where('email', $request->email)->first();

        if ($ifUserExists != '') {
            $affected = DB::table('users')
                ->where('email', $request->email)
                ->update(['deleted_at' => null]);


            return redirect()->route('users.index')->with(
                'success',
                'Dieser Nutzer war inaktivert und wurde soeben wieder reaktiviert. Über die Login Seite kann mit den bestehenden Zugangsdaten wieder auf das CMS zugegriffen werden.'
            );
        } else {

            $user = User::create($this->formatData($request));

            event(new Registered($user));

            if(isset($request->adminUser)){
                $user->assignRole(1);
            }else{
                $user->assignRole($request->input('roles'));
            }

            $expiresAt = now()->addDay(3);
            $invitation = (isset($mail['invitation']) && !empty(strip_tags($mail['invitation']))) ? strip_tags(
                $mail['invitation']
            ) : config('project.mail.default');

            $user->sendWelcomeNotification($expiresAt, $request->firstName, $invitation);

            if (isset($request->projectId)) {
                $permissions = RoleHasPermission::where('role_id',$request->input('roles'))->pluck('permission_id');
                foreach ($permissions as $permission){
                    UserHasPermission::create([
                        'project_id' => $request->projectId,
                        'permission_id' => $permission,
                        'user_id' => $user->id,
                        'created_at' => now()
                                              ]);
                }

                Invitation::create([
                    'user_id' => Auth::user()->id,
                    'guest_id' => $user->id,
                    'project_id' => $request->projectId,
                    'created_at' => now()
                                   ]);

                return Redirect()->back()->with('success', 'User added successful');
            } else {
                return redirect()->route('users.index')->with('success', 'User added successful');
            }
        }
    }

    protected function formatData($request){

        $data = [];

        if(isset($request->firstName)) $data['name'] = $request->firstName;
        if(isset($request->lastName)) $data['last_name'] = $request->lastName;
        if(isset($request->email)) $data['email'] = $request->email;
        if(isset($request->adminUser)) $data['is_admin'] = $request->adminUser;
        if(isset($request->createProject)) $data['create_project'] = $request->createProject;

        $data['password'] = Hash::make(Str::random(8));
        $data['created_at'] = now();

        return $data;
    }

}
