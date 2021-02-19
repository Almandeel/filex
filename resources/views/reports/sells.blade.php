@extends('layouts.dashboard.app', ['datatable' => true])
@section('title')
    تقرير المبيعات
@endsection
@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['التقارير', 'المبيعات'])
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
                <label for="store_id">المخازن</label>
                <select name="store_id" id="store_id" class="form-control select2">
                    <option value="all" {{ ($store_id == 'all') ? 'selected' : '' }}>الكل</option>
                    <option value="out" {{ ($store_id == 'out') ? 'selected' : '' }}>البيع الخارجي</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}" {{ ($store_id == $store->id) ? 'selected' : '' }}>{{ $store->name }}</option>
                    @endforeach
                </select>
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
                @if (auth()->user()->can('customers-read'))
                    <label for="customer_id">العملاء</label>
                    <select name="customer_id" id="customer_id" class="form-control select2">
                        <option value="all" {{ ($customer_id == 'all') ? 'selected' : '' }}>الكل</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ ($customer_id == $customer->id) ? 'selected' : '' }}>{{ $customer->name }}</option>
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
            <table id="invoices-table" class="datatable table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th rowspan="2">المعرف</th>
                        <th rowspan="2">المخزن / المورد</th>
                        <th rowspan="2">العميل</th>
                        <th rowspan="2">المنتجات</th>
                        <th colspan="3">المبلغ</th>
                        <th rowspan="2">الموظف</th>
                        <th rowspan="2">التاريخ</th>
                    </tr>
                    <tr>
                        <th>الاجمالي</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total_items = 0; @endphp
                    @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->id}}</td>
                        <td>
                            @if ($invoice->bill)
                            {{ $invoice->bill->supplier->name }}
                            @else
                            {{ $invoice->store->name}}
                            @endif
                        </td>
                        <td>
                            {{ $invoice->customer->name }}
                        </td>
                        <td>{{ count($invoice->items) }}</td>
                        <td class="invoice-total">{{ number_format($invoice->amount, 2) }}</td>
                        <td class="invoice-totalPayed">{{ number_format($invoice->payed, 2) }}</td>
                        <td class="invoice-totalRemain">{{ number_format($invoice->remain, 2) }}</td>
                        <td>
                            {{ $invoice->user->employee->name }}
                        </td>
                        <td>{{ $invoice->created_at->format("Y-m-d") }}</td>
                    </tr>
                    @php $total_items += count($invoice->items); @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">الاجمالي</th>
                        <th>{{ $total_items }}</th>
                        <th>{{ number_format($invoices->sum('amount'), 2) }}</th>
                        <th>{{ number_format($invoices->sum('payed'), 2) }}</th>
                        <th>{{ number_format($invoices->sum('remain'), 2) }}</th>
                        <th colspan="2"></th>
                    </tr>
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
                "order": [[ 8, "desc" ]]
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