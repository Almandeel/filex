@extends('layouts.dashboard.app', ['modals' => ['employee'], 'datatable' => true])

@section('title', 'الموظفين')

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الموظفين'])
        @slot('url', ['#'])
        @slot('icon', ['users'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"></h3>
            <div class="box-tools">
                @permission('employees-create')
                    {{--  <button type="button" class="btn btn-primary showEmployeeModal">
                        <i class="fa fa-plus">إضافة</i>
                    </button>  --}}
                    <a href="{{ route('employees.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus">إضافة</i>
                    </a>
                @endpermission
            </div>
        </div>
        <div class="box-body">
            <table id="users-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>اسم الموظف</th>
                        <th>المرتب</th>
                        <th>رقم الهاتف</th>
                        <th>العنوان</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->salary != null ? number_format($employee->salary) : 0 }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td>{{ $employee->address }}</td>
                            <td>
                                @permission('salaries-read')
                                    <a class="btn btn-default btn-xs" href="{{ route('salaries.index', $employee) }}"><i class="fa fa-list"></i> المرتبات </a>
                                @endpermission
                                @permission('employees-read')
                                    <a class="btn btn-info btn-xs" href="{{ route('employees.show', $employee) }}"><i class="fa fa-eye"></i> عرض </a>
                                @endpermission

                                @permission('employees-update')
                                    <button type="button" class="btn btn-warning btn-xs showEmployeeModal update" data-action="{{ route('employees.update', $employee->id) }}" data-id="{{ $employee->id }}" data-name="{{ $employee->name }}" data-salary="{{ $employee->salary }}" data-phone="{{ $employee->phone }}" data-address="{{ $employee->address }}"><i class="fa fa-edit"></i> تعديل </button>
                                @endpermission

                                @permission('employees-delete')
                                    <a class="btn btn-danger btn-xs" href="{{ route('employees.show', $employee) }}?delete=true"><i class="fa fa-trash"></i> حذف </a>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <input type="hidden" data-action="{{ route('employees.store') }}" id="create">
        </div>
    </div>
@endsection
