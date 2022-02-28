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

class MediaContent extends Model
{
    use HasFactory, SoftDeletes,CommentTrait,LogsActivity;

    protected static $logName = 'MediaContent';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    public $timestamps = false;
    protected $table = 'media_content';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['media_content_id', 'media_contentable_id', 'media_contentable_type','position'];

    /**
     * Override parent boot and Call deleting event
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function ($content) {
                foreach ($content->gallery()->get() as $gallery) {
                    $gallery->delete();
                }
                foreach ($content->text()->get() as $text) {
                    $text->delete();
                }
                foreach ($content->comments()->get() as $comment) {
                    $comment->delete();
                }

            }

        );
    }

    /**
     * Get media
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function media()
    {
        return $this->morphTo()->orderBy('position', 'DESC');
    }

    /**
     * Get image
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function image(){
        return $this->belongsTo(Image::class, 'media_content_id','id');
    }

    /**
     * Get text
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function text(){
        return $this->belongsTo(Text::class, 'media_content_id','id');
    }

    /**
     * Get entry
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function entry(){
        return $this->belongsToMany(Entry::class, 'media_content', 'id', 'media_contentable_id');
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
     * Get gallery
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gallery(){
        return $this->belongsTo(Gallery::class,'media_content_id', 'id');
    }

    /**
     * Get Audiovisual
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function audiovisual(){
        return $this->belongsTo(Audiovisual::class, 'media_content_id','id');
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
