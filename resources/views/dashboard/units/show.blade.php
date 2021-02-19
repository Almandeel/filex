@extends('layouts.dashboard.app')

@section('title')
    عرض
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الوحدات','عرض'])
        @slot('url', [route('units.index'), '#'])
        @slot('icon', ['users', 'eye'])
    @endcomponent
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3>{{ $unit->name }}</h3>
                </div>
            </div>
        </div>
    </div>
<form action="{{ route('units.destroy', $unit->id) }}" method="POST">
        @csrf
        {{ method_field('DELETE') }}
        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header">
                        <table class="table table-borderd">
                            <tr>
                                <th>اسم الوحدة</th>
                                <td>{{ $unit->name }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-footer">
                        @if(request()->delete)
                            @permission('units-delete')
                                <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> حذف </button>
                            @endpermission
                        @else
                            @permission('units-delete')
                                <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>  تعديل </a>
                            @endpermission
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection