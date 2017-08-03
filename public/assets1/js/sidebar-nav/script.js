
jQuery.noConflict();
$(document).ready(function(){
	$(function() {

		$( "a" ).click(function( event ) {
			event.stopPropagation();
			event.preventDefault();
		});

		$( "body" ).click(function(event){
			if( $(".nav-menu").hasClass("nav-active-menu-container") && $(".nav-link-container").hasClass("nav-inactive-menu-link-container") )
			{
				$(".nav-menu").toggleClass("nav-active-menu-container");
				$(".content").toggleClass("inactive-body");
			}
		});

		$(".nav-close-menu-li > a").click(function(event){
			$(".nav-menu").toggleClass("nav-active-menu-container");
			$(".content").toggleClass("inactive-body");
		});

		$(".nav-menu").on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',function(e) {
			if(! $(".nav-menu").hasClass("nav-active-menu-container") )
				$(".nav-link-container").toggleClass("nav-inactive-menu-link-container");
			else
				$(".content").toggleClass("inactive-body");
		});

		$( ".nav-link-container > a" ).click(function(event){
			$(".nav-link-container").toggleClass("nav-inactive-menu-link-container");
			$(".nav-menu").toggleClass("nav-active-menu-container");
		});



	});

});