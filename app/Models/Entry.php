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
use Spatie\Translatable\HasTranslations;

class Entry extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CommentTrait, HasTranslations;

    protected static $logName = 'Entry';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['chapter_id', 'name', 'subtitle', 'description', 'position'];
    protected $dates = ['deleted_at'];
    public $translatable = ['name', 'subtitle', 'description'];


    /**
     * Override parent boot and Call deleting event
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function ($entry) {
                foreach ($entry->mediaContent()->get() as $content) {
                    $content->delete();
                }

                foreach ($entry->comments()->get() as $comment) {
                    $comment->delete();
                }

            }

        );
    }

    /**
     * Get the chapter for the current entry
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Get all texts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function texts()
    {
        return $this->hasMany(Text::class);
    }

    /**
     * Get all images
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Get all comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    /**
     * Get media content
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mediaContent()
    {
        return $this->hasMany(MediaContent::class, 'media_contentable_id')->orderBy('position', 'asc');
    }

    /**
     * Get media attributes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getAllMediaAttribute()
    {
        $data = [];

        foreach ($this->mediaContent() as $media) {
            $data[] = $media;
        }
        return $this->mediaContent();
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
