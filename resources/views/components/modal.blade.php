<div class="modal {{ $animation ?? 'fade' }} {{ $class ?? '' }}" id="{{ $id ?? 'modal' }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            @isset($title)
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endisset

            <div class="modal-body">
                {{ $body }}
            </div>

            <div class="modal-footer">
                @isset($footer)
                    {{ $footer }}
                @endisset
                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>