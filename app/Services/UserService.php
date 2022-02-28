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
namespace App\Services;


use App\Models\Permission;
use App\Models\UserHasPermission;
use Illuminate\Support\Facades\Auth;

class UserService
{

    /**
     * Granted user's permissions list
     *
     * @param $id
     * @return array[]
     */
    public function getAllUsers($id)
    {
        /**
         * 1 = view
         * 2 = add
         * 3 = edit
         * 4 = delete
         * 5 = publish
         * 6 = comment
         *
         */
        $userDefaultPermissions = Auth::user()->getAllPermissions()->pluck('name')->toArray();

        $userCurrentPermissions = UserHasPermission::where('project_id', $id)
            ->join('permissions','permissions.id','=','user_has_permissions.permission_id')
            ->where('user_id', Auth::user()->id)->pluck('permissions.name')->toArray();

        if(count($userCurrentPermissions) > 0){
            return $userCurrentPermissions;
        }else{
            return $userDefaultPermissions;
        }

    }
}
