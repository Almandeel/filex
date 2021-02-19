@extends('layouts.dashboard.app')

@section('title')
    التحويلات | عرض
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['عرض', 'التحويلات'])
        @slot('url', [route('transferstores.index'), '#'])
        @slot('icon', ['th-large', 'eye'])
    @endcomponent
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <table class="table table-bordered table-hover text-center datatable">
                                    <thead>
                                        <tr>
                                            <th>من مخزن</th>
                                            <th>الى مخزن</th>
                                            <th>عدد الاصناف</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $transferStore->fromStore->name }}</td>
                                            <td>{{ $transferStore->toStore->name }}</td>
                                            <td>{{ $transferStore->items->count() }}</td>
                                            <td>{{ $transferStore->created_at->format('Y/m/d') }} {{ $transferStore->created_at->format('h:i')  }} {{ ['am' => 'ص', 'pm' => 'م'][$transferStore->created_at->format('a')] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-body">
                <div class="col-xs-12 col-md-12">
                    <table class="table text-center table-bordered">
                        <thead>
                            <tr>
                                <td>المنتج</td>
                                <td>الكمية</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transferStore->items as $item)
                                    <tr>
                                        <td>
                                            {{ $item->itemStoreUnit->itemUnit->item->name  }}   ({{ $item->itemStoreUnit->itemUnit->unit->name }})
                                        </td>
                                        <td>
                                            {{ $item->quantity }}
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection
