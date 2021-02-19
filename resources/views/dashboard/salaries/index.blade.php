@extends('layouts.dashboard.app', ['modals'=>['salary'], 'datatable' => true])

@section('title', 'الموظفين - ' . $employee->name . ' - المرتبات')

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الموظفين',$employee->name, 'المرتبات'])
        @slot('url', [route('employees.index'), route('employees.show', $employee), '#'])
        @slot('icon', ['users', 'user', 'list'])
    @endcomponent
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="pull-left">
                <i class="fa fa-list"></i>
                <span>المرتبات</span>
            </h3>
            @permission('salaries-create')
            <button class="btn btn-primary pull-right showSalaryModal"
                data-action="{{ route('salaries.create', $employee) }}"
                data-employee-id="{{ $employee->id }}"
                >اضافة مرتب</button>
            @endpermission
            <a href="{{ route('report.salaries', $employee->id) }}" style="margin:0 5px" class="btn btn-primary pull-right"><i class="fa fa-print"></i> طباعة</a>
        </div>
        <div class="box-body">
            <table id="salaries-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>المعرف</th>
                        <th>الموظف</th>
                        <th>المرتب</th>
                        <th>السلفيات</th>
                        <th>الخصومات</th>
                        <th>العلاوات</th>
                        <th>الصافي</th>
                        <th>التاريخ</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salaries as $salary)
                        <tr>
                            <td>{{ $salary->id}}</td>
                            <td>{{ $salary->user->employee->name}}</td>
                            <td>{{ number_format($salary->total) }}</td>
                            <td>{{ number_format($salary->debts) }}</td>
                            <td>{{ number_format($salary->deducations) }}</td>
                            <td>{{ number_format($salary->bonus) }}</td>
                            <td>{{ number_format($salary->net) }}</td>
                            <td>{{ $salary->created_at->format('Y-m-d')}}</td>
                            <td>
                                @permission('salaries-print')
                                    <a href="{{ route('report.salary', $salary->id) }}" style="margin:0 5px" class="btn btn-default btn-xs"><i class="fa fa-print"></i> طباعة</a>
                                @endpermission
                                @permission('salaries-delete')
                                <form action="{{ route('salaries.destroy', [$employee, $salary]) }}" method="POST" style="display: inline">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> حذف </button>
                                </form>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
