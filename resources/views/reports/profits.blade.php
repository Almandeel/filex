@extends('layouts.dashboard.app', ['datatable' => true])
@section('title', 'تقرير ' . $title)
@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['التقارير', 'تقرير ' . $title])
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
                <label for="store_id">المخزن</label>
                <select name="store_id" id="store_id" class="form-control select2">
                    <option value="all" {{ ($store_id == 'all') ? 'selected' : '' }}>الكل</option>
                    <option value="out" {{ ($store_id == 'out') ? 'selected' : '' }}>بيع مباشر</option>
                    @foreach ($stores as $str)
                        <option value="{{ $str->id }}" {{ ($store_id == $str->id) ? 'selected' : '' }}>{{ $str->name }}</option>
                    @endforeach
                </select>
                <label for="item_id">المنتج</label>
                <select name="item_id" id="item_id" class="form-control select2">
                    <option value="all" {{ ($item_id == 'all') ? 'selected' : '' }}>الكل</option>
                    @foreach ($items as $itm)
                        <option value="{{ $itm->id }}" {{ ($item_id == $itm->id) ? 'selected' : '' }}>{{ $itm->name }}</option>
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
            <table id="invoices-table" class="datatable table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        @if (!$store && $store_id !== 'out') <th>المخزن</th> @endif
                        <th>{{ $item ? 'الوحدة' : 'المنتج' }}</th>
                        <th>سعر الشراء</th>
                        <th>سعر البيع</th>
                        <th>الصافي</th>
                        <th>
                            <span class="text-success">&uarr;</span>
                            <span class="text-danger">&darr;</span>
                            {{--  <i class="text-success fa fa-arrow-circle-up"></i>
                            <i class="text-danger fa fa-arrow-circle-down"></i>  --}}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $purchases = 0; $sells = 0; $nets = 0; $index = 0;
                    @endphp
                  @foreach ($itemz as $it)
                    @foreach ($it as $unit)
                        @php
                            $index += 1;
                            $purchases += $unit->price_purchase; 
                            $sells += $unit->price_sell; 
                            $net = $unit->price_sell - $unit->price_purchase;
                            $nets += $net;
                        @endphp
                        <tr>
                            <td>{{ $index }}</td>
                            @if (!$store && $store_id !== 'out') <td>{{ $unit->storeName() }}</td> @endif
                            <td>
                                @if ($item)
                                    {{ $unit->unitName() }}
                                @else
                                    {{ $unit->itemName() }}(<strong>{{ $unit->unitName() }}</strong>)
                                @endif
                            </td>
                            <td>{{ number_format($unit->price_purchase, 2) }}</td>
                            <td>{{ number_format($unit->price_sell, 2) }}</td>
                            <td>{{ number_format($net, 2) }}</td>
                            <td>
                                @if ($net > 0)
                                {{--  <i class="text-success fa fa-arrow-circle-up"></i>  --}}
                                <span class="text-success">&uarr;</span>
                                <strong>ربح</strong>
                                @elseif ($net < 0)
                                {{--  <i class="text-danger fa fa-arrow-circle-down"></i>  --}}
                                <span class="text-danger">&darr;</span>
                                <strong>خسارة</strong>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                  @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        {{--  <th>#</th>  --}}
                        <th colspan="{{ !$store || $store_id !== 'out' ? 3 : 3 }}">الاجمالي</th>
                        <th>{{ number_format($purchases, 2) }}</th>
                        <th>{{ number_format($sells, 2) }}</th>
                        <th>{{ number_format($nets, 2) }}</th>
                        <th>
                            @if ($nets > 0)
                            {{--  <i class="text-success fa fa-arrow-circle-up"></i>  --}}
                            <span class="text-success">&uarr;</span>
                            <strong>ربح</strong>
                            @elseif ($nets < 0)
                            {{--  <i class="text-danger fa fa-arrow-circle-down"></i>  --}}
                            <span class="text-danger">&darr;</span>
                            <strong>خسارة</strong>
                            @endif
                        </th>
                    </tr>
                </tfoot>
            </table>
            {{ $invoices_items->appends(request()->all())->links() }}
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function(){
        })
    </script>
@endpush