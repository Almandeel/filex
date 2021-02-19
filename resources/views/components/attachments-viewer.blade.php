@permission('attachments-read')
    @php
        $component = isset($component) ? $component : 'all';
        $view = isset($view) ? $view : 'table';
        $attachments_title = ($component == 'all') ? __('accounting::global.files_notes') : (($component == 'notes') ? __('accounting::global.notes') : __('accounting::global.files') );
        $attachments = isset($attachments) ? $attachments : [];
        $attachments = isset($attachable) ? $attachable->attachments : $attachments;
        $attachableId = isset($attachableId) ? $attachableId : null;
        $attachableId = isset($attachable) ? $attachable->id : $attachableId;
        $attachableType = isset($attachableType) ? $attachableType : null;
        $attachableType = isset($attachable) ? get_class($attachable) : $attachableType;
        $attachments_url = auth()->guard('office')->check() ? 'office.attachments' : 'attachments';
    @endphp
    @permission('attachments-read')
    @if ($view == 'timeline')
        <div class="timeline">
            @if (count($attachments))
                @foreach ($attachments->groupBy(function($val) { return \Carbon\Carbon::parse($val->created_at)->format('Y-m-d'); }) as $key => $date_attachment)
                    <div class="time-label">
                        <span class="bg-primary">{{ $key }}</span>
                    </div>
                    @foreach ($date_attachment as $attachment)
                        @if ($attachment->isNote() && ($component == 'all' || $component == 'notes'))
                            <div>
                                <i class="fa fa-sticky-note bg-green"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{ $attachment->created_at->format('h:i') }} @lang('accounting::global.time_modes.' . $attachment->created_at->format('a'))</span>
                                    <h3 class="timeline-header"><span>{{ __('accounting::global.offices.' . $attachment->office) }}: </span><strong>{{ $attachment->auth()->name }}</strong></h3>
                                    <div class="timeline-body">
                                        {!! nl2br(e($attachment->name)) !!}
                                    </div>
                                    @if (auth()->user()->isAbleTo('attachments-update|attachments-delete'))
                                        <div class="timeline-footer">
                                            @if ($attachment->isEdittable())
                                            @permission('attachments-update')
                                                <button class="btn btn-warning btn-xs" data-modal="attachment" 
                                                    data-method="PUT" 
                                                    data-name="{{ $attachment->name }}"
                                                    data-is-note="{{ $attachment->isNote() ? true : false }}"
                                                    data-action="{{ route($attachments_url . '.update', $attachment) }}">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="d-xs-none d-md-inline">تعديل</span>
                                                </button>
                                            @endpermission
                                            @permission('attachments-delete')
                                                <button class="btn btn-danger btn-xs delete" data-form="#deleteFrom-{{$attachment->id}}">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="d-xs-none d-md-inline">حذف</span>
                                                </button>
                                                <form id="deleteFrom-{{$attachment->id}}" action="{{ route($attachments_url . '.destroy', $attachment) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endpermission
                                            @else
                                                @lang('accounting::global.readonly')
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif (!$attachment->isNote() && ($component == 'all' || $component == 'files'))
                            <div>
                                <i class="fas fa-{{ $attachment->isImage() ? 'image' : 'file' }} bg-purple"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{ $attachment->created_at->format('h:i') }} @lang('accounting::global.time_modes.' . $attachment->created_at->format('a'))</span>
                                    <h3 class="timeline-header">
                                        <span>{{ __('accounting::global.offices.' . $attachment->office) }}: </span><strong>{{ $attachment->auth()->name }}</strong>
                                    </h3>
                                    <div class="timeline-body">
                                        {{ $attachment->name }}
                                        @if ($attachment->isImage())
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <img src="{{ $attachment->getFileURL() }}" alt="Image" class="embed-responsive-item">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="timeline-footer">
                                        <a href="{{ $attachment->isImage() ? $attachment->getFileURL() : route($attachments_url . '.show', $attachment) }}"
                                            @if($attachment->isImage()) data-toggle="lightbox" data-type="image" @endif class="btn btn-info btn-xs">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-xs-none d-md-inline">@lang('accounting::global.show')</span>
                                        </a>
                                        @if ($attachment->isEdittable())
                                        @permission('attachments-update')
                                            <button class="btn btn-warning btn-xs" data-modal="attachment" 
                                                data-method="PUT" 
                                                data-name="{{ $attachment->name }}"
                                                data-is-note="{{ $attachment->isNote() ? true : false }}"
                                                data-action="{{ route($attachments_url . '.update', $attachment) }}">
                                                <i class="fas fa-edit"></i>
                                                <span class="d-xs-none d-md-inline">تعديل</span>
                                            </button>
                                        @endpermission
                                        @permission('attachments-delete')
                                            <button class="btn btn-danger btn-xs delete" data-form="#deleteFrom-{{$attachment->id}}">
                                                <i class="fas fa-trash"></i>
                                                <span class="d-xs-none d-md-inline">حذف</span>
                                            </button>
                                            <form id="deleteFrom-{{$attachment->id}}" action="{{ route($attachments_url . '.destroy', $attachment) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endpermission
                                        @else
                                            @lang('accounting::global.readonly')
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            @endif

        </div>
        <div class="btn-group text-center">
            @permission('attachments-create')
            @if (($component == 'all' || $component == 'files') && !(is_null($attachableId) || is_null($attachableType)))
            <button type="button" class="btn btn-default btn-sm btn-add" data-modal="attachment"
                data-action="{{ route($attachments_url . '.store') }}" data-is-note="false" 
                data-attachable-id="{{ $attachableId }}" data-attachable-type="{{ $attachableType }}">
                <i class="fa fa-file"></i>
                <span class="d-none d-md-inline">@lang('accounting::global.add_file')</span>
            </button>
            @endif
            @if (($component == 'all' || $component == 'notes') && !(is_null($attachableId) || is_null($attachableType)))
            <button type="button" class="btn btn-success btn-sm btn-add" data-modal="attachment"
                data-action="{{ route($attachments_url . '.store') }}" data-is-note="true" 
                data-attachable-id="{{ $attachableId }}" data-attachable-type="{{ $attachableType }}">
                <i class="fa fa-sticky-note"></i>
                <span class="d-none d-md-inline">@lang('accounting::global.add_note')</span>
            </button>
            @endif
            @endpermission
        </div>
    @else
        <table class="table table-bordered table-attachments-viewer">
            <thead>
                @isset($noTitle)
                <tr>
                    <th colspan="4" class="text-primary">
                        <i class="fas fa-paperclip"></i>
                        <span>المرفقات</span>
                    </th>
                </tr>
                @endisset
                <tr>
                    <th style="width: 50px">#</th>
                    <th>الاسم</th>
                    <th style="width: 150px">الخيارات</th>
                </tr>
            </thead>

            <tbody>
                @if (count($attachments))
                @foreach ($attachments as $attachment)
                    @if ($attachment->isNote() && ($component == 'all' || $component == 'notes'))
                    <tr>
                        <td>{{ $loop->index + 1}}</td>
                        <td>
                            <span class="badge badge-success">
                                <i class="fa fa-sticky-note"></i>
                                <span class="d-none d-md-inline">@lang('accounting::global.note')</span>
                            </span>
                            {!! nl2br(e($attachment->name)) !!}
                        </td>
                        <td>
                            @if ($attachment->isEdittable())
                                @permission('attachments-update')
                                    <button class="btn btn-warning btn-xs" data-modal="attachment" 
                                        data-method="PUT" 
                                        data-name="{{ $attachment->name }}"
                                        data-is-note="{{ $attachment->isNote() ? true : false }}"
                                        data-action="{{ route($attachments_url . '.update', $attachment) }}">
                                        <i class="fas fa-edit"></i>
                                        <span class="d-xs-none d-md-inline">تعديل</span>
                                    </button>
                                @endpermission
                                @permission('attachments-delete')
                                    <button class="btn btn-danger btn-xs delete" data-form="#deleteFrom-{{$attachment->id}}">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-xs-none d-md-inline">حذف</span>
                                    </button>
                                    <form id="deleteFrom-{{$attachment->id}}" action="{{ route($attachments_url . '.destroy', $attachment) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endpermission
                            @else
                                @lang('accounting::global.readonly')
                            @endif
                        </td>
                    </tr>
                    @elseif (!$attachment->isNote() && ($component == 'all' || $component == 'files'))
                    <tr>
                        <td>{{ $loop->index + 1}}</td>
                        <td>
                            <span class="badge badge-default">
                                <i class="fa fa-file"></i>
                                <span class="d-none d-md-inline">@lang('accounting::global.file')</span>
                            </span>
                            {{ $attachment->name }}
                        </td>
                        <td>
                            <a href="{{ $attachment->isImage() ? $attachment->getFileURL() : route($attachments_url . '.show', $attachment) }}" @if($attachment->isImage()) data-toggle="lightbox" data-type="image" @endif class="btn btn-info btn-xs">
                                <i class="fas fa-eye"></i>
                                <span class="d-xs-none d-md-inline">@lang('accounting::global.show')</span>
                            </a>
                            @if ($attachment->isEdittable())
                                @permission('attachments-update')
                                    <button class="btn btn-warning btn-xs" 
                                        data-modal="attachment" 
                                        data-method="PUT" 
                                        data-name="{{ $attachment->name }}" 
                                        data-is-note="{{ $attachment->isNote() ? true : false }}"
                                        data-action="{{ route($attachments_url . '.update', $attachment) }}">
                                        <i class="fas fa-edit"></i>
                                        <span class="d-xs-none d-md-inline">@lang('accounting::global.edit')</span>
                                    </button>
                                @endpermission
                                @permission('attachments-delete')
                                    <button class="btn btn-danger btn-xs delete" data-form="#deleteFrom-{{$attachment->id}}">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-xs-none d-md-inline">@lang('accounting::global.delete')</span>
                                    </button>
                                    <form id="deleteFrom-{{$attachment->id}}" action="{{ route($attachments_url . '.destroy', $attachment) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endpermission
                            @endif
                        </td>
                    </tr>
                    @endif
                @endforeach
                @else
                <tr>
                    <td colspan="3">@lang('accounting::attachments.empty')</td>
                </tr>
                @endif
            </tbody>
            @isset($canAdd)
                @if ($canAdd)
                <tfoot>
                    <tr>
                        <th colspan="4">
                            <div class="btn-group text-center">
                                @permission('attachments-create')
                                @if (($component == 'all' || $component == 'files') && !(is_null($attachableId) || is_null($attachableType)))
                                <button type="button" class="btn btn-default btn-sm btn-add" 
                                    data-modal="attachment" data-action="{{ route($attachments_url . '.store') }}" data-is-note="false"
                                    data-attachable-id="{{ $attachableId }}" data-attachable-type="{{ $attachableType }}"
                                >
                                    <i class="fa fa-file"></i>
                                    <span class="d-none d-md-inline">@lang('accounting::global.add_file')</span>
                                </button>
                                @endif
                                @if (($component == 'all' || $component == 'notes') && !(is_null($attachableId) || is_null($attachableType)))
                                <button type="button" class="btn btn-success btn-sm btn-add" 
                                    data-modal="attachment" data-action="{{ route($attachments_url . '.store') }}" data-is-note="true"
                                    data-attachable-id="{{ $attachableId }}" data-attachable-type="{{ $attachableType }}"
                                >
                                    <i class="fa fa-sticky-note"></i>
                                    <span class="d-none d-md-inline">@lang('accounting::global.add_note')</span>
                                </button>
                                @endif
                                @endpermission
                            </div>
                        </th>
                    </tr>
                </tfoot>
                @endif
            @endisset
        </table>
    @endif
    @endpermission

    @push('head')
        <style>
            @if ($view == 'timeline')
                .timeline::before {
                right: 31px;
                left: auto;
                }
                
                .timeline>div>.timeline-item {
                margin-left: 15px;
                margin-right: 60px;
                }
                
                .timeline>div>.fa,
                .timeline>div>.fab,
                .timeline>div>.far,
                .timeline>div>.fas,
                .timeline>div>.glyphicon,
                .timeline>div>.ion {
                left: auto;
                right: 8px;
                }
                
                .timeline>div>.timeline-item>.time {
                float: left;
                }
            @endif
        </style>
    @endpush
@endpermission