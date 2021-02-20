@extends('layouts.dashboard.app', ['modals' => ['car'], 'datatable' => true])

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
					<span>المركبات</span>
				</h4>
				<div class="box-tools">
                    @permission('cars-create')
                        <button type="button" class="btn btn-primary btn-sm showcarModal " data-toggle="modal">
                            <i class="fa fa-plus"> إضافة</i>
                        </button>
					@endpermission
				</div>
			</div>
			<div class="box-body">
				<table id="bills-table" class="table table-bordered table-hover text-center">
					<thead>
						<tr>
                            <th>رقم الشاصي</th>
                            <th>رقم اللوحة</th>
                            <th>الوزن الكلى</th>
                            <th>الخيارات</th>
                        </tr>
					</thead>
                    <tbody>
                        @foreach ($cars as $car)
                            <tr>
                                <td>{{ $car->vin_number }}</td>
                                <td>{{ $car->car_number }}</td>
                                <td>{{ $car->max_weight }}</td>
                                <td>
                                    @permission('cars-read')
                                        <a href="{{ route('cars.show', $car->id) }}" class="btn btn-info btn-xs" > <i class="fa fa-eye"></i>  عرض</a>
                                    @endpermission


                                    @permission('cars-update')
                                        <a href="#" class="btn btn-warning btn-xs showcarModal update" data-toggle="modal"
                                            data-vin_number="{{ $car->vin_number }}"
                                            data-car_number="{{ $car->car_number }}"
                                            data-empty_weight="{{ $car->empty_weight }}"
                                            data-max_weight="{{ $car->max_weight }}"
                                            data-action="{{ route('cars.update', $car->id) }}"
                                        ><i class="fa fa-edit"></i> تعديل</a>
                                    @endpermission

                                    @permission('cars-delete')
                                        <form style="display:inline-block" action="{{ route('cars.destroy', $car->id) }}" method="post">
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
				{{ $cars->links() }}
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