@extends('layouts.dashboard.app', ['datatable' => true])
@section('title')
    كشف حساب: {{ $account->name }}
@endsection
@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['التقارير', 'كشف حساب: ' . $account->name])
        @slot('url', ['#', '#'])
        @slot('icon', ['print', 'file'])
    @endcomponent
    <div class="box box-primary">
        <div class="box-header">
            <h4 class="box-title">
                <i class="fa fa-search"></i>
                <span>خيارات البحث</span>
            </h4>
        </div>
        <div class="box-body">
            <form action="" method="GET" class="form-inline d-inline-block">
                @csrf
                <label for="from-date">من</label>
                <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                <label for="to-date">الى</label>
                <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i>
                    <span></span>
                </button>
                <input type="hidden" name="account_id" value="{{ $account->id }}">
            </form>
        </div>
        <div class="box-footer">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th colspan="6">
                            <h3 class="text-center">كشف حساب: {{ $account->name }}</h3>
                        </th>
                    </tr>
                    <tr>
                        <th>الموظف</th>
                        <th>التاريخ</th>
                        <th>البيان</th>
                        <th>داخل</th>
                        <th>خارج</th>
                        <th>الرصيد</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                        $totalDebts = $debts->sum('amount');
                        $totalCredits = $credits->sum('amount');
                        if($opening_balance['type'] == 'debt'){
                            $total += $opening_balance['amount']; 
                            $totalDebts += $opening_balance['amount']; 
                        }
                        elseif($opening_balance['type'] == 'credit'){
                            $total -= $opening_balance['amount']; 
                            $totalCredits += $opening_balance['amount']; 
                        }
                    @endphp
                    @if ($opening_balance['type'])
                    <tr>
                        <td></td>
                        <td></td>
                        <th>
                            @if ($opening_balance['type'])
                                رصيد {{ $opening_balance['title'] }} مرحل
                            @endif
                        </th>
                        <td>
                            @if ($opening_balance['type'] =='debt')
                                {{ number_format(abs($opening_balance['amount']), 2) }}
                            @endif
                        </td>
                        <td>
                            @if ($opening_balance['type'] =='credit')
                                {{ number_format(abs($opening_balance['amount']), 2) }}
                            @endif
                        </td>
                        <td>{{ number_format(abs($total), 2) }}</td>
                    </tr>
                    @endif
                    @foreach ($debts as $debt)
                        @if ($debts->sum('amount'))
                            <tr>
                                <td>{{ $debt->user->employee->name }}</td>
                                <td>{{ $debt->created_at->format('Y-m-d') }}</td>
                                <th>{{ $debt->details }}</th>
                                <th>{{ number_format(abs(($debt->amount)), 2) }}</th>
                                <th></th>
                                <th>{{ number_format(abs(($total)), 2) }}</th>
                            </tr>
                            @php $total += $debt->amount @endphp
                        @endif
                    @endforeach
                    @foreach ($credits as $credit)
                        @if ($credits->sum('amount'))
                            <tr>
                                <td>{{ $credit->user->employee->name }}</td>
                                <td>{{ $credit->created_at->format('Y-m-d') }}</td>
                                <th>{{ $credit->details }}</th>
                                <th></th>
                                <th>{{ number_format(abs(($credit->amount)), 2) }}</th>
                                <th>{{ number_format(abs(($total)), 2) }}</th>
                            </tr>
                            @php $total -= $credit->amount @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <th colspan="3">الاجمالي</th>
                    <th>{{ number_format(abs($totalDebts), 2) }}</th>
                    <th>{{ number_format(abs($totalCredits), 2) }}</th>
                    <th>{{ number_format(abs(($total)), 2) }}</th>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function(){
            $('table.datatable').dataTable({
                'paging'      : false,
                'searching'   : true,
                'ordering'    : false,
                'info'        : true,
                'autoWidth'   : true,
                'dom': 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'طباعة',
                        header: true,
                        footer: true,
                    },
                    {
                        extend: 'excel',
                        text: 'تصدير Excel',
                        header: true,
                        footer: true,
                    },
                ]
            });
        })
    </script>
@endpush