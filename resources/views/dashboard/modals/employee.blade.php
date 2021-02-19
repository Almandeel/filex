<div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="employeesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="employeesLabel">إضافة موظف</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="form" action="{{ route('employees.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>الاسم</label>
                    <input  class="form-control name" autocomplete="off" type="text" name="name" placeholder="الاسم" required/>
                </div>
                <div class="form-group">
                    <label>المرتب</label>
                    <input  class="form-control" autocomplete="off" type="number" name="salary" placeholder="المرتب" required/>
                </div>
                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input  class="form-control" autocomplete="off" type="text" name="phone" placeholder="رقم الهاتف" required/>
                </div>
                <div class="form-group">
                    <label> العنوان</label>
                    <input  class="form-control" autocomplete="off" type="text" name="address" placeholder="رقم العنوان" required/>
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
						<th>المرتب</th>
						<td class="salary"></td>
					</tr>
					<tr>
						<th>رقم الهاتف</th>
						<td class="phone"></td>
					</tr>
					<tr>
						<th>العنوان</th>
						<td class="address"></td>
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
	$('.showEmployeeModal').click(function() {
		if($(this).hasClass("update")){
            $('#employeesLabel').text('تعديل بيانات الموظف: ' + $(this).data('name'))
            $('#employeeModal .form').attr('action', $(this).data('action'))
            $('#employeeModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //set fields data
            $('#employeeModal input[name="name"]').val($(this).data('name'))
            $('#employeeModal input[name="salary"]').val($(this).data('salary'))
            $('#employeeModal input[name="phone"]').val($(this).data('phone'))
			$('#employeeModal input[name="address"]').val($(this).data('address'))
			
            $('#employeeModal .preview').hide()
			$('#employeeModal .form').show()
		}
		else if($(this).hasClass("preview")){
			$('#employeeModal .preview').show()
			$('#employeeModal .form').hide()
			$('#employeesLabel').text('بيانات الموظف: ' + $(this).data('name'))
			$('.id').text($(this).data('id'))
			$('.name').text($(this).data('name'))
			$('.salary').text($(this).data('salary'))
			$('.phone').text($(this).data('phone'))
			$('.address').text($(this).data('address'))
		}	
		else{
			$('#employeeModal .preview').hide()
			$('#employeeModal .form').show()

			$('#employeesLabel').text('اضافة موظف')
			$('#employeeModal .form').attr('action', "{{ route('employees.store') }}")
			$('#employeeModal .form').remove('input[name="_method"]')


            //Clear from fields
            $('#employeeModal input[name="name"]').val('')
            $('#employeeModal input[name="salary"]').val('')
            $('#employeeModal input[name="phone"]').val('')
            $('#employeeModal input[name="address"]').val('')
            
		}
		
		$('#employeeModal').modal('show')
	})
</script>