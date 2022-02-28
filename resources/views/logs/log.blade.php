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
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg btn-block text-left mt-1 mb-2">{{__('back')}}</a>
@endsection
@section('main')
@if(count($changes) > 2) <!-- Because subjectId and subjectType will are in the array and they are not part of changes!-->
    <div class="row border border-secondary p-4 mb-4">
        <div>
        <div style="float: left; width: 40%">
            <p class="mb-4">{{__('old_version')}}</p>
            <form action="{{ route('log.reset') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($changes['subjectId']))
                    <input name="subjectId" value="{{$changes['subjectId']}}" type="hidden"/>
                @endif
                @if(isset($changes['subjectType']))
                    <input name="subjectType" value="{{$changes['subjectType']}}" type="hidden"/>
                @endif

                @if(isset($changes['name']['old']))
                    <span>Name</span>
                    <div class="card p-2 w-5/12">
                        <input name="nameReset" type="hidden" value="{{strip_tags($changes['name']['old'])}}"/>
                        {!! $changes['name']['old'] !!}
                    </div>
                @endif
                @if(isset($changes['subtitle']['old']))
                    <span>Subtitle</span>
                    <div class="card p-2 w-7/12">
                        <input name="subtitleReset" type="hidden" value="{{strip_tags($changes['subtitle']['old'])}}"/>
                        {!! $changes['subtitle']['old'] !!}
                    </div>
                @endif
                @if(isset($changes['description']['old']))
                    <span>Description</span>
                    <div class="card p-2 w-9/12">
                        <input name="descriptionReset" type="hidden"
                               value="{{strip_tags($changes['description']['old'])}}"/>
                        {!! $changes['description']['old'] !!}
                    </div>
                @endif
                @if(isset($changes['link']['old']))
                    <span>Link</span>
                    <div class="row">
                        <div class="col-sm-12 p-2 w-9/12">
                            <input name="linkReset" type="hidden"
                                   value="{{strip_tags($changes['link']['old'])}}"/>
                            {!! $changes['link']['old'] !!}
                        </div>
                    </div>
                @endif
                @if(isset($changes['source']['old']))
                    <span>Copyright</span>
                    <div class="card p-2 w-9/12">
                        <input name="sourceReset" type="hidden"
                               value="{{strip_tags($changes['source']['old'])}}"/>
                        {!! $changes['source']['old'] !!}
                    </div>
                @endif
                @if(isset($changes['origin']['old']))
                    <span>Origin</span>
                    <div class="card p-2 w-9/12">
                        <input name="originReset" type="hidden" value="{{strip_tags($changes['origin']['old'])}}"/>
                        {!! $changes['origin']['old'] !!}
                    </div>
                @endif
                @if(isset($changes['copyright']['old']))
                    <span>Copyright</span>
                    <div class="card p-2 w-9/12">
                        <input name="copyrightReset" type="hidden"
                               value="{{strip_tags($changes['copyright']['old'])}}"/>
                        {!! $changes['copyright']['old'] !!}
                    </div>
                @endif
                @if(isset($changes['text']['old']))
                    <span>Text</span>
                        <input name="textReset" type="hidden" value="{{$changes['text']['old']}}"/>
                        <input name="noHighlight" type="hidden" value="{{$changes['text']['noHighlight']}}"/>
                        {!! $changes['text']['old'] !!}
                    </div>
                @endif
                @if(isset($changes['image']['old']))
                <span>Image</span>
                    <div class="card p-2 w-9/12">
                        <input name="imageReset" type="hidden" value="{{$changes['image']['old']}}"/>
                        <input name="urlReset" type="hidden" value="{{$changes['url']['old']}}"/>
                        <img id="" src="{{route("image", $changes['image']['old'])}}"/>
                    </div>
                @endif

        </div>
        <div style="float: right; width: 40%">
            <p class="mb-4">{{__('new_version')}}</p>
            @if(isset($changes['name']['old']))
                <span>Name</span>
                <div class="card p-2 w-5/12">
                    {!! $changes['name']['new'] !!}
                </div>
            @endif
            @if(isset($changes['subtitle']['new']))
                <span>Subtitle</span>
                <div class="card p-2 w-7/12">
                    {!! $changes['subtitle']['new'] !!}
                </div>
            @endif
            @if(isset($changes['description']['new']))
                <span>Description</span>
                <div class="card p-2 w-9/12">
                    {!! $changes['description']['new'] !!}
                </div>
            @endif
            @if(isset($changes['link']['new']))
                <span>Link</span>
                <div class="row">
                    <div class="col-sm-12 p-2 w-9/12">
                        <input name="linkReset" type="hidden"
                               value="{{strip_tags($changes['link']['old'])}}"/>
                        {!! $changes['link']['new'] !!}
                    </div>
                </div>
            @endif
            @if(isset($changes['source']['new']))
                <span>Copyright</span>
                <div class="card p-2 w-9/12">
                    <input name="sourceReset" type="hidden"
                           value="{{strip_tags($changes['source']['old'])}}"/>
                    {!! $changes['source']['new'] !!}
                </div>
            @endif
            @if(isset($changes['origin']['new']))
                <span>Origin</span>
                <div class="card p-2 w-7/12">
                    <input type="hidden" value="{{$changes['origin']['new']}}"/>
                    {!! $changes['origin']['new'] !!}
                </div>
            @endif
            @if(isset($changes['copyright']['new']))
                <span>Copyright</span>
                <div class="card p-2 w-7/12">
                    <input type="hidden" value="{{$changes['copyright']['new']}}"/>
                    {!! $changes['copyright']['new'] !!}
                </div>
            @endif
            @if(isset($changes['text']['new']))
                <span>Text</span>
                <div class="card p-2 w-9/12">
                    <input type="hidden" value="{{$changes['text']['new']}}"/>
                    {!! $changes['text']['new'] !!}
                </div>
            @endif
            @if(isset($changes['image']['new']))
                <span>Image</span>
                <div class="card p-2 w-9/12">
                    <img id="imageOldId" src="{{route("image", $changes['image']['new'])}}"/>
                </div>
            @endif
        </div>
        <button class="btn btn-primary mt-4">{{__('reset_version')}}</button>
        </form>
    </div>
    </div>
@endif
@endsection
