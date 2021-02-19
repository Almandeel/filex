<div class="modal fade" id="items" tabindex="-1" role="dialog" aria-labelledby="itemsLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="itemsLabel">إضافة منتج</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_items" action="{{ route('items.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
				<div class="form-group col-xs-12">
                  <label>الاسم</label>
                  <input  class="form-control name" autocomplete="off" type="text" name="name" placeholder="الاسم">
                </div>
                <div class="form-group col-xs-12">
					<label>الصورة</label>
					<div class="image-wrapper">
						<div class="image-previewer"></div>
						<label class="btn btn-primary">
							<i class="fa fa-image"></i>
							<span>إختر الصورة</span>
							<input type="file" name="image"/>
						</label>
					</div>
              	</div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="stores">المخازن</label>
                  <select class="select2 form-control" name="stores[]" id="stores" multiple>
                    @foreach($stores as $store)
                      <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-12">
                <label>الوحدة</label>
                <div class="row">
                  @foreach($units as $unit)
                    <div class="col-md-3">
                      <div class="form-check">
                        <div class="units">
                          <input class="form-check-input flat-red {{ $unit->id }}"   type="checkbox" name="units[]" value="{{ $unit->id }}">
                          <label class="form-check-label" for="{{ $unit->id }}">{{ $unit->name }}</label>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
                <div id="newUnits">
                  <div class="row">

                  </div>
              </div>
                <button style="margin: 20px 0;" type="button" class="btn btn-primary" id="addunits">إضافة وحدة جديدة</button>
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

$('.showItemModal').click(function() {
		if($(this).hasClass("update")){
        $('#form_items').attr('action', $(this).data('action'))
        $('#form_items').append('<input type="hidden" name="_method" value="PUT">')

        $('.modal-title').text('تعديل منتج')

        //set data to inputs
        $('#items input[name="name"]').val($(this).data('name'))

        let selectedStoresIds = $(this).data('stores')
        
        let storesList = $('#items select[id="stores"] option');

        storesList.each(function() {
          for (let selectedId of selectedStoresIds) {
            if ($(this).val() == selectedId) {
              $(this).attr('selected', true)
            }
          }
        })

        let unitsId = $(this).data('units');

        unitsId.forEach(id => {
          if($('.units input').attr('id', id)) {
            $('.units .'+id).iCheck('check');
          }
        });

        
        // console.log(...$(this).data('units'))
        
        $('#items').modal('show')
    }else {
          $('#form_items').attr('action', $('#create').data('action'))
          $('.modal-title').text('إضافة منتج')


          $('input[type="checkbox"]').iCheck('uncheck');
          
          //delete data from inputs
          $('#items input[name="name"]').val('')
    }
})



$('#addunits').click(function () {
  let input = 
  `
    <div class="col-md-6">
      <input style="margin: 15px 0;" class="form-control" type="text" name="newUnits[]" placeholder="اسم الوحدة">
    </div>
  `

  $('#newUnits .row').append(input);
})

</script>