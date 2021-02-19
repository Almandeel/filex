<div class="modal fade" id="carModal" tabindex="-1" role="dialog" aria-labelledby="carLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="carLabel">إضافة مركبة</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('cars.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>رقم الشاصي</label>
                    <input  class="form-control vin_number" autocomplete="off" type="text" name="vin_number" placeholder="رقم الشاصي" required/>
                </div>
                <div class="form-group">
                    <label>رقم اللوحة</label>
                    <input  class="form-control" autocomplete="off" type="text" name="car_number" placeholder="رقم اللوحة" required/>
                </div>
                <div class="form-group">
                    <label>وزن العربة فارغة</label>
                    <input  class="form-control" autocomplete="off" type="text" name="empty_weight" placeholder="وزن العربة فارغة" required/>
				</div>
				<div class="form-group">
                    <label>الوزن الكلى</label>
                    <input  class="form-control" autocomplete="off" type="text" name="max_weight" placeholder="الوزن الكلى" required/>
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
						<th>رقم الشاصي</th>
						<td class="vin_number"></td>
					</tr>
					<tr>
						<th>رقم اللوحة</th>
						<td class="car_number"></td>
					</tr>
					<tr>
						<th>وزن العربة فارغة</th>
						<td class="empty_weight"></td>
					</tr>
					<tr>
						<th>الوزن الكلى</th>
						<td class="max_weight"></td>
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
	$('.showcarModal').click(function() {
		if($(this).hasClass("update")){
            $('#carLabel').text('تعديل بيانات مركبة: ' + $(this).data('name'))
            $('#carModal .form').attr('action', $(this).data('action'))
            $('#carModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //set fields data
            $('#carModal input[name="vin_number"]').val($(this).data('vin_number'))
            $('#carModal input[name="car_number"]').val($(this).data('car_number'))
			$('#carModal input[name="empty_weight"]').val($(this).data('empty_weight'))
			$('#carModal input[name="max_weight"]').val($(this).data('max_weight'))
			
            $('#carModal .preview').hide()
			$('#carModal .form').show()
		}
		else if($(this).hasClass("preview")){
			$('#carModal .preview').show()
			$('#carModal .form').hide()
			$('#carLabel').text('بيانات مركبة: ' + $(this).data('name'))
			$('.id').text($(this).data('id'))
			$('.name').text($(this).data('vin_number'))
			$('.phone').text($(this).data('car_number'))
			$('.address').text($(this).data('empty_weight'))
			$('.address').text($(this).data('max_weight'))
		}	
		else{
			$('#carModal .preview').hide()
			$('#carModal .form').show()

			$('#carLabel').text('اضافة موظف')
			$('#carModal .form').attr('action', "{{ route('cars.store') }}")
			$('#carModal .form').remove('input[name="_method"]')


            //Clear from fields
            $('#carModal input[name="vin_number"]').val('')
            $('#carModal input[name="car_number"]').val('')
            $('#carModal input[name="empty_weight"]').val('')
            $('#carModal input[name="max_weight"]').val('')
            
		}
		
		$('#carModal').modal('show')
	})
</script>