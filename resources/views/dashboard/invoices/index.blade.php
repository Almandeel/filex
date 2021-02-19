@extends('layouts.dashboard.app', ['modals' => ['customer', 'supplier', 'employee'], 'datatable' => true])

@section('title', 'المبيعات')

@push('css')
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
        @slot('title', ['المبيعات'])
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
				@permission('invoices-create')
					<a href="{{ route('invoices.create') }}" class="btn btn-primary">
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
						@csrf
						<label for="store_id">المخازن</label>
						<select name="store_id" id="store_id" class="form-control select2">
							<option value="all" {{ ($store_id === 'all') ? 'selected' : '' }}>الكل</option>
							<option value="out" {{ ($store_id === 'out') ? 'selected' : '' }}>البيع الخارجي</option>
							@foreach ($stores as $store)
							<option value="{{ $store->id }}" {{ ($store_id === $store->id) ? 'selected' : '' }}>{{ $store->name }}
							</option>
							@endforeach
						</select>
						<label for="customer_id">العملاء</label>
						<select name="customer_id" id="customer_id" class="form-control select2">
							<option value="all" {{ ($customer_id === 'all') ? 'selected' : '' }}>الكل</option>
							@foreach ($customers as $customer)
							<option value="{{ $customer->id }}" {{ ($customer_id === $customer->id) ? 'selected' : '' }}>{{ $customer->name }}
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
				<table id="invoices-table" class="table table-bordered table-hover text-center">
					<thead>
						<tr>
							<th rowspan="2">الرقم</th>
							<th rowspan="2">المخزن / المورد</th>
							<th rowspan="2">العميل</th>
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
						@foreach ($invoices as $invoice)
							@php $totalItems += $invoice->items->count() @endphp
							<tr>
								<td>{{ $invoice->number }}</td>
								<td>
									@if ($invoice->bill)
										@can('suppliers-read')
										<a href="#" class="showSupplierModal preview" data-balance="{{ $invoice->bill->supplier->balance() }}"
											data-id="{{ $invoice->bill->supplier->id }}" data-name="{{ $invoice->bill->supplier->name }}"
											data-phone="{{ $invoice->bill->supplier->phone }}">{{ $invoice->bill->supplier->name }}</a>
										@else
										{{ $invoice->bill->supplier->name }}
										@endif
									@else
										{{ $invoice->store->name}}
									@endif
								</td>
								<td>
									@can('customers-read')
									<a href="#" class="showCustomerModal preview" data-balance="{{ $invoice->customer->balance() }}"
										data-id="{{ $invoice->customer->id }}" data-name="{{ $invoice->customer->name }}"
										data-phone="{{ $invoice->customer->phone }}">{{ $invoice->customer->name }}</a>
									@else
									{{ $invoice->customer->name }}
									@endif
								</td>
								<td>{{ count($invoice->items) }}</td>
								<td class="invoice-total">{{ number_format($invoice->amount, 2) }}</td>
								<td class="invoice-totalPayed">{{ number_format($invoice->payed, 2) }}</td>
								<td class="invoice-totalRemain">{{ number_format($invoice->remain, 2) }}</td>
								<td>
									@can('employees-read')
									<a href="#" class="showEmployeeModal preview" data-id="{{$invoice->user->id}}"
										data-name="{{$invoice->user->employee->name}}" data-salary="{{$invoice->user->employee->salary}}"
										data-phone="{{$invoice->user->employee->phone}}"
										data-address="{{$invoice->user->employee->address}}">{{ $invoice->user->employee->name }}</a>
									@else
									{{ $invoice->user->employee->name }}
									@endif
								</td>
								<td>{{ $invoice->created_at->format("Y-m-d") }}</td>
								<td>
									<div class="dropdown">
										<button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
											<span class="fa fa-caret-down"></span>
											{{-- <span class="caret"></span> --}}
										</button>
										<ul class="dropdown-menu">
											@permission('invoices-read')
											<li><a href="{{ route('invoices.show', $invoice) }}">
												<i class="fa fa-print  text-primary"></i>
												<span>طباعة</span>
											</a></li>
											@endpermission
											@permission('invoices-read')
											<li><a href="{{ route('invoices.show', $invoice) }}">
												<i class="fa fa-eye  text-info"></i>
												<span>عرض</span>
											</a></li>
											@endpermission
											@permission('invoices-update')
											<li><a href="{{ route('invoices.edit', $invoice) }}">
												<i class="fa fa-pencil  text-warning"></i>
												<span>تعديل</span>
											</a></li>
											@endpermission
											@permission('invoices-delete')
											<li>
												<form action="{{ route('invoices.destroy', $invoice) }}" method="post">
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
							<th>{{ number_format($invoices->sum('amount'), 2) }}</th>
							<th>{{ number_format($invoices->sum('payed'), 2) }}</th>
							<th>{{ number_format($invoices->sum('remain'), 2) }}</th>
							<th colspan="3"></th>
						</tr>
					</tfoot>
				</table>
				{{ $invoices->appends(request()->all())->links() }}
			</div>
		</div>
@endsection

@push('js')
    <script>
		$(function () {
			$('table#invoices-table').dataTable({
				ordering: false,
			})
		})
    </script>
@endpush

