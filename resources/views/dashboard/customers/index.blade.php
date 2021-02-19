@extends('layouts.dashboard.app', ['modals' => ['customer'], 'datatable' => true])

@section('title')
    العملاء
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['العملاء'])
        @slot('url', ['#'])
        @slot('icon', ['users'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            <h4 class="box-tit">
                <i class="fa fa-list"></i>
            </h4>
            @permission('customers-create')
                <div class="box-tools">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-success btn-sm showCustomerModal create" data-toggle="modal"
                            data-target="#customers">
                            <i class="fa fa-user-plus"> إضافة</i>
                        </button>
                        <a href="http://localhost:8000/export/customers" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel-o"> تصدير</i>
                        </a>
                
                        <a href="http://localhost:8000/example/excel" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel-o"> نموذج</i>
                        </a>
                
                        <form style="display:inline-block !important;" action="http://localhost:8000/imports/customers" method="post"
                            enctype="multipart/form-data" novalidate="" class="btn btn-success btn-sm">
                            @csrf
                            <label style="margin-bottom: 0px">
                                <i class="fa fa-file-excel-o"> استيراد</i>
                                <input type="file" required="" style="display:none" name="customers_excel" class="form-submitter">
                            </label>
                        </form>
                    </div>
                </div>
            @endpermission
        </div>
        <div class="box-body">
            <table id="customers-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>اسم العميل</th>
                        <th>رقم الهاتف</th>
                        <th>الرصيد</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ number_format($customer->balance(), 2) }}</td>
                            <td>
                                <div class="btn-group">
                                    @permission('customers-read')
                                        <a class="btn btn-primary btn-xs" href="{{ route('customers.statement', $customer) }}"><i class="fa fa-list"></i> كشف حساب </a>
                                        <a class="btn btn-info btn-xs" href="{{ route('customers.show', $customer->id) }}"><i class="fa fa-eye"></i> عرض </a>
                                    @endpermission
    
                                    <a class="btn btn-default btn-xs showCustomerModal preview" data-balance="{{ $customer->balance() }}" data-id="{{ $customer->id }}" data-name="{{ $customer->name }}" data-phone="{{ $customer->phone }}">
                                        <i class="fa fa-list"></i>
                                        <span>تفاصيل</span>
                                    </a>
                                    @permission('customers-update')
                                        <a class="btn btn-warning btn-xs showCustomerModal update" data-action="{{ route('customers.update', $customer->id) }}" data-id="{{ $customer->id }}" data-name="{{ $customer->name }}" data-phone="{{ $customer->phone }}" data-address="{{ $customer->address }}"><i class="fa fa-edit"></i> تعديل </a>
                                    @endpermission
                                </div>

                                @permission('customers-delete')
                                    <a class="btn btn-danger btn-xs showCustomerModal confirm-delete" data-balance="{{ $customer->balance() }}"
                                        data-id="{{ $customer->id }}" data-name="{{ $customer->name }}" 
                                        data-phone="{{ $customer->phone }}"
                                        data-action="{{ route('customers.destroy', $customer) }}"
                                    >
                                        <i class="fa fa-times"></i>
                                        <span>حذف</span>
                                    </a>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <input type="hidden" data-action="{{ route('customers.store') }}" id="create">
        </div>
    </div>
@endsection
