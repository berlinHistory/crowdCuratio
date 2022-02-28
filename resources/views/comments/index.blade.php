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
@section('sidebar')

@endsection
@section('main')
    <div id="leaveComment" class='row'>
        <form action="{{route('comment.chapter')}}" method="post">
            @csrf
            <input name="id" type="hidden" id="commentId">
            <div class="col-xs-12">
                <textarea name="comment" class="form-control mb-3" placeholder="Leave comment"></textarea>
            </div>
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary float-right">{{__('save')}}</button>
            </div>
        </form>
    </div>
@endsection
