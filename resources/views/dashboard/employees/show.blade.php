@extends('layouts.dashboard.app', ['modals' => ['employee', 'salary', 'transaction'], 'datatable' => true])

@section('title', 'الموظفين - ' . $employee->name)

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الموظفين',$employee->name])
        @slot('url', [route('employees.index'), '#'])
        @slot('icon', ['users', ''])
    @endcomponent
    <div class="box">
        <div class="box-header">
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>المعرف</th>
                        <th>اسم</th>
                        <th>المرتب</th>
                        <th>رقم الهاتف</th>
                        <th>العنوان</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $employee->id }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->salary != null ? number_format($employee->salary) : 0 }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->address }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="box-footer text-center">
            <div class="btn-group">
                @permission('salaries-create')
                <button class="btn btn-primary btn-sm showSalaryModal"
                    data-action="{{ route('salaries.create', $employee) }}"
                    data-employee-id="{{ $employee->id }}"
                    >
                    <i class="fa fa-plus"></i>
                    <span>اضافة مرتب</span>
                </button>
                @endpermission
                @permission('salaries-read')
                <a class="btn btn-default btn-sm" href="{{ route('salaries.index', $employee) }}"><i class="fa fa-list"></i> المرتبات
                </a>
                @endpermission
                @permission('employees-update')
                <button type="button" class="btn btn-warning btn-sm showEmployeeModal update"
                    data-action="{{ route('employees.update', $employee->id) }}" data-id="{{ $employee->id }}"
                    data-name="{{ $employee->name }}" data-salary="{{ $employee->salary }}" data-phone="{{ $employee->phone }}"
                    data-address="{{ $employee->address }}"><i class="fa fa-edit"></i> تعديل
                </button>
                @endpermission
            </div>
            @permission('employees-delete')
            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display: inline">
                @csrf
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> حذف </button>
            </form>
            @endpermission
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header">
            <form action="" method="get" class="form-inline pull-left">
                @csrf
                <div class="form-group">
                    <h3>المعاملات المالية</h3>
                </div>
                <div class="input-group">
                    <div class="input-group-addon">
                        السنة
                    </div>
                    <select class="form-control" name="year">
                        @for($i = date('Y'); $i >= 2000; $i--)
                            <option value="{{ $i }}" @if($year == $i) selected @endif>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="input-group">
                    <div class="input-group-addon">
                        الشهر
                    </div>
                    <select class="form-control" name="month">
                        @for($i = 1; $i <= 12; $i++)
                            @php
                                $m = $i < 10 ? '0' + $i : $i;
                            @endphp
                            <option value="{{ $m }}" @if($month == $m) selected @endif>{{ $m }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-rimary">
                        <i class="fa fa-search"></i>
                        <span>بحث</span>
                    </button>
                </div>
            </form>
            <button class="btn btn-primary pull-right showTransactionModal" data-employee-id="{{ $employee->id }}">اضافة عملية</button>
        </div>
        <div class="box-body">
            <h3>إجمالي المعاملات المالية لشهر: {{ $year . '-' . $month }}</h3>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <td colspan="3">سلفة</td>
                        <td colspan="3">خصم</td>
                        <td colspan="3">علاوة</td>
                    </tr>
                    <tr>
                        <th>تم</th>
                        <th>لاحقا</th>
                        <th>الاجمالي</th>
                        <th>تم</th>
                        <th>لاحقا</th>
                        <th>الاجمالي</th>
                        <th>تم</th>
                        <th>لاحقا</th>
                        <th>الاجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $totalDebts['payed'] }}</td>
                        <td>{{ $totalDebts['remain'] }}</td>
                        <td>{{ $totalDebts['total'] }}</td>
                        <td>{{ $totalDeducations['payed'] }}</td>
                        <td>{{ $totalDeducations['remain'] }}</td>
                        <td>{{ $totalDeducations['total'] }}</td>
                        <td>{{ $totalBonuses['payed'] }}</td>
                        <td>{{ $totalBonuses['remain'] }}</td>
                        <td>{{ $totalBonuses['total'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            <h3>تفاصيل المعاملات المالية لشهر: {{ $year . '-' . $month }}</h3>
            <table id="transactions-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>المعرف</th>
                        <th>الموظف</th>
                        <th>الخزنة</th>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>التفاصيل</th>
                        <th>التاريخ</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employee->monthlyTransactions($year . '-' . $month) as $transaction)
                        <tr>
                            <td>{{ $transaction->id}}</td>
                            <td>{{ $transaction->user->employee->name}}</td>
                            <td>{{ $transaction->safe ? $transaction->safe->name : 'لاحقا'}}</td>
                            <td>{{ App\Transaction::TYPES[$transaction->type]}}</td>
                            <td>{{ number_format($transaction->amount, 2) }}</td>
                            <td>{{ $transaction->details}}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d')}}</td>
                            <td>
                                @permission('transactions-update')
                                <button type="button" class="btn btn-warning btn-xs showTransactionModal update"
                                    data-action="{{ route('transactions.update', $transaction->id) }}" 
                                    data-id="{{ $transaction->id }}"
                                    data-employee-id="{{ $transaction->employee_id }}"
                                    data-safe-id="{{ $transaction->safe_id }}"
                                    data-user-id="{{ $transaction->user_id }}"
                                    data-amount="{{ $transaction->amount }}" data-type="{{ $transaction->type }}" data-details="{{ $transaction->details }}"
                                    data-month="{{ $transaction->month }}"><i class="fa fa-edit"></i> تعديل </button>
                                @endpermission
                                @permission('transactions-delete')
                                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display: inline">
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
