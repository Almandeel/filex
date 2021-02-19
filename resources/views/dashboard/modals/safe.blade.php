<div class="modal fade" id="safeModal" tabindex="-1" role="dialog" aria-labelledby="safesLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left" id="safeModalLabel">إضافة خزنه</h5>
        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form" action="{{ route('safes.store') }}" method="POST">
          @csrf
          <div class="modal-body">
              <div class="form-group">
                  <label>الاسم</label>
                  <input  class="form-control name" autocomplete type="text" name="name" placeholder="الاسم" required>
			  </div>
              <div class="form-group">
                  <label>النوع</label>
                  <select  class="form-control type" name="type" required>
					  @foreach (array_keys(\App\Safe::TYPES) as $type)
						  <option value="{{ $type }}">{{ \App\Safe::TYPES[$type] }}</option>
					  @endforeach
                  </select>
			  </div>
              <div class="form-group">
                  <label>الرصيد الافتتاحي</label>
                  <input  class="form-control" type="number" name="opening_balance" placeholder="الرصيد الافتتاحي" />
			  </div>
			  <fieldset>
				  <legend>خيارات العرض:</legend>
				  <div class="form-group">
					  <div class="row">
						  <div class="col-xs-12 col-md-6 col-lg-3">
							  <label>
								  <input type="checkbox" name="show_bills" checked>
								  <span>المشتريات</span>
							  </label>
						  </div>
						  <div class="col-xs-12 col-md-6 col-lg-3">
							  <label>
								  <input type="checkbox" name="show_invoices" checked>
								  <span>المبيعات</span>
							  </label>
						  </div>
						  <div class="col-xs-12 col-md-6 col-lg-3">
							  <label>
								  <input type="checkbox" name="show_expenses" checked>
								  <span>المنصرفات</span>
							  </label>
						  </div>
						  <div class="col-xs-12 col-md-6 col-lg-3">
							  <label>
								  <input type="checkbox" name="show_cheques" checked>
								  <span>الشيكات</span>
							  </label>
						  </div>
					  </div>
				  </div>
			  </fieldset>
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
					<th>النوع</th>
					<td class="type"></td>
				</tr>
				<tr>
					<th>الرصيد الافتتاحي</th>
					<td class="opening-balance"></td>
				</tr>
				<tr>
					<th>الرصيد</th>
					<td class="balance"></td>
				</tr>
			</table>
		</div>
		<div class="modal-footer">
			  @permission('safes-delete')
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
	$('.showSafeModal').click(function() {
		if($(this).hasClass("update")) {

			$('#safeModal .form').attr('action', $(this).data('action'))
			if($('#safeModal .form input#_method').length <= 0) $('#safeModal .form').append('<input type="hidden" id="_method" name="_method" value="PUT">')
			$('#safeModal .modal-title').text('تعديل خزنه')


			//set data to inputs
			$('#safeModal input[name="name"]').val($(this).data('name'))
			$('#safeModal select[name="type"]').val($(this).data('type'))
			$('#safeModal input[name="opening_balance"]').val($(this).data('opening-balance'))
			$('#safeModal input[name="show_bills"]').prop('checked', $(this).data('show-bills'))
			$('#safeModal input[name="show_invoices"]').prop('checked', $(this).data('show-invoices'))
			$('#safeModal input[name="show_expenses"]').prop('checked', $(this).data('show-expenses'))
			$('#safeModal input[name="show_cheques"]').prop('checked', $(this).data('show-cheques'))
		
			$('#safeModal .preview').hide()
			$('#safeModal .form').show()
		}
		else if($(this).hasClass("preview")){
			$('#safeModal .preview').show()
			$('#safeModal .form').hide()
			$('#safeModal .modal-title').text('بيانات الخزنه: ' + $(this).data('name'))
			$('.id').text($(this).data('id'))
			$('.name').text($(this).data('name'))
			$('.type').text($(this).data('type'))
			$('.balance').text($(this).data('balance'))
			$('.opening-balance').text($(this).data('opening-balance'))
			$('#safeModal .preview form').hide()
		}	
		else if($(this).hasClass("confirm-delete")){
			$('#safeModal .preview').show()
			$('#safeModal .preview form').attr('action', $(this).data('action'))
			$('#safeModal .form').hide()
			$('#safeModal .preview form').show()
			$('#safeModal .modal-title').text('هل انت متأكد من انك تريد حذف الخزنه: ' + $(this).data('name'))
			$('.id').text($(this).data('id'))
			$('.name').text($(this).data('name'))
			$('.balance').text($(this).data('balance'))
		}	
		else{
			$('#safeModal .preview').hide()
			$('#safeModal .form').show()
			$("#safeModal form input#_method").remove()

			$('#safeModal .form').attr('action', "{{ route('safes.store') }}")
			$('#safeModal .modal-title').text('إضافة خزنه')
		

			//delete data from inputs
			$('#safeModal input[name="name"]').val('')
			$('#safeModal input[name="opening_balance"]').val('')
			$('#safeModal select[name="type"]').val(1)
			$('#safeModal input[name="show_bills"]').prop('checked', true)
			$('#safeModal input[name="show_invoices"]').prop('checked', true)
			$('#safeModal input[name="show_expenses"]').prop('checked', true)
			$('#safeModal input[name="show_cheques"]').prop('checked', true)
		
		// $('#safeModal').modal('show')
	}
	$('#safeModal').modal('show')
  })
</script>