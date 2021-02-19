<div class="modal modal-xl fade" id="chequeInputModal" tabindex="-1" role="dialog" aria-labelledby="chequesLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 400px;">
    <div class="modal-content">
      <div class="modal-header">
		<h5 class="modal-title" id="chequeInputModalLabel">بيانات الشيك</h5>
        <button type="button" class="close btn-close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
            <div class="form-group">
			  <label for="chequeAccount">الخزنة</label>
				  <select id="chequeAccount" name="chequeAccount" class="form-control select2">
					  @php
						  $bankSafes = \App\Safe::bankSafes('cheques');
					  @endphp
					  @if ($bankSafes->count())
						@foreach ($bankSafes as $bs)
							<option value="{{ $bs->id }}" data-balance="{{ $bs->balance() }}">{{ $bs->name }} (<strong>{{ $bs->balance() }}</strong>)</option>
						@endforeach
					  @endif
				  </select>
            </div>
            <div class="form-group form-group-type">
				<label for="chequeType">النوع</label>
				<select id="chequeType" name="chequeType" class="form-control">
					@foreach (App\Cheque::TYPES as $type => $name)
						<option value="{{ $type }}">{{ $name }}</option>
					@endforeach
				</select>
            </div>
            <div class="form-group">
              <label for="chequeAmount">القيمة</label>
              <input id="chequeAmount" name="chequeAmount" type="number" class="form-control" step="0.1">
            </div>
            <div class="form-group form-group-bank">
				<label for="chequeBank">البنك</label>
				<input id="chequeBank" name="chequeBank" type="text" class="form-control" />
            </div>
            <div class="form-group">
              <label for="chequeNumber">رقم الشيك</label>
              <input id="chequeNumber" name="chequeNumber" type="number" class="form-control">
            </div>
            <div class="form-group">
              <label for="chequeDueDate">تاريخ الاستحقاق</label>
              <input id="chequeDueDate" name="chequeDueDate" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
			</div>
        </div>
        <div class="modal-footer">
        	<button type="button" class="btn btn-primary btn-submit-cheque">اكمال العملية</button>
        	<button type="button" class="btn btn-secondary btn-close">إغلاق</button>
        </div>
    </div>
  </div>
</div>

