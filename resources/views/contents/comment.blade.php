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

@extends('projects.layout')

@section('content')
    @isset($comments)
        <table id="commentList" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th scope="col">{{__('project')}}</th>
                <th scope="col">{{__('author')}}</th>
                <th scope="col">{{__('created_at')}}</th>
                <th scope="col">{{__('content_type')}}</th>
                <th scope="col">{{__('comment')}}</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($comments))
            @foreach($comments as $comment)
                <tr>
                        <td>
                            @isset($comment->project->name)
                                {!! $comment->project->name !!}
                            @endisset
                        </td>
                        <td>
                            @isset($comment->user->last_name) {!! $comment->user->last_name !!} @endisset
                            @isset($comment->user->name)
                                {!! $comment->user->name !!}
                            @endisset
                        </td>
                        <td>
                            {!! $comment->created_at !!}
                        </td>
                        <td>
                            @if($comment->commentable_type == 'App\Models\MediaContent' && isset($comment->content->media_contentable_type))
                                {!! class_basename($comment->content->media_contentable_type) !!}
                            @else
                                @isset($comment->commentable_type)
                                    {!! class_basename($comment->commentable_type) !!}
                                @endisset
                            @endif
                        </td>
                        <td>
                            @if($comment->commentable_type == 'App\Models\MediaContent' && isset($comment->content->media_contentable_type))
                                <a href="projects/{{$comment->project_id}}/edit?model={{$comment->commentable_type}}&comment={{$comment->commentable_id}}&type={{class_basename($comment->content->media_contentable_type)}}#anchor_{{class_basename($comment->commentable_type)}}_{{$comment->commentable_id}}">
                                    @isset($comment->comment)
                                        {!! $comment->comment !!}
                                    @endisset
                                </a>
                            @else
                                <a href="projects/{{$comment->project_id}}/edit?model={{$comment->commentable_type}}&comment={{$comment->commentable_id}}#anchor_{{class_basename($comment->commentable_type)}}_{{$comment->commentable_id}}">
                                    @isset($comment->comment)
                                        {!! $comment->comment !!}
                                    @endisset
                                </a>
                            @endif
                        </td>
                    </tr>
            @endforeach
            @endif
            </tbody>
        </table>
    @endisset
@endsection
@section('script')
    <script type="text/javascript">

        $(document).ready(function () {
            $('#commentList').DataTable({
                "paging": true,
                //"ordering": false,
                "info": true,
                "language": {
                    "search": "Suchen:",
                    "info": "Zeige Seite _PAGE_ von _PAGES_",
                    "lengthMenu": "Zeige _MENU_ Einträge",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Nächste Seite",
                        "previous": "Vorherige Seite"
                    }
                }
            });
        });

    </script>
@endsection
