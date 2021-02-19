@extends('layouts.dashboard.app', ['datatable' => true])

@section('title')
    الادوار
@endsection

@section('content')
    @include('partials._errors')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">قائمة الادوار</h3>

            <a  href="{{ route('roles.create') }}" style="display:inline-block; margin-left:1%" class="btn btn-primary btn-sm pull-right" >
                <i class="fa fa-plus"> إضافة</i>
            </a>

        </div>
        <div class="box-body">
            <table id="example2" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                                <td>
                                    <a class="btn btn-info btn-xs" href="{{ route('roles.show', $role->id) }}"><i class="fa fa-eye"></i> عرض  </a>
                                    @if($role->id != 1)
                                    <a class="btn btn-warning btn-xs" href="{{ route('roles.edit', $role->id) }}"><i class="fa fa-edit"></i> تعديل </a>
                                    <form class="inline" action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('roles.show', $role->id) }}?delete=true" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> حذف </button>
                                    </form>
                                    @endif
                                </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
