@extends('layouts.dashboard.app', ['select2' => true, 'snippts' => true, 'jqueryUI' => true, 'modals' => ['customer',
'store', 'chequeInput'], 'datatables' => []])
@section('title')
{{'تعديل فاتورة - ' .  $invoice->id}}
@endsection

@push('css')

<link rel="stylesheet" href="{{ asset('dashboard/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
	@component('partials._breadcrumb')
	@slot('title', [
		'المبيعات',
		'فاتورة - ' . $invoice->id,
		'تعديل'
	])
	@slot('url', [route('invoices.index'), route('invoices.show', $invoice), '#'])
	@slot('icon', ['list', 'list-alt', 'pencil'])
	@endcomponent
	<form id="form" class="prevent-input-submition" action="{{ route('invoices.update', $invoice) }}" method="POST">
		@method('PUT')
		@csrf
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label for="number">رقم الفاتورة</label>
					<input type="text" name="number" id="number" class="form-control" value="{{ $invoice->number }}" />
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label for="stores">المخزن</label>
					<div class="input-group">
						<select name="store_id" id="stores" class="form-control select2">
							@foreach (auth()->user()->getStores() as $str)
							<option value="{{ $str->id }}" data-url="{{ route('invoices.create') }}?store_id={{$str->id}}"
								{{ $str->id == $store->id ? 'selected' : '' }}>{{ $str->name }}</option>
							@endforeach
						</select>
						@permission('stores-create')
						<div class="input-group-btn">
							<button type="button" class="btn btn-success showStoreModal"><i class="fa fa-plus"></i></button>
						</div>
						@endpermission
					</div>
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label>العميل</label>
					<div class="input-group">
						<select class="form-control select2-single" name="customer_id">
							@foreach ($customers as $customer)
							<option value="{{ $customer->id }}" @if($customer->id == $invoice->customer->id) selected
								@endif>{{ $customer->name }}</option>
							@endforeach
						</select>
						@permission('customers-create')
						<div class="input-group-btn">
							<button type="button" class="btn btn-success showCustomerModal"><i
									class="fa fa-plus"></i></button>
						</div>
						@endpermission
					</div>
				</div>
			</div>
		</div>
		<div class="box box-primary">
			<div class="box-header">
				<div class="row">
					<div class="col-md-6">
						<h4>
							<i class="fa fa-cubes"></i>
							<span>قائمة المنتجات</span>
						</h4>
					</div>
					<div class="col-md-6">
						<div id="form-add-item" class="form-inline">
							<div class="form-group">
								<label>المنتج</label>
								<select class="form-control select-items"></select>
							</div>
							<div class="form-group">
								<label>الوحدة</label>
								<select class="form-control select-units"></select>
							</div>
							<div class="form-group">
								<button type="button" class="btn btn-primary btn-block btn-add-item">
									<i class="fa fa-plus"></i>
									<span>اضافة</span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="box-body">
				<table id="items-table" class="table table-bordered table-hover text-center">
					<thead>
						<tr>
							<th rowspan="2">#</th>
							<th rowspan="2">المنتج</th>
							<th rowspan="2">الوحدة</th>
							<th rowspan="2">الكمية</th>
							<th rowspan="2">المستلم</th>
							<th colspan="2">السعر</th>
							<th rowspan="2">الاجمالي</th>
							<th rowspan="2">الخيارات</th>
						</tr>
						<tr>
							<th>الشراء</th>
							<th>البيع</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($invoice->items as $item)
							<tr>
								<td>{{ $loop->index + 1 }}</td>
								<td>{{ $item->itemName() }}</td>
								<td>{{ $item->unitName() }}</td>
								<td>
									<input type="hidden" name="units[]" value="{{ $item->item_store_unit_id }}" />
									<input type="number" min="0" max="{{ $item->itemStoreUnit->quantity + $item->quantity }}" class="form-control input input-quantity"
										name="quantities[]" value="{{ $item->quantity }}" />
								</td>
								<td>
									<div class="input-group">
										<label class="input-group-addon">
											<input type="checkbox" @if($item->quantity === $item->items->sum('quantity')) checked @endif class="flat-green receive-all" value="{{ $item->items->sum('quantity') }}"/>
											<span>الكل</span>
										</label>
										<input type="number" min="0" class="form-control input input-receive" name="receives[]" value="{{ $item->items->sum('quantity') }}" />
									</div>
								</td>
								<td>
									<input type="number" min="0" step="0.01" class="form-control input input-price price-purchase"
										name="purchases[]" value="{{ $item->price_purchase }}" />
								</td>
								<td>
									<input type="number" min="0" step="0.01" class="form-control input input-price price-sell" name="sells[]"
										value="{{ $item->price_sell }}" />
								</td>
								<td class="item-total"></td>
								<td>
									<button type="button" class="btn btn-danger btn-xs btn-remove-row"><i class="fa fa-times"></i></button>
								</td>
							</tr>
						@endforeach
					</tbody>
					<tfoot>
						<th></th>
						<th></th>
						<th></th>
						<th class="totalQ">0</th>
						<th class="totalR">0</th>
						<th class="totalPurchases">0</th>
						<th class="totalSells">0</th>
						<th class="total">0</th>
						<th>
							<button id="button-add-item" type="button" class="btn btn-primary btn-xs" data-popover-position="top" data-toggle="popover" data-html="true" title="اضافة منتج للقائمة" data-content="">
								<i class="fa fa-plus"></i>
							</button>
						</th>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="box box-success">
			<div class="box-header">
				<h4 class="box-title">
					<i class="fa fa-money"></i>
					<span>خيارات الدفع</span>
				</h4>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12">
						<fieldset>
							<legend>الدفعات</legend>
							@component('components.form-payments')
								@slot('safes', $invoicesSafes)
								@slot('payments', $invoice->payments)
								@slot('removeCallback', 'calculateTotals')
								@slot('addCallback', 'calculateTotals')
								@slot('remain', '#remain-display')
							@endcomponent
						</fieldset>
					</div>
				</div>
			</div>
			<div class="box-footer form-inline">
				<div class="form-group">
					<label for="cheque">شيك</label>
					<input type="number" step="0.01" id="cheque" data-close-method="calculateRemain"
						class="form-control showChequeInputModal" value="{{ $cheque ? $cheque->amount : 0 }}" />
					<input type="hidden" name="chequeAmount" class="input-payment"
						value="{{ $cheque ? $cheque->amount : 0 }}" />
					<input type="hidden" name="chequeBank" value="{{ $cheque ? $cheque->bank_name : '' }}">
					<input type="hidden" name="chequeNumber" value="{{ $cheque ? $cheque->number : '' }}">
					<input type="hidden" name="chequeAccount" value="{{ $cheque ? $cheque->account_id : '' }}">
					<input type="hidden" name="chequeDueDate" value="{{ $cheque ? $cheque->due_date : '' }}">
				</div>
				<div class="form-group">
					<label>المتبقي</label>
					<input type="number" id="remain-display" min="0" readonly class="form-control" value="0" />
					<input type="hidden" name="remain" id="remain" min="0" readonly value="0" />
					<input type="hidden" name="payed" id="payed" min="0" readonly value="0" />
					<input type="hidden" name="amount" id="amount" min="0" readonly value="0" />
				</div>
				<button type="button" class="btn btn-primary btn-submit disabled">إكمال العملية</button>
			</div>
		</div>			
    </form>
