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
namespace App\Traits;

use App\Models\Comment;
use App\Models\Image;
use App\Models\MediaContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait CommentTrait
{

    /**
     * Leave comment
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function commentAsUser(Request $request, $class = null)
    {

        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->project_id = $request->IdProjectComment;
        $comment->status = 1;
        $comment->created_at = now();
        $comment->user()->associate($request->user());

        $class = !is_null($class) ? $class : get_class();

       /* if (in_array($class, ['App\Models\Image', 'App\Models\Text','App\Models\Audiovisual','App\Models\Gallery'])){
            switch ($comment->commentable_type){
                case 'App\Models\Image':
                    $comment->commentable_type = 'App\Models\Image';
                    Image::where('id',$request->imageId)->update(['has_comment' => 1]);
                    break;
                case 'App\Models\Text':
                    $comment->commentable_type = 'App\Models\Text';
                    break;
                case 'App\Models\Gallery':
                    $comment->commentable_type = 'App\Models\Gallery';
                    break;
                case 'App\Models\Audiovisual':
                    $comment->commentable_type = 'App\Models\Audiovisual';
                    break;
            }
            $comment->commentable_id = $request->id;
            $comment->save();

        } else{*/
            $model = $class::find($request->id);
            $model->comments()->save($comment);
        //}

        return redirect()->back()->with('success', 'Reply to comment added successfully');
    }

    /**
     * Reply to comment
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function replyAsUser(Request $request)
    {
        $request->validate(
            [
                'reply' => 'required',
            ]
        );

        $reply = new Comment;
        $class = get_class();
        $reply->comment = $request->reply;
        $reply->project_id = $request->projectId;
        $reply->user()->associate($request->user());
        $reply->parent_id = $request->commentId;
        $reply->created_at = now();
        $model = $class::find($request->question);

        $model->comments()->save($reply);

        return redirect()->back()->with('success', 'Reply to comment added successfully');
    }


    /**
     * Edit comment
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function editAsUser(Request $request)
    {
        Comment::where('id',$request['pk'])
                ->update(['comment' => json_encode(['de' => $request['value']])]);

        return redirect()->back()->with('success', 'Comment edited successfully');
    }

    /**
     * Delete comment
     *
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAsUser($id)
    {
        $comment = Comment::find($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully');
    }

    /**
     * Set comment status
     *
     * @param $request
     * @return $this
     */
    public function status($request)
    {
        $comment = Comment::find($request['id']);
        $comment->status = $request['status'];

        $comment->save();

        return $this;
    }

}
