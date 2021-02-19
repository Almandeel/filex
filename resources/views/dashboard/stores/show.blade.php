@extends('layouts.dashboard.app', ['datatable' => true])

@section('title')
المخازن
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['المخازن', $store->name])
        @slot('url', [route('stores.index'), '#'])
        @slot('icon', ['th-large', ''])
    @endcomponent
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="{{ (session('tab') == 'items' || session('tab') == 'default' || !session()->has('tab')) ? 'active' : '' }}"><a href="#tab-items" data-toggle="tab" aria-expanded="true">
                <i class="fa fa-cubes"></i>
                <span>المنتجات</span>
            </a></li>
            @permission('users-read')
                <li class="{{ (session('tab') == 'users') ? 'active' : '' }}"><a href="#tab-users" data-toggle="tab" aria-expanded="false">
                    <i class="fa fa-users"></i>
                    <span>المستخدمين</span>
                </a></li>
            @endpermission
            @permission('suppliers-read')
                <li class="{{ (session('tab') == 'suppliers') ? 'active' : '' }}"><a href="#tab-suppliers" data-toggle="tab" aria-expanded="false">
                    <i class="fa fa-users"></i>
                    <span>الموردين</span>
                </a></li>
            @endpermission
            @permission('customers-read')
                <li class="{{ (session('tab') == 'customers') ? 'active' : '' }}"><a href="#tab-customers" data-toggle="tab" aria-expanded="false">
                    <i class="fa fa-users"></i>
                    <span>العملاء</span>
                </a></li>
            @endpermission
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                    <span>
                        <span>إضافة</span>
                        (<span>منتج</span>
                        <span>/</span>
                        <span>مستخدم</span>
                        <span>/</span>
                        <span>مورد</span>
                        <span>/</span>
                        <span>عميل</span>)
                    </span> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @permission('items-create')
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-backdrop="static" data-keyboard="false"
                            data-toggle="modal" data-target="#addItemModal">
                            <i class="fa fa-plus"></i>
                            <span>منتج</span>
                        </a></li>
                    @endpermission
                    @permission('users-create')
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-backdrop="static" data-keyboard="false"
                            data-toggle="modal" data-target="#addUserModal">
                            <i class="fa fa-plus"></i>
                            <span>مستخدم</span>
                        </a></li>
                    @endpermission
                    @permission('suppliers-create')
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-backdrop="static" data-keyboard="false"
                            data-toggle="modal" data-target="#addSupplierModal">
                            <i class="fa fa-plus"></i>
                            <span>مورد</span>
                        </a></li>
                    @endpermission
                    @permission('customers-create')
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-backdrop="static" data-keyboard="false"
                            data-toggle="modal" data-target="#addCustomerModal">
                            <i class="fa fa-plus"></i>
                            <span>عميل</span>
                        </a></li>
                    @endpermission
                </ul>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane {{ (session('tab') == 'items' || session('tab') == 'default' || !session()->has('tab')) ? 'active' : '' }}" id="tab-items">
                <table id="itemsTable" class="table table-bordered table-hover">
                    <thead>
                        <th>المعرف</th>
                        <th>المنتج</th>
                        <th>الخيارات</th>
                    </thead>
                    <tfoot>
                        <th>المعرف</th>
                        <th>المنتج</th>
                        <th>الخيارات</th>
                    </tfoot>
                </table>
            </div>
            @permission('users-read')
            <div class="tab-pane {{ (session('tab') == 'users') ? 'active' : '' }}" id="tab-users">
                <table id="users-table" class=" table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>اسم</th>
                            <th>الخيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($store->users as $user)
                        <tr>
                            <td>{{ $user->employee->name }}</td>
                            <td>
                                @permission('stores-update')
                                    <form action="{{ route('stores.users.remove', $user) }}" method="POST" style="display: inline-block">
                                        @csrf
                                        <input type="hidden" name="store_id" value="{{ $store->id }}">
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <button class="btn btn-danger btn-sm delete">
                                            <i class="fa fa-trash"></i>
                                            <span>حذف</span>
                                        </button>
                                    </form>
                                @endpermission
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endpermission
            @permission('suppliers-read')
            <div class="tab-pane {{ (session('tab') == 'suppliers') ? 'active' : '' }}" id="tab-suppliers">
                <table id="suppliers-table" class=" table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>اسم</th>
                            <th>الرصيد</th>
                            <th>الخيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($store->suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ number_format($supplier->balance(), 2) }}</td>
                            <td>
                                @permission('suppliers-read')
                                <a class="btn btn-info btn-sm" href="{{ route('suppliers.show', $supplier->id) }}"><i class="fa fa-eye"></i> عرض </a>
                                @endpermission
                                @permission('stores-update')
                                    <form action="{{ route('stores.suppliers.remove', $supplier) }}" method="POST" style="display: inline-block">
                                        @csrf
                                        <input type="hidden" name="store_id" value="{{ $store->id }}">
                                        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                                        <button class="btn btn-danger btn-sm delete">
                                            <i class="fa fa-trash"></i>
                                            <span>حذف</span>
                                        </button>
                                    </form>
                                @endpermission
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endpermission
            @permission('customers-read')
            <div class="tab-pane {{ (session('tab') == 'customers') ? 'active' : '' }}" id="tab-customers">
                <table id="customers-table" class=" table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>اسم</th>
                            <th>الرصيد</th>
                            <th>الخيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($store->customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ number_format($customer->balance(), 2) }}</td>
                            <td>
                                @permission('customers-read')
                                <a class="btn btn-info btn-sm" href="{{ route('customers.show', $customer->id) }}"><i class="fa fa-eye"></i> عرض </a>
                                @endpermission
                                @permission('stores-update')
                                    <form action="{{ route('stores.customers.remove', $customer) }}" method="POST" style="display: inline-block">
                                        @csrf
                                        <input type="hidden" name="store_id" value="{{ $store->id }}">
                                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                        <button class="btn btn-danger btn-sm delete">
                                            <i class="fa fa-trash"></i>
                                            <span>حذف</span>
                                        </button>
                                    </form>
                                @endpermission
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endpermission
        </div>
    </div>
    
    @component(Components::Modal)
        @slot('id', 'addUserModal')
        @slot('title')
            <span>إضافة مستخدم للمخزن</span>
        @endslot
        @slot('body')
            <form action="{{ route('stores.users.add') }}" method="GET">
                @csrf
                <input type="hidden" name="store_id" value="{{ $store->id }}">
                <input type="hidden" name="user_id">
                <table class="dataTable table table-bordered table-hover">
                    <thead>
                        <th>المعرف</th>
                        <th>الاسم</th>
                    </thead>
                    <tbody>
                        @foreach ($users as $u)
                            @if(!$store->users->contains('id', $u->id) && $u->id != auth()->user()->id)
                                <tr class="btn-submit" data-input="user_id" data-value="{{ $u->id }}">
                                    <td>{{ $u->id }}</td>
                                    <td>{{ $u->employee->name }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th>المعرف</th>
                        <th>الاسم</th>
                    </tfoot>
                </table>
            </form>
        @endslot
    @endcomponent
    @component(Components::Modal)
        @slot('id', 'addItemModal')
        @slot('title')
            <span>إضافة منتج للمخزن</span>
        @endslot
        @slot('body')
            <form action="{{ route('stores.items.add') }}" method="GET">
                @csrf
                <input type="hidden" name="store_id" value="{{ $store->id }}">
                <input type="hidden" name="item_id">
                <table class="dataTable table table-striped table-hover">
                    <thead>
                        <th>المعرف</th>
                        <th>الاسم</th>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            @if (!$store->items->contains('id', $item->id)))
                                <tr class="btn-submit" data-input="item_id" data-value="{{ $item->id }}">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th>المعرف</th>
                        <th>الاسم</th>
                    </tfoot>
                </table>
            </form>
        @endslot
    @endcomponent
    @component(Components::Modal)
        @slot('id', 'addSupplierModal')
        @slot('title')
            <span>إضافة مورد للمخزن</span>
        @endslot
        @slot('body')
            <form action="{{ route('stores.suppliers.add') }}" method="GET">
                @csrf
                <input type="hidden" name="store_id" value="{{ $store->id }}">
                <input type="hidden" name="supplier_id">
                <table class="dataTable table table-bordered table-hover">
                    <thead>
                        <th>المعرف</th>
                        <th>الاسم</th>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                            @if(!$store->suppliers->contains('id', $supplier->id))
                                <tr class="btn-submit" data-input="supplier_id" data-value="{{ $supplier->id }}">
                                    <td>{{ $supplier->id }}</td>
                                    <td>{{ $supplier->name }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th>المعرف</th>
                        <th>الاسم</th>
                    </tfoot>
                </table>
            </form>
        @endslot
    @endcomponent
    @component(Components::Modal)
        @slot('id', 'addCustomerModal')
        @slot('title')
            <span>إضافة عميل للمخزن</span>
        @endslot
        @slot('body')
            <form action="{{ route('stores.customers.add') }}" method="GET">
                @csrf
                <input type="hidden" name="store_id" value="{{ $store->id }}">
                <input type="hidden" name="customer_id">
                <table class="dataTable table table-bordered table-hover">
                    <thead>
                        <th>المعرف</th>
                        <th>الاسم</th>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            @if(!$store->customers->contains('id', $customer->id))
                                <tr class="btn-submit" data-input="customer_id" data-value="{{ $customer->id }}">
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->name }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th>المعرف</th>
                        <th>الاسم</th>
                    </tfoot>
                </table>
            </form>
        @endslot
    @endcomponent
    <input type="hidden" id="active-tab" name="active_tab" value="{{ session()->has('tab') ? session('tab') : 'default' }}" />
