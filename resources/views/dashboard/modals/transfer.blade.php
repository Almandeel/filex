<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="transferModalLabel">إضافة تحويلة</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form_transfer" action="{{ route('transfers.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                  <label>الخزنة</label>
                  <select name="from_id" class="form-control other-safe transfer-safe" required>
                    @foreach ($safes as $s)
                      @if (isset($safe))
                      @if ($s->id !== $safe->id)
                        <option value="{{ $s->id }}" data-balance="{{ $s->balance() }}">{{ $s->name }}({{ $s->balance() }})</option>
                      @endif
                      @else
                        <option value="{{ $s->id }}" data-balance="{{ $s->balance() }}">{{ $s->name }}({{ $s->balance() }})</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                    <label>القيمة</label>
                    <input  class="form-control amount" required autocomplete="off" type="text" name="amount" placeholder="القيمة">
                </div>
				<div class="form-group">
					<label>التفاصيل</label>
					<textarea  class="form-control details" autocomplete="off" type="text" name="details" placeholder="التفاصيل"></textarea>
				</div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="to_id" class="transfer-safe current-safe">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
      </div>
    </div>
  </div>

<script>
  $('.showTransferModal').click(function() {
    if($(this).hasClass("update")){
      $('#form_transfer').attr('action', $(this).data('action'))
	  //set fields data
		let from = $('#transferModal .transfer-safe[name="from_id"]');
		let to = $('#transferModal .transfer-safe[name="to_id"]');
		$('#transferModal input[name="amount"]').val($(this).data('amount'))
		$('#transferModal textarea[name="details"]').val($(this).data('details'))
		if($(this).data('to-id') !== $(this).data('current')){
			from.prop('name', 'to_id');
			to.prop('name', 'from_id');
		}
	  	$('#transferModal .transfer-safe[name="from_id"]').val($(this).data('from'))
		$('#transferModal .transfer-safe[name="to_id"]').val($(this).data('to'))
    }
    else{
      $('#form_transfer').attr('action', "{{ route('transfers.store') }}")
      $('#form_transfer').remove('input[name="_method"]')
		if($(this).data('to')){
			$('#transferModal .transfer-safe[name="to_id"]').val($(this).data('to'))
		}
      //Clear from fields
	  $('#transferModal input[name="amount"]').val('')
	  $('#transferModal input[name="details"]').val('')
	}
	if($(this).data('collaborate') && $(this).data('collaborator-id')){
		$('#transferModal select.transfer-safe').data('modal', 'collaborate')
		if($('#transferModalCollaborate').length)
		{
			$('#transferModalCollaborate').val($(this).data('collaborate'))
		}else{
			$('#transferModal form#form_transfer').append('<input type="hidden" id="transferModalCollaborate" name="_collaborate" value="'+$(this).data('collaborate')+'">')
		}
		if($(this).data('collaborate') == 'debt'){
			$('#transferModal select.transfer-safe').data('collaborate', 'debt')
			$('#transferModal select.transfer-safe').prop('name', 'to_id')
			$('#transferModal select.transfer-safe').attr('max', true)
			$('#transferModal input.transfer-safe').prop('name', 'from_id')
			$('#transferModal input[name="amount"]').attr('max', $('#transferModal select.transfer-safe option:selected').data('balance'))
			
		}
		else if($(this).data('collaborate') == 'credit'){
			$('#transferModal select.transfer-safe').data('collaborate', 'credit')
			$('#transferModal select.transfer-safe').prop('name', 'from_id')
			$('#transferModal input.transfer-safe').prop('name', 'to_id')
			$('#transferModal input[name="amount"]').removeAttr('max')
		}
		$('#transferModal input.transfer-safe').val($(this).data('collaborator-id'))
	}
	if($(this).data('collaborate') && $(this).data('collaborator-id')){
		$('#transferModal select.transfer-safe').data('modal', 'collaborate')
		if($('#transferModalCollaborate').length)
		{
			$('#transferModalCollaborate').val($(this).data('collaborate'))
		}else{
			$('#transferModal form#form_transfer').append('<input type="hidden" id="transferModalCollaborate" name="_collaborate" value="'+$(this).data('collaborate')+'">')
		}
		if($(this).data('collaborate') == 'debt'){
			$('#transferModal select.transfer-safe').data('collaborate', 'debt')
			$('#transferModal select.transfer-safe').prop('name', 'to_id')
			$('#transferModal select.transfer-safe').attr('max', true)
			$('#transferModal input.transfer-safe').prop('name', 'from_id')
			$('#transferModal input[name="amount"]').attr('max', $('#transferModal select.transfer-safe option:selected').data('balance'))
			
		}
		else if($(this).data('collaborate') == 'credit'){
			$('#transferModal select.transfer-safe').data('collaborate', 'credit')
			$('#transferModal select.transfer-safe').prop('name', 'from_id')
			$('#transferModal input.transfer-safe').prop('name', 'to_id')
			$('#transferModal input[name="amount"]').removeAttr('max')
		}
		$('#transferModal input.transfer-safe').val($(this).data('collaborator-id'))
	}
	if($(this).data('safe-id')){
		$('#transferModal select.transfer-safe').val($(this).data('safe-id'))
	}

	if($(this).data('title')){
		$('#transferModalLabel').text($(this).data('title'));
	}

	if($(this).data('action')){
		$('#transferModal form#form_transfer').attr('action', $(this).data('action'));
	}

	if (typeof $(this).data('method') !== 'undefined') {
		if($('#transferModalMethod').length)
		{
			$('#transferModalMethod').val($(this).data('method'))
		}else{
			$('#transferModal form#form_transfer').append('<input type="hidden" id="transferModalMethod" name="_method" value="'+$(this).data('method')+'">')
		}
	}else{
		if( $('#transferModalMethod').length )
		{
			$('#transferModalMethod').remove()
		}
	}
		
	if($(this).data('max')){
		$('#transferModal input[name="amount"]').attr('max', $(this).data('max'))
		$('#transferModal select.transfer-safe').attr('max', $(this).data('max'))
	}
    
    $('#transferModal').modal('show')
  })
	$("#transferModal").on("hidden.bs.modal", function () {
		$('#transferModal .modal-body .transfer-safe').prop('name', "from_id");
		$('#transferModal .modal-footer .transfer-safe').prop('name', "to_id");
	})

	$(function(){
		$('#transferModal form#form_transfer').on('submit', function(e){
			$(e.target).parsley().isValid();
		})
		var transfer_select = $('#transferModal select.transfer-safe');
		transfer_select.change(function(){
			var selected = transfer_select.children('option:selected');
			if(transfer_select.attr('max') && transfer_select.data('modal') == 'collaborate' && transfer_select.data('collaborate') == 'debt'){
				{{-- $('#transferModal input.transfer-safe').prop('name', 'from_id') --}}
				$('#transferModal input[name="amount"]').attr('max', selected.data('balance'))
			}
		})
	})
</script>