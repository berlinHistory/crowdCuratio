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
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PermissionDescription extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['description'];
    public $translatable = ['description'];
    protected $table = 'permission_descriptions';

    /**
     * Get permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission(){
        return $this->belongsTo(Permission::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function ($permission) {
                foreach ($permission->description()->get() as $description) {
                    $description->delete();
                }
            }
        );
    }
}
