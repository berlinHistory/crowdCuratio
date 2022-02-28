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

@section('main')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>{{__('whoops')}}</strong> {{__('message_problem_input')}}<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form id="frm_project" name="projectForm"
          action="@if(isset($project->id)) {{ route('projects.update',$project->id) }} @else {{ route('projects.store') }} @endif"
          method="POST"
          enctype="multipart/form-data">
    <div class="card p-4 mb-4">
        <div class="row">
            <div class="col-sm-11">
                    @csrf
                    @if(isset($project->id))
                        @method('PUT')
                    @endif
                    <div class="form-group mx-sm-3 mb-2 col-sm-5">
                        <label for="inputProject">{{__('project_name')}} <span
                                    style="color: red">{{__('label_mandatory')}}</span></label>
                        <input name="name" type="text" class="form-control" placeholder="Add name"
                               value="@if(isset($project->name)) {{$project->name}} @else {{old('name')}} @endif" autocomplete="off">
                    </div>
            </div>

        </div>
    </div>
    <div class="card p-4 mb-4">
        <div class="row">
            <div class="col-sm-9">
                <div class="form-group mx-sm-3 mb-2 col-sm-10">
                    <p for="inputProject">{{__('project_thumbnail')}} {{__('label_optional')}}</p>
                    <p for="thumbnail">{{__('add_project_thumbnail')}}</p>
                    <div class="form-group">
                        <label>200px x 200px</label>
                        <div class="input-group">
                    <span class="input-group-btn">
                        <span class="btn btn-default btn-file">
                            <i class="bi bi-folder m-2"></i>{{__('browse')}} <input value="{{old('project_image')}}"
                                                                                    name="project_image" type="file"
                                                                                    id="imgInp">
                        </span>
                    </span>
                            <input name="logo" value="@if(isset($project->logo)) {{$project->logo}} @else {{old('logo')}} @endif" type="text" class="form-control border-0"
                                   style="background-color: white"
                                   readonly>

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                </br>

                <img id='img-upload' src="@if(isset($project->logo)){{route('image', $project->logo)}} @endif"/>

            </div>

        </div>
    </div>

    <hr class="mt-5 mb-5">

    <div class="card p-4 mb-4">
        <div class="row">
            <div class="col-sm-11">
                <div class="form-group mx-sm-12 mb-2 col-sm-12">
                    <label >{{__('description')}} </label>
                    <div id="descriptionId"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="card p-4 mb-4">
        <div class="row">
            <div class="col-sm-11">
                <div class="form-group mx-sm-12 mb-2 col-sm-12">
                    <label for="inputProject">{{__('project_imprint')}} <span
                                style="color: red">{{__('label_mandatory')}}</span></label>
                    <div id="imprintId"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="card p-4 mb-4">
        <div class="row">
            <div class="col-sm-11">
                <div class="form-group mx-sm-12 mb-2 col-sm-12">
                    <label for="inputProject">{{__('project_terms')}} {{__('label_optional')}}</label>
                    <div id="termsId"></div>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection
@section('action')
    <div class="col-sm-9">
        <button id="btn_save" class="btn btn-secondary btn-lg btn-block text-left" type="submit" name="btn_submit"
                value="Save"><i
                    class="bi bi-file-earmark m-2"></i>@if(isset($project->id)) {{__('save')}} @else Save @endif
        </button>
        <!--<button class="btn btn-secondary btn-lg btn-block text-left" type="submit" name="btn_submit" value="Preview"><i
                     class="bi bi-eye m-2"></i>Preview
         </button>
         <button class="btn btn-secondary btn-lg btn-block text-left" type="submit" name="btn_submit" value="Publish"><i
                     class="bi bi-globe m-2"></i>Publish
         </button>!-->
        @if(isset($project->id))
            <form action="{{ route('projects.destroy',$project->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-secondary btn-lg btn-block text-left mt-2" type="submit"
                        onclick="return confirm('{{__('message_delete_confirm')}}')">
                    <i class="bi bi-trash m-2"></i> {{__('delete_project')}}
                </button>
            </form>
        @endif

    </div>
