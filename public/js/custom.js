function readImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $($(input).closest('.image-wrapper').find('.image-previewer')).css("background-image", "url("+e.target.result+")")
        }
    
        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

$(function(){
    let table_attachments = $('.table-attachments')
    if(table_attachments.length){
        table_attachments.closest('form').attr('enctype', "multipart/form-data")
    }
    let input_file = $('input[type=file]')
    if(input_file.length){
        input_file.closest('form').attr('enctype', "multipart/form-data")
    }
    $(document).on('change', '.image-wrapper input[type=file]', function() {
        readImage(this);
    });
})