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

    <div class="row">
        <div class="col-sm-6">
            <div class="card p-2">
                <div class="card-body">
                    <div class="row p-4">
                        <span style="display: inline-block; float: left">{{__('project_terms')}}</span>
                        <span style="display: inline-block; float: right"><a data-toggle="modal" data-target="#termsConditionsModal" id="addContentTerms"><i class="bi bi-pencil-fill m-2"></i></a></span>
                    </div>
                    @isset($terms->terms_conditions) <span id="contentTerms">{!! $terms->terms_conditions !!}</span> @endisset
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card p-2">
                <div class="card-body">
                    <div class="row p-4">
                        <span style="display: inline-block; float: left">{{__('policy')}}</span>
                        <span style="display: inline-block; float: right"><a data-toggle="modal" data-target="#privacyModal" id="addContentPrivacy"><i class="bi bi-pencil-fill m-2"></i></a></span>
                    </div>
                    @isset($privacy->privacy_policy) <span id="contentPolicy">{!! $privacy->privacy_policy !!}</span> @endisset
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="card p-2">
                <div class="card-body">
                    <div class="row p-4">
                        <span style="display: inline-block; float: left">{{__('project_imprint')}}</span>
                        <span style="display: inline-block; float: right"><a data-toggle="modal" data-target="#imprintModal"><i class="bi bi-pencil-fill m-2"></i></a></span>
                    </div>
                    @isset($imprint->name)
                        <p> Angaben gem:</p>
                        <p>
                            @isset($imprint->name['firstname']) {!! $imprint->name['firstname'] !!}@endisset @isset($imprint->name['lastname']) {!! $imprint->name['lastname'] !!}@endisset
                        </p>
                        <p>
                            @isset($imprint->address['address']) {!! $imprint->address['address'] !!}@endisset
                        </p>
                        <p>
                            @isset($imprint->address['postcode']) {!! $imprint->address['postcode'] !!}@endisset
                        </p>
                    <p class="mt-2"> Kontaktaufnahme: </p>
                        <p>
                            @isset($imprint->contact['phone']) {!! $imprint->contact['phone'] !!}@endisset
                        </p>
                        <p>
                            @isset($imprint->contact['fax']) {!! $imprint->contact['fax'] !!}@endisset
                        </p>
                        <p>
                            @isset($imprint->contact['email']) {!! $imprint->contact['email'] !!}@endisset
                        </p>
                    @endisset
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card p-2">
                <div class="card-body">
                    <div class="row p-4">
                        <span style="display: inline-block; float: left">{{__('invitation')}}</span>
                        <span style="display: inline-block; float: right"><a data-toggle="modal" data-target="#invitationModal" id="addContentMail"><i class="bi bi-pencil-fill m-2"></i></a></span>
                    </div>
                    @isset($mail->invitation) <span id="contentMail">{!! $mail->invitation !!}</span> @endisset
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl" id="termsConditionsModal" tabindex="-1" role="dialog" aria-labelledby="Terms Conditions"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{__('add_new_terms')}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row m-2">
                            <form action="{{route('settings.store')}}" name="contentForm"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="idTerms" @isset($terms->id) value="{!! $terms->id !!}" @endisset>
                                <p class="mb-7">{{__('add_text')}}</p>
                                <div id="termsConditionsEditor"></div>
                                <div class="col-xs-12 mt-2">
                                    <button id="btn_text" type="submit" class="btn btn-primary float-right">{{__('save')}}</button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="privacyModal" tabindex="-1" role="dialog" aria-labelledby="Privacy Policy"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{__('add_new_policy')}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row m-2">
                        <form action="{{route('settings.store')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="col-xs-12">
                                <input type="hidden" name="idPrivacy" @isset($privacy->id) value="{{$privacy->id}}" @endisset>
                                <p class="mb-7">{{__('add_text')}}</p>
                                <div id="privacyPolicy"></div>
                            </div>
                            <div class="col-xs-12 mt-2">
                                <button id="btn_privacy" type="submit" class="btn btn-primary float-right">{{__('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="imprintModal" tabindex="-1" role="dialog" aria-labelledby="Imprint"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{__('add_new_imprint')}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="{{route('settings.store')}}" method="post">
                            @csrf
                            <div class="col-xs-12">
                                <input type="hidden" name="IdImprint" @isset($imprint->id) value="{{$imprint->id}}" @endisset>
                                <input type="text" name="firstname" placeholder="Firstname" class="form-control mb-2" @isset($imprint->name['firstname']) value="{{$imprint->name['firstname']}}" @endisset/>
                                <input type="text" name="lastname" placeholder="Lastname" class="form-control mb-2" @isset($imprint->name['lastname']) value="{{$imprint->name['lastname']}}" @endisset/>
                                <input type="text" name="address" placeholder="Address" class="form-control mb-2" @isset($imprint->address['address']) value="{{$imprint->address['address']}}" @endisset/>
                                <input type="text" name="postcode" placeholder="Postcode" class="form-control mb-2" @isset($imprint->address['postcode']) value="{{$imprint->address['postcode']}}" @endisset/>
                                <input type="text" name="phone" placeholder="Phone" class="form-control mb-2" @isset($imprint->contact['phone']) value="{{$imprint->contact['phone']}}" @endisset/>
                                <input type="text" name="fax" placeholder="Fax" class="form-control mb-2" @isset($imprint->contact['fax']) value="{{$imprint->contact['fax']}}" @endisset/>
                                <input type="email" name="email" placeholder="E-mail" class="form-control mb-2" @isset($imprint->contact['email']) value="{{$imprint->contact['email']}}" @endisset/>
                            </div>
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-primary float-right">{{__('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="invitationModal" tabindex="-1" role="dialog" aria-labelledby="Invitation E-mail"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{__('add_new_invitation')}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row m-2">
                        <form action="{{route('settings.store')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="col-xs-12">
                                <input type="hidden" name="IdEmail" @isset($mail->id) value="{!! $mail->id !!}" @endisset>
                                <div id="invitation"></div>
                            </div>
                            <div class="col-xs-12 mt-2">
                                <button id="btn_invitation" type="submit" class="btn btn-primary float-right">{{__('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let Font = Quill.import('formats/font');
        Font.whitelist = ['times-new-roman', 'arial', 'Sans Serif'];
        Quill.register(Font, true);

        let toolbarOptions = [
            [{
                'header': [1, 2, 3, 4, 5, 6, false]
            }],
            ['bold', 'italic', 'underline', 'strike'], // toggled buttons
            //['blockquote', 'code-block'],

            /*[{
                'header': 1
            }, {
                'header': 2
            }], // custom button values
            */[{
                'list': 'ordered'
            }, {
                'list': 'bullet'
            }],
            /*[{
                'script': 'sub'
            }, {
                'script': 'super'
            }], // superscript/subscript
            */[{
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
        let quill = new Quill('#termsConditionsEditor', {
            modules: {
                toolbar: toolbarOptions,
            },
            theme: 'snow'
        });

        let quillPrivacy = new Quill('#privacyPolicy', {
            modules: {
                toolbar: toolbarOptions,
            },
            theme: 'snow'
        });

        let quillMail = new Quill('#invitation', {
            modules: {
                toolbar: toolbarOptions,
            },
            theme: 'snow'
        });

        $('#btn_text').click(function () {
            let hvalue = $('.ql-editor').html();
            $(this).append("<textarea name='termsConditions' style='display:none'>" + hvalue + "</textarea>");
        });

        $('#btn_privacy').click(function () {
            let privacy = quillPrivacy.container.firstChild.innerHTML;
            $(this).append("<textarea name='privacyPolicy' style='display:none'>" + privacy + "</textarea>");
        });

        $('#btn_invitation').click(function () {
            let invitation = quillMail.container.firstChild.innerHTML;
            $(this).append("<textarea name='invitation' style='display:none'>" + invitation + "</textarea>");
        });

        //Add or Modify AGBs
        $('#addContentTerms').click(function () {
            if($('#contentTerms').length){
                quill.container.firstChild.innerHTML = $('#contentTerms').html();
            }
        });

        //Add or Modify Privacy and policy
        $('#addContentPrivacy').click(function () {
            if($('#contentPolicy').length){
                quillPrivacy.container.firstChild.innerHTML = $('#contentPolicy').html();
            }
        });

        //Add or Modify mail invitation
        $('#addContentMail').click(function () {
            if($('#contentMail').length){
                quillMail.container.firstChild.innerHTML = $('#contentMail').html();
            }
        });
    </script>
@endsection
