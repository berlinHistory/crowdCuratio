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
namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //User edit
        Gate::define(
            'edit-project',
            function (User $user, $project) {
                if ($user->roles->first()->name == 'Admin') {
                    return true;
                }
                return $user->id === $project;
            }
        );

        //User add
        Gate::define(
            'add-project',
            function (User $user, $project) {
                if ($user->roles->first()->name == 'Admin') {
                    return true;
                }
                return $user->id === $project;
            }
        );

        //User delete
        Gate::define(
            'delete-project',
            function (User $user, $project) {
                if ($user->roles->first()->name == 'Admin') {
                    return true;
                }
                return $user->id === $project;
            }
        );

        //User publish
        Gate::define(
            'publish-project',
            function (User $user, $project) {
                if ($user->roles->first()->name == 'Admin') {
                    return true;
                }
                return $user->id === $project;
            }
        );

        //User comment
        Gate::define(
            'comment-project',
            function (User $user, $project) {
                if ($user->roles->first()->name == 'Admin') {
                    return true;
                }
                return $user->id === $project;
            }
        );
    }
}
