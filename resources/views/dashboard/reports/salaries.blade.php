@extends('layouts.dashboard.report')

@section('name')
    تقرير مرتب
@endsection


@section('content')
<div class="reportr-header">
    <div>
       <h3> الموظف : {{ $employee->name }}</h3>
    </div>
</div>
<div class="report-body">
    @foreach ($employee->salaries as $salary)
        <div style="margin-bottom:30px;border-bottom:2px solid #000" class="box">            
            <table class="table table-bordered table-hover text-center">
                <caption style="text-align:right">التاريخ : {{  $salary->created_at->format('Y-m-d')  }}</caption>
                <thead>
                    <tr>
                        <th>المعرف</th>
                        <th>المرتب</th>
                        <th>السلفيات</th>
                        <th>الخصومات</th>
                        <th>العلاوات</th>
                        <th>الصافي</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>{{ $salary->id}}</td>
                            <td>{{ number_format($salary->total) }}</td>
                            <td>{{ number_format($salary->debts) }}</td>
                            <td>{{ number_format($salary->deducations) }}</td>
                            <td>{{ number_format($salary->bonus) }}</td>
                            <td>{{ number_format($salary->net) }}</td>
                        </tr>
                    </tbody>
            </table>
            @if(count($employee->monthlyTransactions($salary->created_at->format('Y') . '-' . $salary->created_at->format('m'))))
                <div class="box-footer">
                    <h3>تفاصيل المعاملات المالية </h3>
                    <table id="transactions-table" class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>المعرف</th>
                                <th>الموظف</th>
                                <th>النوع</th>
                                <th>المبلغ</th>
                                <th>التفاصيل</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->monthlyTransactions($salary->created_at->format('Y') . '-' . $salary->created_at->format('m')) as $transaction)
                                <tr>
                                    <td>{{ $transaction->id}}</td>
                                    <td>{{ App\Transaction::TYPES[$transaction->type]}}</td>
                                    <td>{{ $transaction->user->employee->name}}</td>
                                    <td>{{ number_format($transaction->amount) }}</td>
                                    <td>{{ $transaction->details}}</td>
                                    <td>{{ $transaction->created_at->format('Y-m-d')}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else 
            <div class="box-footer">
                <h3>لا توجد معاملات مالية </h3>
            </div>
            @endif

        </div>
    @endforeach
</div>

<div class="report-footer">
</div>
@endsection