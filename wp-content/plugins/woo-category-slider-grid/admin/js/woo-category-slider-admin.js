jQuery(document).ready(function ($) {

    /**
     * Admin Preloader.
     */
    $(".sp_wcsp_shortcode_generator .spf-wrapper").css("visibility", "hidden");
    $(".sp_wcsp_shortcode_generator .spf-wrapper").css("visibility", "visible");
    $(".sp_wcsp_shortcode_generator .spf-wrapper .spf-nav-metabox li").css("opacity", 1);

});
// Product Slider plugin notice
jQuery(document).on('click', '.post-type-sp_wcslider .wps-notice .notice-dismiss', function () {
    jQuery.ajax({
        url: ajaxurl,
        data: {
			action: 'dismiss_product_slider_notice'
        }
    })
});
// Smart Brand plugin notice
jQuery(document).on('click', '.post-type-sp_wcslider .wsb-notice .notice-dismiss', function () {
	jQuery.ajax({
		url: ajaxurl,
		data: {
			action: 'dismiss_smart_brand_notice'
		}
	})
});