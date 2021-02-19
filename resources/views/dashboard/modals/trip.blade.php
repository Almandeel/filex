<div class="modal fade" id="tripModal" tabindex="-1" role="dialog" aria-labelledby="tripsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left" id="tripModalLabel">إضافة رحلة</h5>
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" action="{{ route('trips.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label> من مدينة</label>
                        <select id="from" name="from" class="from-control">
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label> الى مدينة</label>
                        <select id="to" name="to" class="from-control">
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label> المركبة</label>
                        <select id="car" name="car_id" class="from-control">
                            @foreach($cars as $car)
                                <option value="{{ $car->id }}">{{ $car->car_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>التكلفة</label>
                        <input class="form-control" autocomplete="off" type="text" name="amount" placeholder="التكلفة">
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
                            <th>الرصيد</th>
                            <td class="balance"></td>
                        </tr>
                        <tr>
                            <th>رقم الهاتف</th>
                            <td class="phone"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    @permission('trips-delete')
                        <form action="" method="post" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-times"></i>
                                <span>حذف</span>
                            </button>
                        </form>
                    @endpermission
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                </div>
            </section>
        </div>
    </div>
</div>


<script>
    $('.showtripModal').click(function () {
        if ($(this).hasClass("update")) {
            $('#carLabel').text('تعديل بيانات رحلة: ')
            $('#tripModal .form').attr('action', $(this).data('action'))
            $('#tripModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //set fields data
            $('#tripModal input[name="amount"]').val($(this).data('amount'))


            // from state
            let selectedFromIds = $(this).data('from')
            let fromList = $('#tripModal select[id="from"] option');
            fromList.each(function () {
                if ($(this).val() == selectedFromIds) {
                    $(this).attr('selected', true).trigger("change")
                }
            })

            // to state
            let selectedToIds = $(this).data('to')
            let toList = $('#tripModal select[id="to"] option');
            toList.each(function () {
                if ($(this).val() == selectedToIds) {
                    $(this).attr('selected', true).trigger("change")
                }
            })

            // car
            let selectedCarIds = $(this).data('car_id')
            let carList = $('#tripModal select[id="car"] option');
            carList.each(function () {
                if ($(this).val() == selectedCarIds) {
                    $(this).attr('selected', true).trigger("change")
                }
            })

            $('#tripModal .preview').hide()
            $('#tripModal .form').show()
        } else if ($(this).hasClass("preview")) {
            $('#tripModal .preview').show()
            $('#tripModal .form').hide()
            $('#carLabel').text('بيانات رحلة: ')
            $('.id').text($(this).data('id'))
            $('.name').text($(this).data('vin_number'))
            $('.phone').text($(this).data('car_number'))
            $('.address').text($(this).data('empty_weight'))
            $('.address').text($(this).data('max_weight'))
        } else {
            $('#tripModal .preview').hide()
            $('#tripModal .form').show()

            $('#carLabel').text('اضافة موظف')
            $('#tripModal .form').attr('action', "{{ route('trips.store') }}")
            $('#tripModal .form').remove('input[name="_method"]')


            //Clear from fields
            // $('#tripModal input[name="vin_number"]').val('')
            // $('#tripModal input[name="car_number"]').val('')
            // $('#tripModal input[name="empty_weight"]').val('')
            $('#tripModal input[name="amount"]').val('')

        }

        $('#tripModal').modal('show')

    })
</script>