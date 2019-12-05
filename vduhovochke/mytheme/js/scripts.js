jQuery(document).ready(function($){

	//Мобильная навигация
	$('.m-nav').prepend('<div class="slide-out-close">Меню</div>');
	$('.main-box').prepend('<div class="slide-out-open">Меню</div>');
	$('#main').prepend('<div class="menu-overlay">Закрыть меню</div>');
	$('.slide-out-open').click(function() {
		$('body').addClass('js-nav');
		$('.slide-out-close').addClass('rotate');
	});

	$('.slide-out-close').click(function() {
		$('body').removeClass('js-nav');
		$(this).removeClass('rotate');
	});
	$('.menu-overlay').click(function() {
		$('body').removeClass('js-nav');
		$('#slide-out-open').removeClass('slide-out-close').addClass('slide-out-open');
	});	

	//Главное меню
	$('.main-menu__inner ul > li:has(ul)').append('<div class="menu-arrow">Sub-menu</div>');
	$('.main-menu__inner .menu-arrow').click(function() {
		var ul = $(this).parent().find('ul');
		if ( ul.is(':hidden') ) {
			ul.slideDown(450);
			$(this).addClass('active');
		} else {
			ul.slideUp(450);
			$(this).removeClass('active');
		}
	});
	if ( $('.main-menu__inner ul > li > ul >li').hasClass('current-menu-item') ) {
		$('.main-menu__inner ul > li > ul > li.current-menu-item').parent().addClass('ul_block');
		$('.main-menu__inner ul > li > ul > li.current-menu-item').parent().next().addClass('active');
	}
	if ( $('.main-menu__inner ul > li > ul > li').hasClass('current-post-parent') ) {
		$('.main-menu__inner ul > li > ul > li.current-post-parent').parent().addClass('ul_block');
		$('.main-menu__inner ul > li > ul > li.current-post-parent').parent().next().addClass('active');
	}

	//add menu
	$('.add-menu__toggle').click(function() {
		if ($(this).next('ul').is(':hidden')) {
			$(this).next('ul').slideDown(300);
		} else {
			$(this).next('ul').slideUp(300);
		}
	});

	//Меню в сайдбаре
	$('.sidebar-menu ul > li:has(ul) > a').after('<div class="menu-arrow">Sub-menu</div>');
	$('.sidebar-menu ul > li:has(ul) > span').after('<div class="menu-arrow">Sub-menu</div>');
	$('.sidebar-menu .menu-arrow').click(function() {
		var ul = $(this).parent().find('ul');
		if ( ul.is(':hidden') ) {
			ul.slideDown(450);
			$(this).addClass('active');
			$(this).parent().addClass('active');
		} else {
			ul.slideUp(450);
			$(this).removeClass('active');
			$(this).parent().removeClass('active');
		}
	});
	if ( $('.sidebar-menu ul > li > ul >li').hasClass('current-menu-item') ) {
		$('.sidebar-menu ul > li > ul > li.current-menu-item').parent().addClass('ul_block');
		$('.sidebar-menu ul > li > ul > li.current-menu-item').parent().prev().addClass('active');
		$('.sidebar-menu ul > li > ul > li.current-menu-item').parent().parent().addClass('active');
	}
	if ( $('.sidebar-menu ul > li > ul > li').hasClass('current-post-parent') ) {
		$('.sidebar-menu ul > li > ul > li.current-post-parent').parent().addClass('ul_block');
		$('.sidebar-menu ul > li > ul > li.current-post-parent').parent().prev().addClass('active');
		$('.sidebar-menu ul > li > ul > li.current-post-parent').parent().parent().addClass('active');
	}

	//Слайдер на главной
	var slider = $('#slider'); // селектор слайдера
	if (slider.length) {
		slider.bxSlider({
			controls: false,
			pager: true,
			auto: true,
		});
	}

	//Слайдер популярные
	if ( $('#slider-posts').length ) {
		var settingsSlider = function() {
			var settingsSlider1 = {
				minSlides: 3,
				maxSlides: 3,
				moveSlides: 3,
				slideWidth: 210,
				slideMargin: 5,
				adaptiveHeight: true,
			};
			var settingsSlider2 = {
				minSlides: 3,
				maxSlides: 3,
				moveSlides: 1,
				slideWidth: 210,
				slideMargin: 5,
				adaptiveHeight: true,
			};
			return($(window).width()>1024) ? settingsSlider1 : settingsSlider2;
		}
		var slider;
		function reloadSlider() {
			slider.reloadSlider(settingsSlider());
		}
		slider = $('#slider-posts').bxSlider(settingsSlider());
		$(window).resize(reloadSlider);
	}

	//Слайдер рекомендованные
	if ( $('#recommended-posts').length ) {
		var settingsRecc = function() {
			var settingsRecc1 = {
				minSlides: 3,
				maxSlides: 3,
				moveSlides: 3,
				slideWidth: 210,
				slideMargin: 5,
				adaptiveHeight: true,
				pager: false,
			};
			var settingsRecc2 = {
				minSlides: 3,
				maxSlides: 3,
				moveSlides: 1,
				slideWidth: 210,
				slideMargin: 5,
				adaptiveHeight: true,
				pager: false,
			};
			return($(window).width()>1024) ? settingsRecc1 : settingsRecc2;
		}
		var slider;
		function reloadSlider() {
			slider.reloadSlider(settingsRecc());
		}
		slider = $('#recommended-posts').bxSlider(settingsRecc());
		$(window).resize(reloadSlider);
	}

	//Атрибут start тега ol
	$('ol[start]').each(function() {
	     var val = parseFloat($(this).attr("start")) - 1;
	     $(this).css('counter-increment','li '+ val);
	 });

	//перемещение меню на < 1023
	if ($(window).width() <= '1023') {
		$('.sidebar-menu').appendTo('.m-nav');
	} 
	$(window).resize(function() {
		if ($(window).width() <= '1023') {
			$('.sidebar-menu').appendTo('.m-nav');
		} else {
			$('.sidebar-menu').prependTo('.sidebar');
		}
	});

	//Стрелочка наверх
	$('<div id="up" class="up" style="display: none">наверх</div>').appendTo('.footer').click(function() {
		$('html, body').animate({scrollTop: 0}, 700);
	});
	var win = $(window);
	var up = $('#up');
	win.on('scroll', function() {
		if ( win.scrollTop() > win.height() / 3 ) {
			up.fadeIn();
		} else {
			up.fadeOut();
		}
	}).scroll();

	//удалить кнопку загрузить еще, если нет постов
	$.ajaxSetup({complete: function() {
		$('.last_item').next('.more').remove();
	}});

	//пагинация
	if ( $('.more').length ) {
		$('body').on('click', '.more:not(.loading)', function() {
			var more = $(this);
			var container = $(this).prev('.ajax_pagination');
			var defaultText = more.html();
			more.addClass('loading').html( more.data('loading') );
			var offset = more.data('offset');
			var items = more.data('items');
			var theme = more.data('theme');
			var newposts = '';
			var tax = '';
			var tag = '';
			var search = '';
			if( more.data('newposts') ) newposts = '&newposts=' + more.data('newposts');
			if ( more.data('category') ) tax = '&cat=' + more.data('category');
			if ( more.data('tag') ) tag = '&tag=' + more.data('tag');
			if ( more.data('search') ) search = '&search=' + more.data('search');
			console.log(search);
			$.ajax({
				url: '/wp-content/themes/' + theme + '/ajax-posts.php?offset=' + offset + '&items=' + items + newposts + tax + tag + search
			}).done(function(html) {
				container.after(html);
				more.data('offset', offset + items).removeClass('loading').html(defaultText);
				if ($.trim(html) === '') {
					more.remove();
				} else {
					$('html, body').animate({scrollTop: $('.ajax_pagination:last').offset().top -100 }, 900);
				}
			});
		});
	}
});