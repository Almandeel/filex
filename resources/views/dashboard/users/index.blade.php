@extends('layouts.dashboard.app', ['datatable' => true])

@section('title')
    @lang('users.users')
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['المستخدمين'])
        @slot('url', ['#'])
        @slot('icon', ['users'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            @permission('users-create')
                <a  href="{{ route('users.create') }}" style="display:inline-block; margin-left:1%" class="btn btn-primary btn-sm pull-right" >
                    <i class="fa fa-user-plus">إضافة</i>
                </a>
            @endpermission

            @permission('roles-create')
                <a  href="{{ route('roles.index') }}" style="display:inline-block; margin-left:1%" class="btn btn-primary btn-sm pull-right" >
                    <i class="fa fa-user">الادوار</i>
                </a>
            @endpermission


        </div>
        <div class="box-body">
            <table id="users-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>اسم المستخدم</th>
                        <th>رقم الهاتف</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr>
                            <td>{{ $u->username }}</td>
                            <td>{{ $u->employee->phone }}</td>
                            <td>
                                @permission('users-read')
                                    <a class="btn btn-info btn-xs" href="{{ route('users.show', $u->id) }}"><i class="fa fa-eye"></i> عرض </a>
                                @endpermission

                                @permission('users-update')
                                    <a class="btn btn-warning btn-xs" href="{{ route('users.edit', $u->id) }}"><i class="fa fa-edit"></i> تعديل </a>
                                @endpermission

                                @permission('users-delete')
                                    <a class="btn btn-danger btn-xs" href="{{ route('users.show', $u->id) }}?delete=true"><i class="fa fa-trash"></i> حذف </a>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
