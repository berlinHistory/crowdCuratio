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
@section('main')
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
    <form method="POST" action="{{ route('users.update', $user->id) }}">
    @csrf
    @method('PUT')

    <!-- Name -->
        <div>
            <x-label for="name" :value="__('first_name')"/>

            <x-input id="name" class="block mt-1 w-full" type="text" name="firstName" value="{{$user->name}}" required
                     autofocus/>
        </div>

        <div class="mt-4">
            <x-label for="lastName" :value="__('last_name')"/>

            <x-input id="lastName" class="block mt-1 w-full" type="text" name="lastName" value="{{$user->last_name}}"
                     required
                     autofocus/>
        </div>


        <!-- Email Address -->
        <div class="row mt-4">
            <x-label for="email" class="col-sm-2 col-form-label">{{__('email')}}</x-label>
            <div class="col-sm-10">
                <label for="mail">{{$user->email}} </label>
            </div>
        </div>

        <!-- Password -->
        <!--
        <div class="mt-4">
            <x-label for="password" :value="__('password')"/>

            <x-input id="password" class="block mt-1 w-full"
                     type="password"
                     name="password"
                     required autocomplete="new-password"/>
        </div>
        -->
        <!-- Confirm Password -->
        <!--
        <div class="mt-4">
            <x-label for="password_confirmation" :value="__('confirm_password')"/>

            <x-input id="password_confirmation" class="block mt-1 w-full"
                     type="password"
                     name="password_confirmation" required/>
        </div>
        -->
        <div class="block mt-4">
            <p class="mb-4">{{__('role_right')}}</p>
            <label for="is_admin" class="inline-flex items-center">
                <input type="checkbox"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       name="adminUser" value="1" id="hasAdminRight" @if($user->is_admin) checked @endif>
                <span class="ml-2 text-sm text-gray-600">{{ __('is_admin')}}</span>
            </label>
        </div>
        <div id="noAdminRight" class="ml-6">
            <p>{{__('when_not')}}</p>
            <div class="row mt-4">
                <x-label for="lblRole" class="col-sm-2 col-form-label">{{__('role')}}</x-label>
                <div class="col-sm-10">
                    <select name="roles[]" class="" aria-label="Default select example">
                        @foreach($roles as $key => $role)
                            @if($role != 'Admin')
                            <option value="{{$key}}"
                                    @if($user->role->userRole->name == $role) selected="selected" @endif>{{$role}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="block mt-4">
                <label class="inline-flex items-center">
                    <input type="checkbox"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           name="createProject" value="1" @if($user->create_project) checked @endif>
                    <span class="ml-2 text-sm text-gray-600">{{ __('create_project')}}</span>
                </label>
            </div>
        </div>

        @endsection
        @section('sidebar')

            <div class="flex items-center justify-end mt-4">
                <button id="btn_save" class="btn btn-secondary btn-lg btn-block text-left" type="submit"
                        name="btn_submit"
                        value="Save">
                    {{ __('save') }}
                </button>

            </div>
    </form>
@endsection
@section('script')
    <script>
        if ($('#hasAdminRight').is(':checked')) {
            $('#noAdminRight').hide();
        }

        $('#hasAdminRight').click(function (){
            if ($('#hasAdminRight').is(':checked')) {
                $('#noAdminRight').hide();
            }else {
                $('#noAdminRight').show();
            }
        })

    </script>
@endsection
