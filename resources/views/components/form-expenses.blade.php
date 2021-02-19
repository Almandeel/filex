<table class="table table-striped table-bordered table-sortable components-form-expenses">
    <thead>
        <tr>
            <th>#</th>
            <th>الخزنة</th>
            <th>المبلغ</th>
            <th class="options">الخيارات</th>
        </tr>
    </thead>
    @if (isset($expenses))
        <tbody>
            @foreach ($expenses as $expense)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $expense->safe->name }}</td>
                    <td>
                        <input type="hidden" class="expense-safe" name="expenses_safes[]" value="{{ $expense->safe->id }}">
                        <input type="number" name="expenses_amounts[]" class="form-control input-expense" min="1" value="{{ $expense->amount }}">
                    </td>
                    <td class="options">
                        <button type="button" class="btn btn-danger btn-sm expenses-button-remove"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    @else
        <tbody></tbody>
    @endif
    <tfoot>
        <tr>
            <th colspan="2">
                <span>اجمالي المنصرفات</span>
                (<strong class="expenses-total">0</strong>)
            </th>
            <th colspan="2" class="text-left">
                <div class="form-inline">
                    <div class="input-group">
                        <span class="input-group-addon">اضافة</span>
                        <select class="expenses-select-safes form-control">
                            @foreach ($safes as $safe)
                                @if ($safe->balance() > 0)
                                    <option value="{{ $safe->id }}" data-balance="{{ $safe->balance() }}" data-id="{{ $safe->id }}" data-name="{{ $safe->name }}">{{ $safe->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary expenses-button-add"><i class="fa fa-plus"></i></button>
                        </span>
                    </div>
                </div>
            </th>
        </tr>
    </tfoot>
</table>
<script>
    function setExpensesCounter(){
        for( let i = 0; i < $('.components-form-expenses tbody tr').length; i++){
            $($('.components-form-expenses tbody tr')[i]).children('td:nth-child(1)').text(i+1)
        }
    }
    function setExpensesTotals(){
        expenses = 0;
        for( let i = 0; i < $('.components-form-expenses tbody tr').length; i++){
            let expense = $($('.components-form-expenses tbody tr')[i]).find('input.input-expense');
            {{--  .children('input:nth-child(2)')  --}}
            expenses += Number(expense.val())
        }
        $('.components-form-expenses .expenses-total').text(expenses)
    }
    $(function(){
        setExpensesTotals()
        $(".components-form-expenses").on('change, keyup', 'input.input-expense', function(){
            setExpensesTotals()
        })
        $(".components-form-expenses tbody" ).sortable({
            stop: function(){
                setExpensesCounter()
            }
        });
        let component = $('.components-form-expenses');
        let body = $('.components-form-expenses tbody');
        $('.components-form-expenses .expenses-button-add').click(function(){
            let inputs = $('{{ $receiveInputs }}');
            let expensable = inputs.length > 0;
            $('input.input-receive').each(function(index, input){
                expensable = Number($(input).val()) > 0;
                return expensable;
                
            })
            
            if(expensable){
                let component = $(this).closest('table');
                let body = component.children('tbody');
                let option = component.find('.expenses-select-safes option:selected');
                let safe_id = option.data('id');
                let safe_name = option.data('name');
                let safe_balance = option.data('balance');
                let row = `
                    <tr>
                        <td>`+(body.children().length + 1)+`</td>
                        <td>`+ safe_name +`</td>
                        <td>
                            <input type="hidden" class="expense-safe" name="expenses_safes[]" value="`+safe_id+`">
                            <input type="number" name="expenses_amounts[]" class="form-control input-expense" min="1" max=`+safe_balance+` value="0" data-safe="`+safe_id+`">
                        </td>
                        <td class="options">
                            <button type="button" class="btn btn-danger btn-sm expenses-button-remove"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                `
                body.append(row)
            }else{
                alert('يجب اضافة منتجات مستلمة اولا')
            }
        })

        component.on('click', '.expenses-button-remove', function(){
            let row = $(this).closest('tr');
            let total_col = $(this).closest('table').find('.expenses-total');
            let total_expenses = Number(total_col.text());
            let expense = Number(row.find('input.input-expense').val());
            total_expenses -= expense;
            row.remove()
            setExpensesCounter();
            total_col.text(total_expenses)
            @if (isset($removeCallback))
                executeFunctionByName('{{ $removeCallback }}', window)
            @endif
        })
    })
</script>