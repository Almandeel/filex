<div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="suppliersLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left" id="supplierModalLabel">إضافة مورد</h5>
        <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form" action="{{ route('suppliers.store') }}" method="POST">
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
              {{-- <fieldset class="form-group">
				  <legend>المخازن</legend>
				  @foreach (auth()->user()->getStores() as $store)
					  <label class="col-xs-12 col-md-6">
						  <input type="checkbox" name="stores[]" value="{{ $store->id }}" checked>
						  <span>{{ $store->name }}</span>
					  </label>
				  @endforeach
			  </fieldset> --}}
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
			  @permission('suppliers-delete')
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
  $('.showSupplierModal').click(function() {
	  let field_method = $('#customerModal input#_method');
    if($(this).hasClass("update")) {

		$('#supplierModal .form').attr('action', $(this).data('action'))
		if(field_method.length){
			field_method.val('PUT')
		}else{
			$('#customerModal .form').append('<input type="hidden" id="_method" name="_method" value="PUT">')
		}
		$('#supplierModal .modal-title').text('تعديل مورد')


		//set data to inputs
		$('#supplierModal input[name="name"]').val($(this).data('name'))
		$('#supplierModal input[name="phone"]').val($(this).data('phone'))
		$('#supplierModal input[name="address"]').val($(this).data('address'))
		
			$('#supplierModal .preview').hide()
			$('#supplierModal .form').show()
		}
		else if($(this).hasClass("preview")){
			$('#supplierModal .preview').show()
			$('#supplierModal .form').hide()
			$('#supplierModal .modal-title').text('بيانات المورد: ' + $(this).data('name'))
			$('.id').text($(this).data('id'))
			$('.name').text($(this).data('name'))
			$('.balance').text($(this).data('balance'))
			$('.phone').text($(this).data('phone'))
			$('#supplierModal .preview form').hide()
		}	
		else if($(this).hasClass("confirm-delete")){
			$('#supplierModal .preview').show()
			$('#supplierModal .preview form').attr('action', $(this).data('action'))
			$('#supplierModal .form').hide()
			$('#supplierModal .preview form').show()
			$('#supplierModal .modal-title').text('هل انت متأكد من انك تريد حذف المورد: ' + $(this).data('name'))
			$('.id').text($(this).data('id'))
			$('.name').text($(this).data('name'))
			$('.balance').text($(this).data('balance'))
			$('.phone').text($(this).data('phone'))
		}	
		else{
			$('#supplierModal .preview').hide()
			$('#supplierModal .form').show()

			$('#supplierModal .form').attr('action', "{{ route('suppliers.store') }}")
			$('#supplierModal .modal-title').text('إضافة مورد')
		

			//delete data from inputs
			$('#supplierModal input[name="name"]').val('')
			$('#supplierModal input[name="phone"]').val('')
			$('#supplierModal input[name="address"]').val('')
			if(field_method.length){
				field_method.remove()
			}
		
		// $('#supplierModal').modal('show')
	}
	$('#supplierModal').modal('show')

  })
</script>