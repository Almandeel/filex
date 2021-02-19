@extends('layouts.dashboard.app')

@section('title')
    عرض {{ $category->name }}
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['management.items', 'global.categories', $category->name])
        @slot('url', [route('management.items'), route('categories.index'), route('categories.show', $category->id)])
        @slot('icon', ['tasks', 'th-large', 'square'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            <h3>@lang('global.category'): {{ $category->name }}</h3>
        </div>
    </div>
    <div class="box">
        <div class="box-header">
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 200px;">@lang('global.id')</th>
                        <td>{{ $category->id }}</td>
                    </tr>
                    <tr>
                        <th style="width: 200px;">@lang('global.name')</th>
                        <td>{{ $category->name }}</td>
                    </tr>
                    <tr>
                        <th style="width: 200px;">@lang('global.description')</th>
                        <td>{{ $category->description }}</td>
                    </tr>
                    @if(request('delete'))
                        @permission('categories-delete')
                        <tr class="alert alert-warning">
                            <td colspan="2">
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <span>@lang('global.delete_confirm')</span>
                                    <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> @lang('global.delete') </button>
                                </form>
                            </td>
                        </tr>
                        @endpermission
                    @else
                        @permission('categories-update')
                        <tr>
                            <th>@lang('global.options')</th>
                            <td>
                                <a class="btn btn-warning btn-xs" href="{{ route('categories.edit', $category->id) }}"><i class="fa fa-edit"></i> @lang('global.edit') </a>
                            </td>
                        </tr>
                        @endpermission
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

