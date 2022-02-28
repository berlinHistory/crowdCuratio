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

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'view' => ['de' => '<strong>Lesen</strong>, Rollen mit diesem Recht dürfen den Inhalt des zugewiesenen Projekts sehen.', 'en' => '<strong>view</strong>, Roles with this right are allowed to see the content of assigned project.'],
            'add' => ['de' => '<strong>Hinzufügen/ Eigenes bearbeiten</strong>,  Rollen mit diesem Recht benötigen "Lese"-Rechte & dürfen Inhalte zum zugewiesenen Projekt hinzufügen und eigenen Content editieren, aber keine Inhalte Anderer bearbeiten.', 'en' => '<strong>add / edit own</strong>, Roles with this right need "view" rights & are allowed to add content to assigned project and adjust own content.'],
            'edit' => ['de' => '<strong>Alles bearbeiten</strong>, Rollen mit diesem Recht benötigen "Lese" und "Hinzufügen/ Eigenes bearbeiten"-Rechte & dürfen den gesamten Inhalt des zugewiesenen Projekts bearbeiten.', 'en'=> '<strong>edit all</strong>, Roles with this right need "view" and "add" rights & are allowed to edit whole content of assigned project.'],
            'delete' => ['de' => '<strong>Löschen</strong>,  Rollen mit diesem Recht benötigen "Lese" & "Bearbeiten"-Rechte & dürfen den Inhalt des zugewiesenen Projekts löschen.', 'en'=> '<strong>delete</strong>, Roles with this right need "view", "add" and "edit" rights & are allowed to delete content of assigned project.'],
            'publish' => ['de' => '<strong>Publizieren</strong>, Rollen mit diesem Recht benötigen "Lese" und "Bearbeiten"-Rechte & dürfen Inhalte des zugewiesenen Projekts veröffentlichen.', 'en' => '<strong>publish</strong>, Roles with this right need "view", "add" and "edit" rights & are allowed to publish content of assigned project.'],
            'comment' => ['de' => '<strong>Kommentieren</strong>, Rollen mit diesem Recht benötigen "Lese"-Rechte & dürfen den Inhalt des zugewiesenen Projekts sehen und kommentieren.', 'en' => '<strong>comment</strong>, Roles with this right need "view" rights & are allowed to see and comment the content of assigned project.'],
            'invite' => ['de' => '<strong>Nutzer einladen</strong>, Rollen mit diesem Recht dürfen neue Benutzer zu ihren Projekten einladen.', 'en' => '<strong>invite user</strong>, Roles with this right will be allowed to invite new user to their projects.'],
        ];

        foreach ($permissions as $key => $value) {
            $permission = Permission::updateOrCreate(['name' => $key]);
            PermissionDescription::updateOrCreate(['permission_id' => $permission->id], ['description' => $value]);
        }
    }
}
