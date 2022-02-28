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

<nav class="navbar navbar-expand-lg p-3 my-3 border">
		<img class="logo" src="//app.crowdcurat.io/css/images/crowdCuratio_logo.png" >
    <ul class="nav nav-pills mr-auto">
        @if(isset(Auth::user()->currentRole) && Auth::user()->currentRole[0]->name == 'Admin')
            <li class="nav-item">
                <a class="nav-link" href="{{route('settings.index')}}">{{__('setting')}}</a>
            </li>
        @endif
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">{{__('project')}}</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('projects.index') }}">{{__('all_projects')}}</a>
                <a class="dropdown-item" href="{{ route('projects.create') }}">{{__('new_project')}}</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">{{__('users')}}</a>
            <div class="dropdown-menu">
                @if(isset(Auth::user()->currentRole) && Auth::user()->currentRole[0]->name == 'Admin')
                    <a class="dropdown-item" href="{{ route('users.index') }}">{{__('all_users')}}</a>
                    <a class="dropdown-item" href="{{ route('register') }}">{{__('add_new')}}</a>
                    <a class="dropdown-item" href="{{ route('roles.index') }}">{{__('roles')}}</a>
                @endif
                <a class="dropdown-item" href="{{ route('profile') }}">{{__('profile')}}</a>
             </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('all.comments')}}">{{__('comments')}}</a>
        </li>
    </ul>
    <div class="topnav-right">
        <div class=" w-100 order-3 dual-collapse2">
            <ul class="navbar-nav ml-auto">
                @if(!in_array(Route::currentRouteName(),['translate','log.detail']))
                    <li class="nav-item dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ config('languages')[App::getLocale()] }}
                        </a>
                        <ul class="dropdown-menu">
                            @foreach (Config::get('languages') as $lang => $language)
                                @if ($lang != App::getLocale())
                                    <li>
                                        <a href="{{ route('lang.switch', $lang) }}">{{$language}}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
                <li class="nav-item">
                    <div class="btn-group">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if(isset(Auth::user()->name)){{ Auth::user()->name }} {{ Auth::user()->last_name }} @endif
                        </button>
                        <div class="dropdown-menu">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('log_out') }}
                                </x-dropdown-link>

                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="row">
    <div class="col-sm-2 leftbar">
        @yield('log')
    </div>
    @if(View::hasSection('content'))
        <div class="col-sm-10">
            @yield('content')
        </div>
    @else
        <div class="col-sm-7 mainbar">
            @yield('main')
        </div>
        <div class="col-sm-3 rightbar">
            @yield('sidebar')
        </div>
    @endif
    <div class="col-sm-12">
        @yield('footer')
    </div>
</div>
