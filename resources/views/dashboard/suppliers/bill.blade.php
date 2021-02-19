@extends('layouts.dashboard.app', ['snippts' => true, 'datatable' => true, 'modals' => ['supplier', 'employee', 'payment', 'chequeInput']])

@section('title', $title . ' - ' . $bill->id)

@push('css')
    <link rel="stylesheet" href="{{ asset('dashboard/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
	<style>
		.table tr.accordion-toggle {
		cursor: pointer;
		}
		.table{
		background-color: #fff !important;
		}
		.hedding h1{
		color:#fff;
		font-size:25px;
		}
		.main-section{
		margin-top: 120px;
		}
		.hiddenRow {
		padding: 0 4px !important;
		background-color: #eeeeee;
		font-size: 13px;
		}
		.accordian-body span{
		color:#a2a2a2 !important;
		}
	</style>
@endpush

@section('content')
        @component('partials._breadcrumb')
            @slot('title', ['الموردين', $bill->supplier->name, 'الفواتير', $bill->id])
            @slot('url', [route('suppliers.index'), route('suppliers.show', $bill->supplier->id), '#','#'])
            @slot('icon', ['users', 'user', 'file-text-o', 'file-text-o'])
        @endcomponent
		@if ($bill->isPrimary())
			<div class="box box-primary">
				<div class="box-header">
					<div class="row">
						<div class="col-md-6">
							<h4 class="box-title">
								<i class="fa fa-list-alt"></i>
								<span>بيانات {{ $title }}</span>
							</h4>
						</div>
						<div class="col-md-6">
							<h4 class="pull-right">
								@permission('bills-read')
									<a href="{{ route('bill.print', $bill) }}" class="btn btn-default btn-xs">
										<i class="fa fa-print"></i>
										<span>طباعة</span>
									</a>
								@endpermission
							</h4>
						</div>
					</div>
					
					
				</div>
				<div class="box-body">
					<table id="bill-table" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th rowspan="2">المعرف</th>
								<th rowspan="2">المخزن</th>
								<th rowspan="2">المورد</th>
								<th rowspan="2">المنتجات</th>
								<th colspan="3">المبلغ</th>
								<th rowspan="2">الموظف</th>
								@if ($bill->bill)
									<th rowspan="2">النوع</th>
								@endif
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
							<td>{{ $bill->id}}</td>
							<td>{{ $bill->store->name}}</td>
							<td>
								@can('suppliers-read')
									<a href="#" class="showSupplierModal preview"
										data-balance="{{ $bill->supplier->balance() }}" 
										data-id="{{ $bill->supplier->id }}" 
										data-name="{{ $bill->supplier->name }}" 
										data-phone="{{ $bill->supplier->phone }}"
									>{{ $bill->supplier->name }}</a>
								@else
									{{ $bill->supplier->name }}
								@endif
							</td>
							<td>{{ number_format(count($bill->items)) }}</td>
							<td class="bill-total">{{ $bill->amount }}</td>
							<td class="bill-totalPayed">{{ $bill->payed }}</td>
							<td class="bill-totalRemain">{{ $bill->remain }}</td>
							<td>
								@can('employees-read')
									<a href="#" class="showEmployeeModal preview" 
										data-id="{{$bill->user->id}}"
										data-name="{{$bill->user->employee->name}}"
										data-salary="{{$bill->user->employee->salary}}"
										data-phone="{{$bill->user->employee->phone}}"
										data-address="{{$bill->user->employee->address}}"
									>{{ $bill->user->employee->name }}</a>
								@else
									{{ $bill->user->employee->name }}
								@endif
							</td>
							<td>{{ $bill->created_at->format("Y-m-d") }}</td>
							<td>
								@permission('bills-update')
									<a href="{{ route('bills.edit', $bill) }}" class="btn btn-warning btn-xs">
										<i class="fa fa-pencil"></i>
										<span>تعديل</span>
									</a>
								@endpermission
								@permission('bills-delete')
									<form action="{{ route('bills.destroy', $bill) }}" method="post" class="d-inline-block">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-danger btn-xs delete">
											<i class="fa fa-ban"></i>
											<span>إلغاء</span>
										</button>
									</form>
								@endpermission
							</td>
						</tbody>
					</table>
				</div>
			</div>

		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab-items" data-toggle="tab" aria-expanded="true">
					<span>المنتجات</span>
				</a></li>
				<li class=""><a href="#tab-bills" data-toggle="tab" aria-expanded="false">
					<span>فواتير الاستلام</span>
				</a></li>
				<li class=""><a href="#tab-charges" data-toggle="tab" aria-expanded="false">
					<span>المصاريف</span>
				</a></li>
				<li class=""><a href="#tab-payments" data-toggle="tab" aria-expanded="false">
					<span>الدفعات</span>
				</a></li>
				<li class=""><a href="#tab-cheques" data-toggle="tab" aria-expanded="false">
					<span>الشيكات</span>
				</a></li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
						<span>
							<span>إضافة</span>
							(<span>فاتورة</span>
							<span>/</span>
							<span>دفعة</span>
							<span>/</span>
							<span>شيك</span>)
						</span> <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						@permission('bills-create')
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#addBillModal">
							<i class="fa fa-plus"></i>
							<span>فاتورة</span>
						</a></li>
						@endpermission
						@can('payments-create')
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="showPaymentModal" data-url="{{ route('payments.store') }}" data-bill-id="{{ $bill->id }}" data-supplier-id="{{ $bill->supplier->id }}">
							<i class="fa fa-plus"></i>
							<span>دفعة</span>
						</a></li>
						@endcan
						@permission('cheques-create')
						<li role="presentation" class="divider"></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="showChequeInputModal" data-form="ChequeForm">
							<i class="fa fa-plus"></i>
							<span>شيك</span>
						</a></li>
						@endpermission
					</ul>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab-items">
					<table id="items-table" class="datatable table table-bordered table-hover">
						<thead>
							<tr>
								<th colspan="4">
									<i class="fa fa-list"></i>
									<span>فاتورة شراء رقم: {{ $bill->id }}</span>
								</th>
								<th colspan="4">
									<i class="fa fa-user"></i>
									<span>للمورد: {{ $bill->supplier->name }}</span>
								</th>
							</tr>
							<tr>
								<th rowspan="2">#</th>
								<th rowspan="2">المنتج</th>
								<th rowspan="2">الوحدة</th>
								<th colspan="3">الكمية</th>
								<th rowspan="2">سعر الشراء</th>
								<th rowspan="2">الاجمالي</th>
							</tr>
							<tr>
								<th>المجموع</th>
								<th>المستلم</th>
								<th>المتبقي</th>
							</tr>
						</thead>
						<tbody>
							@php
								$totalQuantity = 0; 
								$totalPrice = 0; 
								$totalAmount = 0; 

								$totalReceivedQuantity = 0; 
								$totalRemainQuantity = 0; 
							@endphp
							@foreach ($bill->items as $item)
								@php
									$amount = $item->amount();
									$receivedQuantity = $item->items->sum('quantity');
									$remainQuantity = $item->quantity - $receivedQuantity;
									
									$totalQuantity += $item->quantity;
									$totalPrice += $item->totalPrice();

									$totalReceivedQuantity += $receivedQuantity;
									$totalRemainQuantity += $remainQuantity;

									$totalAmount += $amount;
								@endphp
								<tr @if($item->quantity == $receivedQuantity) class="success" @endif>
									<td>{{ $loop->index + 1 }}</td>
									<td>{{ $item->itemName() }}</td>
									<td>{{ $item->unitName() }}</td>
									<td>{{ $item->quantity }}</td>
									<td>{{ $receivedQuantity }}</td>
									<td>{{ $remainQuantity }}</td>
									<td>{{ number_format($item->totalPrice()) }}</td>
									<td>{{ number_format($amount) }}</td>
								</tr>
							@endforeach
						</tbody>
						<tfoot>
							<th></th>
							<th></th>
							<th></th>
							<th class="totalQ">{{ $totalQuantity }}</th>
							<th class="totalR">{{ $totalReceivedQuantity }}</th>
							<th class="totalRemain">{{ $totalRemainQuantity }}</th>
							<th class="totalP">{{ $totalPrice }}</th>
							<th class="totalAmount">{{ $totalAmount }}</th>
						</tfoot>
					</table>
				</div>
				<div class="tab-pane" id="tab-bills">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>المعرف</th>
								<th>المنتجات</th>
								<th>الموظف</th>
								<th>تاريخ الانشاء</th>
								<th>الخيارات</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($bill->bills as $b)
							<tr colspan="5" data-toggle="collapse" data-target="#collapse-bill-{{ $b->id }}" class="accordion-toggle">
								<td>{{ $b->id}}</td>
								<td>{{ count($b->items) }}</td>
								<td>
									@can('employees-read')
									<a href="#" class="showEmployeeModal preview" data-id="{{$b->user->id}}"
										data-name="{{$b->user->employee->name}}" data-salary="{{$b->user->employee->salary}}"
										data-phone="{{$b->user->employee->phone}}"
										data-address="{{$b->user->employee->address}}">{{ $b->user->employee->name }}</a>
									@else
									{{ $b->user->employee->name }}
									@endif
								</td>
								<td>{{ $b->created_at->format("Y-m-d") }}</td>
								<td>
									<a href="{{ route('bills.show', $b) }}" class="btn btn-default btn-xs">
										<i class="fa fa-eye"></i>
										<span>عرض</span>
									</a>
									{{-- @permission('bills-update')
															<a href="{{ route('bills.edit', $b) }}" class="btn btn-warning btn-xs">
									<i class="fa fa-pencil"></i>
									<span>تعديل</span>
									</a>
									@endpermission --}}
									@permission('bills-delete')
									<form action="{{ route('bills.destroy', $b) }}" method="post" class="d-inline-block">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-danger btn-xs delete">
											<i class="fa fa-ban"></i>
											<span>إلغاء</span>
										</button>
										<input type="hidden" name="bill_id" value="{{ $bill->id }}">
									</form>
									@endpermission
								</td>
							</tr>
							<tr class="p">
								<td colspan="5" class="hiddenRow">
									<div class="accordian-body collapse p-3" id="collapse-bill-{{ $b->id }}">
										<table class="table table-bordered table-hover">
											<thead>
												<tr>
													<th>#</th>
													<th>المنتج</th>
													<th>الكمية</th>
													<th>المصاريف</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($b->items as $item)
												<tr>
													<td>{{ $loop->index + 1 }}</td>
													<td>{{ $item->itemName() }} <strong>({{ $item->unitName() }})</strong></td>
													<td>{{ $item->quantity }}</td>
													<td>{{ $item->expenses }}</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<!-- /.tab-pane -->
				<div class="tab-pane" id="tab-charges">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>المعرف</th>
								<th>القيمة</th>
								<th>الفاتورة</th>
								<th>التفاصيل</th>
								<th>تاريخ الانشاء</th>
								<th>الخيارات</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($bill->chargez as $charge)
								<tr>
									<td>{{ $charge->id }}</td>
									<td>{{ $charge->amount }}</td>
									<td>{{ $charge->bill_id }}</td>
									<td>{{ $charge->details }}</td>
									<td>{{ $charge->created_at->format('Y-m-d') }}</td>
									<td>
										@can('charges-delete')
										<form action="{{ route('charges.destroy', $charge) }}" method="post" class="d-inline-block">
											@csrf
											@method('DELETE')
											<input type="hidden" name="bill_id" value="{{ $bill->id }}">
											<button type="submit" class="btn btn-danger btn-xs delete">
												<i class="fa fa-times"></i>
												<span>حذف</span>
											</button>
										</form>
										@endcan
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<!-- /.tab-pane -->
				<div class="tab-pane" id="tab-payments">
					<table id="payments-table" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>رقم العملية</th>
								<th>المبلغ</th>
								<th>الحساب</th>
								<th>التاريخ</th>
								<th>الخيارات</th>
							</tr>
						</thead>
						<tbody>
							@php
								$totalAmount = 0;
							@endphp
							@foreach ($bill->payments() as $entry)
							<tr>
								<td>{{ $loop->index + 1 }}</td>
								<td>{{ $entry->id }}</td>
								<td>{{ $entry->amount }}</td>
								<td>{{ $entry->to->name }}</td>
								<td>{{ $entry->created_at->format('Y-m-d') }}</td>
								<td>
									@permission('payments-delete')
									<form action="{{ route('payments.destroy', $entry) }}" method="post" class="d-inline-block">
										@csrf
										@method('DELETE')
										<input type="hidden" name="bill_id" value="{{ $bill->id }}">
										<input type="hidden" name="entry_id" value="{{ $entry->id }}">
										<button type="submit" class="btn btn-danger btn-xs delete">
											<i class="fa fa-ban"></i>
											<span>إلغاء</span>
										</button>
									</form>
									@endpermission
								</td>
							</tr>
							@php
								$totalAmount += $entry->amount;
							@endphp
							@endforeach
						</tbody>
						<tfoot>
							<th></th>
							<th></th>
							<th class="totalAmount">{{ $totalAmount }}</th>
							<th></th>
							<th></th>
						</tfoot>
					</table>
				</div>
				<!-- /.tab-pane -->
				<div class="tab-pane" id="tab-cheques">
					<table id="cheques-table" class="table table-bordered table-hover text-center">
						<thead>
							<tr>
								<th>الرقم</th>
								<th>النوع</th>
								<th>الحالة</th>
								<th>البنك</th>
								<th>القيمة</th>
								<th>تاريخ الإستحقاق</th>
								<th>الخيارات</th>
							</tr>
						</thead>
						<tbody>
							@php
								$totalAmount = 0;
							@endphp
							@foreach ($bill->cheques as $cheque)
							<tr>
								<td>{{ $cheque->number }}</td>
								<td>{{ $cheque->getType() }}</td>
								<td>{{ $cheque->getStatus() }}</td>
								<td>{{ $cheque->bank_name }}</td>
								<td>{{ $cheque->amount }}</td>
								<td>{{ $cheque->due_date }}</td>
								<td>
									@if($cheque->status == App\Cheque::STATUS_WAITING)
									@permission('cheques-update')
									<form action="{{ route('cheques.update', $cheque) }}" method="post" class="d-inline-block">
										@csrf
										@method('PUT')
										<button type="submit" class="btn btn-success btn-xs">
											<i class="fa fa-check"></i>
											<span>تأكيد الدفع</span>
										</button>
										<input type="hidden" name="status" value="{{ App\Cheque::STATUS_DELEVERED }}">
										<input type="hidden" name="bill_id" value="{{ $bill->id }}">
									</form>
									@endpermission

									@permission('cheques-update')
									<a class="btn btn-warning btn-xs showChequeModal update"
										data-action="{{ route('cheques.update', $cheque->id) }}" data-id="{{ $cheque->id }}"
										data-bank="{{ $cheque->bank_name }}" data-number="{{ $cheque->number }}"
										data-type="{{ $cheque->type }}" data-benefit="{{ str_replace('App\\', '', $cheque->benefit) }}"
										data-benefit_id="{{ $cheque->benefit_id }}" data-amount="{{ $cheque->amount }}"
										data-due_date="{{ $cheque->due_date }}" data-details="{{ $cheque->details }}"><i
											class="fa fa-edit"></i> تعديل </a>
									@endpermission
					
									@permission('cheques-update')
									<form action="{{ route('cheques.update', $cheque) }}" method="post" class="d-inline-block">
										@csrf
										@method('PUT')
										<button type="submit" class="btn btn-danger btn-xs">
											<i class="fa fa-ban"></i>
											<span>إلغاء</span>
										</button>
										<input type="hidden" name="status" value="{{ App\Cheque::STATUS_CANCELED }}">
									</form>									
									@endpermission
									@endif
					
									@permission('cheques-delete')
										<form action="{{ route('cheques.destroy', $cheque) }}" method="post" class="d-inline-block">
											@csrf
											@method('DELETE')
											<input type="hidden" name="bill_id" value="{{ $bill->id }}">
											<button type="submit" class="btn btn-danger btn-xs delete">
												<i class="fa fa-times"></i>
												<span>حذف</span>
											</button>
										</form>
									@endpermission
								</td>
							</tr>
							@php
								$totalAmount += $cheque->amount;
							@endphp
							@endforeach
						</tbody>
						<tfoot>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th class="totalAmount">{{ $totalAmount }}</th>
							<th></th>
							<th></th>
						</tfoot>
					</table>
				</div>
				<!-- /.tab-pane -->
			</div>
			<!-- /.tab-content -->
		</div>
		<!-- nav-tabs-custom -->
		<!-- addBillModal -->
		<div class="modal fade" id="addBillModal" tabindex="-1" role="dialog" aria-labelledby="addBillModalLabel"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<form id="form" class="prevent-input-submition" action="{{ route('bills.store') }}" method="POST">
						@csrf
						<div class="modal-header">
							<h5 class="modal-title" id="addBillModalLabel">
								<i class="fa fa-plus"></i>
								<span>إضافة فاتورة استلام</span>
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<table id="modal-items-table" class="table table-bordered table-hover text-center">
								<thead>
									<tr>
										<th rowspan="2">#</th>
										<th rowspan="2">المنتج</th>
										<th colspan="3">الكمية</th>
									</tr>
									<tr>
										<th>الاجمالي</th>
										<th>المتبقي</th>
										<th>الكمية المستلمة</th>
									</tr>
								</thead>
								<tbody>
									@php
										$index = 1;
									@endphp
									@foreach ($bill->items as $item)
									@if($item->quantity !== $item->items->sum('quantity'))
									<tr>
										<td>{{ $index }}</td>
										<td>{{ $item->itemName() }} <strong>({{ $item->unitName() }})</strong></td>
										<td>{{ $item->quantity }}</td>
										<td>{{ $item->quantity - $item->items->sum('quantity') }}</td>
										<td>
											<input type="hidden" name="items[]" value="{{ $item->id }}" />
											<input type="number" class="form-control input input-quantity" name="quantities[]" value="0" min="0" max="{{ $item->quantity - $item->items->sum('quantity') }}" />
										</td>
									</tr>
									@php
										$index++;
									@endphp
									@endif
									@endforeach
								</tbody>
							</table>
							<div class="row">
								<div class="col-xs-12 col-md-6">
									<div class="input-group">
										<label class="input-group-addon">مصاريف نقدية</label>
										<input type="number" name="chargesSafe" id="chargesSafe" min="0" class="form-control" value="0" />
									</div>
								</div>
								<div class="col-xs-12 col-md-6">
									<div class="input-group">
										<label class="input-group-addon">مصاريف بنك</label>
										<input type="number" name="chargesBank" id="chargesBank" min="0" class="form-control" value="0" />
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="modal" value="true">
							<input type="hidden" name="bill_id" value="{{ $bill->id }}">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
							<button type="submit" class="btn btn-primary">
								<i class="fa fa-plus"></i>
								<span>إضافة الفاتورة</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		{{--  ChequeForm  --}}
		<form id="AddChequeForm" action="{{ route('cheques.store') }}" method="post">
			@csrf
			<input type="hidden" name="amount" class="paymethod" value="0" />
			<input type="hidden" name="number">
			<input type="hidden" name="bank_name">
			<input type="hidden" name="due_date">
			<input type="hidden" name='details'>

			<input type="hidden" name='type' value="{{ App\Cheque::TYPE_PAY }}" />
			<input type="hidden" name='benefit' value="{{ App\Cheque::BENEFIT_SUPPLIER }}" />
			<input type="hidden" name='benefit_id' value="{{ $bill->supplier_id }}" />  
			<input type="hidden" name='bill_id' value="{{ $bill->id }}" />  
		</form>
		@else
			<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title">
						<i class="fa fa-list-alt"></i>
						<span>بيانات {{ $title }}</span>
					</h4>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>المعرف</th>
								<th>المنتجات</th>
								<th>الموظف</th>
								<th>تاريخ الانشاء</th>
								<th>الخيارات</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>{{ $bill->id}}</td>
								<td>{{ count($bill->items) }}</td>
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
									<a href="{{ route('bills.show', $bill->bill) }}" class="btn btn-default btn-xs">
										<i class="fa fa-eye"></i>
										<span>عرض فاتورة الشراء</span>
									</a>
									{{-- @permission('bills-update')
									<a href="{{ route('bills.edit', $bill) }}" class="btn btn-warning btn-xs">
										<i class="fa fa-pencil"></i>
										<span>تعديل</span>
									</a>
									@endpermission --}}
									@permission('bills-delete')
									<form action="{{ route('bills.destroy', $bill) }}" method="post" class="d-inline-block">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-danger btn-xs delete">
											<i class="fa fa-ban"></i>
											<span>إلغاء</span>
										</button>
									</form>
									@endpermission
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="box box-primary">
				<div class="box-header">
					<h4 class="box-title">
						<i class="fa fa-cubes"></i>
						<span>قائمة المنتجات</span>
					</h4>
				</div>
				<div class="box-body">
					<table id="items-table" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>المنتج</th>
								<th>الكمية</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($bill->items as $item)
							<tr>
								<td>{{ $loop->index + 1 }}</td>
								<td>{{ $item->itemName() }} <strong>({{ $item->unitName() }})</strong></td>
								<td>{{ $item->quantity }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		@endif
@endsection

@push('js')
    <script>
		@if ($bill->isPrimary())
			$(function () {
				calculateTotals()
				$('.accordion-toggle').click(function(){
					$('.hiddenRow').hide();
					$(this).next('tr').find('.hiddenRow').show();
				});
			})
			function calculateTotals(){
				var payed = fillterNumber($('#payments-table .totalAmount').text());
				var total = fillterNumber($('#items-table .totalAmount').text());
				var remain = total - payed;
				$('.bill-total').text(total)
				$('.bill-totalPayed').text(payed)
				$('.bill-totalRemain').text(remain)
			}
		@endif
    </script>
@endpush

