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

use App\Models\Entry;
use App\Services\CommentRetrieve;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    /**
     * Instantiate a new EntryController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request['entryId']) && $request['entryId'] != '') {
            $this->update($request);

            return redirect()->back()->with('success', __("message_edit_entry_success"));
        } else {

            $pos = Entry::where('chapter_id', $request['chapterId'])->orderBy('position', 'desc')->first();
            $position = 0;
            if(!empty($pos->position)) $position = $pos->position;

            Entry::create(
                [
                    'chapter_id' => $request['chapterId'],
                    'name' => $request['entryTitle'],
                    'subtitle' => $request['entrySubtitle'],
                    'description' => $request['entryDescription'],
                    'position' => $position + 1

                ]
            );

            return redirect()->back()->with('success', __("message_add_entry_success"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $entry = Entry::find($request['entryId']);

        if (isset($request['translationEntry'])){
            $entry->setTranslation('name', 'en', $request['entryTitle']);
            $entry->setTranslation('subtitle', 'en', $request['entrySubtitle']);
            if ($request['entryDescription'] != "undefined") $entry->setTranslation('description', 'en', $request['entryDescription']);
        }else{
            $entry->name = $request['entryTitle'];
            $entry->subtitle = $request['entrySubtitle'];
            $entry->description = $request['entryDescription'];
        }

        $entry->is_translated = isset($request['isTranslated']) ? 1 : 0;

        $entry->save();

        return $this;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Entry::findOrFail($id);

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $entry = Entry::find($id);
        $entry->delete();

        return redirect('projects/'.$request->project.'/edit')->with('success', __("message_delete_entry_success"));
    }

    /**
     * Comment entry
     *
     * @param Request $request
     * @param Entry $entry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function commentEntry(Request $request, Entry $entry)
    {
        $request->validate(
            [
                'comment' => 'required',
            ]
        );
        return $entry->commentAsUser($request,'App\Models\Entry');
    }

    /**
     * Retrieve all comment of current entry
     *
     * @param $id
     * @return JsonResponse
     */
    public function getEntryComment($id)
    {
        $comment = new CommentRetrieve();
        return $comment->getComments('App\Models\Entry', $id);
    }

    /**
     * Save current entry
     *
     * @param Request $request
     * @param Entry $entry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveCommentEntry(Request $request, Entry $entry)
    {
        if(isset($request['name']) && $request['name'] == 'edit'){
            return $entry->editAsUser($request);
        }

        if (isset($request['btn_submit'])) {
            if ($request['btn_submit'] == 'Edit') {
                return $entry->editAsUser($request);
            } elseif ($request['btn_submit'] == 'delete') {
                return $entry->deleteAsUser($request['id']);
            } else {
                return $entry->replyAsUser($request);
            }
        }
    }

    /**
     * Set status entry
     *
     * @param Request $request
     * @param Entry $entry
     * @return JsonResponse
     */
    public function setStatusEntry(Request $request, Entry $entry)
    {
        $data = $entry->status($request);
        return response()->json($data);
    }

}
