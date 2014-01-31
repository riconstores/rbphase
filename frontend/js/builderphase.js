jQuery(document).ready(function(){
		
		/*jQuery('.nav-menu ul').append('<li class="page_item"><a id="eleadform" href="javascript:void();">Request More Info</a></li>');*/
		/*Back to top start*/
		/*jQuery('body').append('<a style="display: block;" href="#" class="back-to-top">Back to Top</a>');
		var offset = 220;
		var duration = 500;
		jQuery(window).scroll(function() {
		if (jQuery(this).scrollTop() > offset) {
		jQuery('.back-to-top').fadeIn(duration);
		} else {
		jQuery('.back-to-top').fadeOut(duration);
		}
		});
		jQuery('.back-to-top').click(function(event) {
		event.preventDefault();
		jQuery('html, body').animate({scrollTop: 0}, duration);
		return false;
		}) 
		Back to top end*/
		var js = jQuery.noConflict();
		
		js('body').append('<div class="overlay">');
		js('#eleadform').click(function(){
			js('.inq_form').show();
			js('.overlay').show();
		});
		js('.overlay').click(function(){
						js('.inq_form').hide();
						js('.topomap').hide();
						js(this).hide();
					});
});