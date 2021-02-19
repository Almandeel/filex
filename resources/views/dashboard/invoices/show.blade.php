@extends('layouts.dashboard.app', ['snippts' => true, 'datatable' => true, 'modals' => ['customer', 'employee', 'payment', 'chequeInput']])

@section('title', $title . ' - ' . $invoice->id)

@push('css')
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
        @slot('title', ['المبيعات', $title . ' - ' . $invoice->id])
        @slot('url', [route('invoices.index'), '#'])
        @slot('icon', ['list', 'list-alt'])
    @endcomponent
		@if ($invoice->isPrimary())
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
								@permission('invoices-read')
									<a href="{{ route('invoice.print', $invoice) }}" class="btn btn-default btn-xs">
										<i class="fa fa-print"></i>
										<span>طباعة</span>
									</a>
								@endpermission
							</h4>
						</div>
					</div>
					
					
				</div>
				<div class="box-body">
					<table id="invoice-table" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th rowspan="2">الرقم</th>
								<th rowspan="2">
									@if($invoice->bill_id)
										<span>المورد</span>
									@else
										<span>المخزن</span>
									@endif 
								</th>
								<th rowspan="2">العميل</th>
								<th rowspan="2">المنتجات</th>
								<th colspan="3">المبلغ</th>
								<th rowspan="2">الموظف</th>
								@if ($invoice->invoice)
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
									<a href="#" class="showCustomerModal preview"
										data-balance="{{ $invoice->customer->balance() }}" 
										data-id="{{ $invoice->customer->id }}" 
										data-name="{{ $invoice->customer->name }}" 
										data-phone="{{ $invoice->customer->phone }}"
									>{{ $invoice->customer->name }}</a>
								@else
									{{ $invoice->customer->name }}
								@endif
							</td>
							<td>{{ number_format(count($invoice->items)) }}</td>
							<td class="invoice-total">{{ $invoice->amount }}</td>
							<td class="invoice-totalPayed">{{ $invoice->payed }}</td>
							<td class="invoice-totalRemain">{{ $invoice->remain }}</td>
							<td>
								@can('employees-read')
									<a href="#" class="showEmployeeModal preview" 
										data-id="{{$invoice->user->id}}"
										data-name="{{$invoice->user->employee->name}}"
										data-salary="{{$invoice->user->employee->salary}}"
										data-phone="{{$invoice->user->employee->phone}}"
										data-address="{{$invoice->user->employee->address}}"
									>{{ $invoice->user->employee->name }}</a>
								@else
									{{ $invoice->user->employee->name }}
								@endif
							</td>
							<td>{{ $invoice->created_at->format("Y-m-d") }}</td>
							<td>
								@permission('invoices-update')
									<a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning btn-xs">
										<i class="fa fa-pencil"></i>
										<span>تعديل</span>
									</a>
								@endpermission
								@permission('invoices-delete')
									<form action="{{ route('invoices.destroy', $invoice) }}" method="post" class="d-inline-block">
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
				<li class=""><a href="#tab-invoices" data-toggle="tab" aria-expanded="false">
					<span>فواتير التسليم</span>
				</a></li>
				{{--  <li class=""><a href="#tab-charges" data-toggle="tab" aria-expanded="false">
					<span>المصاريف</span>
				</a></li>  --}}
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
						@permission('invoices-create')
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#addInvoiceModal">
							<i class="fa fa-plus"></i>
							<span>فاتورة</span>
						</a></li>
						@endpermission
						@can('payments-create')
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#" 
							class="showPaymentModal" data-url="{{ route('payments.store') }}" 
							data-invoice-id="{{ $invoice->id }}" 
							data-customer-id="{{ $invoice->customer->id }}"
							data-max="{{ $invoice->remain }}">
							<i class="fa fa-plus"></i>
							<span>دفعة</span>
						</a></li>
						@endcan
						@permission('cheques-create')
						<li role="presentation" class="divider"></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="#" 
							class="showChequeInputModal" data-form="ChequeForm" data-type="{{ App\Cheque::TYPE_COLLECT }}">
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
									<span>فاتورة بيع رقم: {{ $invoice->id }}</span>
								</th>
								<th colspan="4">
									<i class="fa fa-user"></i>
									<span>للعميل: {{ $invoice->customer->name }}</span>
								</th>
							</tr>
							<tr>
								<th rowspan="2">#</th>
								<th rowspan="2">المنتج</th>
								<th rowspan="2">الوحدة</th>
								<th colspan="3">الكمية</th>
								<th rowspan="2">سعر البيع</th>
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
							@foreach ($invoice->items as $item)
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
				<div class="tab-pane" id="tab-invoices">
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
							@foreach ($invoice->invoices as $b)
							<tr colspan="5" data-toggle="collapse" data-target="#collapse-invoice-{{ $b->id }}" class="accordion-toggle">
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
									<a href="{{ route('invoices.show', $b) }}" class="btn btn-default btn-xs">
										<i class="fa fa-eye"></i>
										<span>عرض</span>
									</a>
									{{-- @permission('invoices-update')
															<a href="{{ route('invoices.edit', $b) }}" class="btn btn-warning btn-xs">
									<i class="fa fa-pencil"></i>
									<span>تعديل</span>
									</a>
									@endpermission --}}
									@permission('invoices-delete')
									<form action="{{ route('invoices.destroy', $b) }}" method="post" class="d-inline-block">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-danger btn-xs delete">
											<i class="fa fa-ban"></i>
											<span>إلغاء</span>
										</button>
										<input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
									</form>
									@endpermission
								</td>
							</tr>
							<tr class="p">
								<td colspan="5" class="hiddenRow">
									<div class="accordian-body collapse p-3" id="collapse-invoice-{{ $b->id }}">
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
				{{--  <div class="tab-pane" id="tab-charges">
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
							@foreach ($invoice->chargez as $charge)
								<tr>
									<td>{{ $charge->id }}</td>
									<td>{{ $charge->amount }}</td>
									<td>{{ $charge->invoice_id }}</td>
									<td>{{ $charge->details }}</td>
									<td>{{ $charge->created_at->format('Y-m-d') }}</td>
									<td>
										@can('charges-delete')
										<form action="{{ route('charges.destroy', $charge) }}" method="post" class="d-inline-block">
											@csrf
											@method('DELETE')
											<input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
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
				<!-- /.tab-pane -->  --}}
				<div class="tab-pane" id="tab-payments">
					<table id="payments-table" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>رقم العملية</th>
								<th>المبلغ</th>
								<th>الخزنة</th>
								<th>التاريخ</th>
								<th>الخيارات</th>
							</tr>
						</thead>
						<tbody>
							@php
							$totalAmount = 0;
							@endphp
							@foreach ($invoice->payments as $payment)
							<tr>
								<td>{{ $loop->index + 1 }}</td>
								<td>{{ $payment->id }}</td>
								<td>{{ $payment->amount }}</td>
								<td>{{ $payment->safe->name }}</td>
								<td>{{ $payment->created_at->format('Y-m-d') }}</td>
								<td>
									@permission('payments-delete')
									<form action="{{ route('payments.destroy', $payment) }}" method="post" class="d-inline-block">
										@csrf
										@method('DELETE')
										<input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
										<button type="submit" class="btn btn-danger btn-xs delete">
											<i class="fa fa-ban"></i>
											<span>إلغاء</span>
										</button>
									</form>
									@endpermission
								</td>
							</tr>
							@php
							$totalAmount += $payment->amount;
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
				<!-- /.tab-pane -->
				<div class="tab-pane" id="tab-cheques">
					<table id="cheques-table" class="table table-bordered table-hover text-center">
						<thead>
							<tr>
								<th>الرقم</th>
								{{--  <th>النوع</th>  --}}
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
							@foreach ($invoice->cheques as $cheque)
							<tr>
								<td>{{ $cheque->number }}</td>
								{{--  <td>{{ $cheque->getType() }}</td>  --}}
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
										<input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
									</form>
									@endpermission

									@permission('cheques-update')
									<a href="#" class="btn btn-warning btn-xs showChequeInputModal"
										data-form="ChequeForm"
										data-label="تعديل شيك"
										data-action="{{ route('cheques.update', $cheque->id) }}" 
										data-method="PUT"
										data-number="{{ $cheque->number }}"
										data-bank="{{ $cheque->bank_name }}"
										data-account-id="{{ $cheque->account_id }}" 
										data-amount="{{ $cheque->amount }}"
										data-due-date="{{ $cheque->due_date }}" 
										><i
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
									@permission('cheques-delete')
										<form action="{{ route('cheques.destroy', $cheque) }}" method="post" class="d-inline-block">
											@csrf
											@method('DELETE')
											<input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
											<button type="submit" class="btn btn-danger btn-xs delete">
												<i class="fa fa-times"></i>
												<span>حذف</span>
											</button>
										</form>
									@endpermission
									@endif
					
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
							<th class="totalAmount">{{ $totalAmount }}</th>
							<th></th>
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
		<!-- addInvoiceModal -->
		<div class="modal fade" id="addInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="addInvoiceModalLabel"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<form id="form" class="prevent-input-submition" action="{{ route('invoices.store') }}" method="POST">
						@csrf
						<div class="modal-header">
							<h5 class="modal-title" id="addInvoiceModalLabel">
								<i class="fa fa-plus"></i>
								<span>إضافة فاتورة تسليم</span>
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
									@foreach ($invoice->items as $item)
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
							{{-- <div class="row">
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
							</div> --}}
						</div>
						<div class="modal-footer">
							<input type="hidden" name="modal" value="true">
							<input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
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
		<form id="ChequeForm" action="{{ route('cheques.store') }}" method="post">
			@csrf
			<input type="hidden" name="amount" class="paymethod" value="0" />
			<input type="hidden" name="number">
			<input type="hidden" name="bank_name">
			<input type="hidden" name="due_date">
			<input type="hidden" name='details'>
			<input type="hidden" name='account_id'>

			<input type="hidden" name='type' value="{{ App\Cheque::TYPE_COLLECT }}" />
			<input type="hidden" name='benefit' value="{{ App\Cheque::BENEFIT_CUSTOMER }}" />
			<input type="hidden" name='benefit_id' value="{{ $invoice->customer_id }}" />  
			<input type="hidden" name='invoice_id' value="{{ $invoice->id }}" />  
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
								<td>{{ $invoice->id}}</td>
								<td>{{ count($invoice->items) }}</td>
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
									<a href="{{ route('invoices.show', $invoice->invoice) }}" class="btn btn-default btn-xs">
										<i class="fa fa-eye"></i>
										<span>عرض فاتورة البيع</span>
									</a>
									{{-- @permission('invoices-update')
									<a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning btn-xs">
										<i class="fa fa-pencil"></i>
										<span>تعديل</span>
									</a>
									@endpermission --}}
									@permission('invoices-delete')
									<form action="{{ route('invoices.destroy', $invoice) }}" method="post" class="d-inline-block">
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
							@foreach ($invoice->items as $item)
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
		@if ($invoice->isPrimary())
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
				$('.invoice-total').text(total)
				$('.invoice-totalPayed').text(payed)
				$('.invoice-totalRemain').text(remain)
			}
		@endif
    </script>
@endpush

