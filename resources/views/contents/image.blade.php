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

<div class="modal fade bd-example-modal-xl" id="imageModal" tabindex="-1" role="dialog"
     aria-labelledby="imageModalLabel"
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
                        <p><span id="chapterLbl"></span></p>
                        <p><span id="entryLbl"></span></p>
                        <p><span id="galleryLbl"></span></p>
                        <div id="addImage">
                            <form method="post" action="{{ route('image.store') }}" id="image_frm"
                                  enctype="multipart/form-data">
                                @csrf
                                <input name="entryId" type="hidden" class="form-control mb-3"
                                       value="">
                                <input name="galleryId" type="hidden" class="form-control mb-3"
                                       value="">
                                <input name="imageId" id="imageId" type="hidden" class="form-control mb-3"
                                       value="">
                                <div class="row mb-4">
                                    <div class="col-sm-5">
                                        <img id="uploadId" src=""/>
                                        <div class="form-group" id="savedImage">
                                            <label>{{__('upload_file')}} </label>
                                            <input id="image" name="image" type="file" class="form-control" multiple="">
                                        </div>
                                    </div>
                                    <div class="col-sm-7">
                                        <textarea name="altText" id="altText" class="form-control mb-3 mt-2"
                                                   placeholder="Alt Text" autocomplete="off"></textarea>
                                        <div id="url" class="m-2"></div>
                                        <div id="updateNewImage" class="mt-4">

                                        </div>
                                    </div>
                                </div>
                                <p>{{__('copyright')}}</p>
                                <input name="copyrightImage" id="copyrightImage" type="text"
                                       class="form-control mb-3 mt-2 basicAutoSelect copyright"
                                       value="" placeholder="{{__('add_copyright')}} {{__('label_mandatory')}}"
                                       autocomplete="off">
                                <p>{{__('origin')}}</p>
                                <input name="originImage" id="originImage" type="text" class="form-control mb-3 mt-2"
                                       value="" placeholder="{{__('add_origin')}} {{__('label_mandatory')}}"
                                       autocomplete="off">
                                <div class="col-xs-12 mt-7">
                                    <button id="btn_image" name="btn_submit" type="submit"
                                            class="btn btn-primary float-right" value="image">{{__('save')}}
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
