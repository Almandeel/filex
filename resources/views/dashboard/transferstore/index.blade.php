@extends('layouts.dashboard.app', ['datatable' => true])

@section('title')
    التحويلات
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['التحويلات'])
        @slot('url', ['#'])
        @slot('icon', ['list'])
    @endcomponent
    <div class="box box-primary">
        <div class="box-header">
            قائمة التحويلات
            <a class="btn btn-primary btn-sm pull-right" href="{{ route('transferstores.create') }}"><i class="fa fa-reply"></i> تحويل</a>
        </div>
        <div class="box-body">
            <table class="table text-center datatable table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>من مخزن</th>
                        <th>الى مخزن</th>
                        <th>عدد الاصناف</th>
                        <th>التاريخ</th>
                        <th>خيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transfers as $index=>$transfer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $transfer->fromStore->name }}</td>
                            <td>{{ $transfer->toStore->name }}</td>
                            <td>{{ count($transfer->items) }}</td>
                            <td>{{ $transfer->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a class="btn btn-default btn-xs" href="{{ route('transferstores.show', $transfer->id) }}"><i class="fa fa-eye"></i> عرض</a>
                                {{-- <a class="btn btn-warning btn-xs" href="{{ route('transferstores.edit', $transfer->id) }}"><i class="fa fa-edit"></i> تعديل</a> --}}
{{--                                <form action="{{ route('transferstores.destroy', $transfer->id) }}" method="post" style="display: inline-block;">--}}
{{--                                    @method('DELETE')--}}
{{--                                    @csrf--}}
{{--                                    <button class="btn btn-danger btn-xs confirm">--}}
{{--                                        <i class="fa fa-times"></i>--}}
{{--                                        <span>حذف</span>--}}
{{--                                    </button>--}}
{{--                                </form>--}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
