@extends('layouts.dashboard.app', ['modals' => ['driver'], 'datatable' => true])

@section('title', 'السائقين')

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
        @slot('title', ['السائقين'])
        @slot('url', ['#'])
        @slot('icon', ['list'])
    @endcomponent
		
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">
					<i class="fa fa-list-alt"></i>
					<span>السائقين</span>
				</h4>
				<div class="box-tools">
                    @permission('drivers-create')
                        <button type="button" class="btn btn-primary btn-sm showdriverModal " data-toggle="modal">
                            <i class="fa fa-plus"> إضافة</i>
                        </button>
					@endpermission
				</div>
			</div>
			<div class="box-body">
				<table id="bills-table" class="table table-bordered table-hover text-center">
					<thead>
						<tr>
                            <th>الاسم</th>
                            <th>رقم الهاتف</th>
                            <th>العنوان</th>
                            <th>الخيارات</th>
                        </tr>
					</thead>
                    <tbody>
                        @foreach ($drivers as $driver)
                            <tr>
                                <td>{{ $driver->name }}</td>
                                <td>{{ $driver->phone }}</td>
                                <td>{{ $driver->address }}</td>
                                <td>
                                    @permission('drivers-update')
                                        <a href="#" class="btn btn-warning btn-sm showdriverModal update" data-toggle="modal"
                                            data-name="{{ $driver->name }}"
                                            data-phone="{{ $driver->phone }}"
                                            data-address="{{ $driver->address }}"
                                            data-uid="{{ $driver->uid }}"
                                            data-action="{{ route('drivers.update', $driver->id) }}"
                                        >
                                            <i class="fa fa-edit"></i> تعديل
                                        </a>
                                    @endpermission

                                    @permission('drivers-delete')
                                        <form style="display:inline-block" action="{{ route('drivers.destroy', $driver->id) }}" method="post">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm delete" type="submit"><i class="fa fa-trash"></i> حذف</button>
                                        </form>
                                    @endpermission
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
				</table>
				{{ $drivers->links() }}
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