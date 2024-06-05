              new WOW().init();

              $(document).ready(function(){
              	$(".header-menu-icon").on("click",function(){
              		$(this).toggleClass("open");
              		$(".header-menu").toggleClass("active");
              	});

              	$(".header-menu-list li").on("click",function(){
              		$(".header-menu-icon").removeClass("open");
              		$(".header-menu").removeClass("active");
              	});


              	$(".banner-slider").slick({
              		infinite: true,
              		arrows: true,
              		dots: true,
              		slidesToShow: 1,
              		autoplay: true,
              		autoplaySpeed:4000,
              		slidesToScroll: 1,
              		speed: 1500,
                 responsive: [
                 {
                  breakpoint: 1050,
                  settings: {
                    arrows: false
                  }
                }
                ]
              });

                $(".header-menu-list li").on("click",function(){
                  $(".header-menu-list li").removeClass("active");
                  $(this).addClass("active");
                });
                $(".go-to-top").click(function () {
                  $("html, body").animate({scrollTop: 0}, 1000);
                });

              });

              $(window).on("scroll",function() {
                if ($(this).scrollTop() > 200 ) {
                  $('.go-to-top').fadeIn(400);
                } else {
                  $('.go-to-top').fadeOut(400);
                }
              });



              var $animation_elements = $('.blockreveal');
              var $window = $(window);

              function check_if_in_view() {
              	var window_height = $window.height();
              	var window_top_position = $window.scrollTop();
              	var window_bottom_position = (window_top_position + window_height);

              	$.each($animation_elements, function() {
              		var $element = $(this);
              		var element_height = $element.outerHeight();
              		var element_top_position = $element.offset().top;
              		var element_bottom_position = (element_top_position + element_height);

              		if ((element_bottom_position >= window_top_position) &&
              			(element_top_position <= window_bottom_position)) {
              			$element.addClass('active');
              	} else {
		//$element.removeClass('active');
	}
});
              }

              $window.on('scroll resize', check_if_in_view);
              $window.trigger('scroll');

