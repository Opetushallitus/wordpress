jQuery(document).ready(function() {
    if(jQuery('#excerpt').length) {
        jQuery('#postexcerpt .handlediv').after('<div style="position:absolute;top:0px;right:5px;color:#666;"><small>Excerpt length: </small><input type="text" value="0" maxlength="3" size="3" id="excerpt_counter" readonly="" style="background:#fff;"> <small>character(s).</small></div>');
        jQuery('#excerpt_counter').val(jQuery('#excerpt').val().length);
        jQuery('#excerpt').keyup( function() {
            jQuery('#excerpt_counter').val(jQuery('#excerpt').val().length);
        });
    }
});