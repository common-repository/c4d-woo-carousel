var mc4dSlider = {};
(function($){
	$(document).ready(function(){
		var defaultParams = {
			// Most important owl features
		    items : 3,
		    singleItem : false,
		    
		    //Autoplay
		    autoPlay : false,
		    stopOnHover : false,
		 
		    // Navigation
		    navigation : true,
		    scrollPerPage : false,
		    navigationText: ['',''],
		 
		    //Pagination
		    pagination : true,
		    paginationNumbers: false,
		 	
		 	//Auto height
		    autoHeight : true,
		    lazyLoad : true
    	};

		$(".c4d-woo-carousel").each(function(){
			var id = $(this).find('.c4d-woo-carousel__slider > ul').attr('id'),
			self = this,
			params = mc4dSlider[id];

			$.each(defaultParams, function(index, value){
				if(typeof params[index] != 'undefined') {
					if (params[index] == 'false') {
						defaultParams[index] = false;
					} else if (params[index] == 'true') {
						defaultParams[index] = true;
					} else {
						defaultParams[index] = params[index];	
					}
				}
			});
			
			$('#' + id).owlCarousel(defaultParams);

			// load by cate
			$(self).find('.c4d-woo-carousel__categories span').each(function(index, value){
				$(this).on('click', function(event){
					event.preventDefault();
					var cate = this;
					$(cate).addClass('active').siblings().removeClass('active');
					$(self).find('.c4d-woo-carousel__loading').addClass('active');
					$(self).css('min-height', $(self).height());
					if ($(self).data($(cate).attr('data-category'))) {
						var data = $(self).data($(cate).attr('data-category'));
						c4d_woo_carousel_data(id, data, self);
					} else {
						$.get(c4d_woo_carousel.ajax_url, { 
							'action': 'c4d_woo_carousel', 
							'c4dajax': 1, 
							'category': $(cate).attr('data-category')
							}, function(res){
								$(self).data($(cate).attr('data-category'), res);
								c4d_woo_carousel_data(id, res, self);
						 	}
						).done(function(){
							$(self).find('.c4d-woo-carousel__loading').removeClass('active');
						});	
					}
					return false;	
				});
			});
		});
		c4d_woo_carousel_data = function(id, data, handler) {
			$('#' + id).parent().html('<div id="'+id+'">' + $(data).find('.c4d-woo-carousel__slider > div').html() + '</div>');
	 		$('#' + id).owlCarousel(defaultParams);
	 		$(handler).find('.c4d-woo-carousel__loading').removeClass('active');
	 		setTimeout(function(){
				$(handler).css('min-height', 0);
			}, 1500);
		};
	});
})(jQuery);