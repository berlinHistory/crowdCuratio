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
use App\Models\Comment;
use App\Models\Gallery;
use App\Models\Image;
use App\Models\MediaContent;
use App\Models\Project;
use App\Models\Source;
use App\Models\Text;
use App\Services\CommentRetrieve;
use App\Traits\SourceTrait;
use App\Traits\UploadTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpParser\Builder;
use Symfony\Component\ErrorHandler\Debug;

class ContentController extends Controller
{
    use SourceTrait;
    use UploadTrait;

    /**
     * Instantiate a new ContentController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Delete Text
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function destroyText(Request $request,$id)
    {
        //Detach media
        $this->detachMedia($id, 'App\Models\Text');

        $text = Text::find($id);
        $text->delete();

        return redirect('projects/'.$request->project.'/edit')->with('success', __("message_delete_text_success"));
    }

    /**
     * Detach media from entry
     *
     *
     * @param $id
     * @param $type
     * @return mixed
     */
    public function detachMedia($id, $type)
    {

        Comment::where('commentable_id',$id)->where('commentable_type',$type)->update(['deleted_at' => now()]);

        return MediaContent::where('media_contentable_id', $id)
            ->where('media_contentable_type', $type)
            ->update(['deleted_at' => now()]);
    }

    /**
     * Delete Image
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function destroyImage(Request $request, $id)
    {
        $image = Image::find($id);
        $image->delete();

        return redirect('projects/'.$request->project.'/edit')->with('success', __("message_delete_image_success"));
    }

    /**
     * Ajax autocomplete
     *
     * @param Request $request
     * @return array
     */
    public function autocomplete(Request $request)
    {
        $data = [];
        $res = Source::where('name', 'like', '%' . $request->input("query") . '%')
            ->where('type', '=', $request->input("type"))
            ->get(['id','name']);

        foreach ($res as $key => $value){
            $data[$key] = $value->name;
        }

        return $data;
    }

    /**
     * Save or update image
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveImage(Request $request)
    {

        if (isset($request['translationMode'])){
            if (isset($request['originId'])){
                $this->translateField($request['originId'], $request['originField'], $request['isTranslated']);
            }

            if(isset($request['copyrightId'])){
                $this->translateField($request['copyrightId'], $request['copyrightField'], $request['isTranslated']);
            }

            if(isset($request['altField'])){
                $image = Image::findOrFail($request['imageId']);
                $image->setTranslation('alt', 'en', $request['altField']);
                $image->save();
            }

            return redirect()->back()->with('success', __("message_edit_image_success"));
        }

        $request->validate(
            [
                'copyrightImage' => 'required',
                'originImage' => 'required'
            ]
        );

        if (isset($request['imageId']) && $request['imageId'] != '') {
            $this->updateImage($request);
            return redirect()->back()->with('success', __("message_edit_image_success"));
        } else {
            $request->validate(
                [
                    'image' => 'required',
                ]
            );

            $name = '';
            // Check if an image should be uploaded
            $name = $this->setImage($request);
            $position = Image::where('gallery_id',$request['galleryId'])->orderBy('position','desc')->pluck('position')->first();

            //Set copyright value
            $copyright = $this->getSource($request['copyrightImage'], 'Copyright');

            //Set origin value
            $origin = $this->getSource($request['originImage'], 'Origin');

            $im = Image::firstOrCreate(
                [
                    'gallery_id' => $request['galleryId'],
                    'image' => $name,
                    'position' => $position + 1,
                    'origin' => $origin,
                    'copyright' => $copyright,
                    'url' => Storage::path($name),
                    'alt' => $request['altText'],
                    'created_at' => now()
                ]
            );

            //Attach image to gallery
            //$this->attachMedia($im->id, $request['entryId'], 'App\Models\Image');

            //Attach gallery to entry
            //$this->attachMedia($im->id, $request['entryId'], 'App\Models\Image');

            return redirect()->back()->with('success', __("message_add_image_success"));
        }
    }

    /**
     * Update image
     *
     * @param Request $request
     * @return $this
     */
    public function updateImage(Request $request)
    {
        $image = Image::find($request['imageId']);

        //Set copyright value
        $copyright = $this->getSource($request['copyrightImage'], 'Copyright');

        //Set origin value
        $origin = $this->getSource($request['originImage'], 'Origin');

        if (isset($request['newImage']) && !is_null($request['newImage'])) {
            // Check if an image should be uploaded
            $name = $this->setImage($request);
            $image->image = $name;
            $image->url = Storage::path($name);
        }

        $image->origin = $origin;
        $image->copyright = $copyright;
        $image->updated_at = now();

        if (isset($request['altText'])){
            $image->alt = $request['altText'];
        }

        $image->save();

        return $this;
    }

