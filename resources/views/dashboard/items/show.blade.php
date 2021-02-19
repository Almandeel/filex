@extends('layouts.dashboard.app', ['datatable' => true])

@section('title')
    منتج: {{ $item->name }}
@endsection

@push('css')
    <style>
        .barcode {
            position: relative;
            right: 39%;
        }
    </style>
@endpush

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['المنتجات', $item->name])
        @slot('url', [route('items.index'), '#'])
        @slot('icon', ['th-large', 'plush'])
    @endcomponent
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">
                            <span>منتج: {{ $item->name }}</span>
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <h3>الوحدات</h3>
                                <table class="table table-bordered table-hover text-center datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>الوحدة</th>
                                            <th>السعر</th>
                                            <th>الخيارات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->units as $unit)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $unit->name }}</td>
                                            <td>
                                                @permission('items-update')
                                                <form style="display:inline-block" action="{{ route('items.units.update', $item) }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                                                    <div class="input-group">
                                                        <input type="number" name="price" class="form-control" value="{{ $unit->pivot->price }}">
                                                        <span class="input-group-btn">
                                                            <button type="submit" class="btn btn-warning"><i class="fa fa-edit"></i><span>تعديل</span></button>
                                                        </span>
                                                    </div>
                                                </form>
                                                @endpermission
                                            </td>
                                            <td>
                                                @permission('units-read')
                                                <a href="{{ route('units.show', $unit) }}" class="btn btn-info">
                                                    <i class="fa fa-eye"></i>
                                                    <span>عرض</span>
                                                </a>
                                                @endpermission
        
                                                {{--  @permission('units-update')
                                                <a href="{{ route('units.edit', $unit) }}" class="btn btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                    <span>تعديل</span>
                                                </a>
                                                @endpermission  --}}
                                
                                                @permission('units-delete')
                                                <form style="display:inline-block" action="{{ route('items.units.detach', $item) }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                                                    <button type="submit" class="btn btn-danger delete"><i class="fa fa-trash"></i> حذف </button>
                                                </form>
                                                @endpermission
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <h3>المخازن</h3>
                                <table class="table table-bordered table-hover text-center datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>المخزن</th>
                                            <th>الخيارات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->stores as $store)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $store->name }}</td>
                                            <td>
                                                @permission('stores-read')
                                                <a href="{{ route('stores.show', $store) }}" class="btn btn-info">
                                                    <i class="fa fa-eye"></i>
                                                    <span>عرض</span>
                                                </a>
                                                @endpermission
        
                                                {{--  @permission('stores-update')
                                                <a href="{{ route('stores.edit', $store) }}" class="btn btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                    <span>تعديل</span>
                                                </a>
                                                @endpermission  --}}
                                
                                                @permission('stores-delete')
                                                <form style="display:inline-block" action="{{ route('items.stores.detach', $item) }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="store_id" value="{{ $store->id }}">
                                                    <button type="submit" class="btn btn-danger delete"><i class="fa fa-trash"></i> حذف </button>
                                                </form>
                                                @endpermission
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <form id="change-image-form" action="{{ route('items.update', $item) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" value="{{ $item->name }}">
                    <div class="box box-primary">
                        {{--  <div class="box-header">
                        </div>  --}}
                        <div class="box-body reset">
                            <div class="image-wrapper">
                                <div class="image-previewer" style="background-image: url({{ $item->image_url }});"></div>
                                <label class="btn btn-primary">
                                    <i class="fa fa-image"></i>
                                    <span>تغيير الصورة</span>
                                    <input type="file" id="image-input" name="image"/>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
@endsection
@push('js')
    <script>
        $(function(){
            $('#image-input').change(function(){
                $(this).closest('form').submit()
            })
            $('.check-all-stores').change(function(){
                $('.check-all-stores').prop('checked', $(this).prop('checked'))
                $(this).closest('table').find('input.store').prop('checked', $(this).prop('checked'))
            })
            $(document).on('click', 'tr.store-row', function(){
                let store = $(this).find('input.store')
                store.prop('checked', !store.prop('checked'))
            })
            $(document).on('click', '.btn-add-unit', function(){
                let units_table_body = $('#units-table tbody')
                let unit_name = $(this).closest('tfoot').find('input.new-unit-name')
                let unit_price = $(this).closest('tfoot').find('input.new-unit-price')
                if(!unit_name.val()){
                    alert('ادخل اسم الوحدة ')
                    unit_name.focus()
                }
                else if(!unit_price.val()){
                    alert('ادخل سعر الوحدة ')
                    unit_price.focus()
                }else{
                    let row = `
                        <tr>
                            <td>`+ (units_table_body.children().length + 1) +`</td>
                            <td>`+ unit_name.val() +`</td>
                            <td>
                                <div class="input-group">
                                    <input type="hidden" name="units_names[]" value="`+ unit_name.val() +`">
                                    <input type="number" class="form-control" placeholder="سعر الوحدة" name="units_prices[]" min="1" value="`+ unit_price.val() +`">
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-remove-unit">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    units_table_body.append(row)
                    unit_name.val('')
                    unit_price.val('')
                }
            })
            $(document).on('click', '.btn-remove-unit', function(){
                let units_table_body = $('#units-table tbody')
                let confirmed = confirm('سوف يتم حذف الوحدة من القائمة هل انت متأكد؟')
                if(confirmed){
                    $(this).closest('tr').remove()
                    for(let index = 0; index < units_table_body.children().length; index++){
                        let row = $(units_table_body.children()[index])
                        $(row.children()[0]).text((index + 1))
                    }
                }
            })

            $(document).on('click', '.btn-add-store', function(){
                let stores_table_body = $('#stores-table tbody')
                let store_name = $(this).closest('tfoot').find('input.new-store-name')
                if(!store_name.val()){
                    alert('ادخل اسم المخزن')
                    store_name.focus()
                }
                else{
                    let row = `
                        <tr>
                            <td>`+ (stores_table_body.children().length + 1) +`</td>
                            <td>` + store_name.val() + `</td>
                            <td class="text-center">
                                <input type="hidden" name="stores_names[]" value="` + store_name.val() + `">
                                <button type="button" class="btn btn-danger btn-remove-store">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    stores_table_body.append(row)
                    store_name.val('')
                }
            })
            $(document).on('click', '.btn-remove-store', function(){
                let stores_table_body = $('#stores-table tbody')
                let confirmed = confirm('سوف يتم حذف المخزن من القائمة هل انت متأكد؟')
                if(confirmed){
                    $(this).closest('tr').remove()
                    for(let index = 0; index < stores_table_body.children().length; index++){
                        let row = $(stores_table_body.children()[index])
                        $(row.children()[0]).text((index + 1))
                    }
                }
            })
        })
    </script>
@endpush
