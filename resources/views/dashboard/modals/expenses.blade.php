<div class="modal fade" id="expensesModal" tabindex="-1" role="dialog" aria-labelledby="unitsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="expensesModalLabel">إضافة منصرفات</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form_expenses" action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <div class="modal-body">
				<div class="form-group">
					<label>الخزنة</label>
					<select name="safe_id" class="form-control" required>
						@foreach (\App\Safe::expensesSafes() as $s)
							@if (isset($safe))
							@if ($s->id !== $safe->id)
								<option value="{{ $s->id }}" data-balance="{{ $s->balance() }}">{{ $s->name }} (<strong>{{ $s->balance() }}</strong>)</option>
							@endif
							@else
								<option value="{{ $s->id }}" data-balance="{{ $s->balance() }}">{{ $s->name }} (<strong>{{ $s->balance() }}</strong>)</option>
							@endif
						@endforeach
					</select>
				</div>
                <div class="form-group">
                    <label>القيمة</label>
                    <input  class="form-control amount" autocomplete="off" type="text" name="amount" placeholder="القيمة">
                </div>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <label>التفاصيل</label>
                  <textarea  class="form-control details" autocomplete="off" type="text" name="details" placeholder="التفاصيل"></textarea>
              </div>
          </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
      </div>
    </div>
  </div>

<script>
	$(function(){
		let option = $('#expensesModal select[name=safe_id] option:selected');
		$('#expensesModal input[name=amount]').prop('max', option.data('balance'))

	})
	$('#expensesModal select[name=safe_id]').change(function(){
		let option = $(this).find('option:selected');
		$('#expensesModal input[name=amount]').prop('max', option.data('balance'))
	})

  $('.showexpensesModal').click(function() {
    if($(this).hasClass("update")){
      $('#form_expenses').attr('action', $(this).data('action'))
      $('#form_expenses').append('<input type="hidden" name="_method" value="PUT">')
		$('#expensesModalLabel').text('تعديل منصرف')
      //set fields data
      $('#expensesModal input[name="amount"]').val($(this).data('amount'))
      $('#expensesModal textarea[name="details"]').val($(this).data('details'))
    }
    else{
      $('#form_expenses').attr('action', "{{ route('expenses.store') }}")
      $('#form_expenses').remove('input[name="_method"]')
		$('#expensesModalLabel').text('اضافة منصرف')

		//Clear from fields
      $('#expensesModal input[name="amount"]').val('')
      $('#expensesModal input[name="details"]').val('')
    }

    if($(this).data('safe-id')){
		console.log('fg');
		$('#expensesModal select[name=safe_id]').parent().remove();
		$('#form_expenses input#safe_id').remove();
		$('#form_expenses').append('<input type="hidden" id="safe_id" name="safe_id" value="'+$(this).data('safe-id')+'">')
		
	}
    if($(this).data('max')){
		$('#expensesModal input[name=amount]').prop('max', $(this).data('max'))
	}
    $('#expensesModal').modal('show')
  })
</script>