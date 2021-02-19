<div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="customersLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left" id="accountModalLabel">تعديل حساب</h5>
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" action="{{ route('accounts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>الرصيد</label>
                        <input class="form-control" autocomplete="off" type="number" name="amount" placeholder="الرصيد" required>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>الى حساب</label>
                        <select name="to_id" class="form-control">
                            @foreach (\App\Account::whereIn('name', ['safe', 'bank'])->get() as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
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
    $('.account').click(function () {
        if ($(this).hasClass("update")) {
            $('#accountModal .form').attr('action', $(this).data('action'))
            $('#accountModal .form').append('<input type="hidden" name="_method" value="PUT">')

            //set data to inputs
            $('#accountModal input[name="amount"]').val($(this).data('amount'))
            $('#accountModal').modal('show')
        }
    })

</script>
