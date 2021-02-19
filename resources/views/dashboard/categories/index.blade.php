@extends('layouts.dashboard.app')

@section('title')
    الاقسام
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('dashboard/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <section class="content-header">
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> لوحة التحكم</li>
        </ol>
    </section>
    <section class="content">
        <h1></h1>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">قائمة الاقسام</h3>  

                @permission('categories-create')
                    <a  href="{{ route('categories.create') }}" style="display:inline-block; margin-left:1%" class="btn btn-primary btn-sm pull-right" >
                        <i class="fa fa-plus"> إضافة </i>
                    </a>
                @endpermission

            </div>
            <div class="box-body">
                <table id="categories-table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>القسم الرئيسي</th>
                            <th>خيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->parent->name ?? '-' }}</td>
                                <td>
                                    @permission('categories-update')
                                        <a class="btn btn-warning btn-xs" href="{{ route('categories.edit', $category->id) }}"><i class="fa fa-edit"></i> تعديل </a>
                                    @endpermission

                                    @permission('categories-delete')
                                        <form style="display:inline-block" action="{{ route('categories.destroy', $category->id) }}" method="post">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> حذف </a>
                                        </form>
                                    @endpermission
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('dashboard/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script>
    $(function () {
        $('#categories-table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'oLanguage'    : {
                "sEmptyTable":  "@lang('table.sEmptyTable')",
                "sInfo":    "@lang('table.sInfo')",
                "sInfoEmpty":   "@lang('table.sInfoEmpty')",
                "sInfoFiltered":    "@lang('table.sInfoFiltered')",
                "sInfoPostFix": "@lang('table.sInfoPostFix')",
                "sInfoThousands":   "@lang('table.sInfoThousands')",
                "sLengthMenu":  "@lang('table.sLengthMenu')",
                "sLoadingRecords":  "@lang('table.sLoadingRecords')",
                "sProcessing":  "@lang('table.sProcessing')",
                "sSearch":  "@lang('table.sSearch')",
                "sZeroRecords": "@lang('table.sZeroRecords')",
                "oPaginate": {
                    "sFirst":   "@lang('table.sFirst')",
                    "sLast":    "@lang('table.sLast')",
                    "sNext":    "@lang('table.sNext')",
                    "sPrevious":    "@lang('table.sPrevious')"
                },
                "oAria": {
                    "sSortAscending":   "@lang('table.sSortAscending')",
                    "sSortDescending":  "@lang('table.sSortDescending')"
                }
            }
        })
    })
    </script>
@endpush