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
                        <td class="total">{{ $bill->total() }}</td>
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
                    <i class="fa fa-list-alt"></i>
                    <span>فواتير الاستلام</span>
                </h4>
            </div>
            <div class="box-body">
                @foreach ($bill->bills as $b)
                    <div style="border:1px solid #444;margin-bottom:20px" class="box">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>المنتجات</th>
                                    <th>الموظف</th>
                                    <th>تاريخ الانشاء</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $b->id}}</td>
                                    <td>{{ count($b->items) }}</td>
                                    <td>
                                        {{ $b->user->employee->name }}
                                    </td>
                                    <td>{{ $b->created_at->format("Y-m-d") }}</td>
                                </tr>
                                </tbody>
                        </table>

                        <table style="width:100%" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المنتج</th>
                                    <th>الوحدة</th>
                                    <th>الكمية</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($b->items as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->itemName() }}</td>
                                    <td>{{ $item->unitName() }}</td>
                                    <td>{{ $item->quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<div class="report-footer">
</div>

@endsection