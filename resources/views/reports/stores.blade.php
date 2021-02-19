@extends('layouts.dashboard.app', ['datatable' => true])
@section('title', $title)
@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['التقارير', $title])
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
                <label for="store_id">المخزن</label>
                <select name="store_id" id="store_id" class="form-control select2">
                    <option value="all" {{ ($store_id == 'all') ? 'selected' : '' }}>الكل</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}" {{ ($store_id == $store->id) ? 'selected' : '' }}>{{ $store->name }}</option>
                    @endforeach
                </select>
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
                        <th>#</th>
                        @if ($store_id == 'all')
                        <th>المخزن</th>
                        @endif
                        <th>المنتج</th>
                        <th>الكمية</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach ($items_stores_units as $unit)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            @if ($store_id == 'all')
                            <td>{{ $unit->storeName() }}</td>
                            @endif
                            <td>{{ $unit->itemName() }}(<strong>{{ $unit->unitName() }}</strong>)</td>
                            <td>{{ $unit->quantity }}</td>
                        </tr>
                  @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th colspan="{{ ($store_id == 'all') ? 2 : 1 }}">الاجمالي</th>
                        <th>{{ $items_stores_units->sum('quantity') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function(){
            /* var table = $('table.datatable').dataTable({
                'paging'      : false,
                'searching'   : true,
                'ordering'    : true,
                "order": [[ 5, "desc" ]],
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
            }); */

            $('a.toggle-vis').on( 'click', function (e) {
                e.preventDefault();
                
                // Get the column API object
                var column = table.column( $(this).attr('data-column') );
                
                // Toggle the visibility
                column.visible( ! column.visible() );
            } );
        })
    </script>
@endpush