    /**
     * Translate metadata
     *
     * @param $id
     * @param $request
     * @return $this
     */
    public function translateField($id,$field,$translated){

        $source = Source::findOrFail($id);
        $source->setTranslation('name', 'en', $field);
        $source->is_translated = isset($translated) ? 1 : 0;

        $source->save();

        return $this;

    }

    /**
     * Get source of content
     *
     * @param $value
     * @param $type
     * @return mixed
     */
    protected function getSource($value, $type)
    {
        $source = Source::where('type',$type)->get();
        $id = '';
        foreach ($source as $key => $v){
            if($v->name == $value){
                $id = $v->id;
                return $id;
            }
        }

        if($id == ''){
            $id = Source::insertGetId(['name'=> json_encode([app()->getLocale() => $value]), 'type' => $type, 'created_at' => now()]);
            return $id;
        }

        return $this;

    }

    /**
     * Set Image
     *
     * @param Request $request
     * @return string
     */
    protected function setImage(Request $request)
    {
        $name = '';
        $image = '';

        // Define folder path
        $folder = '/uploads/images/';

        if ($request->has('image')) {
            // Get image file
            $image = $request->file('image');

            // Make a image name based on user name and current timestamp
            $name = date('Ymd').'_'.time().'.'.$request->file('image')->extension();
        }

        if ($request->has('newImage')) {
            // Get image file
            $image = $request->file('newImage');

            // Make a image name based on user name and current timestamp
            $name = date('Ymd').'_'.time().'.'.$request->file('newImage')->extension();
        }

        if ($name != '' && $image != '') {
            $this->uploadOne($image, $folder, 'public', $name);
        }

        return $name;
    }

    /**
     * Attach media to entry
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
     * Get selected Image to be modified
     *
     * @param $id
     * @return JsonResponse
     */
    public function editImage($id)
    {
        $image = Image::findOrFail($id);
        $data = ['id' => $image->id, 'image' => $image->image, 'url' => $image->url, 'alt' => $image->alt, 'origin' => $image->originImage->name, 'copyright' => $image->copyrightImage->name];

        return response()->json($data);
    }

    /**
     * Save or update text
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveText(Request $request)
    {

        if (isset($request['translationMode'])){
            if (isset($request['textId'])){
                $this->saveTranslatedText($request);
            }
            if (isset($request['originId'])){
                $this->translateField($request['originId'], $request['originField'], $request['isTranslated']);
            }

            if(isset($request['copyrightId'])){
                $this->translateField($request['copyrightId'], $request['copyrightField'], $request['isTranslated']);
            }

            return redirect()->back()->with('success', __("message_edit_text_success"));
        }

        $request->validate(
            [
                'contentText' => 'required',
                'copyrightText' => 'required',
                'originText' => 'required'
            ]
        );

        if (isset($request['textId']) && $request['textId'] != '') {
            $this->updateText($request);
            return redirect()->back()->with('success', __("message_edit_text_success"));
        } else {
            //Set copyright value
            $copyright = $this->getSource($request['copyrightText'], 'Copyright');

            //Set origin value
            $origin = $this->getSource($request['originText'], 'Origin');

            //filter text before saving
            $strClean = str_replace(array('<script>', '</script>'), array('', ''), $request['contentText']);

            $id = Text::insertGetId(
                [
                    'text' => json_encode([app()->getLocale() => $strClean]),
                    'origin' => $origin,
                    'copyright' => $copyright,
                    'created_at' => now()
                ]

            );

            //Attach text to entry
            $this->attachMedia($id, $request['entryId'], 'App\Models\Text');

            return redirect()->back()->with('success', __("message_add_text_success"));
        }
    }

    /**
     * Update text
     *
     * @param Request $request
     * @return $this
     */
    public function updateText(Request $request)
    {
        //Set copyright value
        $copyright = $this->getSource($request['copyrightText'], 'Copyright');

        //Set origin value
        $origin = $this->getSource($request['originText'], 'Origin');

        //filter text before saving
        $strClean = str_replace(array('<script>', '</script>'), array('', ''), $request['contentText']);

        $text = Text::find($request['textId']);
        $text->text = $strClean;
        $text->origin = $origin;
        $text->copyright = $copyright;
        $text->updated_at = now();
        $text->is_translated = isset($request['isTranslatedText']) ? 1 : 0;
        $text->save();

        return $this;
    }

