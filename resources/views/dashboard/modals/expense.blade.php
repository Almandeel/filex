<div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="expenseLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left" id="expenseLabel">إضافة منصرف</h5>
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" action="{{ route('expensestrips.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="trips">الرحلة</label>
                            <select class="select2 form-control" name="trip_id" id="trips" >
                                @foreach($trips as $trip)
                                    <option value="{{ $trip->id }}">{{ $trip->id }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>المبلغ</label>
                        <input class="form-control amount" autocomplete="off" type="number" name="amount"
                            placeholder="المبلغ" required />
                    </div>
                    <div class="form-group">
                        <label>نوع المنصرف</label>
                        <input class="form-control" autocomplete="off" type="text" name="expense_type"
                            placeholder="نوع المنصرف" required />
                    </div>
                    <div class="form-group">
                        <label>ملاحظات</label>
                        <textarea class="form-control" autocomplete="off" name="notes"
                            placeholder="ملاحظات" required ></textarea>
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
    $('.showexpenseModal').click(function () {
        if ($(this).hasClass("update")) {
            $('#expenseLabel').text('تعديل بيانات منصرف: ' + $(this).data('name'))
            $('#expenseModal .form').attr('action', $(this).data('action'))
            $('#expenseModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //set fields data
            $('#expenseModal input[name="amount"]').val($(this).data('amount'))
            $('#expenseModal input[name="expense_type"]').val($(this).data('expense_type'))
            $('#expenseModal textarea[name="notes"]').val($(this).data('notes'))

            // to state
            let selectedTripIds = $(this).data('trip_id')
            let tripsList = $('#tripModal select[id="trips"] option');
            tripsList.each(function () {
                if ($(this).val() == selectedTripIds) {
                    $(this).attr('selected', true).trigger("change")
                }
            })

            $('#expenseModal .preview').hide()
            $('#expenseModal .form').show()
        }else {
            $('#expenseModal .preview').hide()
            $('#expenseModal .form').show()

            $('#expenseLabel').text('اضافة منصرف')
            $('#expenseModal .form').attr('action', "{{ route('expensestrips.store') }}")
            $('#expenseModal .form').remove('input[name="_method"]')


            //Clear from fields
            $('#expenseModal input[name="amount"]').val('')
            $('#expenseModal input[name="expense_type"]').val('')
            $('#expenseModal input[name="notes"]').val('')

        }

        $('#expenseModal').modal('show')
    })
</script>