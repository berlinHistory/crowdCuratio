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
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Text extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CommentTrait,HasTranslations;

    protected static $logName = 'Text';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'text', 'origin', 'copyright', 'position'];
    public $translatable = ['text'];

    /**
     * Get text origin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function originText()
    {
        return $this->belongsTo(Source::class, 'origin');
    }

    /**
     * Get text copyright
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function copyrightText()
    {
        return $this->belongsTo(Source::class, 'copyright');
    }

    /**
     * Get all comments
     *
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    /**
     * Get media
     *
     * @return MorphMany
     */
    public function medias()
    {
        return $this->morphMany(MediaContent::class, 'media');
    }

    public function entry(){
        return $this->morphToMany('App\Models\Text','media_contentable','media_content','media_contentable_id','id');
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
