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
@section('log')
    <a href="{{ route('projects.edit', $data['projectId']) }}" class="btn btn-secondary btn-lg btn-block text-left mt-1 mb-2">Zurück</a>
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    @isset($data['data'])
        @foreach($data['data'] as $chapter)
            <form action="{{route('chapters.store')}}" method="POST">
                @csrf
                <div class="form-group row">
                    <div class="col-sm-6">
                        <p>{{__('chapter_title')}}</p>
                        <p>{!! $chapter->name !!}</p>
                    </div>
                    <div class="col-sm-6">
                        <input type="hidden" name="translationChapter">
                            <input type="hidden" name="chapterId" value="{!! $chapter->id !!}">
                        <input type="text" class="form-control" name="chapterTitle" placeholder="Name" value="{{$chapter->getTranslation('name', 'en', false)}}">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <p>{{__('chapter_subtitle')}}</p>
                        <p>{!! $chapter->subtitle !!}</p>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="chapterSubtitle" placeholder="Subtitle" value="{{$chapter->getTranslation('subtitle', 'en', false)}}">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <p>{{__('chapter_description')}}</p>
                        <p>{!! $chapter->description !!}</p>
                    </div>
                    <div class="col-sm-6">
                        <div id="chapterDescription_{{$chapter->id}}"></div>
                        <div id="chapter_{{$chapter->id}}">{!! $chapter->getTranslation('description', 'en', false) !!}</div><a href="#chapter_{{$chapter->id}}" class="add-chapter-description" data-id="{{$chapter->id}}" > <i id="edit_chapter_{{$chapter->id}}" class="bi-pencil-square text-editor"></i></a>
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" name="isTranslated" @if($chapter->is_translated) value="{!! $chapter->is_translated !!}"  @endif @if($chapter->is_translated > 0) checked @endif>
                            <label class="form-check-label" for="translationComplete">{{__('translation_is_complete')}}</label>
                        </div>
                        <button type="submit" data-chapter="{{$chapter->id}}" class="btn btn-primary float-right mt-2 save-chapter">{{__('save')}}</button>
                    </div>
                </div>
                <hr class="mt-2 mb-3"/>
            </form>
            @if(isset($chapter->entries) && count($chapter->entries) > 0)
                @foreach($chapter->entries as $entry)
                    <form action="{{route('entries.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="entryId" value="{!! $entry->id !!}" />
                        <input type="hidden" name="translationEntry" />
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <p>{{__('entry_title')}}</p>
                            <p>{!! $entry->name !!}</p>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="entryTitle" placeholder="Entry title" value="{{$entry->getTranslation('name', 'en', false)}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <p>{{__('entry_subtitle')}}</p>
                            <p>{!! $entry->subtitle !!}</p>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="entrySubtitle" placeholder="Entry subtitle" value="{{$entry->getTranslation('subtitle', 'en', false)}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <p>{{__('entry_description')}}</p>
                            <p>{!! $entry->description !!}</p>
                        </div>
                        <div class="col-sm-6">
                            <div id="entryDescription_{{$entry->id}}"></div>
                            <div id="entry_{{$entry->id}}">{!! $entry->getTranslation('description', 'en', false) !!}</div><a href="#entry_{{$entry->id}}" class="add-entry-description" data-id="{{$entry->id}}" > <i id="edit_entry_{{$entry->id}}" class="bi-pencil-square text-editor"></i></a>
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" name="isTranslated" @if($entry->is_translated) value="{!! $entry->is_translated !!}"  @endif @if($entry->is_translated > 0) checked @endif>
                                <label class="form-check-label" for="translationComplete">{{__('translation_is_complete')}}</label>
                            </div>
                            <button type="submit" data-entry="{{$entry->id}}" class="btn btn-primary float-right mt-2 save-entry">{{__('save')}}</button>
                        </div>
                    </div>
                    </form>
                    <hr class="mt-2 mb-3"/>
                    @if(count($entry->media) > 0)
                        @foreach($entry->media as $item)
                            @if(isset($item) && get_class($item) == 'App\Models\Text')

                                <div>
                                    <form action="{{route('text.store')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="translationMode">
                                        <input type="hidden" name="textId" value="{{$item->id}}"/>
                                        <input type="hidden" name="copyrightId" value="{{$item->copyrightText->id}}"/>
                                        <input type="hidden" name="originId" value="{{$item->originText->id}}"/>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <p>{{__('text')}}</p>
                                                <p>{!! $item->text !!}</p>
                                            </div>
                                        <div class="col-sm-6">
                                            <div id="textContent_{{$item->id}}"></div>
                                            <div id="text_{{$item->id}}">{!! $item->getTranslation('text', 'en', false) !!}</div><a href="#text_{{$item->id}}" class="add-text-content" data-id="{{$item->id}}" ><i class="bi-pencil-square text-editor"></i></a>
                                        </div>
                                    </div>
                                    @if($item->origin)
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <p>{{__('origin')}}</p>
                                                    <p>{!! $item->originText->name !!}</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="originField" placeholder="Text copyright" value="{{$item->originText->getTranslation('name', 'en', false)}}">
                                                </div>
                                            </div>
                                    @endif
                                    @if($item->copyright)
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <p>{{__('copyright')}}</p>
                                                    <p>{!! $item->copyrightText->name !!}</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="copyrightField" placeholder="Text copyright" value="{{$item->copyrightText->getTranslation('name', 'en', false)}}">
                                                    <div class="form-check mt-2">
                                                        <input type="checkbox" class="form-check-input" name="isTranslatedText" @if($item->is_translated) value="{!! $item->is_translated !!}"  @endif @if($item->is_translated > 0) checked @endif>
                                                        <label class="form-check-label" for="translationComplete">{{__('translation_is_complete')}}</label>
                                                    </div>
                                                    <button type="submit" data-entry="{{$entry->id}}" class="btn btn-primary float-right mt-2 save-text">{{__('save')}}</button>
                                                </div>
                                            </div>
                                    @endif
                                    </form>
                                </div>
                                <hr class="mt-2 mb-3"/>
                            @endif
                            @if(isset($item) && get_class($item) == 'App\Models\Gallery')
                                <form action="{{route('save.gallery')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="galleryId" value="{!! $item->id !!}" />
                                    <input type="hidden" name="translationGallery" />
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <p>{{__('gallery_title')}}</p>
                                            <p>{!! $item->title !!}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="galleryTitle" placeholder="Gallery title" value="{{$item->getTranslation('title', 'en', false)}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <p>{{__('gallery_subtitle')}}</p>
                                            <p>{!! $item->subtitle !!}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="gallerySubtitle" placeholder="Gallery subtitle" value="{{$item->getTranslation('subtitle', 'en', false)}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <p>{{__('gallery_description')}}</p>
                                            <p>{!! $item->description !!}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="galleryDescription" placeholder="Gallery subtitle" value="{{$item->getTranslation('description', 'en', false)}}">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input" name="isTranslated" @if($entry->is_translated) value="{!! $item->is_translated !!}"  @endif @if($item->is_translated > 0) checked @endif>
                                                <label class="form-check-label" for="translationComplete">{{__('translation_is_complete')}}</label>
                                            </div>
                                            <button type="submit" class="btn btn-primary float-right mt-2">{{__('save')}}</button>
                                        </div>
                                    </div>
                                </form>
                                @if(isset($item->image_list) && count($item->image_list) > 0)
                                    @foreach($item->image_list as $image)
                                        <form action="{{route('image.store')}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="translationMode" value="1"/>
                                            <input type="hidden" name="imageId" value="{{$image->id}}"/>
                                            <input type="hidden" name="originId" value="{{$image->originImage->id}}"/>
                                            <input type="hidden" name="copyrightId" value="{{$image->copyrightImage->id}}"/>
                                            <div>
                                                <img class="img-thumbnail mb-2" src="{{route('image', $image->image)}}" alt="{{$image->alt}}"
                                                     style="width: 25%">
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <p>{{__('alt')}}</p>
                                                            <p>{!! $image->alt !!}</p>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="altField" placeholder="Image Alt" value="{{$image->getTranslation('alt', 'en', false)}}">

                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <p>{{__('origin')}}</p>
                                                            <p>{!! $image->originImage->name !!}</p>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="originField" placeholder="Image origin" value="{{$image->originImage->getTranslation('name', 'en', false)}}">

                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <p>{{__('copyright')}}</p>
                                                            <p>{!! $image->copyrightImage->name !!}</p>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="copyrightField" placeholder="Image copyright" value="{{$image->copyrightImage->getTranslation('name', 'en', false)}}">
                                                            <div class="form-check mt-4">
                                                                <input type="checkbox" class="form-check-input" name="isTranslated[]" value="{!! $image->copyrightImage->is_translated !!}" @if($image->copyrightImage->is_translated) checked="checked" @endif>
                                                                <label class="form-check-label" for="translationComplete">{{__('translation_is_complete')}}</label>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary float-right mt-2">{{__('save')}}</button>
                                                        </div>
                                                    </div>
                                            </div>
                                            <hr class="mt-2 mb-3"/>
                                        </form>
                                    @endforeach
                                @endif
                            @endif
                            @if(isset($item) && get_class($item) == 'App\Models\Audiovisual')
                                <form action="{{route('save.audiovisual')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="audiovisualId" value="{{$item->id}}"/>
                                    <input type="hidden" name="translationMode" value="1"/>
                                    <div>
                                        @if($item->type == 'audio')
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <audio controls class="embed-responsive-item" id="audio" src="{{route('audio',$item->link)}}"  ></audio>
                                                </div>
                                                <div class="col-sm-6">
                                                    @if($item->getTranslation('link', 'en', false))
                                                        <audio controls class="embed-responsive-item" id="audio" src="{{route('audio',$item->getTranslation('link', 'en', false))}}"  ></audio>
                                                    @endif
                                                    <div class="form-group" id="">
                                                        <label>{{__('upload_file')}} </label>
                                                        <input id="audio_{{$item->id}}" name="audio" type="file" class="form-control" multiple="">
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <iframe width="100%" height="315" src="{!! $item->link !!}" frameborder="0" allowfullscreen>
                                                    </iframe>
                                                </div>
                                                <div class="col-sm-6">
                                                    <iframe width="100%" height="315" src="{!! $item->getTranslation('link', 'en', false) !!}" frameborder="0" allowfullscreen>
                                                    </iframe>

                                                    <input name="link" id="link" type="text" class="form-control mb-3"
                                                           placeholder="{{__('audiovisual_link')}}" value="{!! $item->getTranslation('link', 'en', false) !!}">
                                                </div>
                                            </div>
                                        @endif

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <p>{{__('source')}}</p>
                                                    <p>{!! $item->source !!}</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="source" placeholder="{{__('source')}}" value="{{$item->getTranslation('source', 'en', false)}}">

                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <p>{{__('copyright')}}</p>
                                                    <p>{!! $item->copyright !!}</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="copyright" placeholder="{{__('copyright')}}" value="{{$item->getTranslation('copyright', 'en', false)}}">
                                                    <div class="form-check mt-4">
                                                        <input type="checkbox" class="form-check-input" name="isTranslated[]" value="{!! $item->is_translated !!}" @if($item->is_translated) checked="checked" @endif>
                                                        <label class="form-check-label" for="translationComplete">{{__('translation_is_complete')}}</label>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary float-right mt-2">{{__('save')}}</button>
                                                </div>
                                            </div>
                                    </div>
                                    <hr class="mt-2 mb-3"/>
                                </form>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach
        @isset($data['percentageOfTranslation'])
            <p>{{$data['percentageOfTranslation']}}% {{__('complete')}}</p>
            <div class="progress mb-4">
                <div class="progress-bar" role="progressbar" style="width: {{$data['percentageOfTranslation']}}%;" aria-valuenow="{{$data['percentageOfTranslation']}}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        @endisset
    @endisset
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

        $(document).ready(function (){

            $('.add-chapter-description').click(function (){

                let chapterId = $(this).attr('data-id');
                let quillChapter = new Quill('#chapterDescription_'+chapterId, {
                    modules: {
                        toolbar: toolbarOptions,
                    },
                    theme: 'snow'
                });
                quillChapter.container.firstChild.innerHTML = $('#chapter_'+chapterId).text();
                $('#chapter_'+chapterId).toggle();
                $('.text-editor').toggle();

            })

            $('.add-entry-description').click(function (){

                let entryId = $(this).attr('data-id');
                let quillEntry = new Quill('#entryDescription_'+entryId, {
                    modules: {
                        toolbar: toolbarOptions,
                    },
                    theme: 'snow'
                });
                quillEntry.container.firstChild.innerHTML = $('#entry_'+entryId).text();
                $('#entry_'+entryId).toggle();
                $('.text-editor').toggle();

            })

            $('.add-text-content').click(function (){

                let textId = $(this).attr('data-id');
                let quillText = new Quill('#textContent_'+textId, {
                    modules: {
                        toolbar: toolbarOptions,
                    },
                    theme: 'snow'
                });
                quillText.container.firstChild.innerHTML = $('#text_'+textId).text();
                $('#text_'+textId).toggle();
                $('.text-editor').toggle();

            })

        });

        $('.save-chapter').click(function () {
            let chapterDescription = $('.ql-editor').html();
            $(this).append("<textarea name='chapterDescription' style='display:none'>" + chapterDescription + "</textarea>");
        });

        $('.save-entry').click(function () {
            let entryDescription = $('.ql-editor').html();
            $(this).append("<textarea name='entryDescription' style='display:none'>" + entryDescription + "</textarea>");
        });

        $('.save-text').click(function () {
            let textContent = $('.ql-editor').html();
            $(this).append("<textarea name='text' style='display:none'>" + textContent + "</textarea>");
        });

    </script>
@endsection
