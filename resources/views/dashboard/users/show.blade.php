@extends('layouts.dashboard.app')

@section('title')
    @lang('global.edit')
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['المستخدمين','عرض'])
        @slot('url', [route('users.index'), '#'])
        @slot('icon', ['users', 'eye'])
    @endcomponent
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3>{{ $user->username }}</h3>
                </div>
            </div>
        </div>
    </div>
<form action="{{ route('users.destroy', $user->id) }}" method="POST">
        @csrf
        {{ method_field('DELETE') }}
        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header">
                        <table class="table table-borderd">
                            <tr>
                                <th>@lang('users.username')</th>
                                <td>{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <th>اسم الموظف</th>
                                <td>{{ $user['employee']['name'] }}</td>
                            </tr>
                            <tr>
                                <th>رقم الهاتف</th>
                                <td>{{ $user['employee']['phone'] }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-footer">
                        @if(request()->delete)
                            @permission('users-delete')
                                <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> حذف </button>
                            @endpermission
                        @else
                            @permission('users-delete')
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>  تعديل </a>
                            @endpermission
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection