(function( $ ) {
	'use strict';

		$( document ).ready(function() {

			$("div.gpsc--product-slider-carousel").each(function () {

				// Getting ID.
				var _theSlider = $(this).attr('id');
				// API.
				$('#' + _theSlider).each(function () {

					var $this = $(this),
						$speed = ($this.attr('data-speed')) ? $this.data('speed') : 300,
						$autoplay = ($this.data('autoplay')) ? $this.data('autoplay') : false,
						$loop = ($this.data('loop')) ? $this.data('loop') : false,
						$slidesPerView = ($this.data('slidesperview')) ? $this.data('slidesperview') : 3,
						$spacebetween = ($this.data('spacebetween')) ? $this.data('spacebetween') : 500,
						$paginationtype = ($this.data('paginationtype')) ? $this.data('paginationtype') : 500;

					var swiper = new Swiper('#' + _theSlider + ' .swiper-container', {
						speed: $speed,
						autoplay: $autoplay.autoplay,
						loop: $loop,
						slidesPerView: $slidesPerView,
						spaceBetween: $spacebetween,
						pagination: {
							el: '#' + _theSlider + ' .swiper-pagination',
							clickable: true,
							type: $paginationtype,
						},
						navigation: {
							nextEl: '#' + _theSlider + ' .swiper-button-next',
							prevEl: '#' + _theSlider + ' .swiper-button-prev',
						},
					});
				});
			});
		});

})( jQuery );
