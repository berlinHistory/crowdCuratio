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
namespace Database\Seeders;

use App\Models\PermissionDescription;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PreviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = Permission::updateOrCreate(['name' => 'preview']);
        PermissionDescription::updateOrCreate(['permission_id' => $permission->id],['description' => ['de' => '<strong>Vorschau-Zugriff</strong>, Rollen mit diesem Recht können nur die Vorschau des Projektes aufrufen, zu dem sie auch eingeladen sind.', 'en' => '<strong>Preview access</strong>, roles with this right have the access to the preview of projects they are invited to.']]);

    }
}
