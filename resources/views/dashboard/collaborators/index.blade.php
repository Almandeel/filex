@extends('layouts.dashboard.app', ['modals' => ['collaborator'], 'datatable' => true])

@section('title')
    المتعاونون
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['المتعاونون'])
        @slot('url', ['#'])
        @slot('icon', ['users'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            <h4 class="box-tit">
                <i class="fa fa-list"></i>
            </h4>
            @permission('collaborators-create')
                <div class="box-tools">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-success btn-sm showCollaboratorModal create" data-toggle="modal"
                            data-target="#collaborators">
                            <i class="fa fa-user-plus"> إضافة</i>
                        </button>
                        {{--  <a href="http://localhost:8000/export/collaborators" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel-o"> تصدير</i>
                        </a>
                
                        <a href="http://localhost:8000/example/excel" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel-o"> نموذج</i>
                        </a>
                
                        <form style="display:inline-block !important;" action="http://localhost:8000/imports/collaborators" method="post"
                            enctype="multipart/form-data" novalidate="" class="btn btn-success btn-sm">
                            @csrf
                            <label style="margin-bottom: 0px">
                                <i class="fa fa-file-excel-o"> استيراد</i>
                                <input type="file" required="" style="display:none" name="collaborators_excel" class="form-submitter">
                            </label>
                        </form>  --}}
                    </div>
                </div>
            @endpermission
        </div>
        <div class="box-body">
            <table id="collaborators-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>اسم المتعاون</th>
                        <th>رقم الهاتف</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($collaborators as $collaborator)
                        <tr>
                            <td>{{ $collaborator->name }}</td>
                            <td>{{ $collaborator->phone }}</td>
                            <td>
                                <div class="btn-group">
                                    @permission('collaborators-read')
                                        <a class="btn btn-primary btn-xs" href="{{ route('reports.statement', ['account_id' => $collaborator->id]) }}"><i class="fa fa-list"></i> كشف حساب </a>
                                        <a class="btn btn-info btn-xs" href="{{ route('collaborators.show', $collaborator->id) }}"><i class="fa fa-eye"></i> عرض </a>
                                    @endpermission
    
                                    <a class="btn btn-default btn-xs showCollaboratorModal preview" data-balance="{{ $collaborator->account->balance() }}" data-id="{{ $collaborator->id }}" data-name="{{ $collaborator->name }}" data-phone="{{ $collaborator->phone }}">
                                        <i class="fa fa-list"></i>
                                        <span>تفاصيل</span>
                                    </a>
                                    @permission('collaborators-update')
                                        <a class="btn btn-warning btn-xs showCollaboratorModal update" data-action="{{ route('collaborators.update', $collaborator->id) }}" data-id="{{ $collaborator->id }}" data-name="{{ $collaborator->name }}" data-phone="{{ $collaborator->phone }}" data-address="{{ $collaborator->address }}"><i class="fa fa-edit"></i> تعديل </a>
                                    @endpermission
    
                                    @permission('collaborators-delete')
                                        <a class="btn btn-danger btn-xs showCollaboratorModal confirm-delete" data-balance="{{ $collaborator->account->balance() }}"
                                            data-id="{{ $collaborator->id }}" data-name="{{ $collaborator->name }}" 
                                            data-phone="{{ $collaborator->phone }}"
                                            data-action="{{ route('collaborators.destroy', $collaborator) }}"
                                        >
                                            <i class="fa fa-times"></i>
                                            <span>حذف</span>
                                        </a>
                                    @endpermission
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <input type="hidden" data-action="{{ route('collaborators.store') }}" id="create">
        </div>
    </div>
@endsection
