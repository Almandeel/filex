@extends('layouts.dashboard.app', ['datatable' => true])
@section('title')
    تقرير الخزن
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
                @if (auth()->user()->can('users-read'))
                    <label for="user_id">المستخدمين</label>
                    <select name="user_id" id="user_id" class="form-control select2">
                        <option value="all" {{ ($user_id == 'all') ? 'selected' : '' }}>الكل</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ ($user_id == $user->id) ? 'selected' : '' }}>{{ $user->employee->name }}</option>
                        @endforeach
                    </select>
                @endif
                @if (auth()->user()->can('safes-read'))
                    <label for="safe_id">الخزن</label>
                    <select name="safe_id" id="safe_id" class="form-control select2">
                        <option value="all" {{ ($safe_id == 'all') ? 'selected' : '' }}>الكل</option>
                        @foreach ($safes as $safe)
                            <option value="{{ $safe->id }}" {{ ($safe_id == $safe->id) ? 'selected' : '' }}>{{ $safe->name }}</option>
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
                        <th>#</th>
                        <th>الخزنة</th>
                        <th>داخل</th>
                        <th>خارج</th>
                        <th>الترصيد</th>
                        <th>الرصيد</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0; $totalDebts = 0; $totalCredits = 0; $balances = 0;
                    @endphp
                    @if ($entries)
                        @foreach ($safes as $safe)
                            @php
                                $debts = $entries->where('from_id', $safe->id);
                                $credits = $entries->where('to_id', $safe->id);
                            @endphp
                            @if ($debts->sum('amount') || $credits->sum('amount'))
                                @php
                                $amount = $credits->sum('amount') - $debts->sum('amount');
                                $total += $amount;
                                $totalDebts += $debts->sum('amount');
                                $totalCredits += $credits->sum('amount');
                                $balances += $safe->balance();
                                @endphp
                                <tr>
                                    <td>{{ $loop->index }}</td>
                                    <td>{{ $safe->name }}</td>
                                    <th>{{ number_format($debts->sum('amount'), 2) }}</th>
                                    <th>{{ number_format($credits->sum('amount'), 2) }}</th>
                                    <th>{{ number_format(($amount), 2) }}</th>
                                    <th>{{ number_format(($safe->balance()), 2) }}</th>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <th colspan="2">الاجمالي</th>
                    <th>{{ number_format($totalDebts, 2) }}</th>
                    <th>{{ number_format($totalCredits, 2) }}</th>
                    <th>{{ number_format(($total), 2) }}</th>
                    <th>{{ number_format(($balances), 2) }}</th>
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
                'ordering'    : true,
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