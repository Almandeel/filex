@permission('attachments-create')
    @php
        $component = isset($component) ? $component : 'all';
        $attachments_title = ($component == 'all') ? __('accounting::global.files_notes') : (($component == 'notes') ? __('accounting::global.notes') : __('accounting::global.files') );
    @endphp
    <table class="table table-bordered table-attachments">
        <thead>
            @isset($noTitle)
            <tr>
                <th colspan="4" class="text-primary">
                    <i class="fas fa-paperclip"></i>
                    <span>@lang('accounting::global.attachments')</span>
                </th>
            </tr>
            @endisset
            <tr>
                <th style="width: 50px">#</th>
                <th colspan="2">{{ $attachments_title }}</th>
                <th style="width: 50px">@lang('accounting::global.options')</th>
            </tr>
        </thead>

        <tbody></tbody>
        <tfoot>
            <tr>
                <th colspan="4">
                    <div class="btn-group text-center">
                        @permission('attachments-create')
                        @if ($component == 'all' || $component == 'files')
                            <button type="button" class="btn btn-default btn-sm btn-add" data-component="file">
                                <i class="fa fa-file"></i>
                                <span class="d-none d-md-inline">@lang('accounting::global.add_file')</span>
                            </button>
                        @endif
                        @if ($component == 'all' || $component == 'notes')
                            <button type="button" class="btn btn-success btn-sm btn-add" data-component="note">
                                <i class="fa fa-sticky-note"></i>
                                <span class="d-none d-md-inline">@lang('accounting::global.add_note')</span>
                            </button>
                        @endif
                        @endpermission
                    </div>
                </th>
            </tr>
        </tfoot>
    </table>

    @push('foot')
        <script>
            $(function(){
                let component = '{{ $component }}';
                setAttachmentsCounter($('.table-attachments'));
                $('.table-attachments .btn-add').on('click', function(event){
                    // if(!event.detail || event.detail == 1){}
                    let tbody = $(this).closest('table').children('tbody');
                    let row = ``;
                    if($(this).data('component') == 'note'){
                        row = `<tr>
                            <td>`+(tbody.children().length + 1)+`</td>
                            <td colspan="2">
                                <textarea type="text" class="form-control attachment-name" placeholder="@lang('accounting::global.note')" name="attachments_names[]" required></textarea>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs btn-remove">
                                    <i class="fa fa-trash"></i>
                                    <span class="d-none d-md-inline">@lang('accounting::global.delete')</span>
                                </button>
                            </td>
                        </tr>
                        `;
                    }else{
                        row = `<tr>
                            <td>`+(tbody.children().length + 1)+`</td>
                            <td>
                                <input type="text" class="form-control attachment-name" placeholder="@lang('accounting::global.name')" name="attachments_names[]" required>
                            </td>
                            <td>
                                <input type="file" class="form-control attachment-file" placeholder="Name" name="attachments_file`+(tbody.children().length + 1)+`" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs btn-remove">
                                    <i class="fa fa-trash"></i>
                                    <span class="d-none d-md-inline">@lang('accounting::global.delete')</span>
                                </button>
                            </td>
                        </tr>
                        `;
                    }
                    
                    tbody.append(row);
                })
                $('.table-attachments').on('click', '.btn-remove', function(){
                    if(confirm("@lang('accounting::global.delete')")){
                        let table = $(this).parent().parent().parent().parent();
                        $(this).parent().parent().remove()
                        setAttachmentsCounter(table)
                    }
                })
            })
            function setAttachmentsCounter(table){
                let rows = table.children('tbody').find('tr');
                for( let i = 0; i < rows.length; i++){ 
                    $(rows[i]).children('td:nth-child(1)').text(i+1) 
                    $(rows[i]).find('input[type=file]').attr('name', 'attachments_file' + (i + 1)) 
                } 
            }
        </script>
    @endpush
@endpermission