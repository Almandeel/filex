@extends('layouts.dashboard.app', ['datatable' => true])

@section('title', 'الرحلات')

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
        @slot('title', ['الرحلات'])
        @slot('url', ['#'])
        @slot('icon', ['list'])
    @endcomponent
		
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">
					<i class="fa fa-list-alt"></i>
					<span>بيانات الرحلة</span>
				</h4>
			</div>
			<div class="box-body">
				<table id="bills-table" class="table table-bordered table-hover text-center">
					<thead>
						<tr>
                            <th>من مدينة</th>
                            <th>الى مدينة</th>
                            <th>رقم اللوحة</th>
                            <th>السائق</th>
                            <th>التكلفة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $trip->fromState->name }}</td>
                            <td>{{ $trip->toState->name }}</td>
                            <td>{{ $trip->car->car_number }}</td>
                            <td>{{ $trip->driver->name ?? null }}</td>
                            <td>{{ $trip->amount }}</td>
                            <td>{{ $trip->created_at->format('Y-m-d') }}</td>
                        </tr>
                    </tbody>
                </table>
			</div>
        </div>

        <div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">
					<i class="fa fa-list-alt"></i>
					<span>منصرفات الرحلة</span>
				</h4>
			</div>
			<div class="box-body">
				<table id="bills-table" class="table table-bordered table-hover text-center">
					<thead>
						<tr>
                            <th>رقم الرحلة</th>
                            <th>المنصرف</th>
                            <th>نوع المنصرف</th>
                            <th>ملاحظات</th>
                            <th>التاريح</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td>{{ $expense->trip_id }}</td>
                                <td>{{ $expense->amount }}</td>
                                <td>{{ $expense->expense_type }}</td>
                                <td>{{ $expense->notes }}</td>
                                <td>{{ $expense->created_at->format('Y-m-d') }}</td>

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