@php
    $year = isset($year) ? $year : date('Y');
    $month = isset($month) ? $month : date('m');
@endphp
<div class="modal fade" id="salaryModal" tabindex="-1" role="dialog" aria-labelledby="salariesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pull-left" id="salariesLabel">المرتب</h5>
          <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form_salaries" action="" method="GET" class="form-inline">
            @csrf
            <div class="modal-body">
                <div class="input-group">
                    <div class="input-group-addon">
                        السنة
                    </div>
                    <select class="form-control" name="year">
                        @for($i = date('Y'); $i >= 2000; $i--)
                            <option value="{{ $i }}" @if($year == $i) selected @endif>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="input-group">
                    <div class="input-group-addon">
                        الشهر
                    </div>
                    <select class="form-control" name="month">
                        @for($i = 1; $i <= 12; $i++)
                            @php
                                $m = $i < 10 ? '0' + $i : $i;
                            @endphp
                            <option value="{{ $m }}" @if($month == $m) selected @endif>{{ $m }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="employee_id" value="">
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
      </div>
    </div>
  </div>

<script>
	$('.showSalaryModal').click(function() {
        $('#form_salaries').attr('action', $(this).data('action'))
		$('#salaryModal input[name="employee_id"]').val($(this).data('employee-id'))
		$('#salaryModal').modal('show')
	})
</script>