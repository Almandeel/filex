@extends('layouts.dashboard.app', ['modals' => ['supplier', 'employee'], 'datatable' => true])

@section('title', 'المشتريات')

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
        @slot('title', ['المشتريات'])
        @slot('url', ['#'])
        @slot('icon', ['list'])
    @endcomponent
		
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">
					<i class="fa fa-list-alt"></i>
					<span>الفواتير</span>
				</h4>
				<div class="box-tools">
				@permission('bills-create')
					<a href="{{ route('bills.create') }}" class="btn btn-primary">
						<i class="fa fa-plus"></i>
						<span>انشاء فاتورة</span>
					</a>
					@endpermission
					<a href="#search" data-toggle="collapse">
						<i class="fa fa-cogs"></i>
						<span>بحث متقدم</span>
					</a>
				</div>
				<div id="search" class="collapse well {{ $advanced_search ? 'in' : '' }}">
					<form action="" method="GET" class="form-inline d-inline-block">
						<label for="store_id">المخازن</label>
						<select name="store_id" id="store_id" class="form-control select2">
							<option value="all" {{ ($store_id === 'all') ? 'selected' : '' }}>الكل</option>
							{{-- <option value="out" {{ ($store_id === 'out') ? 'selected' : '' }}>البيع الخارجي</option> --}}
							@foreach ($stores as $store)
							<option value="{{ $store->id }}" {{ ($store_id === $store->id) ? 'selected' : '' }}>{{ $store->name }}
							</option>
							@endforeach
						</select>
						<label for="supplier_id">الموردين</label>
						<select name="supplier_id" id="supplier_id" class="form-control select2">
							<option value="all" {{ ($supplier_id === 'all') ? 'selected' : '' }}>الكل</option>
							@foreach ($suppliers as $supplier)
							<option value="{{ $supplier->id }}" {{ ($supplier_id === $supplier->id) ? 'selected' : '' }}>{{ $supplier->name }}
							</option>
							@endforeach
						</select>
						{{-- <label for="is_delivered">الاستلام</label>
						<select name="is_delivered" id="is_delivered" class="form-control select2">
							<option value="both" {{ ($is_delivered === 'both') ? 'selected' : '' }}>الكل</option>
							<option value="1" {{ ($is_delivered === '1') ? 'selected' : '' }}>تم</option>
							<option value="0" {{ ($is_delivered === '0') ? 'selected' : '' }}>ليس بعد</option>
						</select>
						<label for="is_payed">الدفع</label>
						<select name="is_payed" id="is_payed" class="form-control select2">
							<option value="both" {{ ($is_payed === 'both') ? 'selected' : '' }}>الكل</option>
							<option value="1" {{ ($is_payed === '1') ? 'selected' : '' }}>تم</option>
							<option value="0" {{ ($is_payed === '0') ? 'selected' : '' }}>ليس بعد</option>
						</select> --}}
						<label for="from-date">من</label>
						<input type="date" name="from_date" id="from-date" value="{{ $from_date }}" class="form-control">
						<label for="to-date">الى</label>
						<input type="date" name="to_date" id="to-date" value="{{ $to_date }}" class="form-control">
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
							<th rowspan="2">الرقم</th>
							<th rowspan="2">المخزن</th>
							<th rowspan="2">المورد</th>
							<th rowspan="2">المنتجات</th>
							<th colspan="3">المبلغ</th>
							<th rowspan="2">الموظف</th>
							<th rowspan="2">تاريخ الانشاء</th>
							<th rowspan="2">الخيارات</th>
						</tr>
						<tr>
							<th>الاجمالي</th>
							<th>المدفوع</th>
							<th>المتبقي</th>
						</tr>
					</thead>
					<tbody>
						@php $totalItems = 0 @endphp
						@foreach ($bills as $bill)
							@php $totalItems += $bill->items->count() @endphp
							<tr>
								<td>{{ $bill->number}}</td>
								<td>
									{{ $bill->store->name}}
								</td>
								<td>
									@can('suppliers-read')
									<a href="#" class="showCustomerModal preview" data-balance="{{ $bill->supplier->balance() }}"
										data-id="{{ $bill->supplier->id }}" data-name="{{ $bill->supplier->name }}"
										data-phone="{{ $bill->supplier->phone }}">{{ $bill->supplier->name }}</a>
									@else
									{{ $bill->supplier->name }}
									@endif
								</td>
								<td>{{ count($bill->items) }}</td>
								<td class="bill-total">{{ number_format($bill->amount, 2) }}</td>
								<td class="bill-totalPayed">{{ number_format($bill->payed, 2) }}</td>
								<td class="bill-totalRemain">{{ number_format($bill->remain, 2) }}</td>
								<td>
									@can('employees-read')
									<a href="#" class="showEmployeeModal preview" data-id="{{$bill->user->id}}"
										data-name="{{$bill->user->employee->name}}" data-salary="{{$bill->user->employee->salary}}"
										data-phone="{{$bill->user->employee->phone}}"
										data-address="{{$bill->user->employee->address}}">{{ $bill->user->employee->name }}</a>
									@else
									{{ $bill->user->employee->name }}
									@endif
								</td>
								<td>{{ $bill->created_at->format("Y-m-d") }}</td>
								<td>
									<div class="dropdown">
										<button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
											<span class="fa fa-caret-down"></span>
											{{-- <span class="caret"></span> --}}
										</button>
										<ul class="dropdown-menu">
											@permission('bills-read')
											<li><a href="{{ route('bills.show', $bill) }}">
												<i class="fa fa-print  text-primary"></i>
												<span>طباعة</span>
											</a></li>
											@endpermission
											@permission('bills-read')
											<li><a href="{{ route('bills.show', $bill) }}">
												<i class="fa fa-eye  text-info"></i>
												<span>عرض</span>
											</a></li>
											@endpermission
											@permission('bills-update')
											<li><a href="{{ route('bills.edit', $bill) }}">
												<i class="fa fa-pencil  text-warning"></i>
												<span>تعديل</span>
											</a></li>
											@endpermission
											@permission('bills-delete')
											<li>
												<form action="{{ route('bills.destroy', $bill) }}" method="post">
												<a href="#" class="delete">
												<i class="fa fa-trash text-danger"></i>
												<span>حذف</span>
													</a>
													@csrf
													@method('DELETE')
												</form>
											</li>
											@endpermission
										</ul>
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th colspan="3">الاجمالي</th>
							<th>{{ $totalItems }}</th>
							<th>{{ number_format($bills->sum('amount'), 2) }}</th>
							<th>{{ number_format($bills->sum('payed'), 2) }}</th>
							<th>{{ number_format($bills->sum('remain'), 2) }}</th>
							<th colspan="3"></th>
						</tr>
					</tfoot>
				</table>
				{{ $bills->appends(request()->all())->links() }}
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