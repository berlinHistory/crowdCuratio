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
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <p>{{__('users')}}</p><br>
    <a class="btn btn-secondary mb-5" href="{{ route('register') }}">
        <i class="bi bi-plus m-2"></i>
        {{__('add_new')}}
    </a>
    <table id="userList" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th scope="col">{{__('name')}}</th>
            <th scope="col">{{__('email')}}</th>
            <th scope="col">{{__('role')}}</th>
            <th scope="col" data-orderable="false"></th>
        </tr>
        </thead>
        <tbody>

        @isset($data)
            @foreach ($data as $key => $value)
                <tr class="clickable-row" data-href="{{route('users.edit', $value->id)}}">
                    <td>

                        <label class="form-check-label" for="projectName">
                            {{ $value->name }} {{ $value->last_name }}
                        </label>

                    </td>
                    <td>
                        {{ $value->email }}
                    </td>
                    <td>
                        {{$value->role}}
                    </td>
                    <td>
                        <form action="{{ route('users.destroy',$value->id) }}" method="POST">
                            <a href="{{ route('users.edit', $value->id) }}" title="{{__('edit_user')}}"><i
                                        class="bi bi-pencil-fill m-2"></i></a>
                            @csrf
                            @method('DELETE')
                            @if(auth()->user()->id != $value->id)
                                <button type="submit" onclick="return confirm('{{__('message_delete_confirm')}}')" title="{{__('delete_user')}}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                            @if(!is_null($value->welcome_valid_until))
                                <a href="{{route('resend.invitation', $value->id)}}" title="{{__('resend_invitation')}}"><i class="bi bi-envelope"></i></a>
                            @endif
                        </form>
                    </td>
                </tr>
            @endforeach
        @endisset
        </tbody>
    </table>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('#userList').DataTable({
                "paging": true,
                //"ordering": false,
                "info": true,
                "language": {
                    "search": "Suchen:",
                    "info": "Zeige Seite _PAGE_ von _PAGES_",
                    "lengthMenu": "Zeige _MENU_ Einträge",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Nächste Seite",
                        "previous": "Vorherige Seite"
                    }
                }
            });
        });

    </script>
@endsection
