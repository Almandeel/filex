<div class="modal fade" id="settings" tabindex="-1" role="dialog" aria-labelledby="settingsLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingsLabel">الاعدادات</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_settings" action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>الاسم</label>
                  <input  class="form-control name" autocomplete="off" type="text" name="name" placeholder="الاسم">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>القيمة</label>
                  <input  class="form-control" autocomplete="off" type="text" name="value" placeholder="القيمة">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label class="btn btn-primary form-control">
                    صورة
                    <input style="display: none" class="form-control" autocomplete="off" name="image" type="file" >
                  </label>
                </div>
              </div>
              
              </div>
            </div>
              
          
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
              <button type="submit" class="btn btn-primary">تعديل</button>
          </div>
      </form>
    </div>
  </div>
</div>




<script>

$('.settings').click(function() {
		if($(this).hasClass("update")){
        $('#form_settings').attr('action', $(this).data('action'))
        $('#form_settings').append('<input type="hidden" name="_method" value="PUT">')

        $('.modal-title').text('الاعدادت')

        //set data to inputs
        $('#settings input[name="name"]').val($(this).data('name'))
        $('#settings input[name="value"]').val($(this).data('value'))



        
        // console.log(...$(this).data('units'))
        
        $('#settings').modal('show')
    }else {
      
    }
})

</script>