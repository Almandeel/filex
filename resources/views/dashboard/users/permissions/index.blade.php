@extends('layouts.dashboard.app', ['datatable' => true])

@section('title')
    المشرفين
@endsection

@section('content')
    @include('partials._errors')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">جدول المشرفين</h3>

            <a  href="{{ route('users.create') }}" style="display:inline-block; margin-left:1%" class="btn btn-primary btn-sm pull-right" >
                <i class="fa fa-user-plus"> إضافة مشرف</i>
            </a>

            <a  href="{{ route('users.create') }}" style="display:inline-block; margin-left:1%" class="btn btn-primary btn-sm pull-right" >
                <i class="fa fa-user-plus"> إضافة مجموعة</i>
            </a>

            <a  href="{{ route('users.create') }}" style="display:inline-block; margin-left:1%" class="btn btn-primary btn-sm pull-right" >
                <i class="fa fa-user-plus"> إضافة صلاحية</i>
            </a>

        </div>
        <div class="box-body">
            <table id="example2" class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>اسم المستخدم</th>
                        <th>البريد الاكتروني</th>
                        <th>خيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <a class="btn btn-info btn-xs" href="{{ route('users.edit', $user->id) }}"><i class="fa fa-edit"></i> تعديل </a>
                                <form class="inline" action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i> حذف </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
