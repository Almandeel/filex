@extends('layouts.dashboard.app')

@section('title')
    عرض
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الادوار','عرض'])
        @slot('url', [route('roles.index'), '#'])
        @slot('icon', ['users', 'eye'])
    @endcomponent
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3>{{ $role->name }}</h3>
                </div>
            </div>
        </div>
    </div>
<form action="{{ route('roles.destroy', $role->id) }}" method="POST">
        @csrf
        {{ method_field('DELETE') }}
        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header">
                        <table class="table table-borderd">
                            <tr>
                                <th>الاسم </th>
                                <td>{{ $role['name'] }}</td>
                            </tr>
                            <tr>
                                <th>الصلاحيات</th>
                                <td>
                                    @foreach ($role->permissions as $permission)
                                        <span style="background-color:#499c47; padding:6px 10px" class="badge">{{ __('permissions.' . $permission->name) }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-footer">
                        @if(request()->delete && $role->id != 1)
                            @permission('roles-delete')
                                <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> حذف </button>
                            @endpermission
                        @else
                            @if($role->id != 1)
                            @permission('roles-update')
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>  تعديل </a>
                            @endpermission
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection