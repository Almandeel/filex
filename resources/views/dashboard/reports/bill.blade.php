@extends('layouts.dashboard.report')

@section('name')
    تقرير الفواتير
@endsection


@section('content')
<div class="reportr-header">
    <div>
       <h3> فاتورة رقم : {{ $bill->id }}</h3>
    </div>
</div>

<div class="report-body">
    <div class="box box-primary">
        <div class="box-header">
            <h4 class="box-title">
                <i class="fa fa-list-alt"></i>
                <span>بيانات {{ $title }}</span>
            </h4>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th rowspan="2">المعرف</th>
                        <th rowspan="2">المخزن</th>
                        <th rowspan="2">المورد</th>
                        <th colspan="2">الاجمالي</th>
                        <th rowspan="2">الموظف</th>
                        @if ($bill->bill)
                            <th rowspan="2">النوع</th>
                        @endif
                        <th rowspan="2">تاريخ الانشاء</th>
                    </tr>
                    <tr>
                        <th>المنتجات</th>
                        <th>المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $bill->id}}</td>
                        <td>{{ $bill->store->name}}</td>
                        <td>
                            {{ $bill->supplier->name }}
                        </td>
                        <td>{{ number_format(count($bill->items)) }}</td>
                        <td class="total">{{ $bill->amount }}</td>
                        <td>
                            {{ $bill->user->employee->name }}
                        </td>
                        <td>{{ $bill->created_at->format("Y-m-d") }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="box box-primary">
            <div class="box-header">
                <h4 class="box-title">
                    <i class="fa fa-cubes"></i>
                    <span>قائمة المنتجات</span>
                </h4>
            </div>
            <div class="box-body">
                <table id="items-table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">المنتج</th>
                            <th rowspan="2">الوحدة</th>
                            <th colspan="3">الكمية</th>
                            <th rowspan="2">سعر الشراء</th>
                            <th rowspan="2">الاجمالي</th>
                        </tr>
                        <tr>
                            <th>المجموع</th>
                            <th>المستلم</th>
                            <th>المتبقي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $totalQ = 0;  
                            $totalR = 0;  
                            $totalRemain = 0;
                            $totalP = 0;
                            $total = 0;
                        @endphp
                        @foreach ($bill->items as $item)
                            <tr @if($item->quantity == $item->items->sum('quantity')) class="success" @endif>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $item->itemName() }}</td>
                                <td>{{ $item->unitName() }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->items->sum('quantity') }}</td>
                                <td>{{ $item->quantity - $item->items->sum('quantity') }}</td>
                                <td>{{ number_format($item->price) }}</td>
                                <td>{{ number_format($item->quantity * $item->price) }}</td>
                            </tr>
                            @php 
                                $totalQ += $item->quantity;
                                $totalR += $item->items->sum('quantity');
                                $totalRemain += $item->quantity - $item->items->sum('quantity');
                                $totalP += ($item->price);
                                $total  =  ($total + $item->quantity * $item->price);
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="totalQ">{{ $totalQ }}</th>
                            <th class="totalR">{{ $totalR  }}</th>
                            <th class="totalRemain">{{ $totalRemain }}</th>
                            <th class="totalP">{{ number_format($totalP) }}</th>
                            <th class="total">{{ number_format($total) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="report-footer">
</div>

@endsection