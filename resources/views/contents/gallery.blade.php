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

<div class="modal fade bd-example-modal-xl" id="galleryModal" tabindex="-1" role="dialog"
     aria-labelledby="galleryModalLabel"
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
                        <span id="lblChapter"></span>
                        <form id="entry_frm" name="entry_frm"
                              action="{{ route('save.gallery') }}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="col mt-3">
                                <input name="entryId" id="entryId" type="hidden" class="form-control mb-3"
                                       value="">
                                <input name="galleryId" id="galleryId" type="hidden" class="form-control mb-3"
                                       value="">
                                {{__('gallery_title')}}
                                <input name="title" id="title" type="text" class="form-control mb-3"
                                       placeholder="{{__('gallery_title')}}">
                            </div>
                            <div class="col">
                                {{__('gallery_subtitle')}}
                                <input name="subtitle" id="subtitle" type="text"
                                       class="form-control mb-3" placeholder="{{__('gallery_subtitle')}}">
                            </div>
                            <div class="col">
                                {{__('gallery_description')}}
                                <textarea name="description" id="description" class="form-control mb-3"
                                          rows="3"
                                          placeholder="{{__('gallery_description')}}"></textarea>
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
</div>
