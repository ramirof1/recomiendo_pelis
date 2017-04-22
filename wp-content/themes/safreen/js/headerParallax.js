jQuery(document).ready(function($){
	
	fullscreen();
$(window).resize(fullscreen);
$(window).scroll(headerParallax);

function fullscreen() {
	var mq = window.matchMedia('all and (max-width: 992px)');
	
	var masthead = $('.masthead');
	if(mq.matches) {
	var windowH = $(window).height()*0.7;
	} else {
		var windowH = $(window).height();
		}
	
	

	
	masthead.height(windowH);
}

function headerParallax() {
	var st = $(window).scrollTop();
	var headerScroll = $('.masthead-overlay');
	var headerScroll2 = $('.masthead-dsc');

	if (st < 1200) {
		headerScroll.css('opacity', 0+st/1200);
		
		
	}
	if (st < 500) {
		headerScroll2.css('opacity', 1-st/1000);
		$('.masthead-arrow ').css('opacity', 1-st/500);
		
	}
}
});