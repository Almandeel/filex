<div class="modal fade" id="unitModal" tabindex="-1" role="dialog" aria-labelledby="unitsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="unitsLabel">إضافة وحدة</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form_units" action="{{ route('units.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>الاسم</label>
                    <input  class="form-control name" autocomplete="off" type="text" name="name" placeholder="الاسم">
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
  $('.showUnitModal').click(function() {
    if($(this).hasClass("update")){
      $('#form_units').attr('action', $(this).data('action'))
      $('#form_units').append('<input type="hidden" name="_method" value="PUT">')

      //set fields data
      $('#unitModal input[name="name"]').val($(this).data('name'))
    }
    else{
      $('#form_units').attr('action', "{{ route('units.store') }}")
      $('#form_units').remove('input[name="_method"]')

      //Clear from fields
      $('#unitModal input[name="name"]').val('')
    }
    
    $('#unitModal').modal('show')
  })
</script>