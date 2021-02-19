<style>
    body {
    padding: 20px;
    text-align: center;
    }
    
    .popover-block-container .popover-icon {
    background: none;
    color: none;
    border: none;
    padding: 0;
    outline: none;
    cursor: pointer;
    }
    .popover-block-container .popover-icon i {
    color: #04a0b2;
    text-align: center;
    margin-top: 4px;
    }
    
    .popover-header {
    display: none;
    }
    
    .popover {
    max-width: 306.6px;
    border-radius: 6px;
    border: none;
    box-shadow: 0 0 6px 1px #eee;
    }
    
    .popover-body {
    border: none;
    padding: 20px 49.4px 24px 24px;
    color: #5f6976;
    font-size: 15px;
    font-style: italic;
    z-index: 2;
    line-height: 1.53;
    letter-spacing: 0.1px;
    }
    .popover-body .popover-close {
    position: absolute;
    top: 5px;
    right: 10px;
    opacity: 1;
    }
    .popover-body .popover-close .material-icons {
    font-size: 16px;
    font-weight: bold;
    color: #04a0b2;
    }
</style>
<section>
    <!-- button popover  -->
    <div class="popover-block-container">
        <button tabindex="0" type="button" class="popover-icon" data-popover-content="#{{ $title or '#uniqueId' }}" data-toggle="popover"
            data-placement="right">
            {{ $title }}
        </button>
        <div id="{{ $title or '#uniqueId' }}" style="display:none;">
            {!! $body !!}
        </div>
    </div>
    <!-- /End button popover  -->
</section>
<script>
    $(function(){
        $("[data-toggle=popover]").popover({
            html : true,
            trigger: 'focus',
            content: function() {
                var content = $(this).attr("data-popover-content");
                return $(content).children(".popover-body").html();
            }
        });
    })
</script>