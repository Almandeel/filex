@extends('layouts.dashboard.app', ['modals' => ['expense'], 'datatable' => true])

@section('title', 'منصرفات الرحلات')

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
        @slot('title', ['منصرفات الرحلات'])
        @slot('url', ['#'])
        @slot('icon', ['list'])
    @endcomponent
		
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">
					<i class="fa fa-list-alt"></i>
					<span>منصرفات الرحلات</span>
				</h4>
				<div class="box-tools">
                    @permission('expenses-create')
                        <button type="button" class="btn btn-primary btn-sm showexpenseModal " data-toggle="modal">
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
                            <th>رقم الرحلة</th>
                            <th>المنصرف</th>
                            <th>نوع المنصرف</th>
                            <th>الخيارات</th>
                        </tr>
					</thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $expense->trip_id }}</td>
                                <td>{{ $expense->amount }}</td>
                                <td>{{ $expense->expense_type }}</td>
                                <td>
                                    @permission('expenses-update')
                                        <a href="#" class="btn btn-warning btn-sm showexpenseModal update" data-toggle="modal"
                                            data-trip_id="{{ $expense->trip_id }}"
                                            data-amount="{{ $expense->amount }}"
                                            data-expense_type="{{ $expense->expense_type }}"
                                            data-notes="{{ $expense->notes }}"
                                            data-action="{{ route('expensestrips.update', $expense->id) }}"
                                        ><i class="fa fa-edit"></i> تعديل</a>
                                    @endpermission

                                    {{-- @permission('expenses-delete')
                                        <form style="display:inline-block" action="{{ route('expenses.destroy', $expense->id) }}" method="post">
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
				{{ $expenses->links() }}
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