@endsection

@push('js')
    <script>
		var items = new Array();
		var options = "";
		@foreach ($store->items as $item)
			var item = new Item({{$item->pivot->id}}, "{{$item->name}}");
			@foreach ($item->units as $unit)
				@php
					$itemStoreUnit = $store->itemStoreUnit($item->pivot->id, $unit->pivot->id);
					$itemStoreUnit = $itemStoreUnit ? $itemStoreUnit->quantity > 0 ? $itemStoreUnit : null : null;
				@endphp
				@if ($itemStoreUnit)
					var unit;
					unit = new Unit("{{ $unit->pivot->id }}", "{{ $item->name }}", "{{ $unit->name }}");
					unit.id = {{ $itemStoreUnit->id }};
					unit.quantity = {{ $itemStoreUnit->quantity }};
					unit.price_purchase = '{{ number_format($itemStoreUnit->price_purchase, 2) }}';
					unit.price_sell = '{{ number_format($itemStoreUnit->price_sell, 2) }}';
					item.units[unit.id] = unit;						
				@endif
			@endforeach
			item.units.removeAll(undefined)
			if(item.units.length > 0){
				items[item.id] = item;
				options += "<option value='"+item.id+"'>"+item.name+"</option>";
			}
		@endforeach
		items.removeAll(undefined)
		$(function () {
			$("tbody" ).sortable({
				stop: function(){
					setCounter()
				}
			});
			$('.select-items').html(options);
			$('#stores').change(function(e){
				@if (count(request()->all()))
					@php
						if(array_key_exists('store_id', request()->all())) unset(request()->all()['store_id']);
					@endphp
					window.location.replace('{{ request()->fullUrl() }}'+ '&store_id=' + $(this).find(':selected').val())
				@else
					window.location.replace('{{ request()->fullUrl() }}'+ '?store_id=' + $(this).find(':selected').val())
				@endif
			})
			// setItemUnits($('.select-items').prop("selectedIndex", 0).val())
			// setItemUnits($('.select-items option:nth-child(1)').val())
			setItemUnits($('.select-items option:first').val())
			$(document).on('change', '.select-items', function(e){
				setItemUnits($(this).find(":selected").val())
			})

			$(document).on('click', '.btn-add-item', function(e){
				e.preventDefault()
				var item = items.find(item => item.id == $(this).parent().parent().find('.select-items option:selected').val())
				var unit = item.units.find(item => item.id == $(this).parent().parent().find('.select-units option:selected').val())
				if(!isItemExists(unit.itemName, unit.unitName)){
					var row = `<tr>
						<td>` + ($('#items-table tbody tr').length + 1)+ `</td>
						<td>` + unit.itemName + `</td>
						<td>` + unit.unitName + `</td>
						<td>
							<input type="hidden" name="units[]" value="` + unit.id + `" />
							<input type="number" min="0" max="`+unit.quantity+`" class="form-control input input-quantity" name="quantities[]" value="1" />
						</td>
						<td>
							<div class="input-group">
								<label class="input-group-addon">
									<input type="checkbox" checked class="flat-green receive-all" />
									<span>الكل</span>
								</label>
								<input type="number" min="0" class="form-control input input-receive" name="receives[]" value="0" />
							</div>
						</td>
						<td>
							<input type="number" min="0" step="0.01" class="form-control input input-price price-purchase" name="purchases[]" value="`+fillterNumber(unit.price_purchase)+`" />
						</td>
						<td>
							<input type="number" min="0" step="0.01" class="form-control input input-price price-sell" name="sells[]" value="`+fillterNumber(unit.price_sell)+`" />
						</td>
						<td class="item-total"></td>	
						<td>
							<button type="button" class="btn btn-danger btn-xs btn-remove-row"><i class="fa fa-times"></i></button>
						</td>
					</tr>`;
					
					$('#items-table tbody').append(row)
					calculateTotals()
					$("[data-toggle=popover]").popover('hide')
				}else{
					alert('المنتج موجود في القائمة')
				}
			})

			$(document).on('click', '.btn-remove-row', function(e) {
				e.preventDefault()
				$(this).parent().parent().remove()
				setCounter()
				{{-- $('input#safe').val(0)
				$('input#bank').val(0) --}}
				calculateTotals()
			})
			
			$(document).bind('keyup change', '.input, .input-charge', function(e) {
				e.preventDefault()
				calculateTotals()
			})
			$(document).bind('keyup change', 'input.input-payment', function(e) {
				e.preventDefault()
				calculateRemain()
			})
			
			$(document).on('click change', 'input.receive-all', function(e){
				var q = $(this).parent().parent().parent().parent().find("input.input-quantity").val();
				var input_receive = $(this).parent().parent().find("input.input-receive");
				if($(this).prop('checked')) input_receive.val(q);
				calculateTotals()
			})

			$('#button-add-item').attr('data-content', $('#form-add-item').html());
			calculateTotals();
		})
		function setCounter(){
			for( let i = 0; i < $('#items-table tbody tr').length; i++){
				$($('#items-table tbody tr')[i]).children('td:nth-child(1)').text(i+1)
			}
		}

		function calculateRemain(){
			var payments = 0;
			$('input.input-payment').each(function() {
				payments += Number($(this).val())
			})
			var remain = fillterNumber($('#items-table tfoot tr .total').text()) - payments;
			
			$('input#remain-display').val(remain)
			let totalPayed = 0;
			$('input#remain').val(fillterNumber($('#items-table tfoot tr .total').text()) - payments)
			$('input#payed').val(payments)
		}

		function calculateTotals(){
			var total = 0, totalQ = 0, totalR = 0, totalSells = 0, totalPurchases = 0;
			var itemsCount = $('#items-table tbody tr input.input-receive[type="number"]').filter(function () {
				return this.value > 0;
			}).length;
			/*var chargesSafe = fillterNumber($('input#chargesSafe').val()),
				chargesBank = fillterNumber($('input#chargesBank').val());
			var charges = chargesSafe + chargesBank;
			var chargesPerItem = charges / itemsCount;*/
			$('#items-table tbody tr').each(function() {
				var quantity = fillterNumber($(this).find("input.input-quantity").val());
				var ra = $(this).find("input.receive-all").prop('checked');
				var price_purchase = fillterNumber($(this).find("input.price-purchase").val());
				var price_sell = fillterNumber($(this).find("input.price-sell").val());
				if(ra) $(this).find("input.input-receive").val(quantity);
				$(this).find("input.input-receive").prop('max', quantity)
				var amount = quantity * price_sell;
				totalQ += quantity;
				@if (!$invoice)
					var r = fillterNumber($(this).find("input.input-receive").val());
					totalR += r;
				@endif
				totalPurchases += price_purchase;
				totalSells += price_sell;
				total += amount;
				/* if($(this).find("input.input-receive").val() > 0 && $(this).find("input.input-price").val() > 0){
					total += chargesPerItem;
					$(this).find('td.item-total').text(currencyFormat(amount + chargesPerItem))
				}else{
					$(this).find('td.item-total').text(currencyFormat(amount))
				} */
				$(this).find('td.item-total').text(currencyFormat(amount))

			});
			$('#items-table tfoot tr .totalQ').text(totalQ)
			$('#items-table tfoot tr .totalR').text(totalR)
			$('#items-table tfoot tr .totalPurchases').text(currencyFormat(totalPurchases))
			$('#items-table tfoot tr .totalSells').text(currencyFormat(totalSells))
			$('#items-table tfoot tr .total').text(currencyFormat(total))
			$('input#amount').val(total)
			calculateRemain()

			if(total > 0) {
				$('button.btn-submit').removeClass('disabled')
				$('button.btn-submit').attr('type', 'submit')
			}
			else{
				$('button.btn-submit').addClass('disabled')
				$('button.btn-submit').attr('type', 'button')
			}
		}
		function isItemExists(itemName, unitName){
			var exists = false;
			var index = 0;

			do{
				var item = $($('#items-table tbody tr')[index]).children('td:nth-child(2)').text()
				var unit = $($('#items-table tbody tr')[index]).children('td:nth-child(3)').text()
				exists = (item == itemName) && (unit == unitName)
				index++;
			}while(!exists && index < $('#items-table tbody tr').length);

			return exists;
		}
		function Item(id, item) {
			this.id = id;
			this.name = item;
			this.units = [];
		}
		function Unit(id, item, unit, quantity = 0, price_purchase = 0, price_sell = 0) {
			this.id = id;
			this.itemName = item;
			this.unitName = unit;
			this.quantity = quantity;
			this.price_purchase = price_purchase;
			this.price_sell = price_sell;
			this.total = function(){
				return this.quantity * this.price_sell;
			}
		}

		function setItemUnits(itemId){
			var item = items.find(item => item.id == itemId);
			if(item){
				var units = item.units;
				var options = "";
				units.forEach(function(unit, i) { 
					options += "<option value='"+unit.id+"'>"+unit.unitName+"</option>";
				});
				$('.select-units').html(options);
			}
		}
    </script>
@endpush

