@extends('layouts.dashboard.app', ['modals' => ['store'], 'datatable' => true])

@section('title')
المخازن
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['المخازن'])
        @slot('url', ['#'])
        @slot('icon', ['th-large'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            @permission('stores-create')
                <button type="button" style="display:inline-block; margin-left:1%" class="btn btn-primary btn-sm pull-right showStoreModal create"  data-toggle="modal" data-target="#stores">
                    <i class="fa fa-plus"> إضافة</i>
                </button>
            @endpermission
        </div>
        <div class="box-body">
            <table id="stores-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>اسم المخزن</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stores as $store)
                        <tr>
                            <td>{{ $store->name }}</td>
                            <td>
                                <a class="btn btn-default btn-xs" href="{{ route('stores.show', $store->id) }}"><i class="fa fa-eye"></i> عرض </a>
                                @permission('stores-update')
                                    <a class="btn btn-warning btn-xs showStoreModal update" data-action="{{ route('stores.update', $store->id) }}" data-name="{{ $store->name }}"><i class="fa fa-edit"></i> تعديل </a>
                                @endpermission

                                @permission('stores-delete')
                                    <form style="display:inline-block" action="{{ route('stores.destroy', $store->id) }}" method="post">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> حذف </button>
                                    </form>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <input type="hidden" data-action="{{ route('stores.store') }}" id="create">
        </div>
    </div>
@endsection
