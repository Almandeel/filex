@extends('layouts.dashboard.app', ['select2' => true, 'snippts' => true, 'jqueryUI' => true, 'modals' => ['supplier', 'store', 'chequeInput'], 'datatables' => []])
@section('title')
	{{'تعديل فاتورة - ' .  $bill->id}}
@endsection

@push('css')

    <link rel="stylesheet" href="{{ asset('dashboard/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    @component('partials._breadcrumb')
        @slot('title', [
			'المشتريات',
			'فاتورة - ' . $bill->id,
			'تعديل'
		])
        @slot('url', [route('bills.index'), route('bills.show', $bill), '#'])
        @slot('icon', ['list', 'list-alt', 'pencil'])
    @endcomponent
    <form id="form" class="prevent-input-submition" action="{{ route('bills.update', $bill) }}" method="POST">
		@method('PUT')
		@csrf
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label for="number">رقم الفاتورة</label>
					<input type="text" name="number" id="number" class="form-control" value="{{ $bill->number }}" />
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label for="stores">المخزن</label>
					<div class="input-group">
						<select name="store_id" id="stores" class="form-control select2">
							@foreach (auth()->user()->getStores() as $str)
							<option value="{{ $str->id }}" data-url="{{ route('bills.create') }}?store_id={{$str->id}}"
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
					<label>المورد</label>
					<div class="input-group">
						<select class="form-control select2-single" name="supplier_id">
							@foreach ($suppliers as $supplier)
							<option value="{{ $supplier->id }}" @if($supplier->id == $bill->supplier->id) selected
								@endif>{{ $supplier->name }}</option>
							@endforeach
						</select>
						@permission('suppliers-create')
						<div class="input-group-btn">
							<button type="button" class="btn btn-success showSupplierModal"><i class="fa fa-plus"></i></button>
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
							<th>#</th>
							<th>المنتج</th>
							<th>الوحدة</th>
							<th>الكمية</th>
							<th>المستلم</th>
							<th>سعر الشراء</th>
							<th>الاجمالي</th>
							<th>الخيارات</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($bill->items as $item)
						<tr>
							<td>{{ $loop->index + 1 }}</td>
							<td>{{ $item->itemName() }}</td>
							<td>{{ $item->unitName() }}</td>
							<td>
								<input type="hidden" name="items[]" value="{{ $item->itemId() }}" />
								<input type="hidden" name="units[]" value="{{ $item->unitId() }}" />
								<input type="number" class="form-control input input-quantity" name="quantities[]"
									value="{{ $item->quantity }}" />
							</td>
							<td>
								<div class="input-group">
									<label class="input-group-addon">
										<input type="checkbox" @if($item->quantity == $item->items->sum('quantity')) checked
										@endif class="flat-green receive-all" />
										<span>الكل</span>
									</label>
									<input type="number" min="0" class="form-control input input-receive" name="receives[]"
										value="{{ $item->items->sum('quantity') }}" />
								</div>
							</td>
							<td>
								<input type="number" class="form-control input input-price" name="prices[]"
									value="{{ $item->price }}" />
							</td>
							<td class="item-total"></td>
							<td>
								<button type="button" class="btn btn-danger btn-xs btn-remove-row"><i
										class="fa fa-times"></i></button>
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
						<th class="totalP">0</th>
						<th class="total">0</th>
						<th>
							<button id="button-add-item" type="button" class="btn btn-primary btn-xs"
								data-popover-position="top" data-toggle="popover" data-html="true" title="اضافة منتج للقائمة"
								data-content="">
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
					<div class="col-xs-12 col-md-6">
						<fieldset>
							<legend>الدفعات</legend>
							@component('components.form-payments')
								@slot('safes', $billsSafes)
								@slot('payments', $bill->payments)
								@slot('removeCallback', 'calculateTotals')
								@slot('addCallback', 'calculateTotals')
								@slot('remain', '#remain-display')
								@slot('pay', true)
							@endcomponent
						</fieldset>
					</div>
					<div class="col-xs-12 col-md-6">
						<fieldset>
							<legend>المنصرفات</legend>
							@component('components.form-expenses')
								@slot('safes', $expensesSafes)
								@slot('expenses', $bill->expenses)
								@slot('receiveInputs', '.input-receive')
								@slot('removeCallback', 'calculateTotals')
							@endcomponent
						</fieldset>
					</div>
				</div>
			</div>
			<div class="box-footer form-inline">
				<div class="form-group">
					<label for="cheque">شيك</label>
					<input type="number" id="cheque" data-hide-bank="true" data-from="bill" data-close-method="calculateRemain" class="form-control showChequeInputModal"
						value="{{ $cheque ? $cheque->amount : 0 }}" />
					<input type="hidden" name="chequeAmount" class="input-payment" value="{{ $cheque ? $cheque->amount : 0 }}" />
					<input type="hidden" name="chequeBank" value="{{ $cheque ? $cheque->bank_name : '' }}">
					<input type="hidden" name="chequeNumber" value="{{ $cheque ? $cheque->number : 0 }}">
					<input type="hidden" name="chequeDueDate" value="{{ $cheque ? $cheque->due_date : 0 }}">
					<input type="hidden" name="chequeAccount" value="{{ $cheque ? $cheque->account_id : '' }}">
				</div>
				<div class="form-group">
					<label>المتبقي</label>
					<input type="number" id="remain-display" min="0" readonly class="form-control" value="{{ $bill->remain + $bill->activeCheques()->sum('amount') }}" />
					<input type="hidden" name="remain" id="remain" min="0" readonly value="{{ $bill->remain }}" />
					<input type="hidden" name="payed" id="payed" min="0" readonly value="{{ $bill->payed }}" />
					<input type="hidden" name="amount" id="amount" min="0" readonly value="{{ $bill->amount }}" />
				</div>
				<button type="submit" class="btn btn-primary btn-outline-primary btn-submit disabled">إكمال العملية</button>
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
				var unit;
				unit = new Unit("{{ $unit->pivot->id }}", "{{ $item->name }}", "{{ $unit->name }}");
				item.units[unit.id] = unit;
			@endforeach
			item.units.removeAll(undefined)
			items[item.id] = item;
			options += "<option value='"+item.id+"'>"+item.name+"</option>";
		@endforeach
		items.removeAll(undefined)
		$(function () {
			$("#items-table tbody" ).sortable({
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
			if(items.length) setItemUnits($('.select-items option:first').val())
			$(document).on('change', '.select-items', function(e){
				setItemUnits($(this).find(":selected").val())
			})

			calculateTotals()
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
							<input type="hidden" name="items[]" value="` + item.id + `" />
							<input type="hidden" name="units[]" value="` + unit.id + `" />
							<input type="number" min="0" class="form-control input input-quantity" name="quantities[]" value="1" />
						</td>
						<td>
							<div class="input-group">
								<label class="input-group-addon">
									<input type="checkbox" class="flat-green receive-all" />
									<span>الكل</span>
								</label>
								<input type="number" min="0" class="form-control input input-receive" name="receives[]" value="0" />
							</div>
						</td>
						<td>
							<input type="number" min="0" class="form-control input input-price" name="prices[]" value="0" />
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
			
			$(document).bind('keyup change', '.input, .input-expense', function(e) {
				e.preventDefault()
				calculateTotals()
			})
			$(document).bind('keyup change', 'input.input-payment', function(e) {
				e.preventDefault()
				calculateRemain()
			})
			
			$(document).bind('keyup change', 'input.input-payment, .input-expense', function(e) {
				var input = $(e.target);
				var max = Number(input.prop('max'));
				var value = Number(input.val());
				var taken = 0;
				$('input[data-safe='+input.data('safe')+']').each(function(index, element){
					taken += Number($(element).val())
				})
				taken -= input.val();
				max -= taken;
				if(value > max){
					alert('رصيد الخزنة هو: '+max+' قيمة الدفعة اكبر من رصيد الخزنة')
					input.val(max)
					setPaymentsTotals()
					setExpensesTotals()
				}
			})
			
			
			$(document).on('change', 'input.receive-all', function(e){
				e.preventDefault()
				var q = Number($(this).parent().parent().children("input.input-quantity").val());
				if($(this).prop('checked')) $(this).parent().parent().find("input.input-receive").val(q);
				calculateTotals()
			})

			$('#button-add-item').attr('data-content', $('#form-add-item').html())
		})
		function setCounter(){
			for( let i = 0; i < $('#items-table tbody tr').length; i++){
				$($('#items-table tbody tr')[i]).children('td:nth-child(1)').text(i+1)
			}
		}
		function calculateRemain(){
			var payed = 0;
			$('input.input-payment').each(function() {
				payed += Number($(this).val())
			})
			$('input.input-expense').each(function() {
				payed += Number($(this).val())
			})
			var remain = fillterNumber($('#items-table tfoot tr .total').text()) - (payed);
			
			$('input#remain-display').val(remain)
			$('input#remain').val(fillterNumber($('#items-table tfoot tr .total').text()) - (payed))
			$('input#payed').val(payed)

		}
		function calculateTotals(){
			var total = 0, totalQ = 0, totalR = 0, totalP = 0;
			var itemsCount = $('#items-table tbody tr input.input-receive[type="number"]').filter(function () {
				return this.value > 0;
			}).length;
			{{--  var chargesSafe = fillterNumber($('input#chargesSafe').val()),
				chargesBank = fillterNumber($('input#chargesBank').val());
			var charges = chargesSafe + chargesBank;  --}}
			var charges = 0;
			$('input.input-expense').each(function() {
				charges += Number($(this).val())
			})
			var chargesPerItem = charges / itemsCount;
			$('#items-table tbody tr').each(function() {
				var q = fillterNumber($(this).find("input.input-quantity").val());
				var ra = $(this).find("input.receive-all").prop('checked');
				var p = fillterNumber($(this).find("input.input-price").val());
				if(ra) $(this).find("input.input-receive").val(q);
				var r = fillterNumber($(this).find("input.input-receive").val());
				$(this).find("input.input-receive").prop('max', q)
				totalQ += q;
				totalR += r;
				totalP += p;
				total += q * p;
				if($(this).find("input.input-receive").val() > 0 && $(this).find("input.input-price").val() > 0){
					total += chargesPerItem;
					$(this).find('td.item-total').text(currencyFormat((q * p) + chargesPerItem))
				}else{
					$(this).find('td.item-total').text(currencyFormat(q * p))
				}

			});
			$('#items-table tfoot tr .totalQ').text(totalQ)
			$('#items-table tfoot tr .totalR').text(totalR)
			$('#items-table tfoot tr .totalP').text(currencyFormat(totalP))
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
		function Unit(id, item, unit, quantity = 0, price = 0) {
			this.id = id;
			this.itemName = item;
			this.unitName = unit;
			this.quantity = quantity;
			this.price = price;
			this.total = function(){
				return this.quantity * this.price;
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

