@extends('layouts.dashboard.app', ['datatable' => true])
@section('title')
    كشف حساب: {{ $supplier->name }}
@endsection
@section('content')
    @component('partials._breadcrumb')
        @slot('title', [
            'الموردين',
            $supplier->name,
            'كشف حساب'])
        @slot('url', [route('suppliers.index'), route('suppliers.show', $supplier), '#'])
        @slot('icon', ['users','user','eye'])
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
            </form>
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th colspan="5">
                            <h3 class="text-center">كشف حساب المورد: {{ $supplier->name }}</h3>
                        </th>
                    </tr>
                    <tr>
                        <th>التاريخ</th>
                        <th>البيان</th>
                        <th>اجمالي الفاتورة</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bills as $bill)
                        <tr>
                            <td>{{ $bill->created_at->format('Y-m-d') }}</td>
                            <th><a href="{{ route('bills.show', $bill) }}">{{ $bill->details() }}</a></th>
                            <th>{{ number_format(abs(($bill->amount)), 2) }}</th>
                            <th>{{ number_format(abs(($bill->payed)), 2) }}</th>
                            <th>{{ number_format(abs(($bill->remain)), 2) }}</th>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <th colspan="2">الاجمالي</th>
                    <th>{{ number_format(abs($bills->sum('amount')), 2) }}</th>
                    <th>{{ number_format(abs($bills->sum('payed')), 2) }}</th>
                    <th>{{ number_format(abs(($bills->sum('remain'))), 2) }}</th>
                </tfoot>
            </table>
        </div>
        {{--  <div class="box-footer">
        </div>  --}}
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