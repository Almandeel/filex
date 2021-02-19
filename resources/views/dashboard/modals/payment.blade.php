<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="chequesLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document" style="width: 75%">
    <div class="modal-content">
        <form action="" method="POST">
			@csrf
			<div class="modal-header">
				<h5 class="modal-title" id="paymentModalLabel">بيانات الدفعة</h5>
				<button type="button" class="close btn-close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="safe_id">الخزنة</label>
					<select id="safe_id" name="safe_id" class="form-control select2-single">
						@foreach ($billsSafes as $safe)
							<option value="{{ $safe->id }}" required data-max="{{ $safe->balance() }}">{{ $safe->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="amount">القيمة</label>
					<input id="amount" name="amount" type="number" class="form-control" required step="0.1">
				</div>
				<div class="form-group">
					<label for="details">التفاصيل</label>
					<textarea id="details" name="details" class="form-control"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
				<button type="submit" class="btn btn-primary">
					<span>اضافة</span>
				</button>
			</div>
        </form>
    </div>
  </div>
</div>

<script>
	$(document).on('click', '.showPaymentModal', function() {
		if (typeof $(this).data('title') !== 'undefined') {
			$('#paymentModalLabel').text($(this).data('title'))
		}else{
			$('#paymentModalLabel').text('اضافة دفعة')
		}

		if (typeof $(this).data('url') !== 'undefined') {
            $('#paymentModal form').attr('action', $(this).data('url'))
		}

		if (typeof $(this).data('amount-max') !== 'undefined') {
            $('#paymentModal input[name=amount]').attr('max', $(this).data('amount-max'))
		}

		if (typeof $(this).data('amount-min') !== 'undefined') {
            $('#paymentModal input[name=amount]').attr('min', $(this).data('amount-min'))
		}

		if (typeof $(this).data('amount-value') !== 'undefined') {
            $('#paymentModal input[name=amount]').attr('value', $(this).data('amount-value'))
		}

		if (typeof $(this).data('max') !== 'undefined') {
            $('#paymentModal input[name=amount]').attr('max', $(this).data('max'))
		}

		if (typeof $(this).data('details-value') !== 'undefined') {
            $('#paymentModal input[name=details]').attr('value', $(this).data('details-value'))
		}

		if (typeof $(this).data('account-id') !== 'undefined') {
            $('#paymentModal select[name=safe_id]').val($(this).data('account-id'))
		}

		var method = ($('#paymentModal input#method'));
		if (typeof $(this).data('method') !== 'undefined') {
			if(method.length <= 0){
				$('#paymentModal form').append('<input type="hidden" id="method" name="_method" value="'+$(this).data('method')+'">')
			}
		}else{
			if(method.length) method.remove();
		}

		var bill_id = ($('#paymentModal input#bill_id'));
		if (typeof $(this).data('bill-id') !== 'undefined') {
			if(bill_id.length <= 0){
				$('#paymentModal form').append('<input type="hidden" id="bill_id" name="bill_id" value="'+$(this).data('bill-id')+'">')
			}
		}else{
			if(bill_id.length) bill_id.remove();
		}

		var supplier_id = ($('#paymentModal input#supplier_id'));
		if (typeof $(this).data('supplier-id') !== 'undefined') {
			if(supplier_id.length <= 0){
				$('#paymentModal form').append('<input type="hidden" id="supplier_id" name="supplier_id" value="'+$(this).data('supplier-id')+'">')
			}
		}else{
			if(supplier_id.length) supplier_id.remove();
		}

		var customer_id = ($('#paymentModal input#customer_id'));
		if (typeof $(this).data('customer-id') !== 'undefined') {
			if(customer_id.length <= 0){
				$('#paymentModal form').append('<input type="hidden" id="customer_id" name="customer_id" value="'+$(this).data('customer-id')+'">')
			}
		}else{
			if(customer_id.length) customer_id.remove();
		}

		var invoice_id = ($('#paymentModal input#invoice_id'));
		if (typeof $(this).data('invoice-id') !== 'undefined') {
			if(invoice_id.length <= 0){
				$('#paymentModal form').append('<input type="hidden" id="invoice_id" name="invoice_id" value="'+$(this).data('invoice-id')+'">')
			}
		}else{
			if(invoice_id.length) invoice_id.remove();
		}
		
		
		/*if (typeof $(this).data('max') !== 'undefined') {
			$('#paymentModal #amount').prop('max', $(this).data('max'))
		}*/
		if($(this).data('pay')){
			var max = $('#paymentModal #safe_id option:selected').data('max');
			$('#paymentModal #amount').attr('max', max);
			$('#paymentModal #safe_id').change(function(){
				var max = $(this).children('option:selected').data('max')
				$('#paymentModal #amount').attr('max', max)
			})
		}

		$('#paymentModal').modal('show')
	})
</script>
