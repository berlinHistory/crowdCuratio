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
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{__('role_management')}}</h2>
            </div>

        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered mt-7">
        <tr>

            <th>{{__('name')}}</th>
            <th>{{__('action')}}</th>
        </tr>
        @foreach ($roles as $key => $role)

            <tr>

                <td>{{ $role->name }}</td>

                <td>
                    <form id="frmRole" action="{{ route('roles.destroy',$role->id) }}" method="POST">
                        <a title="view role" href="{{ route('roles.show',$role->id) }}" data-toggle="tooltip"
                           data-placement="top" title="See role"> <i class="bi bi-eye m-2"></i></a>
                        @if($role->name != 'Admin')
                        @can('edit')
                            <a title="{{__('edit_role')}}" href="{{ route('roles.edit',$role->id) }}"
                               data-toggle="tooltip"
                               data-placement="top" title="{{__('edit_role')}}"><i
                                        class="bi bi-pencil-fill m-2"></i></a>
                        @endcan
                        @csrf
                        @method('DELETE')
                        @if(auth()->user()->id != $role->id)
                            @can('delete')
                                @if($role->cnt > 0)
                                    <a id="" href="" class="roleDelete" data-id="{{$role->id}}" data-toggle="tooltip"
                                       data-placement="top" title="Delete role"><i class="bi bi-trash"></i></a>
                                @else
                                    <button type="submit" onclick="return confirm('{{__('message_delete_confirm')}}')"
                                            data-toggle="tooltip"
                                            data-placement="top" title="Delete role">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            @endcan
                        @endif
                        @endif
                    </form>
                </td>
            </tr>

        @endforeach
    </table>

@endsection
@section('sidebar')
    <div class="pull-right">
        @can('add')
            <a class="btn btn-secondary btn-lg btn-block text-left"
               href="{{ route('roles.create') }}"> {{__('create_new_role')}}</a>
        @endcan
    </div>

    <!-- Modal window-->
    <div class="modal fade bd-example-modal-xl" id="roleModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Löschen
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
                            <form id="frmChangeRole"
                                  action=""
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="alternativeRole" id="alternativeRole" value=""/>
                                <input type="hidden" name="deletedRole" id="deletedRole" value=""/>
                                <span>Achtung! Diese Rolle ist vergeben! Welche andere Rolle sollen die betroffenen Nutzer erhalten?</span>
                                <div class="row mt-7 mb-4">
                                    <x-label for="lblRole" class="col-sm-2 col-form-label">{{__('role')}}</x-label>
                                    <div class="col-sm-10">
                                        <select id="roleAlternative" name="roles" class="alt-role"
                                                aria-label="Default select example">

                                        </select>
                                    </div>
                                </div>

                                <div id="btnRoleDelete">

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        //tooltip initialize
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        $(document).ready(function () {
            $("#deleteCustomize").click(function () {
                let roleAlt = $(this).val();
                let url = $('#frmChangeRole').attr('action');
                url.replace(':alt', roleAlt);
            });
        });

        $('.roleDelete').click(function (e) {
            e.preventDefault();
            $('#btnRoleDelete').html('');
            $('#deletedRole').val('');
            $('#roleAlternative').empty();
            let id = $(this).attr('data-id');
            let roleList = {!! $roles !!};
            let options = '<option>Rolle auswählen</option>';

            $.each(roleList, function (i, value) {
                if (value.id != id) {
                    options += '<option value="' + value.id + '">' + value.name + '</option>';
                }
            })
            $(options).appendTo('#roleAlternative');
            let deleteUrl = "{{route("customizedDelete",[":id",":alt"])}}";
            deleteUrl = deleteUrl.replace(':id', id);
            $('#frmChangeRole').attr('action', deleteUrl);
            $('#roleModal').modal('show');

        })

        $('.alt-role').change(function () {
            $('#btnRoleDelete').html('');
            let roleAlt = $(this).val();
            let url = $('#frmChangeRole').attr('action');
            url = url.replace(':alt', roleAlt);
            $('#frmChangeRole').attr('action', url);

            if ($.isNumeric($(this).val())) {
                $('<button id="deleteCustomize" class="btn btn-primary float-right">Delete</button>').appendTo('#btnRoleDelete');
            }

        })

    </script>
@endsection
