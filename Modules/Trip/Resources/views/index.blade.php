@extends('layouts.dashboard.app', ['modals' => ['trip'], 'datatable' => true])

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
					<span>الرحلات</span>
				</h4>
				<div class="box-tools">
                    @permission('trips-create')
                        <button type="button" class="btn btn-primary btn-sm showtripModal " data-toggle="modal">
                            <i class="fa fa-plus"> إضافة</i>
                        </button>
					@endpermission
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
                            <th>التكلفة</th>
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
                                <td>{{ $trip->amount }}</td>
                                <td>
                                    @permission('trips-update')
                                        <a href="#" class="btn btn-warning btn-sm showtripModal update" data-toggle="modal"
                                            data-from="{{ $trip->from }}"
                                            data-to="{{ $trip->to }}"
                                            data-amount="{{ $trip->amount }}"
                                            data-car_id="{{ $trip->car_id }}"
                                            data-action="{{ route('trips.update', $trip->id) }}"
                                        ><i class="fa fa-edit"></i> تعديل</a>



                                        @if($trip->status == 0)
                                            <form style="display:inline-block" action="{{ route('trips.update', $trip->id) }}?type=done" method="post">
                                                @csrf 
                                                @method('PUT')
                                                <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i> تمت </button>
                                            </form>
                                        @endif
                                    @endpermission

                                    {{-- @permission('trips-delete')
                                        <form style="display:inline-block" action="{{ route('trips.destroy', $trip->id) }}" method="post">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm delete" type="submit"><i class="fa fa-trash"></i> حذف</button>
                                        </form>
                                    @endpermission --}}
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