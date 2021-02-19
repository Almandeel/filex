@extends('layouts.dashboard.app', ['modals' => ['unit'], 'datatable' => true])

@section('title')
    الوحدات
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الوحدات'])
        @slot('url', ['#'])
        @slot('icon', ['users'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            @permission('units-create')
                <button type="button" style="display:inline-block; margin-left:1%" class="btn btn-primary btn-sm pull-right create showUnitModal"  data-toggle="modal" data-target="#units">
                    <i class="fa fa-user-plus">إضافة</i>
                </button>
            @endpermission
        </div>
        <div class="box-body">
            <table id="units-table" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>اسم الوحدة</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                        <tr>
                            <td>{{ $unit->name }}</td>
                            <td>
                                {{-- @permission('units-read')
                                    <a class="btn btn-info btn-xs" href="{{ route('units.show', $unit->id) }}"><i class="fa fa-eye"></i> عرض </a>
                                @endpermission --}}

                                @permission('units-update')
                                    <a class="btn btn-warning btn-xs showUnitModal update" data-action="{{ route('units.update', $unit->id) }}" data-id="{{ $unit->id }}" data-name="{{ $unit->name }}"><i class="fa fa-edit"></i> تعديل </a>
                                @endpermission

                                @permission('units-delete')
                                    <a class="btn btn-danger btn-xs" href="{{ route('units.show', $unit->id) }}?delete=true"><i class="fa fa-trash"></i> حذف </a>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <input type="hidden" data-action="{{ route('units.store') }}" id="create">
        </div>
    </div>
@endsection
