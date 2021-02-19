<table class="table table-striped table-bordered table-sortable components-form-payments">
    <thead>
        <tr>
            <th>#</th>
            <th>الخزنة</th>
            <th>المبلغ</th>
            <th class="options">الخيارات</th>
        </tr>
    </thead>
    @if (isset($payments))
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $payment->safe->name }}</td>
                    <td>
                        <input type="hidden" class="payment-safe" name="payments_safes[]" value="{{ $payment->safe->id }}">
                        <input type="number" name="payments_amounts[]" class="form-control input-payment" min="1" @if(isset($pay)) max='{{ $payment->safe->balance()+$payment->amount }}' @endif data-safe="{{ $payment->safe->id }}" value="{{ $payment->amount }}">
                        {{-- <input type="number" name="payments_amounts[]" class="form-control input-payment" min="1" value="{{ $payment->amount }}"> --}}
                    </td>
                    <td class="options">
                        <button type="button" class="btn btn-danger btn-sm payments-button-remove"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    @else
        <tbody></tbody>
    @endif
    <tfoot>
        <th colspan="2">
            <span>اجمالي المدفوعات</span>
            (<strong class="payments-total">0</strong>)
        </th>
        <th colspan="2" class="text-left">
            <div class="form-inline">
                <div class="input-group">
                    <span class="input-group-addon">اضافة</span>
                    <select class="payments-select-safes form-control">
                        @foreach ($safes as $safe)
                            @if (isset($pay))
                                @if ($safe->balance() > 0)
    								<option value="{{ $safe->id }}" data-balance="{{ $safe->balance() }}" data-id="{{ $safe->id }}" data-name="{{ $safe->name }}">{{ $safe->name }}</option>
                                @endif
                            @else
								<option value="{{ $safe->id }}" data-balance="{{ $safe->balance() }}" data-id="{{ $safe->id }}" data-name="{{ $safe->name }}">{{ $safe->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-primary payments-button-add"><i class="fa fa-plus"></i></button>
                    </span>
                </div>
            </div>
        </th>
    </tfoot>
</table>
<script>
    function setPaymentsCounter(){
        for( let i = 0; i < $('.components-form-payments tbody tr').length; i++){
            $($('.components-form-payments tbody tr')[i]).children('td:nth-child(1)').text(i+1)
        }
    }
    function setPaymentsTotals(){
        payments = 0;
        for( let i = 0; i < $('.components-form-payments tbody tr').length; i++){
            let payment = $($('.components-form-payments tbody tr')[i]).find('input.input-payment');
            {{--  .children('input:nth-child(2)')  --}}
            payments += Number(payment.val())
        }
        $('.components-form-payments .payments-total').text(payments)
    }
    $(function(){
        let is_pay = false;
        @isset($pay)
            is_pay = true;
        @endisset
        setPaymentsTotals()
        $(".components-form-payments").on('change, keyup', 'input.input-payment', function(){
            setPaymentsTotals()
        })
        $(".components-form-payments tbody" ).sortable({
            stop: function(){
                setPaymentsTotals()
            }
        });
        let component = $('.components-form-payments');
        let body = $('.components-form-payments tbody');
        $('.components-form-payments .payments-button-add').click(function(){
            let value = 0;
            @isset($remain)
                let remain = $('{{ $remain }}');
                value = Number(remain.val());
            @endisset
            if(typeof remain !== 'undefined' && value <= 0){
                alert('لا توجد مدفوعات متبقية')
            }else{
                let component = $(this).closest('table');
                let body = component.children('tbody');
                let safe = component.children('tfoot').find('.payments-select-safes option:selected');
                let safe_id = safe.data('id');
                let safe_name = safe.data('name');
                let safe_balance = Number(safe.data('balance'));
                value = (value > safe_balance && is_pay) ? safe_balance : value ;
                if(is_pay && safe_balance <= 0){
                    alert('لا يوجد رصيد في الخزنه')
                }else{
                    let row = `
                        <tr>
                            <td>`+(body.children().length + 1)+`</td>
                            <td>`+ safe_name +`</td>
                            <td>
                                <input type="hidden" class="payment-safe" name="payments_safes[]" value="`+safe_id+`">
                                <input type="number" name="payments_amounts[]" class="form-control input-payment" min="1" {{ isset($pay) ? ' max=`+safe_balance+` ' : '' }} data-safe="`+safe_id+`" value="` + value +`">
                            </td>
                            <td class="options">
                                <button type="button" class="btn btn-danger btn-sm payments-button-remove"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    `
                    body.append(row)
                }
                if(is_pay){
                    let safe_remain = safe_balance - value;
                    safe.data('balance', safe_remain)
                }
            }
            @isset($addCallback)
                executeFunctionByName('{{ $addCallback }}', window)
            @endisset
        })

        component.on('click', '.payments-button-remove', function(){
            let row = $(this).closest('tr');
            let total_col = $(this).closest('table').find('.payments-total');
            let total_payments = Number(total_col.text());
            let payment_input = row.find('input.input-payment');
            let payment = Number(payment_input.val());
            let safe_id = payment_input.data('safe');
            let safe = row.closest('table').find('.payments-select-safes option[value='+safe_id+']')
            total_payments -= payment;
            if(is_pay){
                safe_balance = Number(safe.data('balance'))
                safe.data('balance', safe_balance + payment)
            }
            row.remove()
            setPaymentsCounter();
            total_col.text(total_payments)
            @if (isset($removeCallback))
                executeFunctionByName('{{ $removeCallback }}', window)
            @endif
        })
    })
</script>