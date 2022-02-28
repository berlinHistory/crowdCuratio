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

@isset($user)
    <div>
        <span>Email-Address</span><br>
        <span>{!! $user->email !!}</span><br>
        <div class="row">
            <div class="col-xs-3">
                Nutzer:
            </div>
            <div class="col-xs-9">
                {!! $user->name !!} {!! $user->last_name !!}
            </div>
        </div>
        @isset($role)
            <div class="row">
                <div class="col-xs-3">
                    Rolle:
                </div>
                <div class="col-xs-9">
                    {!! $role !!} <br>
                    @foreach($permissions as $key => $permission)
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
                </div>
            </div>
            <span>
                @endisset
        </span><br>
    <div class="row">
        <div class="col-xs-3">Projektspezifische Rechte:</div>
        <div class="col-xs-9">
            @foreach($permissionForProject as $value)
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
            <form
                  action="{{ route('project.permission') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                <input name="project"  value="{{$projectId}}" type="hidden"/>
                <input name="user" id="selectedUserId" value="{{$user->id}}" type="hidden" />
                @foreach($listAllPermissions as $k => $val)
                    <div class="form-check">
                        <input name="permissions[]" class="form-check-input" type="checkbox" value="{{$k}}" @if(in_array($val, $permissionForProject)) checked @endif>
                        <label class="form-check-label" for="flexCheckChecked"> {{$val}} </label>
                    </div>
                @endforeach
                <div class="col-xs-12 mt-7">
                    <button type="submit" class="btn btn-primary float-right" id="btnSavePermission">{{__('save')}}</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endisset
