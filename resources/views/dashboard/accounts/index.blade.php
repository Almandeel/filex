@extends('layouts.dashboard.app', ['modals' => ['account'], 'datatable' => true])

@section('title')
    الحسابات
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الحسابات'])
        @slot('url', ['#'])
        @slot('icon', ['users'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            <h4 class="box-tit">
                <i class="fa fa-list"></i>
            </h4>
            {{-- @permission('accounts-create')
                <div class="box-tools">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-success btn-sm showaccountModal create" data-toggle="modal"
                            data-target="#accounts">
                            <i class="fa fa-user-plus"> إضافة</i>
                        </button>
                        <a href="http://localhost:8000/export/accounts" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel-o"> تصدير</i>
                        </a>
                
                        <a href="http://localhost:8000/example/excel" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel-o"> نموذج</i>
                        </a>
                
                        <form style="display:inline-block !important;" action="http://localhost:8000/imports/accounts" method="post"
                            enctype="multipart/form-data" novalidate="" class="btn btn-success btn-sm">
                            @csrf
                            <label style="margin-bottom: 0px">
                                <i class="fa fa-file-excel-o"> استيراد</i>
                                <input type="file" required="" style="display:none" name="accounts_excel" class="form-submitter">
                            </label>
                        </form>
                    </div>
                </div>
            @endpermission --}}
        </div>
        <div class="box-body">
            <table id="accounts-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>اسم الحساب</th>
                        <th>القيمة</th>
                        <th>الرصيد</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                        <tr>
                            <td>@lang('accounts.' . $account->name)</td>
                            <td>{{ number_format($account->entries()->first()->amount ?? 0, 2) }}</td>
                            <td>{{ number_format($account->balance() ?? 0 , 2) }}</td>
                            <td>
                                <div class="btn-group">
                                    {{-- @permission('accounts-read')
                                        <a class="btn btn-primary btn-xs" href="{{ route('accounts.statement', $account) }}"><i class="fa fa-list"></i> كشف حساب </a>
                                        <a class="btn btn-info btn-xs" href="{{ route('accounts.show', $account->id) }}"><i class="fa fa-eye"></i> عرض </a>
                                    @endpermission --}}

                                    @if($account->balance() <= 0)
                                        @permission('accounts-update')
                                            <a class="btn btn-warning btn-xs account update" data-action="{{ route('accounts.update', $account->id) }}" data-amount="{{ $account->balance() }}" ><i class="fa fa-edit"></i> تعديل </a>
                                        @endpermission
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
