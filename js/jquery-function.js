jQuery.noConflict();
jQuery(document).ready(function($) {
  // Code that uses jQuery's $ can follow here.
// services
	// ==============================

	var owl = $("#owl-demo");
	 owl.owlCarousel({
		items : 5, //10 items above 1000px browser width
		itemsDesktop : [1000, 3], //5 items between 1000px and 901px
		itemsDesktopSmall : [900, 3], // betweem 900px and 601px
		itemsTablet : [600, 1], //2 items between 600 and 0
		autoPlay : 5000,
		navigation: true,
		navigationText	: ["<i class='icon-left-open'></i>","<i class='icon-right-open'></i>"],
		itemsMobile : false // itemsMobile disabled - inherit from itemsTablet option
	});



	
   });
// Code that uses other library's $ can follow here.

