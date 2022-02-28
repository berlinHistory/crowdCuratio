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
namespace App\Http\Controllers;

use App\Models\Audiovisual;
use App\Models\MediaContent;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AudiovisualController extends Controller
{

    use UploadTrait;
    /**
     * Instantiate a new AudioVisualController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

    }

    /**
     * Store or update audiovisual
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){

        if($request->has('audio')){
            $request['link'] = $this->uploadAudio($request);
        }else{
            $request['link'] = ($this->youtubeID($request['link'])) ? "https://www.youtube.com/embed/".$this->youtubeID($request['link']) : $request['link'];
        }

        if(isset($request['audiovisualId']) && $request['audiovisualId'] != ''){
            $model = Audiovisual::findOrFail($request['audiovisualId']);

            if($request['translationMode']){
                if(!is_null($request['link'])) $model->setTranslation('link', 'en', $request['link']);
                $model->setTranslation('copyright', 'en', $request['copyright']);
                $model->setTranslation('source', 'en', $request['source']);
                $model->is_translated = isset($request['isTranslated']) ? 1 : 0;

            }else{

                if(!is_null($request['link'])) $model->link = $request['link'];
                if(!is_null($request['type'])) $model->type = $request['type'];
                if(!is_null($request['copyright'])) $model->copyright = $request['copyright'];
                if(!is_null($request['source'])) $model->source = $request['source'];
            }

            $model->save();
            return redirect()->back()->with('success', __("message_update_success"));
        }

        $item = Audiovisual::create($this->mapData($request));
        $this->attachMedia($item->id, $request['entryId'], 'App\Models\Audiovisual');

        return redirect()->back()->with('success', __("message_add_success"));
    }


    /**
     * Delete audiovisual
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $id){

        //Detach from media content
        MediaContent::where('media_content_id',$id)->where('media_contentable_type','App\Models\Audiovisual')->delete();

        //delete content
        Audiovisual::where('id', $id)->delete();

        return redirect('projects/'.$request->project.'/edit')->with('success', __("message_delete_success"));

    }

    /**
     * Map incoming data
     *
     * @param $request
     * @return array
     */
    protected function mapData($request){

        $data = [];

        if(isset($request['link'])) $data['link'] = $request['link'];
        if(isset($request['source'])) $data['source'] = $request['source'];
        if(isset($request['copyright'])) $data['copyright'] = $request['copyright'];
        if(isset($request['type'])) $data['type'] = $request['type'];

        return $data;

    }

    /**
     * Attach audiovisual to media content
     *
     * @param $id
     * @param $entry
     * @param $type
     * @return mixed
     */
    public function attachMedia($id, $entry, $type)
    {
        //get last position
        $position = MediaContent::where('media_contentable_id', $entry)->orderBy('position', 'desc')->first();

        $pos = 0;
        if(!empty($position->position)) $pos = $position->position;

        return MediaContent::create(
            [
                'position' => $pos + 1,
                'media_content_id' => $id,
                'media_contentable_id' => $entry,
                'media_contentable_type' => $type
            ]
        );
    }

    /**
     * Upload audio
     *
     * @param $request
     * @return string
     */
    protected function uploadAudio($request){
        // Define folder path
        $folder = '/uploads/audio/';

        if ($request->has('audio')) {
            // Get image file
            $audio = $request->file('audio');

            // Make a image name based on user name and current timestamp
            $name = $request->file('audio')->getClientOriginalName();
        }

        if ($request->has('newImage')) {
            // Get image file
            $audio = $request->file('newImage');

            // Make an image name based on user name and current timestamp
            $name = $request->file('newImage')->getClientOriginalName();
        }

        if ($name != '' && $audio != '') {
            $name = Str::random(10);
            $this->uploadOne($audio, $folder, 'public', $name);
        }

        return $name;
    }

    /**
     * Comment or reply on audiovisual
     *
     * @param Request $request
     * @param Audiovisual $audiovisual
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function commentAudiovisual(Request $request, Audiovisual $audiovisual){

        if (isset($request['btn_submit'])) {
            if ($request['btn_submit'] == 'Edit') {
                return $audiovisual->editAsUser($request);
            } elseif ($request['btn_submit'] == 'delete') {
                return $audiovisual->deleteAsUser($request['id']);
            } else {
                $model = Audiovisual::findOrFail($request['question']);

                return $model->replyAsUser($request);
            }
        }

        return $this;
    }

    /**
     * New comment on audiovisual
     *
     * @param Request $request
     * @param Audiovisual $audiovisual
     * @return \Illuminate\Http\RedirectResponse
     */
    public function audiovisualCommentSave(Request $request, Audiovisual $audiovisual){

        $request->validate(
            [
                'comment' => 'required',
            ]
        );

        return $audiovisual->commentAsUser($request,'App\Models\Audiovisual');
    }

    /**
     * Get youtube video ID
     *
     * @param $url
     * @return false|mixed
     */
    protected function youtubeID($url)
    {
        if(strlen($url) > 11)
        {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match))
            {
                return $match[1];
            }
            else
                return false;
        }

        return $url;
    }


}
