@extends('layouts.dashboard.app')

@section('title')
    التحويلات | اضافة
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['التحويلات', 'اضافة'])
        @slot('url', [route('transferstores.index'), '#'])
        @slot('icon', ['list', 'reply'])
    @endcomponent
    <form action="{{ route('transferstores.store') }}" method="post">
        @csrf 

        <div class="box box-primary">
            <div class="box-header">
                <h3>تحويل</h3>
            </div>
            <div class="box-body">
    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>من مخزن</label>
                                <select required onchange="getItems()" id="from" name="from_store" class="form-control">
                                    <option  value="">اختار المخزن</option>
                                    @foreach ($stores as $from_store)
                                        <option {{ request()->from_store == $from_store->id ? 'selected' : '' }} value="{{ $from_store->id }}">{{ $from_store->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>الى مخزن</label>
                                <select onchange="getItems()" id="to" name="to_store" class="form-control">
                                    <option value="">اختار المخزن</option>
                                    @foreach ($stores as $to_store)
                                        <option {{ request()->to_store == $to_store->id ? 'selected' : '' }} value="{{ $to_store->id }}">{{ $to_store->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
        </div>

        @isset($items_store)
            <div class="box">
                <div class="box-body">
                    <table class="table text-center table-bordered">
                        <thead>
                            <tr>
                                <td>المنتج</td>
                                <td>الوحدة</td>
                                <td>الكمية</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items_store as $items)
                                @foreach ($items->items as $item)
                                    <tr>
                                        <td>
                                            {{ $item->itemStore->item->name }}
                                            <input type="hidden" name="item_store_unit_id[]" value="{{ $item->id }}">
                                        </td>
                                        <td>{{ $item->itemUnit->unit->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>
                                            <input type="number" class="form-control" max="{{ $item->quantity }}" name="quantity[]" value="0">
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                    <button style="margin:2% 0" class="btn btn-primary btn-xs"><i class="fa fa-save"></i> اكمال اعملية</button>
                </div>
            </div>    
        @endisset
    </form>
@endsection

@push('js')
    <script>
        function getItems() {
            let from_store = $('#from').val()
            let to_store = $('#to').val()

            if(from_store != '' && to_store != '' && from_store != to_store) {
                window.location.href = "{{ url('management/transferstores/create') }}" + "?from_store=" + from_store + '&to_store=' + to_store
            }
        }
    </script>
@endpush