    /**
     * get selected text to be modified
     *
     * @param $id
     * @return JsonResponse
     */
    public function editText($id)
    {
        $text = Text::findOrFail($id);
        $data = ['id' => $text->id, 'text'=> $text->text, 'origin' => $text->originText->name, 'copyright' => $text->copyrightText->name];

        return response()->json($data);
    }

    /**
     * Comment Text
     *
     * @param Request $request
     * @param Text $text
     * @return RedirectResponse
     */
    public function commentText(Request $request, Text $text)
    {

        $request->validate(
            [
                'comment' => 'required',
            ]
        );

        return $text->commentAsUser($request);
    }

    /**
     * Retrieve all comment of current text
     *
     * @param $id
     * @return JsonResponse
     */
    public function getTextComment($id)
    {
        $comment = new CommentRetrieve();
        return $comment->getComments('App\Models\MediaContent', $id);
    }

    /**
     * Save current text
     *
     * @param Request $request
     * @param Text $text
     * @return RedirectResponse
     */
    public function saveCommentText(Request $request, Text $text)
    {
        if (isset($request['btn_submit'])) {
            if ($request['btn_submit'] == 'Edit') {
                return $text->editAsUser($request);
            } elseif ($request['btn_submit'] == 'delete') {
                return $text->deleteAsUser($request['id']);
            } else {
                $model = Text::findOrFail($request['question']);

                return $model->replyAsUser($request);
            }
        }
    }

    /**
     * Comment Image
     *
     * @param Request $request
     * @param Image $image
     * @return RedirectResponse
     */
    public function commentImage(Request $request, Image $image)
    {
        $request->validate(
            [
                'comment' => 'required',
            ]
        );

        return $image->commentAsUser($request);
    }

    /**
     * Retrieve all comment of current image
     *
     * @param $id
     * @return JsonResponse
     */
    public function getImageComment($id)
    {
        $comment = new CommentRetrieve();
        return $comment->getComments('App\Models\MediaContent', $id);
    }

    /**
     * Save current image
     *
     * @param Request $request
     * @param Image $image
     * @return RedirectResponse
     */
    public function saveCommentImage(Request $request, Image $image)
    {
        if(isset($request['name']) && $request['name'] == 'edit'){
            return $image->editAsUser($request);
        }

        if (isset($request['btn_submit'])) {
            if ($request['btn_submit'] == 'Edit') {
                return $image->editAsUser($request);
            } elseif ($request['btn_submit'] == 'delete') {
                return $image->deleteAsUser($request['id']);
            } else {
                $model = Image::findOrFail($request['question']);

                return $model->replyAsUser($request);
            }
        }
    }

    /**
     * Set status text
     *
     * @param Request $request
     * @param Text $text
     * @return JsonResponse
     */
    public function setStatusText(Request $request, Text $text)
    {
        $data = $text->status($request);
        return response()->json($data);
    }

    /**
     * Set status image
     *
     * @param Request $request
     * @param Image $image
     * @return JsonResponse
     */
    public function setStatusImage(Request $request, Image $image)
    {
        $data = $image->status($request);
        return response()->json($data);
    }

    /**
     * Reset text
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function resetText(Request $request)
    {
        $model = Text::findOrFail($request['idReset']);
        $model->text = $request['valueReset'];

        $model->save();

        return redirect()->back()->with('success', 'Text reset successfully');
    }

    /**
     * List all comments
     *
     * @return Application|Factory|View
     */
    public function listComments()
    {

        if(Auth::user()->isAdmin()){
            $comments = Comment::with('User')->whereNotNull('project_id')->get();

            return view('contents.comment', compact('comments'));
        }

        $projects = Project::query()
            ->join('users', 'users.id', '=', 'projects.user_id')
            ->leftJoin('invitations', 'invitations.project_id', '=', 'projects.id')
            ->distinct()
            ->Where(function($query) {
                $query->where('invitations.guest_id',Auth::user()->id)
                    ->orWhere('projects.user_id',Auth::user()->id);
            })
            ->whereNull('projects.deleted_at')
            ->whereNull('users.deleted_at')
            ->whereNotNull('project_id')
            ->pluck('projects.id')->toArray();

        $comments = Comment::whereIn('project_id', $projects)->whereNotNull('project_id')->get();


        return view('contents.comment', compact('comments'));
    }

