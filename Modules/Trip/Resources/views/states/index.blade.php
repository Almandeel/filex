@extends('layouts.dashboard.app', ['modals' => ['state'], 'datatable' => true])

@section('title', 'المدن')

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
        @slot('title', ['المدن'])
        @slot('url', ['#'])
        @slot('icon', ['list'])
    @endcomponent
		
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">
					<i class="fa fa-list-alt"></i>
					<span>المدن</span>
				</h4>
				<div class="box-tools">
                    @permission('states-create')
                        <button type="button" class="btn btn-primary btn-sm showstateModal " data-toggle="modal">
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
                            <th>الاسم</th>
                            <th>الخيارات</th>
                        </tr>
					</thead>
                    <tbody>
                        @foreach ($states as $state)
                            <tr>
                                <td>{{ $loop->index + 1}}</td>
                                <td>{{ $state->name }}</td>
                                <td>
                                    @permission('states-update')
                                        <a href="#" class="btn btn-warning btn-sm showstateModal update" data-toggle="modal"
                                            data-name="{{ $state->name }}"
                                            data-action="{{ route('states.update', $state->id) }}"
                                        ><i class="fa fa-edit"></i> تعديل</a>
                                    @endpermission

                                    @permission('states-delete')
                                        <form style="display:inline-block" action="{{ route('states.destroy', $state->id) }}" method="post">
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
				{{ $states->links() }}
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