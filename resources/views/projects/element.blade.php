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

@extends('projects.create')
@section('element')

    <div id="newElement">

    </div>
    <a class="btn btn-secondary btn-lg" data-toggle="modal" data-target="#myModal">
        <i class="bi bi-plus m-2"></i>
        {{__('add_new_element')}}
    </a>
    <hr class="mt-5 mb-5">
    <!-- Modal -->

    <div class="modal fade bd-example-modal-xl" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{__('add_new_element')}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="writeinfo"></div>
                    <div class='row'>
                        <div class="col-xs-12">
                            <div class=" form-group col-xs-8">
                                <a id="addChapter" href="#chapter">
                                    <i class="bi bi-plus m-2 "></i>{{__('new_chapter')}}
                                </a>
                            </div>
                            <div id="chapter" style="display: none">
                                <div class="col-xs-6">
                                    <input name="chapterId" id="chapterId" type="text" class="form-control mb-3"
                                           placeholder="Chapter Title">
                                </div>
                                <div class="col-xs-12">
                                    <input type="text" class="form-control mb-3" placeholder="Chapter Subtitle">
                                </div>
                                <div class="col-xs-12">
                                <textarea class="form-control mb-3" id="exampleFormControlTextarea1" rows="3"
                                          placeholder="Chapter Description"></textarea>
                                </div>
                                <button id="addNewChapter" type="button" class="btn btn-primary disabled">{{__('save')}}
                                </button>
                            </div>
                            <div class=" form-group col-xs-8">
                                <a id="addEntry" href="#entry" class="btn disabled">
                                    <i class="bi bi-plus m-2 "></i>{{__('add_entry')}}
                                </a>
                            </div>
                            <div id="entry" style="display: none">
                                <div class="col-xs-6">
                                    <input type="text" class="form-control mb-3" placeholder="Entry Title">
                                </div>
                                <div class="col-xs-12">
                                    <input type="text" class="form-control mb-3" placeholder="Entry Subtitle">
                                </div>
                                <div class="col-xs-12">
                                <textarea class="form-control mb-3" id="exampleFormControlTextarea1" rows="3"
                                          placeholder="Entry Description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancel')}}</button>
                    <button id="addNewElement" type="button" class="btn btn-primary disabled">{{__('add')}}</button>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {

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


        })
    </script>
@endsection
