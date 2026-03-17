var $ = jQuery.noConflict();
jQuery( document ).ready(function($) {
//contact form faq
	document.addEventListener( 'wpcf7mailsent', function( event ) {
		var id = event.detail.contactFormId;
		if ( id == 1550 || id == 1540 || id == 1551 ) {   
			$('.succes-faq').trigger('click');
			//$('.mfp-close').trigger('click');
		}
	}, false );
//contact form contact
document.addEventListener( 'wpcf7mailsent', function( event ) {
	var id = event.detail.contactFormId;
	if ( id == 1304 || id == 8 || id == 1303 ) {   
		$('.succes-form').trigger('click');
		//$('.mfp-close').trigger('click');
	}
}, false );
//contact form partners
document.addEventListener( 'wpcf7mailsent', function( event ) {
	var id = event.detail.contactFormId;
	if ( id == 1555 || id == 1459 || id == 1554 ) {   
		$('.succes-partners').trigger('click');
		//$('.mfp-close').trigger('click');
	}
}, false );
//parnership bg 
$('.tabbed-content li:nth-child(1) a').click(function() {
	$('.partnershib__bg .bg-loaded').css('background-image','url(/wp-content/uploads/partnership-3.jpg)');	
});
$('.tabbed-content li:nth-child(2) a').click(function() {
	$('.partnershib__bg .bg-loaded').css('background-image','url(/wp-content/uploads/partnership-2.jpg)');		
});
$('.tabbed-content li:nth-child(3) a').click(function() {
	$('.partnershib__bg .bg-loaded').css('background-image','url(/wp-content/uploads/partnership-1.jpg)');	
});
//tags
$('.tags__btn').click(function() {
  $('#tag-cloud').toggleClass('active');
	$(this).toggleClass('rotate');
});
//slider slick
  $('.slider__main').slick({
		infinite: true,
		dots: true,
		autoplay: true,
		autoplaySpeed: 3000,
		fade: true,
		pauseOnHover: true,
		fadeSpeed: 1000
	});
	$('.products__slider.ru').slick({
		infinite: true,
		prevArrow: '<div class="prev"><button class="btn btn_red js-products-prev slick-arrow" style=""><svg class="icon icon-arr-left"><use xlink:href="/wp-content/uploads/sprite.svg#icon-arr-left"></use></svg>Предыдущий</button></div>',
    nextArrow: '<div class="next"><button class="btn btn_red js-products-next slick-arrow" style="">Следующий<svg class="icon icon-arr-right"><use xlink:href="/wp-content/uploads/sprite.svg#icon-arr-right"></use></svg></button></div>',
	});
	$('.products__slider.ua').slick({
		infinite: true,
		prevArrow: '<div class="prev"><button class="btn btn_red js-products-prev slick-arrow" style=""><svg class="icon icon-arr-left"><use xlink:href="/wp-content/uploads/sprite.svg#icon-arr-left"></use></svg>Попередній</button></div>',
    nextArrow: '<div class="next"><button class="btn btn_red js-products-next slick-arrow" style="">Наступний<svg class="icon icon-arr-right"><use xlink:href="/wp-content/uploads/sprite.svg#icon-arr-right"></use></svg></button></div>',
	});
	$('.products__slider.en').slick({
		infinite: true,
		prevArrow: '<div class="prev"><button class="btn btn_red js-products-prev slick-arrow" style=""><svg class="icon icon-arr-left"><use xlink:href="/wp-content/uploads/sprite.svg#icon-arr-left"></use></svg>Prev</button></div>',
    nextArrow: '<div class="next"><button class="btn btn_red js-products-next slick-arrow" style="">Next<svg class="icon icon-arr-right"><use xlink:href="/wp-content/uploads/sprite.svg#icon-arr-right"></use></svg></button></div>',
	});
	$('.slider-dots .slider-dots__item').click(function() {
		var $this = $(this);
		$('.slider-dots__item').removeClass('active');
		$(this).addClass('active');
		$('.products__slider').slick('slickGoTo', $this.data('index'));
	});
	$('.slider__products').slick({
		fade: true,
		fadeSpeed: 1000,
		arrows: true,
		autoplay: false,
		slidesToScroll: 1,
		appendArrows: $('.arrows__block'), 
		appendDots: $('.arrows__block'),
		dots: true,
		responsive:[
			{ 
				breakpoint: 480, 
				settings: {
					dots: false,
				}
			}
		],
		customPaging : function(slider, i) {
			var title = $(slider.$slides[i]).data("title");
			return '<a class="pager__item"> '+title+' </a>';
		}
	});
	$('.certificates-slider').slick({
		dots: true,
		vertical: true,
		verticalSwiping: true,
		centerMode: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		appendArrows: $('.arrows__block'), 
		appendDots: $('.arrows__block'),
		responsive:[
			{ 
				breakpoint: 849, 
				settings: {
					vertical: false,
					verticalSwiping: false,
					slidesToShow: 1
				}
			},{ 
				breakpoint: 549, 
				settings: {
					vertical: false,
					verticalSwiping: false,
					slidesToShow: 1,
					centerMode: false,
				}
			}
		],
	});
	$('.blog-slider').slick({
		fade: true,
		slidesToShow: 1,
		slidesToScroll: 1,
	});
});
//click out
