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


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class LogService
{

    protected $model;
    protected $table;
    protected $property;

    function __construct($model)
    {
        switch ($model) {
            case 'text':
                $this->model = 'App\Models\Text';
                $this->table = 'texts';
                $this->property = 'text';
                break;
            case 'image':
                $this->model = 'App\Models\Image';
                $this->table = 'images';
                $this->property = 'name';
                break;
            case 'entry':
                $this->model = 'App\Models\Entry';
                $this->table = 'entries';
                $this->property = 'name';
                break;
            case 'chapter':
                $this->model = 'App\Models\Chapter';
                $this->table = 'chapters';
                $this->property = 'name';
                break;
            case 'gallery':
                $this->model = 'App\Models\gallery';
                $this->table = 'galleries';
                $this->property = 'name';
                break;
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function history($id)
    {
        $activities = Activity::where('subject_id', '=', $id)->where('subject_type', '=', $this->model)->orderBy(
            'updated_at',
            'desc'
        )->get();

        $changes = [];
        $logs = [];

        foreach ($activities as $key => $value) {
            if (isset($value->changes)) {
                $firstName = isset($value->causer->name) ? $value->causer->name : null;
                $lastName = isset($value->causer->last_name) ? $value->causer->last_name : null;
                $logs[] = [
                    'id' => $value->id,
                    'userName' => $firstName . ' ' . $lastName,
                    'created_at' => isset($value->created_at) ? $value->created_at : null
                ];
            }
        }

        return $logs;
    }

    /**
     * Get logs text
     *
     * @param $id
     * @return mixed
     */
    public function textLog($id)
    {
        $activities = Activity::where('subject_id', '=', $id)->where('subject_type', '=', $this->model)->orderBy(
            'updated_at',
            'desc'
        )->get();

        $changes = [];

        foreach ($activities as $key => $value) {
            if (isset($value->changes)) {
                foreach ($value->properties['old'] as $key => $property) {
                    $highlight = $this->HighlightTextDifference(
                        $property,
                        $value->properties['attributes'][$key]
                    );

                    $firstName = isset($value->causer->name) ? $value->causer->name : null;
                    $lastName = isset($value->causer->last_name) ? $value->causer->last_name : null;
                    $value->userName = $firstName . ' ' . $lastName;
                    $value->highlight = $highlight['new'];
                    $value->old = $highlight['old'];
                    $changes[]['userName'] = $firstName . ' ' . $lastName;
                    $changes[]['highlight'] = $highlight['new'];
                    $changes[]['old'] = $highlight['old'];
                    $changes[]['subjectId'] = $value->subject_id;
                    $changes[]['created_at'] = $value->created_at;
                    $changes[]['entry_name'] = $value->entry_name;
                    $changes[]['chapter_name'] = $value->chapter_name;
                }
            }
        }

        return $changes;
    }

    /**
     * Difference between text
     *
     * @param $old
     * @param $old
     * @param $new
     * @return string[]
     */
    public function HighlightTextDifference($old, $new)
    {
        $from_start = strspn($old ^ $new, "\0");
        $from_end = strspn(strrev($old) ^ strrev($new), "\0");

        $old_end = strlen($old) - $from_end;
        $new_end = strlen($new) - $from_end;

        $start = substr($new, 0, $from_start);
        $end = substr($new, $new_end);
        $new_diff = substr($new, $from_start, $new_end - $from_start);
        $old_diff = substr($old, $from_start, $old_end - $from_start);

        $new = "$start<span style='background-color:#ccffcc'>$new_diff</span>$end";
        $old = "$start<del style='background-color:#ffcccc'>$old_diff</del>$end";
        return array("old" => $old, "new" => $new);
    }

    /**
     * Get parent text
     *
     * @param $id
     * @return Collection
     */
    public function getParentText($id)
    {
        switch ($this->table) {
            case 'entries':
                return DB::table($this->table)
                    ->join('chapters', 'chapters.id', '=', 'entries.chapter_id')
                    ->select('chapters.name as chapter_name', 'entries.name as entry_name')
                    ->where($this->table . '.id', '=', $id)
                    ->get();
            case 'images':
            case 'texts':
                return DB::table($this->table)
                    ->join('media_content', $this->table . '.id', '=', 'media_content.media_content_id')
                    ->join('entries', 'entries.id', '=', 'media_content.media_contentable_id')
                    ->join('chapters', 'chapters.id', '=', 'entries.chapter_id')
                    ->select('chapters.name as chapter_name', 'entries.name as entry_name')
                    ->where($this->table . '.id', '=', $id)
                    ->where('media_content.media_contentable_type', '=', $this->model)
                    ->get();
        }
    }
}
