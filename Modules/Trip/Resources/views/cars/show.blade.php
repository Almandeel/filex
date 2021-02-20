@extends('layouts.dashboard.app', ['datatable' => true])

@section('title', 'المركبات')

@push('css')
    <link rel="stylesheet" href="{{ asset('dashboard/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
	<style>
		.well{
			margin: 15px 0px;
			font-size: 13px;
			padding: 8px;
		}
		.form-inline .form-control {
			padding: 0px 8px;
		}	
	</style>
@endpush
	
@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['المركبات'])
        @slot('url', ['#'])
        @slot('icon', ['list'])
    @endcomponent
		
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">
					<i class="fa fa-list-alt"></i>
					<span>بيانات المركبة</span>
				</h4>
			</div>
			<div class="box-body">
				<table id="bills-table" class="table table-bordered table-hover text-center">
					<thead>
						<tr>
                            <th>رقم الشاصي</th>
                            <th>رقم اللوحة</th>
                            <th>وزن المركبة فارغ</th>
                            <th>وزن المركبة الكلى</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $car->vin_number }}</td>
                            <td>{{ $car->car_number }}</td>
                            <td>{{ $car->empty_weight }}</td>
                            <td>{{ $car->max_weight }}</td>
                        </tr>
                    </tbody>
                </table>
			</div>
        </div>

        <div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">
					<i class="fa fa-list-alt"></i>
					<span>الرحلات ({{ $trips->count() }})</span>
				</h4>
			</div>
			<div class="box-body">
				<table id="bills-table" class="table table-bordered table-hover text-center">
					<thead>
						<tr>
                            <th>#</th>
                            <th>من مدينة</th>
                            <th>الى مدينة</th>
                            <th>رقم اللوحة</th>
                            <th>السائق</th>
                            <th>التكلفة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trips as $trip)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $trip->fromState->name }}</td>
                                <td>{{ $trip->toState->name }}</td>
                                <td>{{ $trip->car->car_number }}</td>
                                <td>{{ $trip->driver->name ?? null }}</td>
                                <td>{{ $trip->amount }}</td>
                                <td>{{ $trip->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @permission('trips-read')
                                        <a href="{{ route('trips.show', $trip->id) }}" class="btn btn-info btn-xs" > <i class="fa fa-eye"></i>  عرض</a>
                                    @endpermission
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
			</div>
        </div>
        


                
@endsection

@push('js')
    <script>
		$(function () {
			$('table#bills-table').dataTable({
				ordering: false,
			})
		})
    </script>
@endpush