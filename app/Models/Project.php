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

use App\Traits\CommentTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CommentTrait,HasPermissions, HasTranslations;

    protected static $logName = 'Project';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected $guard_name = 'web';
    protected static $submitEmptyLogs = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'user_id', 'logo', 'imprint', 'terms', 'status','description'];
    public $translatable = ['name','imprint','terms','description'];
    /*
     * Get all of the chapters for the project
     */


    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('position', 'asc');
    }

    /*
     * Get user from project
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
     * Get single chapter from project
     */
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Get comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    /**
     * Granted users
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permittedUsers(){
        return $this->hasMany(ModelHasPermission::class,'project_id');
    }

    /**
     * Grant user's right
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grantUserRights(){
        return $this->hasMany(UserHasPermission::class);
    }

    /**
     * Add language to log
     *
     * @param Activity $activity
     */
    public function tapActivity(Activity $activity)
    {
        $activity->properties = $activity->properties->merge([
                                                                 'language' => Lang::getLocale(),
                                                             ]);
    }
}
