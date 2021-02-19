@extends('layouts.dashboard.app', ['modals' => ['employee', 'transaction'], 'datatable' => true])

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
            @permission('employees-delete')
            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display: inline">
                @csrf
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> حذف </button>
            </form>
            @endpermission
            @permission('employees-update')
            <button type="button" class="btn btn-warning btn-xs showEmployeeModal update"
                data-action="{{ route('employees.update', $employee->id) }}" data-id="{{ $employee->id }}"
                data-name="{{ $employee->name }}" data-salary="{{ $employee->salary }}" data-phone="{{ $employee->phone }}"
                data-address="{{ $employee->address }}"><i class="fa fa-edit"></i> تعديل </button>
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
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <td colspan="3">سلفة</td>
                        <td colspan="3">خصم</td>
                        <td colspan="3">علاوة</td>
                    </tr>
                    <tr>
                        <th>لاحقا</th>
                        <th>خزنة</th>
                        <th>بنك</th>
                        <th>لاحقا</th>
                        <th>خزنة</th>
                        <th>بنك</th>
                        <th>لاحقا</th>
                        <th>خزنة</th>
                        <th>بنك</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $totalDebts['later'] }}</td>
                        <td>{{ $totalDebts['safe'] }}</td>
                        <td>{{ $totalDebts['bank'] }}</td>
                        <td>{{ $totalDeducations['later'] }}</td>
                        <td>{{ $totalDeducations['safe'] }}</td>
                        <td>{{ $totalDeducations['bank'] }}</td>
                        <td>{{ $totalCredits['later'] }}</td>
                        <td>{{ $totalCredits['safe'] }}</td>
                        <td>{{ $totalCredits['bank'] }}</td>
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
                            <td>{{ App\Transaction::TYPES[$transaction->type]}}</td>
                            <td>{{ $transaction->user->employee->name}}</td>
                            <td>{{ number_format($transaction->amount) }}</td>
                            <td>{{ $transaction->details}}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d')}}</td>
                            <td>
                                @permission('transactions-update')
                                <button type="button" class="btn btn-warning btn-xs showTransactionModal update"
                                    data-action="{{ route('transactions.update', $transaction->id) }}" data-id="{{ $transaction->id }}"
                                    data-employee-id="{{ $transaction->employee_id }}"
                                    data-user-id="{{ $transaction->user_id }}"
                                    @if (isset($transaction->entry))
                                        @if ($transaction->entry->from_id == App\Account::safe()->id || $transaction->entry->to_id == App\Account::safe()->id)
                                            data-account-id="{{ App\Account::safe()->id }}"
                                        @elseif ($transaction->entry->from_id == App\Account::bank()->id || $transaction->entry->to_id == App\Account::bank()->id)
                                            data-account-id="{{ App\Account::bank()->id }}"
                                        @endif
                                    @else
                                        data-account-id="0"
                                    @endif
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
