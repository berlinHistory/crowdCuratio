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
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <form method="POST" action="{{ route('users.update', Auth::user()->id) }}">
    @csrf
    @method('PUT')

    <!-- Name -->
        <div>
            <x-label for="name" :value="__('first_name')"/>

            <x-input id="name" class="block mt-1 w-full" type="text" name="firstName" value="{{Auth::user()->name}}"
                     required
                     autofocus/>
        </div>

        <div class="mt-4">
            <x-label for="lastName" :value="__('last_name')"/>

            <x-input id="lastName" class="block mt-1 w-full" type="text" name="lastName"
                     value="{{Auth::user()->last_name}}" required
                     autofocus/>
        </div>


        <!-- Email Address -->
        <div class="row mt-4">
            <x-label for="email" class="col-sm-2 col-form-label">{{__('email')}}</x-label>
            <div class="col-sm-10">
                <label for="mail">{{Auth::user()->email}} </label>
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
        @if(Auth::user()->role->userRole->name == 'Admin')
            <div class="row mt-4">
                <x-label for="lblRole" class="col-sm-2 col-form-label">{{__('role')}}</x-label>
                <div class="col-sm-10">
                    <select name="roles[]" class="" aria-label="Default select example">
                        @foreach($roles as $key => $role)
                            <option value="{{$key}}"
                                    @if(Auth::user()->role->userRole->name == $role) selected="selected" @endif>{{$role}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        <div class="mt-7">
            <x-label for="old_password" :value="__('old_password')"/>

            <x-input id="old_password" class="block mt-1 w-full" type="password" name="old_password" value=""
                     autofocus/>
        </div>

        <div class="mt-4">
            <x-label for="new_password" :value="__('new_password')"/>

            <x-input id="new_password" class="block mt-1 w-full" type="password" name="new_password" value=""
                     autofocus/>
        </div>

        <div class="mt-4">
            <x-label for="confirm_password" :value="__('confirm_password')"/>

            <x-input id="confirm_password" class="block mt-1 w-full" type="password" name="confirm_password" value=""
                     autofocus/>
        </div>


        @endsection
        @section('sidebar')

            <div class="flex items-center justify-end mt-4">
                <button id="btn_save" class="btn btn-secondary btn-lg btn-block text-left" type="submit"
                        name="btn_submit"
                        value="save_profile">
                    {{ __('save') }}
                </button>

            </div>
    </form>
@endsection