@endsection
@push('js')
    <script>
        var data = new Array();
        @foreach ($store->items as $item)
            @php
                $itemStore = $store->item($item->pivot->id);
            @endphp
            var row = {
                "item": {
                    "id" : "{{ $item->id }}",
                    "name" : "{{ $item->name }}",
                },
                "units":[
                @foreach ($itemStore->items as $itemStoreUnit)
                   {
                        "id" : "{{ $itemStoreUnit->id }}",
                        "itemId" : "{{ $item->id }}",
                        "itemId" : "{{ $itemStoreUnit->itemId() }}",
                        "itemName" : "{{ $itemStoreUnit->itemName() }}",
                        "unitId" : "{{ $itemStoreUnit->unitId() }}",
                        "unitName" : "{{ $itemStoreUnit->unitName() }}",
                        "quantity" : "{{ $itemStoreUnit->quantity }}",
                        "sellPrice" : "{{ $itemStoreUnit->price_sell}}",
                        "purchasePrice" : "{{ $itemStoreUnit->price_purchase }}",
                    },
                @endforeach   
                ]      
            };
            data.push(row);
        @endforeach
        data.removeAll(undefined);
        function format ( d ) {
            // `d` is the original data object for the row
            var table =  `<table class="datatable table table-responsive table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2">المعرف</th>
                        <th rowspan="2">الوحدة</th>
                        <th rowspan="2">الكمية</th>
                        <th colspan="2">السعر</th>
                        <th rowspan="2">الخيارات</th>
                    </tr>
                    <tr>
                        <th>الشراء</th>
                        <th>البيع</th>
                    </tr>
                </thead>
                <tbody>
            `;
            for(var i = 0; i < d.units.length; i++){
                var delete_btn = ``;
                var update_btn = ``;
                @permission('stores-update')
                delete_btn = ``;
                update_btn = ``;
                @endpermission
                var unit = d.units[i];
                table += `
                    <tr>
                        <td>`+unit.id+`</td>
                        <td>`+unit.unitName+`</td>
                        <td>`+unit.quantity+`</td>
                        <td>`+unit.purchasePrice+`</td>
                        <td>
                            @permission('stores-update')
                            <form action="{{ route('stores.update', $store) }}" method="post" class="d-inline-block">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="item_store_unit_id" value="`+unit.id+`"/>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="price_sell" value="`+unit.sellPrice+`" class="form-control" min="0" />
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">تعديل</button>
                                    </span>
                                </div>
                            </form>
                            @endpermission
                        </td>
                        <td>
                            @permission('stores-delete')
                            <form action="{{ route('stores.destroy', $store) }}" method="post" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="item_store_unit_id" value="`+unit.id+`" />
                                <button type="submit" class="btn btn-danger btn-sm delete">
                                    <i class="fa fa-times"></i>
                                    <span>حذف</span>
                                </button>
                            </form>
                            @endpermission
                        </td>
                    </tr>
                `;
            }
            table += `
                    </tbody>
                </table>
            `;

            return table;
        }
        $(function(){
            $('form').on('submit', (e) => {
                $(e.target).append($('input#active-tab'))
            })
            $('.nav.nav-tabs > li > a').click(function(){
                if(!$(this).parent().hasClass('dropdown')){
                    let tab = $(this).attr('href') + "";
                    $('input#active-tab').val(tab.substring(5, tab.length))
                }
            })

            $('.dataTable').dataTable({buttons: []});
            $(document).on('click', '.modal-trigger', function(){
                $($(this).data('modal')).modal('show')
            });
            $(document).on('click', '.btn-submit', function(){
                var input = $(this).closest('form').find('input[name="'+$(this).data('input') + '"]')
                input.val($(this).data('value'))
                $(this).closest('form').submit();
            });
            $(document).on('click', '.btn-delete', function(){
                var input = $(this).siblings('input[name="'+$(this).data('input') + '"]')
                var value = $(this).parent().parent().parent().find('td:first').text();
                input.val(value)
                $(this).closest('form').submit();
            });
            var table = $('#itemsTable').DataTable( {
                "data": data,
                "columns": [
                    { "data": "item.id" },
                    { "data": "item.name" },
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": `<button type="button" class="btn btn-default btn-sm">
                            <i class="fa fa-eye"></i>
                            <span class="show-text">عرض البيانات</span>
                            <span class="hide-text hide">اخفاء البيانات</span>
                        </button>
                        @permission('stores-delete')
                        <form action="{{ route('stores.items.remove') }}" method="POST" class="d-inline-block">
                            @csrf
                            <input type="hidden" name="store_id" value="{{ $store->id }}">
                            <input type="hidden" name="item_id">
                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-input="item_id">
                                <i class="fa fa-times"></i>
                                <span>حذف</span>
                            </button>
                        </form>
                        @endpermission
                        `
                    },
                ],
                "order": [[1, 'asc']],
            } );
            
            // Add event listener for opening and closing details
            $('#itemsTable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
        
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                    $(this).find('button:first .fa').addClass('fa-eye');
                    $(this).find('button:first .fa').removeClass('fa-eye-slash');
                    $(this).find('button:first .show-text').removeClass('hide');
                    $(this).find('button:first .hide-text').addClass('hide');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                    $(this).find('button:first .fa').removeClass('fa-eye');
                    $(this).find('button:first .fa').addClass('fa-eye-slash');
                    $(this).find('button:first .show-text').addClass('hide');
                    $(this).find('button:first .hide-text').removeClass('hide');
                }
            } );
        })
    </script>
@endpush