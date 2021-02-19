<div class="modal fade" id="driverModal" tabindex="-1" role="dialog" aria-labelledby="driverLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="driverLabel">إضافة سائق</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('drivers.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>الاسم</label>
                    <input  class="form-control name" autocomplete="off" type="text" name="name" placeholder="الاسم" required/>
                </div>
                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input  class="form-control" autocomplete="off" type="text" name="phone" placeholder="رقم الهاتف" required/>
                </div>
                <div class="form-group">
                    <label> العنوان</label>
                    <input  class="form-control" autocomplete="off" type="text" name="address" placeholder=" العنوان" required/>
				</div>
				<div class="form-group">
                    <label> الرقم الوطني</label>
                    <input  class="form-control" autocomplete="off" type="text" name="uid" placeholder=" الرقم الوطني" required/>
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
						<th>رقم الهاتف</th>
						<td class="phone"></td>
					</tr>
					<tr>
						<th>العنوان</th>
						<td class="address"></td>
					</tr>
					<tr>
						<th>الرقم الوطني</th>
						<td class="uid"></td>
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
	$('.showdriverModal').click(function() {
		if($(this).hasClass("update")){
            $('#driverLabel').text('تعديل بيانات سائق: ' + $(this).data('name'))
            $('#driverModal .form').attr('action', $(this).data('action'))
            $('#driverModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //set fields data
            $('#driverModal input[name="name"]').val($(this).data('name'))
            $('#driverModal input[name="phone"]').val($(this).data('phone'))
			$('#driverModal input[name="address"]').val($(this).data('address'))
			$('#driverModal input[name="uid"]').val($(this).data('uid'))
			
            $('#driverModal .preview').hide()
			$('#driverModal .form').show()
		}
		else if($(this).hasClass("preview")){
			$('#driverModal .preview').show()
			$('#driverModal .form').hide()
			$('#driverLabel').text('بيانات سائق: ' + $(this).data('name'))
			$('.id').text($(this).data('id'))
			$('.name').text($(this).data('name'))
			$('.phone').text($(this).data('phone'))
			$('.address').text($(this).data('address'))
			$('.uid').text($(this).data('uid'))
		}	
		else{
			$('#driverModal .preview').hide()
			$('#driverModal .form').show()

			$('#driverLabel').text('اضافة موظف')
			$('#driverModal .form').attr('action', "{{ route('drivers.store') }}")
			$('#driverModal .form').remove('input[name="_method"]')


            //Clear from fields
            $('#driverModal input[name="name"]').val('')
            $('#driverModal input[name="phone"]').val('')
            $('#driverModal input[name="address"]').val('')
            $('#driverModal input[name="uid"]').val('')
            
		}
		
		$('#driverModal').modal('show')
	})
</script>