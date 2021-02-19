@extends('layouts.dashboard.app', ['modals' => ['employee']])

@section('title')
الفواتير
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الموردين', $supplier->name, 'الفواتير'])
        @slot('url', [route('suppliers.index'), '#', '#'])
        @slot('icon', ['users', 'user', 'file-text-o'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            <h3>فواتير : {{ $supplier->name }}</h3>
        </div>
        <div class="box-body">
            <table id="bills-table" class="datatable table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th rowspan="2">المعرف</th>
                        <th rowspan="2">المخزن</th>
                        <th rowspan="2">المنتجات</th>
                        <th colspan="3">المبلغ</th>
                        <th rowspan="2">الموظف</th>
                        <th rowspan="2">تاريخ الانشاء</th>
                        <th rowspan="2">الخيارات</th>
                    </tr>
                    <tr>
                        <th>الاجمالي</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bills as $bill)
                        <tr>
                            <td>{{ $bill->id}}</td>
                            <td>{{ $bill->store->name}}</td>
                            
                            <td>{{ number_format(count($bill->items)) }}</td>
                            <td class="bill-total">{{ $bill->amount }}</td>
                            <td class="bill-totalPayed">{{ $bill->payed }}</td>
                            <td class="bill-totalRemain">{{ $bill->remain }}</td>
                            <td>
                                @can('employees-read')
                                <a href="#" class="showEmployeeModal preview" data-id="{{$bill->user->id}}"
                                    data-name="{{$bill->user->employee->name}}" data-salary="{{$bill->user->employee->salary}}"
                                    data-phone="{{$bill->user->employee->phone}}"
                                    data-address="{{$bill->user->employee->address}}">{{ $bill->user->employee->name }}</a>
                                @else
                                {{ $bill->user->employee->name }}
                                @endif
                            </td>
                            <td>{{ $bill->created_at->format("Y-m-d") }}</td>
                            <td>
                                @permission('bills-read')
                                <a href="{{ route('bill.print', $bill) }}" class="btn btn-default btn-xs">
                                    <i class="fa fa-print"></i>
                                    <span>طباعة</span>
                                </a>
                                @endpermission
                                @permission('bills-read')
                                <a href="{{ route('supplier.bill', $bill) }}" class="btn btn-info btn-xs">
                                    <i class="fa fa-eye"></i>
                                    <span>عرض</span>
                                </a>
                                @endpermission
                                @permission('bills-update')
                                <a href="{{ route('bills.edit', $bill) }}" class="btn btn-warning btn-xs">
                                    <i class="fa fa-pencil"></i>
                                    <span>تعديل</span>
                                </a>
                                @endpermission
                                @permission('bills-delete')
                                <form action="{{ route('bills.destroy', $bill) }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs delete">
                                        <i class="fa fa-ban"></i>
                                        <span>إلغاء</span>
                                    </button>
                                </form>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection