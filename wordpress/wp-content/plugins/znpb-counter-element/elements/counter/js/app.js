(function($){

	$(document).ready(function() {

		var counter_element = function( scope ) {

			var elements = scope.find('.zn-counterElm');

			if(elements && elements.length){
				elements.each(function(i, el){

					if(typeof($.fn.countTo) != 'undefined') {

						var $el = $(el),
							$params = $el.is("[data-zn-count]") ? JSON.parse( $el.attr("data-zn-count") ) : {},
							$paramsInt = {},
							loaded = false;

						$.map( $params, function( val, i ) {
							$paramsInt[i] = parseInt(val);
						});

						var doElement = function(){
							// animate counter
							$el.addClass('is-appeared').countTo( $paramsInt );
							// set loaded
							loaded = true;
						};
						// If it's in viewport, load it
						if( typeof $.fn.isInViewport != 'undefined'){
							$(window).on('scroll', function() {
								if(!loaded && $el.is( ':in-viewport' )){
									doElement();
								}
							}).trigger('scroll');
						}
						// If viewport script isn't available,
						// just load it straight away
						else {
							if(!loaded)
								doElement();
						}

					}
				});
			}
		};

		if(typeof $.ZnThemeJs != 'undefined')
			$.extend( true, $.ZnThemeJs.prototype.zinit, counter_element( $(document) ) );

		$(window).on('ZnNewContent',function(e){
			counter_element( e.content );
		});
	});

})(jQuery);