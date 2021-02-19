@extends('layouts.dashboard.report')

@section('name')
سند استلام راتب 
@endsection


@section('content')
<div class="reportr-header">
    <div>
       <h3></h3>
    </div>
</div>
<div style="text-align:right" class="report-body">
    <div style="border:2px solid #333;padding:0 20px" class="box">
        <p>انا الموقع ادناه  \ {{ $salary->employee->name }}</p>
        <div class="">
            <div class="col-md-6">
                <p>استلمت من \ {{ $salary->user->name }}</p>
            </div>
            <div class="col-md-6">
                <div class="col-md-6">
                    <p> مبلغ و قدره  \ {{ $salary->net }} جنيه</p>
                </div>
            </div>
        </div>
        <p>وذلك راتب شهر \ {{ $salary->created_at->format('Y-m') }}</p>
    </div>
    <p style="text-align:left" class="">التوقيع : .............................</p>

    <div style="margin-bottom:30px;border-bottom:2px solid #000" class="box">
        <caption style="font-size:20px;font-weight:bold;margin:10px 0 ">تفاصيل المرتب</caption>            
        <table class="table table-bordered table-hover text-center">
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
        @if(count($salary->employee->monthlyTransactions($salary->created_at->format('Y') . '-' . $salary->created_at->format('m'))))
            <div class="box-footer">
                <h3>تفاصيل المعاملات المالية </h3>
                <table id="transactions-table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>المعرف</th>
                            <th>النوع</th>
                            <th>المبلغ</th>
                            <th>التفاصيل</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salary->employee->monthlyTransactions($salary->created_at->format('Y') . '-' . $salary->created_at->format('m')) as $transaction)
                            <tr>
                                <td>{{ $transaction->id}}</td>
                                <td>{{ App\Transaction::TYPES[$transaction->type]}}</td>
                                <td>{{ number_format($transaction->amount) }}</td>
                                <td>{{ $transaction->details}}</td>
                                <td>{{ $transaction->created_at->format('Y-m-d')}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</div>

<div class="report-footer">
</div>
@endsection