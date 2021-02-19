<div class="modal modal-xl fade" id="chequeModal" tabindex="-1" role="dialog" aria-labelledby="chequesLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 75%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="chequesLabel">إضافة شيك مصرفي</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_cheques" action="{{ route('cheques.store') }}" method="POST">
        @csrf
        <div class="modal-body clearfix">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="number">رقم الشيك</label>
                <input id="number" name="number" type="number" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="cheque_type">النوع</label>
                <select id="cheque_type" name="type" class="form-control ">
                  @foreach (\App\Cheque::TYPES as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                @foreach(\App\Cheque::BINIFITS as $key => $value)
                  <label for="{{ $key }}">{{ $value }}</label>
                  <input type="radio" name="benefit" id="{{ $key }}" value='{{
                  $key }}' onchange="showContainer('{{ $key }}')">
                @endforeach
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="hidden containers customersContainer">
                  <label for="customers">قائمة العملاء</label>
                  <select class="form-control select2" name="benefit_id" id="customers">
                    <option value="" selected disabled>قم بإختيار عميل</option>
                    @foreach($customers as $customer)
                      <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="hidden containers suppliersContainer">
                  <label for="suppliers">قائمة الموردين</label>
                  <select class="form-control select2" name="benefit_id" id="suppliers">
                    <option value="" selected disabled>قم بإختيار مرود</option>
                    @foreach($suppliers as $supplier)
                      <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="hidden containers employeesContainer">
                  <label for="employees">قائمة الموظفين</label>
                  <select class="form-control select2" name="benefit_id" id="employees">
                    <option value="" selected disabled>قم بإختيار موظف</option>
                    @foreach($employees as $employee)
                      <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="hidden containers collaboratorsContainer">
                  <label for="collaborators">قائمة المتعاونين</label>
                  <select class="form-control select2" name="benefit_id" id="collaborators">
                    <option value="" selected disabled>قم بإختيار متعاون</option>
                    @foreach($collaborators as $collaborator)
                      <option value="{{ $collaborator->id }}">{{ $collaborator->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-6">
              <label for="amount">القيمة</label>
              <input id="amount" name="amount" type="number" class="form-control" required step="0.1">
            </div>

            <div class="col-md-6">
              <label for="due_date">تاريخ الاستحقاق</label>
              <input id="due_date" name="due_date" type="date" class="form-control" value="{{ now()->format('Y-m-d') }}" required min="{{ now()->format('Y-m-d') }}">
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-6">
				<div class="form-group">
					<label for="account_id">الخزنة</label>
					<select id="account_id" name="account_id" class="form-control select2">
						@php
						$bankSafes = \App\Safe::bankSafes('cheques');
						@endphp
						@if ($bankSafes->count())
						@foreach ($bankSafes as $bs)
						<option value="{{ $bs->id }}" data-balance="{{ $bs->balance() }}">{{ $bs->name }}
							(<strong>{{ $bs->balance() }}</strong>)</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="form-group form-group-bank">
					<label for="bank">إسم البنك</label>
					<input id="bank" name="bank_name" type="text" class="form-control">
				</div>
            </div>
            <div class="col-md-6">
              <label class="col-sm-4 col-md-2 control-label" for="details">التفاصيل</label>
              <textarea id="details" name="details" rows="3" class="form-control input-transparent"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer clearfix">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
          <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $('.showChequeModal').click(function() {
    if($(this).hasClass("update")){
      $('#form_cheques').attr('action', $(this).data('action'))
      $('#form_cheques').append('<input type="hidden" name="_method" value="PUT">')

      let selectedChequeIds = $(this).data('type')
        
      let chequeList = $('#form_cheques select[id="cheque_type"] option');

      // console.log(selectedChequeIds, chequeList)

      chequeList.each(function() {
        
          if ($(this).val() == selectedChequeIds) {
            $(this).attr('selected', true)
          }
        
      })

      //set fields data
      $('#chequeModal input[name="number"]').val($(this).data('number'))
      $('#chequeModal input[name="type"]').val($(this).data('type'))
      $('#chequeModal input[name="benefit_id"]').val($(this).data('benefit_id'))
      $('#chequeModal input[name="benefit"]').val($(this).data('benefit'))
      $('#chequeModal input[name="amount"]').val($(this).data('amount'))
      $('#chequeModal input[name="due_date"]').val($(this).data('due_date'))
      $('#chequeModal input[name="due_date"]').attr('min', $(this).data('due_date'))
      $('#chequeModal input[name="details"]').val($(this).data('details'))
      $('#chequeModal input[name="bank_name"]').val($(this).data('bank'))
    }
    else{
      $('#form_cheques').attr('action', "{{ route('cheques.store') }}")
      $('#form_cheques').remove('input[name="_method"]')

      //Clear from fields
      $('#chequeModal input[name="number"]').val('')
      $('#chequeModal input[name="type"]').val('')
      $('#chequeModal input[name="benefit_id"]').val('')
      $('#chequeModal input[name="benefit"]').val('')
      $('#chequeModal input[name="amount"]').val('')
      $('#chequeModal input[name="due_date"]').val('')
      $('#chequeModal input[name="details"]').val('')
      $('#chequeModal input[name="bank_name"]').val('')
    }
	checkBank()
    $('#chequeModal').modal('show')

    showContainer('Customer')
  })

  function showContainer(benefit) {
    benefit =  benefit.toLowerCase() + 's'

    $('.containers').removeClass('show')
    $('.containers').addClass('hidden')

    $('.' + benefit + 'Container').removeClass('hidden')
    $('.' + benefit + 'Container').addClass('show')

    $('select[name="benefit_id"]').attr('required', false)
    $('select[id="' + benefit + '"]').attr('required', true)

    $('input[name="benefit"]').val(benefit)
  }
  $(function(){
		$('#chequeModal #cheque_type').change(function(){
			$('#chequeModal .form-group-bank input').val('')
			$('#chequeModal .form-group-bank').fadeToggle()
			if($(this).val() == {{ App\Cheque::TYPE_COLLECT }}){
				$('#chequeModal input#amount').removeAttr('max')
				$('#chequeModal input#bank').prop('required', true)
			}else{
				// $('#chequeModal .form-group-bank').fadeIn()
				$('#chequeModal input#bank').removeAttr('required')
				$('#chequeModal input#amount').attr('max', $('#chequeModal #account_id option:selected').data('balance'))
			}
		})

		if($('#chequeModal #cheque_type option:selected').val() == {{ App\Cheque::TYPE_COLLECT }}){
			$('#chequeModal .form-group-bank input').val('')
			$('#chequeModal .form-group-bank').fadeOut()
			$('#chequeModal input#amount').removeAttr('max')
			$('#chequeModal input#bank').prop('required', true)
		}else{
			$('#chequeModal input#amount').attr('max', $('#chequeModal #account_id option:selected').data('balance'))
			$('#chequeModal input#bank').removeAttr('required')
		}

		var account_select = $('#chequeModal select#account_id');
    
		account_select.change(function(){
			var selected = account_select.children('option:selected');
			if($('#chequeModal #cheque_type option:selected').val() == {{ App\Cheque::TYPE_COLLECT }}){
				$('#chequeModal input#amount').attr('max', selected.data('balance'))
			}else{
				$('#chequeModal input#amount').removeAttr('max')
			}
		})
	})

	function checkBank(){
		if($('#chequeModal #cheque_type option:selected').val() == {{ App\Cheque::TYPE_PAY }}){
			$('#chequeModal .form-group-bank input').val('')
			$('#chequeModal .form-group-bank').fadeOut()
		}else{
			$('#chequeModal .form-group-bank').fadeIn()
		}
	}
</script>
