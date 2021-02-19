<div class="modal fade" id="collaboratorModal" tabindex="-1" role="dialog" aria-labelledby="collaboratorsLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left" id="collaboratorModalLabel">إضافة متعاون</h5>
        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form" action="{{ route('collaborators.store') }}" method="POST">
          @csrf
          <div class="modal-body">
              <div class="form-group">
                  <label>الاسم</label>
                  <input  class="form-control name" autocomplete="off" type="text" name="name" placeholder="الاسم" required>
              </div>
              <div class="form-group">
                  <label>رقم الهاتف</label>
                  <input  class="form-control" autocomplete="off" minlength="10" maxlength="30"		 type="text" name="phone" placeholder="رقم الهاتف">
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
				<tr>
					<th>الرصيد</th>
					<td class="balance"></td>
				</tr>
				<tr>
					<th>رقم الهاتف</th>
					<td class="phone"></td>
				</tr>
			</table>
		</div>
		<div class="modal-footer">
			  @permission('collaborators-delete')
					<form action="" method="post" class="d-inline-block">
						@csrf
						@method('DELETE')
						<button type="submit" class="btn btn-danger">
							<i class="fa fa-times"></i>
							<span>حذف</span>
						</button>
					</form>
				@endpermission
			<button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
		</div>
	</section>
    </div>
  </div>
</div>


<script>
  $('.showCollaboratorModal').click(function() {
    if($(this).hasClass("update")) {

		$('#collaboratorModal .form').attr('action', $(this).data('action'))
		$('#collaboratorModal .form').append('<input type="hidden" name="_method" value="PUT">')
		$('#collaboratorModal .modal-title').text('تعديل متعاون')


		//set data to inputs
		$('#collaboratorModal input[name="name"]').val($(this).data('name'))
		$('#collaboratorModal input[name="phone"]').val($(this).data('phone'))
		$('#collaboratorModal input[name="address"]').val($(this).data('address'))
		
			$('#collaboratorModal .preview').hide()
			$('#collaboratorModal .form').show()
		}
		else if($(this).hasClass("preview")){
			$('#collaboratorModal .preview').show()
			$('#collaboratorModal .form').hide()
			$('#collaboratorModal .modal-title').text('بيانات المتعاون: ' + $(this).data('name'))
			$('.id').text($(this).data('id'))
			$('.name').text($(this).data('name'))
			$('.balance').text($(this).data('balance'))
			$('.phone').text($(this).data('phone'))
			$('#collaboratorModal .preview form').hide()
		}	
		else if($(this).hasClass("confirm-delete")){
			$('#collaboratorModal .preview').show()
			$('#collaboratorModal .preview form').attr('action', $(this).data('action'))
			$('#collaboratorModal .form').hide()
			$('#collaboratorModal .preview form').show()
			$('#collaboratorModal .modal-title').text('هل انت متأكد من انك تريد حذف المتعاون: ' + $(this).data('name'))
			$('.id').text($(this).data('id'))
			$('.name').text($(this).data('name'))
			$('.balance').text($(this).data('balance'))
			$('.phone').text($(this).data('phone'))
		}	
		else{
			$('#collaboratorModal .preview').hide()
			$('#collaboratorModal .form').show()

			$('#collaboratorModal .form').attr('action', "{{ route('collaborators.store') }}")
			$('#collaboratorModal .modal-title').text('إضافة متعاون')
		

			//delete data from inputs
			$('#collaboratorModal input[name="name"]').val('')
			$('#collaboratorModal input[name="phone"]').val('')
			$('#collaboratorModal input[name="address"]').val('')
		
		// $('#collaboratorModal').modal('show')
	}
	$('#collaboratorModal').modal('show')

  })
</script>