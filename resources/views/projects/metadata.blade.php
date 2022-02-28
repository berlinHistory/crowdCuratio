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

<div class="card p-4 mb-4 ">
    <div class="row">
        <div class="col-sm-11">

            <div class="form-group mx-sm-3 mb-2 col-sm-5">
                <label for="inputProject">{{__('project_name')}} <span
                            style="color: red">{{__('label_mandatory')}}</span></label>
                <input name="name" type="text" class="form-control border-0" placeholder="{{__('add_name')}}"
                       value=" @if(isset($project->name))  {{$project->name}} @endif ">
            </div>
        </div>
        <div class="col-sm-1"></br><i class="bi bi-pencil-fill"></i></div>

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
                            <i class="bi bi-folder m-2"></i>{{__('browse')}} <input name="project_image" type="file"
                                                                                    id="imgInp">
                        </span>
                    </span>
                        <input name="logo" type="text" class="form-control border-0" style="background-color: white"
                               readonly>

                    </div>

                </div>
            </div>
        </div>
        <div class="col-sm-2">
            </br>

            <img id='img-upload' src="@if(isset($project->logo)){{route('image', $project->logo)}} @endif"/>

        </div>
        <div class="col-sm-1">
            </br>
            <i class="bi bi-pencil-fill"></i>
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
                <input name="imprint" type="text" class="form-control border-0"
                       value=" @if(isset($project->imprint))  {{$project->imprint}} @endif "
                       placeholder="{{__('add_imprint')}}">
            </div>
        </div>
        <div class="col-sm-1"></br><i class="bi bi-pencil-fill"></i></div>

    </div>
</div>

<div class="card p-4 mb-4">
    <div class="row">
        <div class="col-sm-11">
            <div class="form-group mx-sm-12 mb-2 col-sm-12">
                <label for="inputProject">{{__('project_terms')}} {{__('label_optional')}}</label>
                <div id="termsId"></div>
                <input name="terms" type="text" class="form-control border-0" placeholder="{{__('add_terms')}}"
                       value=" @if(isset($project->terms))  {{$project->terms}} @endif ">
            </div>
        </div>
        <div class="col-sm-1"></br><i class="bi bi-pencil-fill"></i></div>

    </div>
</div>