@endsection
@section('sidebar')
    <button id="btn_save" class="btn btn-secondary btn-lg btn-block text-left" type="submit" name="btn_submit"
            value="Save"><i
                class="bi bi-file-earmark m-2"></i>@if(isset($project->id)) {{__('save')}} @else {{__('save')}} @endif
    </button>
    @if(isset($project->id))
    <a class="btn btn-secondary btn-lg btn-block text-left" href="{{ route('projects.edit', $project->id) }}"> {{__('content')}}
    </a>
    @endif
    @if(isset($project->id))
        @if(Auth::user()->id == $project->user_id || Auth::user()->isAdmin() || in_array('invite', $listPermissions))
        <div class="container card p-4 mb-4 mt-4">
            <div class="row">
                <div class="col-sm-12">
                    <p>{{__('user_permissions')}}</p>
                    <hr>
                    <div class="mt-7">
                        @isset($listGrantedUsers)
                            @foreach($listGrantedUsers as $key => $value)
                                <div class="col-sm-7">{{$value['name']}}</div>
                                <form action="{{ route('project.user_delete',['userId' => $key, 'projectId' => $project->id]) }}" method="POST">
                                    <div class="col-sm-5 text-right"><a data-id="{{$key}}" data-project="{{$project->id}}"
                                                                        data-permission="{{json_encode($value['permission'])}}"
                                                                        href="" data-toggle="modal"
                                                                        data-target="#userModal" class="edit-user"><i
                                                    class="bi bi-pencil-fill"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button data-toggle="tooltip" data-placement="top" title="{{__('delete_user')}}" type="submit" onclick="return confirm('{{__('message_delete_confirm')}}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </form>
                            @endforeach
                        @endisset
                    </div>
                </div>
                <div class="col-sm-12 mt-7">
                    <a data-toggle="modal" data-target="#userInvitation" href=""
                       class="btn btn-secondary btn-block btn-responsive add-user"> Add user</a>
                </div>
            </div>
        </div>
    @endif
    <!--<button class="btn btn-secondary btn-lg btn-block text-left" type="submit" name="btn_submit" value="Preview"><i
                class="bi bi-eye m-2"></i>Preview
    </button>
    <button class="btn btn-secondary btn-lg btn-block text-left" type="submit" name="btn_submit" value="Publish"><i
                class="bi bi-globe m-2"></i>Publish
    </button>-->
    <!-- Modal Chapter -->

    <div class="modal fade bd-example-modal-xl" id="userInvitation" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Add user
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        <div class="col-lg-12" id="detailUser">
                            <form action="{{route('check.email')}}"
                                  method="POST"
                                  enctype="multipart/form-data" class="form-group form-inline" id="frmCheckEmail">
                                @csrf
                                <input name="project" @isset($project->id) value="{{$project->id}}"
                                       @endisset type="hidden"/>
                                <div class="form-group col-xs-8 mb-2">
                                    <input type="email" class="form-control" name="userEmail" placeholder="User email"
                                           style="width: 100% !important;">
                                </div>
                                <button id="" type="submit" class="btn btn-primary mb-2">{{__('invite')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-xl" id="userModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Add user permissions
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        <div id="infoMsg" class="">

                        </div>
                        <div class="writeinfo"></div>
                        <div class="col-xs-12" id="editUserPermission">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="newUserInvitation" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Add user
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        @if(Session::get('user'))
                            <div class="col-lg-12">
                                <p>{{__('email')}}</p>
                                <p>{!! Session::get('user.email') !!}</p>
                                <div class="row mt-2 mb-2">
                                    <div class="col-xs-3">
                                        {{__('users')}}:
                                    </div>
                                    <div class="col-xs-9">
                                        {!! Session::get('user.name') !!} {!! Session::get('user.last_name') !!}
                                    </div>
                                </div>
                                <p class="text-success">{{__('existing_user')}}</p>
                                @if(Session::get('role'))
                                    <div class="row mt-2 mb-2">
                                        <div class="col-xs-3">
                                            {{__('role')}}:
                                        </div>
                                        <div class="col-xs-9">
                                            {!! Session::get('role') !!} <br>
                                            @if(Session::get('permissionForRole'))
                                                @foreach(json_decode(Session::get('permissionForRole')) as $key => $permission)
                                                    @if($loop->first)
                                                        <span>(</span>
                                                    @endif
                                                    {!! $permission !!}
                                                    @if($loop->last)
                                                        <span>)</span>
                                                    @else
                                                        <span>,</span>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-xs-3">{{__('project_right')}}:</div>
                                    <div class="col-xs-9">
                                        @if(Session::get('permissionForProject'))
                                            @foreach((Session::get('permissionForProject')) as $k => $value)
                                                @if($loop->first)
                                                    <span>(</span>
                                                @endif
                                                {!! $value !!}
                                                @if($loop->last)
                                                    <span>)</span>
                                                @else
                                                    <span>,</span>
                                                @endif
                                            @endforeach
                                        @endif
                                        <form
                                                action="{{ route('project.permission') }}"
                                                method="POST"
                                                enctype="multipart/form-data">
                                            @csrf
                                            <input name="project" value="{{$project->id}}" type="hidden"/>
                                            <input name="user" id="selectedUserId"
                                                   value="{{Session::get('user.id')}}" type="hidden"/>
                                            @if(Session::get('listAllPermissions'))
                                                @foreach(json_decode(Session::get('listAllPermissions')) as $k => $val)
                                                    <div class="form-check">
                                                        <input name="permissions[]" class="form-check-input"
                                                               type="checkbox" value="{{$k}}"
                                                               @if(in_array($val, (Session::get('permissionProject')))) checked @endif>
                                                        <label class="form-check-label"
                                                               for="flexCheckChecked"> {{$val}} </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                            <div class="col-xs-12 mt-7">
                                                <button type="submit" class="btn btn-primary float-right"
                                                        id="btnSavePermission">{{__('save')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="newUser" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Add user
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        <div class="col-lg-12">
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <input name="projectId" type="hidden" value="{{$project->id}}">
                                <div class="block mt-4">
                                    <label for="remember_me" class="inline-flex items-center">
                                        <input id="policy" type="checkbox"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                               name="policy">
                                        <span class="ml-2 text-sm text-gray-600">{{ __('consent')}}</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>{{__('first_name')}}</label>
                                    <input name="firstName" type="text" class="form-control" aria-describedby="{{__('first_name')}}" placeholder="{{__('first_name')}}">
                                </div>
                                <div class="form-group">
                                    <label>{{__('last_name')}}</label>
                                    <input name="lastName" type="text" class="form-control" placeholder="{{__('last_name')}}">
                                </div>
                                <div class="form-group">
                                    <label>{{__('email')}}</label>
                                    <input name="email" type="email" class="form-control" value="{{Session::get('email')}}" placeholder="{{__('email')}}">
                                </div>
                                @isset($listRole)
                                    <div class="col-auto my-1">
                                        <label class="mr-sm-4" for="inlineFormCustomSelect">{{__('role')}}</label>
                                        <select name="roles[]" class="custom-select mr-sm-4" >
                                            @foreach($listRole as $key => $role)
                                                <option value="{{ $key }}" {{ (old('roles.0') == $role ? "selected":"") }}>{{$role}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endisset
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">{{__('save')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif
@endsection
@section('script')
    <script>
        $(document).ready(function () {

            //Send form
            $('#btn_save').click(function (){
                $('#frm_project').submit();
            })

            //enable and disabled Entry click
            $('input[name=chapterId]').change(function () {
                var check = $('#chapterId').val();
                if (check != '') {
                    $('#addEntry').removeClass('btn disabled');
                    $('#addNewElement').removeClass('disabled');
                } else {
                    $('#addEntry').addClass('btn disabled');
                    $('#addNewElement').addClass('disabled');
                }
            });

            //toggle elements
            $('#addChapter').click(function () {
                $('#chapter').toggle();
            })

            $('#addEntry').click(function () {
                $('#entry').toggle();
            })

            //set content of new Element
            $('#addNewElement').click(
                function () {
                    var someText = $('#chapterId').val();
                    var action = $('');
                    var newDiv = $('<div class="card p-4 mb-4"><div class="row"><div class="col-sm-11"><p>Chapter</p><input name="chapter[]" type="text" class="form-control-plaintext border-0" value="' + someText + '" readonly></div></div></div>');
                    $('#newElement').append(newDiv);
                    $("#myModal").modal('hide');
                }
            )

            //Add thumbnail
            $(document).on('change', '.btn-file :file', function () {
                var input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [label]);
            });

            $('.btn-file :file').on('fileselect', function (event, label) {

                var input = $(this).parents('.input-group').find(':text'),
                    log = label;

                if (input.length) {
                    input.val(log);
                } else {
                    if (log) alert(log);
                }

            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#img-upload').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#imgInp").change(function () {
                readURL(this);
            });

            //Check for new changes before leaving the page
            /*let formmodified = 0;
            $('#frm_project').change(function () {
                formmodified = 1;
            });
            $('#btn_save').click(function () {
                formmodified = 0;
            });
            window.onbeforeunload = confirmExit;

            function confirmExit() {
                if (formmodified == 1) {
                    return "Exit?";
                }
            }*/
        })



        //Modify metadat imprint
        $(document).ready(function (){
            let Font = Quill.import('formats/font');
            Font.whitelist = ['times-new-roman', 'arial', 'Sans Serif'];
            Quill.register(Font, true);

            let toolbarOptions = [
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                ['bold', 'italic', 'underline', 'strike'], // toggled buttons
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }], // outdent/indent
                [{
                    'direction': 'rtl'
                }], // text direction
                [{
                    'size': ['small', false, 'large', 'huge']
                }], // custom dropdown
                [{
                    'color': []
                }, {
                    'background': []
                }], // dropdown with defaults from theme
                [{
                    'font': ['', 'times-new-roman', 'arial']
                }],
                [{align: ''}, {align: 'center'}, {align: 'right'}, {align: 'justify'}],
                ['link'],
                ['clean'] // remove formatting button
            ];
            let quill = new Quill('#imprintId', {
                modules: {
                    toolbar: toolbarOptions,
                },
                theme: 'snow'
            });

            let quillTerms = new Quill('#termsId', {
                modules: {
                    toolbar: toolbarOptions,
                },
                theme: 'snow'
            });

            let quillDescription = new Quill('#descriptionId', {
                modules: {
                    toolbar: toolbarOptions,
                },
                theme: 'snow'
            });

            <?php if(isset($project)): ?>
                quillDescription.container.firstChild.innerHTML = '{!! !empty($project->description) ? $project->description : ''!!}';
                quill.root.innerHTML = '{!! !empty($project->imprint) ? $project->imprint : ''!!}';
                quillTerms.container.firstChild.innerHTML = '{!! !empty($project->terms) ? $project->terms : ''!!}';
            <?php endif; ?>


            /*imprintModify.root.addEventListener('keydown', evt => {
                $('#updateProjectBtn').html('<button id="btn_save" class="btn btn-secondary btn-block text-left" type="submit" name="btn_submit" value="Save"><i class="bi bi-file-earmark m-2"></i>{{__('save')}}</button>');
            });

            termsModify.root.addEventListener('keydown', evt => {
                $('#updateProjectBtn').html('<button id="btn_save" class="btn btn-secondary btn-block text-left" type="submit" name="btn_submit" value="Save"><i class="bi bi-file-earmark m-2"></i>{{__('save')}}</button>');
            });*/

            //Add imprint and terms to forms
            $('#frm_project').submit(function() {
                let imprint = quill.root.innerHTML;
                let terms = quillTerms.root.innerHTML;
                let description = quillDescription.root.innerHTML;
                $(this).append("<textarea name='imprint' style='display:none'>" + imprint + "</textarea>");
                $(this).append("<textarea name='terms' style='display:none'>" + terms + "</textarea>");
                $(this).append("<textarea name='description' style='display:none'>" + description + "</textarea>");
                return true;
            });

        })

        //Invitation for existing user
        @isset($project->id)
            @if(!empty(Session::get('error_code')) && Session::get('error_code') == 6)
            $('#newUserInvitation').modal('show');
            @endif

            //User not existing
            @if(!empty(Session::get('error_code')) && Session::get('error_code') == 7)
            $('#newUser').modal('show');
            @endif

            $('.edit-user').click(function (event) {
                //event.preventDefault();
                $("#selectedUser").html('');
                let user = $(this).attr("data-id");
                let project = $(this).attr("data-project");
                let permission = $(this).attr("data-permission");
                let listPermissions = @json($permissions);
                jQuery.each(listPermissions, function (i, val) {
                    let check = "";
                    if (val.id in $.parseJSON(permission)) check = "checked";
                    $("#selectedUser").append('<div class="form-check"><input name="permissions[]" class="form-check-input" type="checkbox" value="' + val.id + '" id="flexCheckChecked"' + check + ' > <label class="form-check-label" for="flexCheckChecked"> ' + val.name + ' </label></div>')
                });
                $('#selectedUserId').val(user);
                $('#editUserPermission').load('/user/' + user + '/project/' + project + '/info');
            })

            $('.add-user').click(function () {
                $("#detailUser").html('<form action="{{route('check.email')}}" method="POST" enctype="multipart/form-data" class="form-group form-inline" id="frmCheckEmail">@csrf<input name="project" @isset($project->id) value="{{$project->id}}" @endisset type="hidden"/><div class="form-group col-xs-8 mb-2"><input type="email" class="form-control" name="userEmail" placeholder="User email" style="width: 100% !important;"></div><button id="userEmailCheck" type="submit" class="btn btn-primary mb-2">Einladen</button></form>');
            })
        @endisset

    </script>
@endsection
