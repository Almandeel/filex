<div class="modal fade" id="plan" tabindex="-1" role="dialog" aria-labelledby="planLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="planLabel">إضافة باقة</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_plan" action="{{ route('plans.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">

              <div class="col-md-12">
                <div class="form-group">
                  <label for="stores">الاسم</label>
                  <input type="text" name="name" class="form-control" placeholder="الاسم" required>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label for="stores">القيمة</label>
                  <input type="text" name="amount" class="form-control" placeholder="القيمة" required>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label for="stores">المدة</label>
                  <input type="text" name="period" class="form-control" placeholder="المدة" required>
                </div>
              </div>

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

$('.plan').click(function() {
		if($(this).hasClass("update")){
        $('#form_plan').attr('action', $(this).data('action'))
        $('#form_plan').append('<input type="hidden" name="_method" value="PUT">')

        $('.modal-title').text('تعديل باقة')

        //set data to inputs
        $('#form_plan input[name="name"]').val($(this).data('name'))
        $('#form_plan input[name="amount"]').val($(this).data('amount'))
        $('#form_plan input[name="period"]').val($(this).data('period'))

        
        $('#items').modal('show')
    }else {
          $('#form_plan').attr('action', "{{ route('plans.store') }}")
          $('.modal-title').text('إضافة باقة')

          $('#form_plan input[name="name"]').val('')
          $('#form_plan input[name="amount"]').val('')
          $('#form_plan input[name="period"]').val('')

    }
})


</script>