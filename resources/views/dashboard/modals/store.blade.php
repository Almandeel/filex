<div class="modal fade" id="storeModal" tabindex="-1" role="dialog" aria-labelledby="storesLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="storesLabel">إضافة مخزن</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_stores" action="{{ route('stores.store') }}" method="POST">
          @csrf
          <div class="modal-body">
              <div class="form-group">
                  <label>الاسم</label>
                  <input  class="form-control name" autocomplete="off" type="text" name="name" placeholder="الاسم">
              </div>
              <div class="form-group">
                  <label>
					          <input  type="checkbox" class="flat-green" name="user_id" value="{{ auth()->user()->id }}" checked>
					          <span>اضفني لمستخدمي المخزن</span>
				          </label>
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

$('.showStoreModal').click(function() {
    if($(this).hasClass("update")) {
      $('#form_stores').attr('action', $(this).data('action'))
      $('#form_stores').append('<input type="hidden" name="_method" value="PUT">')
      $('.modal-title').text('تعديل مخزن')


      //set data to inputs
      $('#storeModal input[name="name"]').val($(this).data('name'))
      $('#storeModal input[name="phone"]').val($(this).data('phone'))
      $('#storeModal input[name="address"]').val($(this).data('address'))
      
      
    }else {
      $('#form_stores').attr('action', "{{ route('stores.store') }}")
      $('.modal-title').text('إضافة مخزن')
      
      
      //delete data from inputs
      $('#storeModal input[name="name"]').val('')
      $('#storeModal input[name="phone"]').val('')
      $('#storeModal input[name="address"]').val('')
    }
    $('#storeModal').modal('show')
})
</script>