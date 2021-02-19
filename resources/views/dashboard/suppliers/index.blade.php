@extends('layouts.dashboard.app', ['modals' => ['supplier'], 'datatable' => true])

@section('title')
    الموردين
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الموردين'])
        @slot('url', ['#'])
        @slot('icon', ['users'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            <h4 class="box-tit">
                <i class="fa fa-list"></i>
            </h4>
            @permission('suppliers-create')
                <div class="box-tools">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-success btn-sm showSupplierModal create" data-toggle="modal"
                            data-target="#suppliers">
                            <i class="fa fa-user-plus"> إضافة</i>
                        </button>
                        <a href="http://localhost:8000/export/suppliers" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel-o"> تصدير</i>
                        </a>
                
                        <a href="http://localhost:8000/example/excel" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel-o"> نموذج</i>
                        </a>
                
                        <form style="display:inline-block !important;" action="http://localhost:8000/imports/suppliers" method="post"
                            enctype="multipart/form-data" novalidate="" class="btn btn-success btn-sm">
                            @csrf
                            <label style="margin-bottom: 0px">
                                <i class="fa fa-file-excel-o"> استيراد</i>
                                <input type="file" required="" style="display:none" name="suppliers_excel" class="form-submitter">
                            </label>
                        </form>
                    </div>
                </div>
            @endpermission
        </div>
        <div class="box-body">
            <table id="suppliers-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>اسم المورد</th>
                        <th>رقم الهاتف</th>
                        <th>الرصيد</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ number_format($supplier->balance(), 2) }}</td>
                            <td>
                                <div class="btn-group">
                                    @permission('suppliers-read')
                                        <a class="btn btn-primary btn-xs" href="{{ route('suppliers.statement', $supplier) }}"><i class="fa fa-list"></i> كشف حساب </a>
                                        <a class="btn btn-info btn-xs" href="{{ route('suppliers.show', $supplier->id) }}"><i class="fa fa-eye"></i> عرض </a>
                                    @endpermission
    
                                    <a class="btn btn-default btn-xs showSupplierModal preview" data-balance="{{ $supplier->balance() }}" data-id="{{ $supplier->id }}" data-name="{{ $supplier->name }}" data-phone="{{ $supplier->phone }}">
                                        <i class="fa fa-list"></i>
                                        <span>تفاصيل</span>
                                    </a>
                                    @permission('suppliers-update')
                                        <a class="btn btn-warning btn-xs showSupplierModal update" data-action="{{ route('suppliers.update', $supplier->id) }}" data-id="{{ $supplier->id }}" data-name="{{ $supplier->name }}" data-phone="{{ $supplier->phone }}" data-address="{{ $supplier->address }}"><i class="fa fa-edit"></i> تعديل </a>
                                    @endpermission
                                </div>

                                @permission('suppliers-delete')
                                    <a class="btn btn-danger btn-xs showSupplierModal confirm-delete" data-balance="{{ $supplier->balance() }}"
                                        data-id="{{ $supplier->id }}" data-name="{{ $supplier->name }}" 
                                        data-phone="{{ $supplier->phone }}"
                                        data-action="{{ route('suppliers.destroy', $supplier) }}"
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

            <input type="hidden" data-action="{{ route('suppliers.store') }}" id="create">
        </div>
    </div>
@endsection
