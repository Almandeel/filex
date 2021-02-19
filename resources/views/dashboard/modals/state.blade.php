<div class="modal fade" id="stateModal" tabindex="-1" role="dialog" aria-labelledby="stateLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="stateLabel">إضافة مدينة</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('states.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>الاسم</label>
                    <input  class="form-control" autocomplete="off" type="text" name="name" placeholder="الاسم" required/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
		</form>
		<section class="preview">
			<div class="modal-body">
				<table class="table table-striped">
					<tr>
						<th style="width: 120px;">المعرف</th>
						<td class="id"></td>
					</tr>
					<tr>
						<th>الاسم</th>
						<td class="name"></td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
			</div>
		</section>
      </div>
    </div>
  </div>

<script>
	$('.showstateModal').click(function() {
		if($(this).hasClass("update")){
            $('#stateLabel').text('تعديل بيانات مدينة: ' + $(this).data('name'))
            $('#stateModal .form').attr('action', $(this).data('action'))
            $('#stateModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //set fields data
            $('#stateModal input[name="name"]').val($(this).data('name'))
			
            $('#stateModal .preview').hide()
			$('#stateModal .form').show()
		}
		else if($(this).hasClass("preview")){
			$('#stateModal .preview').show()
			$('#stateModal .form').hide()
			$('#stateLabel').text('بيانات مدينة: ' + $(this).data('name'))
			$('.id').text($(this).data('id'))
			$('.name').text($(this).data('name'))
		}	
		else{
			$('#stateModal .preview').hide()
			$('#stateModal .form').show()

			$('#stateLabel').text('اضافة مدينة')
			$('#stateModal .form').attr('action', "{{ route('states.store') }}")
			$('#stateModal .form').remove('input[name="_method"]')


            //Clear from fields
            $('#stateModal input[name="name"]').val('')
            
		}
		
		$('#stateModal').modal('show')
	})
</script>