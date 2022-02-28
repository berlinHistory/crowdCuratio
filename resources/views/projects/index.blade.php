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

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <p>{{__('project')}}</p><br>
    <a class="btn btn-secondary mb-5" href="{{ route('projects.create') }}">
        <i class="bi bi-plus m-2"></i>
        {{__('add_new')}}
    </a>
    <table id="projectList" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th scope="col">{{__('title')}}</th>
            <th scope="col">{{__('status')}}</th>
            <th scope="col">{{__('author')}}</th>
            <th scope="col">{{__('date')}}</th>
            <th scope="col" data-orderable="false"></th>
        </tr>
        </thead>
        <tbody>

        @isset($data)
            @foreach ($data as $key => $value)
                <tr>
                    <td class="clickable-row" data-href="{{route('projects.edit', $value->id)}}">

                        <label class="form-check-label" for="projectName">
                            {!! $value->name !!}
                        </label>

                    </td>
                    <td class="clickable-row" data-href="{{route('projects.edit', $value->id)}}">
                        {{ $value->status }}
                    </td>
                    <td>
                        {{$value->user_name}}
                    </td>
                    <td class="clickable-row" data-href="{{route('projects.edit', $value->id)}}">
                        <label class="form-check-label" for="lastUpdated">
                            {{ isset($value->updated_at) ? $value->updated_at : '00.00.0000' }}
                        </label>

                    </td>

                    <td>

                        <form action="{{ route('projects.destroy',$value->id) }}" method="POST">
                            <span data-toggle="tooltip" data-placement="top"
                                  title="{{__('edit_project')}}"><a href="{{ route('projects.edit', $value->id) }}"
                                                                    title="{{__('edit_project')}}"><i
                                            class="bi bi-pencil-fill m-2"></i></a></span>
                            @csrf
                            @if(Auth::user()->can('edit-project', $value->user_id) ||  Auth::user()->can('edit'))
                                @method('DELETE')
                                <button data-toggle="tooltip" data-placement="top" title="{{__('delete_project')}}"
                                        type="submit" onclick="return confirm('{{__('message_delete_confirm')}}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                            @if(Auth::user()->can('publish-project', $value->user_id) ||  Auth::user()->can('preview'))
                                <a href="#" data-placement="top" data-project="{{$value->id}}" class="preview m-4" data-toggle="modal" data-target="#previewModal" target="_blank" title="{{__('preview')}}"><i class="bi bi-globe"></i>
                                </a>
                            @endif
                        </form>
                    </td>
                </tr>
            @endforeach
        @endisset
        </tbody>
    </table>

    <!-- Modal -->

    <div class="modal fade bd-example-modal-xl" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title"><i class="bi bi-save"></i> <i class="bi bi-reply"></i></h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="project_list">

                        @isset($data)
                            @foreach ($data as $key => $value)
                                <li class="list-group-item">
                                    <input class="form-check-input project" type="checkbox" value=""
                                           id="{{$value->id}}">
                                    {{ $value->name }} <em>(by {{$value->user_name}})</em>
                                </li>
                            @endforeach
                        @endisset
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{__('add_new_element_comment')}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='row m-2'>
                        <div id="headerComment"></div>
                        <hr style="width:100%;text-align:left;margin-left:0">
                        <div id="listComment"></div>
                        <form id="frm_preview" action="{{route('preview')}}" method="get">
                            @csrf
                            <input type="hidden" name="project" id="project">
                            <div class="form-check">
                                <input type="color" value="#EDBA0E" class="form-check-input color-element" name="colorAccent">

                                <label class="form-check-label">{{__('color_accent')}}</label>
                            </div>
                            <div class="form-check">
                                <input type="color" value="#EDBA0E" class="form-check-input color-element" name="colorChapter">

                                <label class="form-check-label" >{{__('color_chapter')}}</label>
                            </div>
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" name="backgroundSecond">
                                <label class="form-check-label" >{{__('background_second')}}</label>
                            </div>
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" name="collapse">
                                <label class="form-check-label" >{{__('collapse')}}</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="pdf">
                                <label class="form-check-label" >{{__('pdf')}}</label>
                            </div>
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-primary" >{{__('html')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">

        $(document).ready(function () {
            $('#projectList').DataTable({
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

        //autcomplete fields
        var path = "{{ route('autocomplete') }}";

        $('#part_id').typeahead({
            source: function (query, process) {
                return $.get(path, {query: query}, function (data) {
                    return process(data);
                });
            },
            displayText: function (item) {
                return `${item.id} - ${item.name} `;
            },
            afterSelect: function (item) {
                $('#part_id').val(item.id);
            },
            fitToElement: true
        });


        //clickable row
        $(".clickable-row").click(function () {
            window.location = $(this).data("href");
        });

        Sortable.create(project_list, {
            animation: 100,
            draggable: '.list-group-item',
            handle: '.list-group-item',
            sort: true,
            filter: '.sortable-disabled',
            chosenClass: 'active',
            multiDrag: true,
            selectedClass: "selected",

        });

        $('.preview').click(function (){
            $('#project').val($(this).attr('data-project'));
        })
    </script>
@endsection

