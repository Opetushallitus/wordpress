jQuery(function($) {
    $('input[name="photocrati-nextgen_pro_blog_gallery[override_image_settings]"]')
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_blog_gallery_image_dimensions'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_blog_gallery_image_quality'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_blog_gallery_image_crop'))
        .nextgen_radio_toggle_tr('1', $('#tr_photocrati-nextgen_pro_blog_gallery_image_watermark'));
});