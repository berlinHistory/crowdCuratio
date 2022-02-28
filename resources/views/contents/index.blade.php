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

<div class="modal fade bd-example-modal-xl" id="contentModal" tabindex="-1" role="dialog"
     aria-labelledby="contentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                {{__('add_content')}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class='row'>
                    <div id="infoMsg" class="">

                    </div>
                    <div class="writeinfo"></div>
                    <div class="col-xs-12">
                        <div id="contentType">
                            <a href="" class="add-Text"><i class="bi bi-file-font"
                                                           style="font-size:80px;"></i>{{__('text')}} </a>
                            <a href="" class="add-Image m-7"><i class="bi bi-file-image"
                                                                style="font-size:80px;"></i>{{__('gallery')}} </a>
                            <a href="" class="add-audio m-7"><i class="bi bi-file-earmark-play"
                                                                style="font-size:80px;"></i>{{__('audio')}} </a>
                            <a href="" class="add-video m-7"><i class="bi bi bi-camera-video"
                                                                style="font-size:80px;"></i> {{__('video')}} </a>
                        </div>

                        <div id="addText" style="display: none;">
                            <p><span id="chapterLbl"></span></p>
                            <p><span id="entryLbl"></span></p>
                            <p><span id="galleryLbl"></span></p>
                            <form class="mt-7" id="text_frm" name="contentForm"
                                  action="{{ route('text.store') }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <input name="entryId" type="hidden" class="form-control mb-3"
                                       value="">
                                <input name="textId" id="textId" type="hidden" class="form-control mb-3"
                                       value="">
                                <p class="mb-7">{{__('add_text')}}</p>
                                <div id="contentText"></div>
                                <p class="mt-4">{{__('copyright')}}</p>
                                <input name="copyrightText" id="copyrightText" type="text"
                                       class="form-control mb-3 mt-2 basicAutoSelect copyright"
                                       value="" placeholder="{{__('add_copyright')}} {{__('label_mandatory')}}"
                                       autocomplete="off">
                                <p>{{__('origin')}}</p>
                                <input name="originText" id="originText" type="text" class="form-control mb-3 mt-2"
                                       value="" placeholder="{{__('add_origin')}} {{__('label_mandatory')}}"
                                       autocomplete="off">
                                <div class="col-xs-12 mt-7">
                                    <button id="btn_text" name="btn_submit" type="submit"
                                            class="btn btn-primary float-right" value="text">{{__('save')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('contents.gallery')
