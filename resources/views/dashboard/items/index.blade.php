@extends('layouts.dashboard.app', ['modals' => ['item'], 'datatable' => true])

@section('title')
    المنتجات
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['المنتجات'])
        @slot('url', ['#'])
        @slot('icon', ['users'])
    @endcomponent
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">المنتجات</h3>
            <div class="box-tools">
                @permission('items-create')
                {{--  <button type="button" style="display:inline-block; margin-left:1%" class="btn btn-primary btn-sm pull-right showItemModal create" data-toggle="modal" data-target="#items">
                    <i class="fa fa-plus"></i>
                    <span>إضافة</span>
                </button>  --}}
                    <a href="{{ route('items.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        <span>إضافة</span>
                    </a>
                @endpermission
            </div>
        </div>
        <div class="box-body">
            <table id="items-table" class="datatable table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th><i class="fa fa-image"></i></th>
                        <th>المنتج</th>
                        <th>المخازن</th>
                        <th>الوحدات</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td class="text-center">
                                <span class="image-preview image-preview-inline" style="background-image: url({{ $item->image_url }});"></span>
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @foreach ($item->stores as $store)
                                    <span class="badge bg-blue">{{ $store->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($item->units as $unit)
                                    <span class="badge bg-green">{{ $unit->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @permission('items-read')
                                    <a href="{{ route('items.show', $item) }}" class="btn btn-info">
                                        <i class="fa fa-eye"></i>
                                        <span>عرض</span>
                                    </a>
                                @endpermission
                                @permission('items-update')
                                    <a href="{{ route('items.edit', $item) }}" class="btn btn-warning">
                                        <i class="fa fa-edit"></i>
                                        <span>تعديل</span>
                                    </a>
                                @endpermission
                                {{--  @permission('items-update')
                                    <a class="btn btn-warning showItemModal  update" data-action="{{ route('items.update', $item->id) }}" data-units="{{ $item->unitsId() }}"
                                    data-stores="{{ $item->stores->pluck('id') }}"
                                    data-name="{{ $item->name }}"><i class="fa fa-edit"></i> تعديل </a>
                                @endpermission  --}}

                                @permission('items-delete')
                                    <form style="display:inline-block" action="{{ route('items.destroy', $item->id) }}" method="post">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger delete"><i class="fa fa-trash"></i> حذف </button>
                                    </form>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <input type="hidden" data-action="{{ route('items.store') }}" id="create">
        </div>
    </div>
@endsection
