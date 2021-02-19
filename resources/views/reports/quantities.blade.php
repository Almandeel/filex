@extends('layouts.dashboard.app', ['datatable' => true])
@section('title')
    تقرير الكميات لمخزن: {{ $store->name }}
@endsection
@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['التقارير', 'الكميات - مخزن: ' . $store->name])
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
                    @foreach ($stores as $s)
                        <option value="{{ $s->id }}" {{ ($store->id == $s->id) ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
                <label for="from-date">من</label>
                <input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
                <label for="to-date">الى</label>
                <input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i>
                    <span></span>
                </button>
            </form>
        </div>
        <div class="box-footer">
            <table id="bills-table" class="datatable table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th rowspan="2">المنتج</th>
                        <th colspan="5">الكميات</th>
                    </tr>
                    <tr>
                        <th>في المخزن</th>
                        <th>المشترى</th>
                        <th>المستلم</th>
                        <th>المبيع</th>
                        <th>المسلم</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total_in_store = 0; $total_bills_items = 0; $total_bills_receives = 0; $total_invoices_items = 0; $total_invoices_receives = 0; @endphp
                    @foreach ($items_stores as $item_store)
                        @foreach ($item_store->items as $item)
                            @php
                                $bills_items = \App\BillItem::where('item_store_unit_id', $item->id)
                                    ->whereNull('bill_item_id');
                                $bills_receives = \App\BillItem::where('item_store_unit_id', $item->id)
                                    ->whereNotNull('bill_item_id');
                                $invoices_items = \App\InvoiceItem::where('item_store_unit_id', $item->id)
                                    ->whereNull('invoice_item_id');
                                $invoices_receives = \App\InvoiceItem::where('item_store_unit_id', $item->id)
                                    ->whereNotNull('invoice_item_id');
                                
                                if(request('from_date') && request('to_date')){
                                    $fromDate = $from_date . ' 00:00:00';
                                    $toDate = $to_date . ' 23:59:59';

                                    $bills_items = $bills_items->whereBetween('created_at', [$fromDate, $toDate])
                                        ->get();

                                    $bills_receives = $bills_receives->whereBetween('created_at', [$fromDate, $toDate])
                                        ->get();

                                    $invoices_items = $invoices_items->whereBetween('created_at', [$fromDate, $toDate])
                                        ->get();

                                    $invoices_receives = $invoices_receives->whereBetween('created_at', [$fromDate, $toDate])
                                        ->get();
                                }else{
                                    $bills_items = $bills_items->get();
                                    $bills_receives = $bills_receives->get();
                                    
                                    $invoices_items = $invoices_items->get();
                                    $invoices_receives = $invoices_receives->get();
                                }
                                $in_store = $bills_receives->sum('quantity') - $invoices_receives->sum('quantity');
                                
                                $total_in_store += $in_store; 
                                $total_bills_items += $bills_items->sum('quantity'); 
                                $total_bills_receives += $bills_receives->sum('quantity'); 
                                $total_invoices_items += $invoices_items->sum('quantity'); 
                                $total_invoices_receives += $invoices_receives->sum('quantity');

                                @endphp
                            <tr>
                                <td>{{ $item->itemName() }} - {{ $item->unitName() }}</td>
                                <td>{{ $in_store }}</td>
                                <td>{{ $bills_items->sum('quantity') }}</td>
                                <td>{{ $bills_receives->sum('quantity') }}</td>
                                <td>{{ $invoices_items->sum('quantity') }}</td>
                                <td>{{ $invoices_receives->sum('quantity') }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الاجمالي</th>
                        <th>{{ $total_in_store }}</th>
                        <th>{{ $total_bills_items }}</th>
                        <th>{{ $total_bills_receives }}</th>
                        <th>{{ $total_invoices_items }}</th>
                        <th>{{ $total_invoices_receives }}</th>
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