
<div class="modal fade" id="subscription" tabindex="-1" role="dialog" aria-labelledby="subscriptionLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="subscriptionLabel">إضافة اشتراك</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_subscription" action="{{ route('subscriptions.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div id="content-subscription">
                <div class="col-md-12">
                  <div class="form-group" id="customers">
                    <label for="stores">العميل</label>
                    <select id="customer_id" onchange="counter()"  required class="select2 form-control" name="customer_id" data-parsley-excluded="true">
                      @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group plans">
                    <label for="stores">الباقة</label>
                    <select required class="select2 form-control" name="plan_id" data-parsley-excluded="true">
                      <option value="">اختار الباقة</option>
                      @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="stores">طريقة الدفع</label>
                  <div class="row">
                    @foreach(Modules\Subscription\Models\Subscription::PAYMENTS as $key=>$value)
                        <div class="col-md-6">
                          <label>
                            <input type="radio" name="payment_type" value="{{ $key }}"> {{ $value }}
                          </label>
                        </div>
                    @endforeach
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group " id="count">
                  <label for="stores">العدد</label>
                  <input class="virtual-keyboard-demo " onfocus="KioskBoard.Merge({theme:'light'});" data-kioskboard-type="numpad"
                  data-kioskboard-specialcharacters="true" type="number" name="count" placeholder="العدد">
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




@include('partials._keybord')

<style>
  #KioskBoard-VirtualKeyboard {
    width: 27% !important;
    right: 72%
  }
</style>

<script>

  function counter() {
    if($('#customer_id').val() == 100) {
      $('#subscription #count').css('display', 'block')
    }else {
      $('#subscription #count').css('display', 'none')
    }
  }


  $(function () {
    counter()
  })

$('.subscription').click(function() {
      $('#form_subscription input[class="refresh"]').remove()

		if($(this).hasClass("update")){
        $('#form_subscription').attr('action', $(this).data('action'))
        $('#form_subscription').append('<input type="hidden" name="_method" value="PUT">')
        $('#content-subscription').css('display', 'block')
        $('#content-subscription #customers').css('display', 'none')

        $('#form_subscription input[name="customer_id]').remove();
        $('#form_subscription').append(`<input type="hidden" class="refresh" name="customer_id" value="${$(this).data('customer')}" />`);


        $('.modal-title').text('تعديل اشتراك')

        //set data to inputs
        let customer_id = $(this).data('customer')
        let plan_id = $(this).data('plan')
        let payment_type = $(this).data('payment')
        
        let customersList   = $('#form_subscription select[name="customer_id"] option');
        let plansList       = $('#form_subscription select[name="plan_id"] option');
        let paymentList     = $('#form_subscription select[name="payment_type"] option');

        // customersList.each(function() {
        //     if ($(this).val() == customer_id) {
        //       $(this).attr('selected', true)
        //     }
        // })

        plansList.each(function() {
            if ($(this).val() == plan_id) {
              $(this).attr('selected', true)
            }
        })

        paymentList.each(function() {
            if($(this).val() == payment_type) {
              console.log(payment_type);
              $(this).attr('selected', true)
            }
        })
        
        $('#items').modal('show')
    }else if($(this).hasClass("refresh")) {
      $('#form_subscription').attr('action', $(this).data('action'))
      $('#form_subscription').append('<input type="hidden" name="_method" value="PUT">')
      $('#form_subscription input[name="customer_id]').remove();

      $('.modal-title').text('اعادة اشتراك')
      $('#content-subscription').css('display', 'none')

      $('#form_subscription').append(`<input type="hidden" class="refresh" name="customer_id" value="${$(this).data('customer')}" />`);
      $('#form_subscription').append(`<input type="hidden" class="refresh" name="plan_id" value="${$(this).data('plan')}" />`);
      $('#form_subscription').append(`<input type="hidden" class="refresh" name="id" value="${$(this).data('id')}" />`);
    }else {
          $('#form_subscription').attr('action', "{{ route('subscriptions.store') }}")
          $('.modal-title').text('إضافة اشتراك')
          $('#form_subscription input[name="customer_id]').remove();
          $('#content-subscription #customers').css('display', 'block')

          if($(this).hasClass("no-plan")) {
            $('#content-subscription .plans').css('display', 'none')
            $('#form_subscription').append(`<input type="hidden" name="plan_id" value="${$(this).data('plan')}">`)
          }


          $('#content-subscription').css('display', 'block')

          $('#form_subscription select[name="customer_id"]').val('')
          $('#form_subscription select[name="plan_id"]').val('')
          $('#form_subscription select[name="payment_type"]').val('')

          //delete data from inputs
          $('#items input[name="name"]').val('')
    }
})


</script>