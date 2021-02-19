/**
 * Submit Modal Scripts
 */

 $(function () {
     $('.btn-show-submit-modal').click(function () {
         let tab = $(this).data('tab');
         if (tab) {
             $('#submitModal .nav-tabs a[href="' + tab + '"]').tab('show')
             resetTabs(tab)
         }
         $('#submitModal').modal('show')
     })
     $('#submitModal .nav-tabs .nav-link').click(function () {
         resetTabs($(this).attr('href'))

     })
     $("#submitModal").on("hidden.bs.modal", function () {
         resetTabs();
     });
     $(document).on('change', 'input.table-id', function (e) {
         e.preventDefault()
         $('label.table').removeClass('active')
         $(this).closest('label.table').addClass('active')
         $('#table_id').val($(this).val())

         $('#tables-wrapper').fadeOut()
         $('#tables-order-details').fadeIn()
     })

     $(document).on('click', '#tables-btn-wrapper .btn', function (e) {
         e.preventDefault()
         $('#tables-order-details').fadeOut()
         $('#tables-wrapper').fadeIn()
         resetTabs()
     })
 })

 function resetTabs(tab = null) {
     $('select#table_id').val($($('select#table_id').children('option:first')).val())
     $('select#waiter_id').val($($('select#waiter_id').children('option:first')).val())
     $('label.table').removeClass('active')
     $('label.table input[type=radio]').prop('checked', false)
     $('#tables-order-details').hide()
     $('#tables-wrapper').show()

     $('#delivery_name').val('')
     $('#delivery_phone').val('')
     $('#delivery_address').val('')
     $('select#driver_id').val($($('select#driver_id').children('option:first')).val())

     if (tab == '#deliveryTab') {
         $('#delivery_name').attr('required', true)
         $('#delivery_phone').attr('required', true)
         $('#delivery_address').attr('required', true)
         $('select#driver_id').attr('required', true)

         $('select#table_id').removeAttr('required')
         $('select#waiter_id').removeAttr('required')
     } else if (tab == '#tablesTab') {
         $('select#table_id').attr('required', true)
         $('select#waiter_id').attr('required', true)

         $('select#driver_id').removeAttr('required')
         $('#delivery_name').removeAttr('required')
         $('#delivery_phone').removeAttr('required')
         $('#delivery_address').removeAttr('required')
     } else {
         $('select#table_id').removeAttr('required')
         $('select#waiter_id').removeAttr('required')
         $('select#driver_id').removeAttr('required')
         $('#delivery_name').removeAttr('required')
         $('#delivery_phone').removeAttr('required')
         $('#delivery_address').removeAttr('required')
     }
 }

 function setTables(tables) {
     let wrapper = $('#tables-wrapper');
     let tables = ``;
     let table_html = ``;
    for (let index = 0; index < tables.length; index++) {
        const table = tables[index];
        table_html = `
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 text-center" style="margin: 15px 0;">
                <label class="table">
                    <span class="table-counter">`+ table.number + `</span>
                    <input type="radio" name="table" class="table-id" value="` + table.id + `">
                </label>
            </div>
        `;
        tables += table_html;
    }

     wrapper.html(tables)
 }