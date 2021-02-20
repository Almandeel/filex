@extends('layouts.dashboard.app', ['datatable' => true])

@section('title', 'ارشيف الرحلات')

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
        @slot('title', ['ارشيف الرحلات'])
        @slot('url', ['#'])
        @slot('icon', ['list'])
    @endcomponent
		
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">
					<i class="fa fa-list-alt"></i>
					<span>ارشيف الرحلات</span>
				</h4>
				<div class="box-tools">
                    <a href="#search" data-toggle="collapse">
						<i class="fa fa-cogs"></i>
						<span>بحث متقدم</span>
					</a>
                </div>
                <div id="search" class="collapse well ">
					<form action="" method="GET" class="form-inline d-inline-block">
						<label for="state_id">المدينة</label>
						<select name="state_id" id="state_id" class="form-control select2">
							<option value="all" {{ (request()->state_id == 'all') ? 'selected' : '' }}>الكل</option>
							@foreach ($states as $state)
                                <option value="{{ $state->id }}" {{ (request()->state_id == $state->id) ? 'selected' : '' }}>{{ $state->name }}
                                </option>
							@endforeach
                        </select>
                        
                        <label for="car_id">المركبة</label>
						<select name="car_id" id="car_id" class="form-control select2">
							<option value="all" {{ (request()->car_id === 'all') ? 'selected' : '' }}>الكل</option>
							@foreach ($cars as $car)
                                <option value="{{ $car->id }}" {{ (request()->car_id == $car->id) ? 'selected' : '' }}>{{ $car->car_number }}
                                </option>
							@endforeach
                        </select>
                        
                        <label for="driver_id">السائق</label>
						<select name="driver_id" id="driver_id" class="form-control select2">
							<option value="all" {{ (request()->driver_id === 'all') ? 'selected' : '' }}>الكل</option>
							@foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ (request()->driver_id == $driver->id) ? 'selected' : '' }}>{{ $driver->name }}
                                </option>
							@endforeach
						</select>
						
						<label for="from-date">من</label>
						<input type="date" name="from_date" id="from-date" value="{{ request()->from_date }}" class="form-control">
                        
                        <label for="to-date">الى</label>
						<input type="date" name="to_date" id="to-date" value="{{ request()->to_date }}" class="form-control">
                        
                        <button type="submit" class="btn btn-primary">
							<i class="fa fa-search"></i>
							<span></span>
						</button>
					</form>
				</div>
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
                            <th>الخيارات</th>
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
									@permission('trips-update')
										<a href="{{ route('trips.show', $trip->id) }}" class="btn btn-info btn-xs" > <i class="fa fa-eye"></i>  عرض</a>
									@endpermission
                                    @permission('trips-delete')
                                        <form style="display:inline-block" action="{{ route('trips.destroy', $trip->id) }}" method="post">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs delete" type="submit"><i class="fa fa-trash"></i> حذف</button>
                                        </form>
                                    @endpermission
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
				</table>
				{{ $trips->links() }}
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