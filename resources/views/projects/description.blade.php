<!--
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

If not, see <https://www.gnu.org/licenses/>. -->

@section('log')

@endsection
@section('sidebar')
    @if(isset($isComment) && $isComment == true)
        @if(isset($comments['comment']) && count($comments['comment']) > 0)
            @foreach($comments['comment'] as $key => $comment)
                <div class="row mb-4 mt-4">
                    <div class="form-group col-sm-8">
                        @if(in_array('edit', $listPermissions) || Auth::user()->can('edit-project', $project->user_id))
                            <label class="col-xs-6 control-label">{{$comment['user']}}</label>
                            <select id="items_{{$comment['id']}}" class="update-status" name="status" data-id="{{$comment['id']}}" data-model="{{strtolower($comment['commentable_type'])}}" class="form-control" style="width:auto;">
                                @foreach($comment['status'] as $k => $v)
                                    <option value="{{$k}}" @if($k == $comment['stat']) selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        @else
                            {{$comment['user']}} <strong>{{$comment['status'][$comment['stat']]}}</strong>
                        @endif
                    </div>
                    <div class="col-sm-4">
                        {{$comment['created']}}
                    </div>
                    <div class="col-sm-12">
                        @if(in_array('edit', $listPermissions) || Auth::user()->can('edit-project', $project->user_id))
                            <a href="#" id="comment_{{$comment['id']}}" data-type="text" data-pk="{{$comment['id']}}" data-button="btn_submit" data-name="edit" data-url="{{route('comment.save', $comment['id'])}}" data-original-title="Comment" class="comment-edit">{{$comment['comment']}}</a>
                        @else
                            {{$comment['comment']}}
                        @endif
                    </div>
                </div>
                @if(isset($comment['replies']) && count($comment['replies']) > 0)
                    @foreach($comment['replies'] as $keyReply => $reply)
                        <div class="row mr-3 mt-2 mb-4 ml-auto w-11/12">
                            <div class="col-sm-8">
                                {{$reply['user']}}
                            </div>
                            <div class="col-sm-4">
                                {{$reply['created']}}
                            </div>
                            <div class="col-sm-12">
                                @if(in_array('edit', $listPermissions) || Auth::user()->can('edit-project', $project->user_id))
                                    <a href="#" data-type="text" data-pk="{{$reply['id']}}" data-button="btn_submit" data-name="edit" data-url="{{route('comment.save', $reply['id'])}}" data-original-title="Comment" class="comment-edit">{{$reply['comment']}}</a>
                                @else
                                    {{$reply['comment']}}
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @if(in_array('comment', $listPermissions) || Auth::user()->can('edit-project', $project->user_id))
                        <div class="row mr-3 mt-2 mb-4 ml-auto w-11/12">
                            <a href="#reply_{{$comment['id']}}" class="enable-reply text-primary" id="{{$comment['id']}}">{{__('reply')}}</a>
                            <form action="{{route($comment['path'], $comment['id'])}}" method="post" class="reply reply_{{$comment['id']}}" data-id="reply_{{$comment['id']}}">
                                @csrf
                                <input name="question" type="hidden" value="{{$comment['commentable_id']}}">
                                <input name="commentId" type="hidden" id="commentIdReply" value="{{$comment['id']}}">
                                <input name="projectId" type="hidden" id="IdProjectComment" value="{{$project->id}}">
                                <div class="col-xs-12 mt-7">
                                    <textarea id="{{$comment['id']}}" name="reply" class="form-control mb-3 enable-textarea"
                                              placeholder="{{__('leave_comment')}}" ></textarea>
                                </div>
                                <div class="col-xs-12">
                                    <button name="btn_submit" value="reply" id="commentProjectId_{{$comment['id']}}" type="submit" class="btn btn-primary float-right reply-comment" disabled>{{__('save')}}</button>
                                </div>
                            </form>
                        </div>
                    @endif
                @else
                    @if(in_array('edit', $listPermissions) || Auth::user()->can('edit-project', $project->user_id))
                        <div class="row mr-3 mt-2 mb-4 ml-auto w-11/12">
                            <a href="#reply_{{$comment['id']}}" class="enable-reply text-primary" id="{{$comment['id']}}">{{__('reply')}}</a>
                            <form id="frmComment" action="{{route($comment['path'], $comment['id'])}}" method="post" class="reply reply_{{$comment['id']}}" data-id="reply_{{$comment['id']}}">
                                @csrf
                                <input name="question" type="hidden" value="{{$comment['commentable_id']}}">
                                <input name="commentId" type="hidden" id="commentIdReply" value="{{$comment['id']}}">
                                <input name="projectId" type="hidden" id="IdProjectComment" value="{{$project->id}}">
                                <div class="col-xs-12 mt-7">
                                    <textarea id="{{$comment['id']}}" name="reply" class="form-control mb-3 enable-textarea"
                                              placeholder="{{__('leave_comment')}}"></textarea>
                                </div>
                                <div class="col-xs-12">
                                    <button name="btn_submit" value="reply" id="commentProjectId_{{$comment['id']}}" type="submit" class="btn btn-primary float-right reply-comment" disabled>{{__('save')}}</button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endif

            @endforeach
            @if(in_array('edit', $listPermissions) || Auth::user()->can('edit-project', $project->user_id))
                <div class="comment">
                    <form id="frmComment" action="{{route($comments['pathComment'])}}" method="post">
                        @csrf
                        <input name="commentId" type="hidden" >
                        <input name="id" type="hidden" value="{{$comments['id']}}">
                        <input name="IdProjectComment" type="hidden" value="{{$project->id}}">
                        <div class="col-xs-12 mt-7">
                                    <textarea id="{{$comments['id']}}" name="comment" class="form-control mb-3 enable-textarea"
                                              placeholder="{{__('leave_comment')}}"></textarea>
                        </div>
                        <div class="col-xs-12">
                            <button id="commentProjectId_{{$comments['id']}}" type="submit" class="btn btn-primary float-right reply-comment" disabled>{{__('save')}}</button>
                        </div>
                    </form>
                </div>
            @endif
        @else
            @if(in_array('edit', $listPermissions) || Auth::user()->can('edit-project', $project->user_id))
                <div class="comment">
                    <form id="frmComment" action="{{route($comments['pathComment'])}}" method="post">
                        @csrf
                        <input name="commentId" type="hidden" >
                        <input name="id" type="hidden" value="{{$comments['id']}}">
                        <input name="IdProjectComment" type="hidden" value="{{$project->id}}">
                        <div class="col-xs-12 mt-7">
                                    <textarea id="{{$comments['id']}}" name="comment" class="form-control mb-3 enable-textarea"
                                              placeholder="{{__('leave_comment')}}"></textarea>
                        </div>
                        <div class="col-xs-12">
                            <button id="commentProjectId_{{$comments['id']}}" type="submit" class="btn btn-primary float-right reply-comment" disabled>{{__('save')}}</button>
                        </div>
                    </form>
                </div>
            @endif
        @endif
    @endif
    <div class="card p-4 mb-4 mt-4">
        <div class="row versions">
           <!-- <span class="ml-3">{{__('version')}}</span>-->
            @isset($textLog)

                @foreach($textLog as $log => $v)
                    <div class="mt-4">
                        <p class="ml-4 mb-4">{{date('d.m.Y',strtotime( $v['created_at']))}}</p>
                        <div class="col-sm-8 mb-4">
                            <a href="{{route('log.detail',[$project->id,$v['id']])}}">{{$v['userName']}}</a>
                        </div>
                        <div class="col-sm-4 text-right mb-4">
                            {{date('G:i',strtotime( $v['created_at']))}}
                        </div>

                    </div>
                @endforeach
            @endisset

        </div>
    </div>
@endsection