<script>
	var form = $('.showChequeInputModal').data('form'),
		amount = $("#chequeInputModal").find('input[name="chequeAmount"]'),
		bank = $("#chequeInputModal").find('input[name="chequeBank"]'),
		number = $("#chequeInputModal").find('input[name="chequeNumber"]'),
		date = $("#chequeInputModal").find('input[name="chequeDueDate"]');
		account = $("#chequeInputModal").find('select[name="chequeAccount"]');
	$('.showChequeInputModal').focusin(function() {
		form = $(this).data('form');
		if (typeof form == 'undefined') {
			$('button.btn-submit-cheque').hide();
			$("#chequeInputModal").find('input[name="chequeAmount"]').val($(this).val())
			$("#chequeInputModal").find('input[name="chequeAmount"]').val($(this).siblings('input[name="chequeAmount"]').val())
			$("#chequeInputModal").find('input[name="chequeBank"]').val($(this).siblings('input[name="chequeBank"]').val())
			$("#chequeInputModal").find('input[name="chequeNumber"]').val($(this).siblings('input[name="chequeNumber"]').val())
			$("#chequeInputModal").find('input[name="chequeDueDate"]').val($(this).siblings('input[name="chequeDueDate"]').val())
			if($(this).siblings('input[name="chequeAccount"]').val())
				$("#chequeInputModal").find('select[name="chequeAccount"]').val($(this).siblings('input[name="chequeAccount"]').val())
			if($(this).siblings('input[name="chequeType"]').val())
				$("#chequeInputModal").find('select[name="chequeType"]').val($(this).siblings('input[name="chequeType"]').val())
		}else{
			if($(this).data('action')){
				$('#' + form).attr('action', $(this).data('action'))
			}
			else{
				$('#' + form).attr('action', '{{ route("cheques.store") }}')
			}
			if($(this).data('method')){
				methodInput = '<input type="hidden" name="_method" value="'+$(this).data('method')+'" />';
				$('#' + form).append(methodInput)
				console.log(methodInput)
			}else{
				$('#' + form).find('input[name=_method]').remove()
			}
			if($(this).data('label')){
				$('#chequeInputModalLabel').text($(this).data('label'))
			}else{
				$('#chequeInputModalLabel').text('اضافة شيك')
			}
			if($(this).data('amount')){
				$("#chequeInputModal").find('input[name="chequeAmount"]').val($(this).data('amount'))
			}else{
				$("#chequeInputModal").find('input[name="chequeAmount"]').val('')
			}
			if($(this).data('bank')){
				$("#chequeInputModal").find('input[name="chequeBank"]').val($(this).data('bank'))
			}else{
				$("#chequeInputModal").find('input[name="chequeBank"]').val('')
			}
			if($(this).data('number')){
				$("#chequeInputModal").find('input[name="chequeNumber"]').val($(this).data('number'))
			}else{
				$("#chequeInputModal").find('input[name="chequeNumber"]').val('')
			}
			if($(this).data('due-date')){
				$("#chequeInputModal").find('input[name="chequeDueDate"]').val($(this).data('due-date'))
			}else{
				$("#chequeInputModal").find('input[name="chequeDueDate"]').val('')
			}
			if($(this).data('account-id')){
				$("#chequeInputModal").find('select[name="chequeAccount"]').val($(this).data('account-id'))
			}
			if($(this).data('type')){
				$("#chequeInputModal").find('select[name="chequeType"]').val($(this).data('type'))
			}

			if($(this).data('from') == 'bill'){
				let account_select = $("#chequeInputModal").find('select[name="chequeAccount"]')
				$("#chequeInputModal").find('select[name="chequeBank"]').hide()
				account_select.data('from', 'bill')
				var selected = account_select.children('#chequeInputModal select[name="chequeAccount"] option:selected');
				$('#chequeInputModal input#chequeAmount').attr('max', selected.data('balance'))
			}


			$('button.btn-submit-cheque').click(function(){
				$('#' + form + ' input[name="amount"]').val($("#chequeInputModal").find('input[name="chequeAmount"]').val())
				$('#' + form + ' input[name="number"]').val($("#chequeInputModal").find('input[name="chequeNumber"]').val())
				$('#' + form + ' input[name="bank_name"]').val($("#chequeInputModal").find('input[name="chequeBank"]').val())
				$('#' + form + ' input[name="due_date"]').val($("#chequeInputModal").find('input[name="chequeDueDate"]').val())
				$('#' + form + ' input[name="account_id"]').val($("#chequeInputModal").find('select[name="chequeAccount"]').val())
				$('#' + form + ' input[name="type"]').val($("#chequeInputModal").find('select[name="chequeType"]').val())
				{{--  if(amount.val() > 0 && !bank.val() && typeof $(this).data('hide-bank')  === 'undefined'){
					alert('يجب تحديد البنك')
				}
				else   --}}
				if(amount.val() > 0 && !number.val()){
					alert('يجب تحديد رقم الشيك')
				}
				else if(amount.val() > 0 && !date.val()){
					alert('يجب تحديد تاريخ استحقاق الشيك')
				}
				{{--  else if(amount.val() > 0 && !account.val()){
					alert('يجب تحديد الخزنة')
				}  --}}
				else{
					$('#' + form).submit()
				}
			})
		}
		if($(this).data('hide-bank')){
			$('#chequeInputModal .form-group-bank').hide()
		}else{
			$('#chequeInputModal .form-group-bank').show()
		}
		if($(this).data('show-type')){
			$('#chequeInputModal .form-group-type').show()
		}else{
			$('#chequeInputModal .form-group-type').hide()
		}
		if($(this).data('hide-bank')){
			checkBank();
		}
		$('#chequeInputModal').modal({backdrop: 'static', keyboard: false})
		{{-- $('#chequeInputModal').modal('show') --}}
	})
	$('#chequeInputModal .btn-close').on('click', function(e){
		e.preventDefault()
		if (typeof form == 'undefined') {
			{{--  if(amount.val() > 0 && !bank.val() && typeof $(this).data('hide-bank')  === 'undefined'){
				alert('يجب تحديد البنك')
			}
			else   --}}
			if(amount.val() > 0 && !number.val()){
				alert('يجب تحديد رقم الشيك')
			}
			else if(amount.val() > 0 && !date.val()){
				alert('يجب تحديد تاريخ استحقاق الشيك')
			}
			{{--  else if(amount.val() > 0 && !account.val()){
				alert('يجب تحديد الخزنة')
			}  --}}
			else{
				$('#chequeInputModal').modal('hide')
			}
		}
		else{
			$('#chequeInputModal').modal('hide')
		}
	})
	$("#chequeInputModal").on("hidden.bs.modal", function () {
		if (typeof form === 'undefined') {
			$('.showChequeInputModal').val($("#chequeInputModal").find('input[name="chequeAmount"]').val())
			$('.showChequeInputModal').siblings('input[name="chequeAmount"]').val($("#chequeInputModal").find('input[name="chequeAmount"]').val())
			$('.showChequeInputModal').siblings('input[name="chequeNumber"]').val($("#chequeInputModal").find('input[name="chequeNumber"]').val())
			$('.showChequeInputModal').siblings('input[name="chequeBank"]').val($("#chequeInputModal").find('input[name="chequeBank"]').val())
			$('.showChequeInputModal').siblings('input[name="chequeDueDate"]').val($("#chequeInputModal").find('input[name="chequeDueDate"]').val())
			$('.showChequeInputModal').siblings('input[name="chequeAccount"]').val($("#chequeInputModal").find('select[name="chequeAccount"]').val())
			$('.showChequeInputModal').siblings('input[name="chequeType"]').val($("#chequeInputModal").find('select[name="chequeType"]').val())
			if (typeof $('.showChequeInputModal').data('close-method') !== 'undefined') {
				executeFunctionByName($('.showChequeInputModal').data('close-method'), window)
			}
		}
	});

	$(function(){
		$('#chequeInputModal #chequeType').change(function(){
			if($(this).val() == {{ App\Cheque::TYPE_COLLECT }}){
				$('#chequeInputModal .form-group-bank input').val('')
				$('#chequeInputModal .form-group-bank').fadeOut()
			}else{
				$('#chequeInputModal .form-group-bank').fadeIn()
			}
		})

		var account_select = $('#chequeInputModal select#chequeAccount');
		account_select.change(function(){
			var selected = account_select.children('option:selected');
			if($(this).data('from') == 'bill'){
				$('#chequeInputModal input#chequeAmount').attr('max', selected.data('balance'))
			}
		})
	})
	function checkBank(){
		if($('#chequeInputModal #chequeType option:selected').val() == {{ App\Cheque::TYPE_COLLECT }}){
			$('#chequeInputModal .form-group-bank input').val('')
			$('#chequeInputModal .form-group-bank').fadeOut()
		}else{
			$('#chequeInputModal .form-group-bank').fadeIn()
		}
	}
</script>
