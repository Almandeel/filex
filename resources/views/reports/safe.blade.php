@extends('layouts.dashboard.app', ['datatable' => true])
@section('title')
    حركة: {{ $safe->name }}
@endsection
@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['التقارير', 'الخزن'])
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
                @if (auth()->user()->can('safes-read'))
                    <label for="safe_id">الخزن</label>
                    <select name="safe_id" id="safe_id" class="form-control select2">
                        @foreach ($safes as $s)
                            <option value="{{ $s->id }}" {{ ($safe_id == $s->id) ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                @endif
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i>
                    <span></span>
                </button>
            </form>
        </div>
        <div class="box-footer">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th colspan="6">
                            <h3 class="text-center">حركة: {{ $safe->name }}</h3>
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
                        $total = $opening_balance; 
                        $totalDebts = $debts->sum('amount');
                        $totalCredits = $credits->sum('amount');
                    @endphp
                    <tr>
                        <td></td>
                        <td></td>
                        <th>رصيد مرحل</th>
                        <td></td>
                        <td></td>
                        <td>{{ number_format($total, 2) }}</td>
                    </tr>
                    @foreach ($debts as $debt)
                        @if ($debts->sum('amount'))
                            <tr>
                                <td>{{ $debt->user->employee->name }}</td>
                                <td>{{ $debt->created_at->format('Y-m-d') }}</td>
                                <th>{{ $debt->details }}</th>
                                <th>{{ number_format(($debt->amount), 2) }}</th>
                                <th></th>
                                <th>{{ number_format(($total), 2) }}</th>
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
                                <th>{{ number_format(($credit->amount), 2) }}</th>
                                <th>{{ number_format(($total), 2) }}</th>
                            </tr>
                            @php $total -= $credit->amount @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <th colspan="3">الاجمالي</th>
                    <th>{{ number_format($totalDebts, 2) }}</th>
                    <th>{{ number_format($totalCredits, 2) }}</th>
                    <th>{{ number_format(($total), 2) }}</th>
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