    /**
     * Save translation text
     *
     * @param Request $request
     * @return $this
     */
    public function saveTranslatedText(Request $request){

        $text = Text::findOrFail($request['textId']);

        //filter text before saving
        if($request['text'] != "undefined"){
            $strClean = str_replace(array('<script>', '</script>'), array('', ''), $request['text']);
            $text->setTranslation('text', 'en', $strClean);
        }
        $text->is_translated = isset($request['isTranslated']) ? 1 : 0;
        $text->save();

        return $this;
    }

    /**
     * Update status
     *
     * @param $id
     * @param $status
     * @return RedirectResponse
     */
    public function updateStatus($id, $status){

       Comment::where('id', $id)->update(['status' => $status]);

        return redirect()->back()->with('success', __("message_status_success"));
    }

    public function saveGallery(Request $request){

        if(isset($request['galleryId']) && $request['galleryId'] != ''){
            $gallery = Gallery::findOrFail($request['galleryId']);

            if (isset($request['translationGallery'])){
                $gallery->setTranslation('title', 'en', $request['galleryTitle']);
                $gallery->setTranslation('subtitle', 'en', $request['gallerySubtitle']);
                $gallery->setTranslation('description', 'en', $request['galleryDescription']);
            }else {

                $gallery->title = $request['title'];
                $gallery->subtitle = $request['subtitle'];
                $gallery->description = $request['description'];

            }

            $gallery->is_translated = isset($request['isTranslated']) ? 1 : 0;
            $gallery->save();

            return redirect()->back()->with('success', __("message_update_success"));
        }

        $gallery = Gallery::create($this->mapData($request));
        $this->attachMedia($gallery->id, $request['entryId'], 'App\Models\Image');

        return redirect()->back()->with('success', __("message_gallery_success"));

    }

    /**
     * Mapping request
     *
     * @param $data
     * @return array
     */
    protected function mapData($data){

        $result = [];

        if(isset($data['entryId']) && $data['entryId'] != ''){

            $result['entryId'] = $data['entryId'];

        }

        if (isset($data['title'])) $result['title'] = $data['title'];
        if (isset($data['subtitle'])) $result['subtitle'] = $data['subtitle'];
        if (isset($data['description'])) $result['description'] = $data['description'];

        return $result;
    }


    /**
     * Get gallery
     *
     * @param $id
     * @return JsonResponse
     */
    public function editGallery($id){

        $gallery = Gallery::where('id',$id)->first();

        return \response()->json($gallery);
    }


    /**
     * Destroy gallery
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function destroyGallery(Request $request,$id){

        //Detach media
        $this->detachMedia($id, 'App\Models\Gallery');

        //delete from image
        DB::table('images')->where('gallery_id', '=', $id)->update(['deleted_at' => now()]);

        DB::table('galleries')->where('id', '=', $id)->update(['deleted_at' => now()]);

        return redirect('projects/'.$request->project.'/edit')->with('success', __("message_delete_text_success"));
    }

    /**
     * Comment or reply on gallery
     *
     * @param Request $request
     * @param Gallery $gallery
     * @return $this|RedirectResponse
     */
    public function commentGallery(Request $request, Gallery $gallery){

        if (isset($request['btn_submit'])) {
            if ($request['btn_submit'] == 'Edit') {
                return $gallery->editAsUser($request);
            } elseif ($request['btn_submit'] == 'delete') {
                return $gallery->deleteAsUser($request['id']);
            } else {
                $model = Gallery::findOrFail($request['question']);

                return $model->replyAsUser($request);
            }
        }

        return $this;
    }

    /**
     * New comment on audiovisual
     *
     * @param Request $request
     * @param Gallery $gallery
     * @return RedirectResponse
     */
    public function galleryCommentSave(Request $request, Gallery $gallery){

        $request->validate(
            [
                'comment' => 'required',
            ]
        );

        return $gallery->commentAsUser($request,'App\Models\Gallery');
    }
}
