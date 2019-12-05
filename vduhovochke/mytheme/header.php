<?php
/*
* HTML-минимизация
*/
ob_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta name="telderi" content="891600ddaba7ed86e351015b083281fc" />
    <meta name="5ccacd7b24d3050f0cc9c5b223f16220" content="">

    <!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter45171297 = new Ya.Metrika({ id:45171297, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/45171297" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->

    <meta name="verify-admitad" content="b95c74bb89" />
	<meta name="yandex-verification" content="56131e4b933982d9" />
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,700,700i&subset=cyrillic" rel="stylesheet">
	<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet">
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<!--[if lte IE 9]><script src="http://cdn.jsdelivr.net/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
	<!--[if gte IE 9]><style type="text/css">.gradient{filter: none;}</style><![endif]-->
	<?php 
	if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) wp_enqueue_script('comment-reply'); 
	wp_head(); 
	if ($site_ico) {
		?>
		<link rel="icon" href="<?php echo $site_ico; ?>" type="image/x-icon">
		<?php
	}
	if ( is_front_page() || is_category() )
		?>
		<script src="<?php bloginfo('template_url'); ?>/js/jquery.bxslider.min.js"></script>
		<?php
	//Цветовые настройки
	$section_color_1 = get_theme_mod('section_color_1');    
	$section_color_2 = get_theme_mod('section_color_2');    
	$section_color_3 = get_theme_mod('section_color_3');
	?>
	<script src="<?php bloginfo('template_url'); ?>/js/scripts.js"></script>
	<style>/*1*/.main-menu, .sidebar-menu > ul > li:hover > a, .sidebar-menu > ul > li:hover > span, .sidebar-menu > ul > li > span, .sidebar-menu > ul li.active > a, .slider .bx-pager-item .active, .slider .bx-pager-item a:hover, .slider-posts-wrap .bx-pager-item .active, .slider-posts-wrap .bx-pager-item a:hover, .footer-bottom, .single ul li:before, .single ol li:before, .add-menu > ul > li > a:hover, .add-menu > ul > li > span:hover, .main-menu__list > li > ul > li > a:hover, .main-menu__list > li > ul > li > span:hover, .cat-children__item a:hover, .related__item-img .related__item-cat > a:hover, .main-menu__list > li > ul > li > span, .main-menu__list > li > ul > li.current-post-parent > a, .add-menu > ul > li.current-post-parent > a, .add-menu > ul > li > span, .sidebar-menu > ul > .current-post-parent > a, .sidebar-menu > ul > li .menu-arrow:before, .sidebar-menu > ul > li .menu-arrow:after, .commentlist .comment .reply a:hover{background: <?php echo $section_color_1; ?>;}.title, .single #toc_container .toc_title{color: <?php echo $section_color_1; ?>;border-left: 4px solid <?php echo $section_color_1; ?>;}.description{border-top: 4px solid <?php echo $section_color_1; ?>;}.description__title, .single .wp-caption-text, .more, a:hover{color: <?php echo $section_color_1; ?>;}.commentlist .comment, .add-menu > ul > li > a, .add-menu > ul > li > span, .main-menu__list > li > ul > li > a, .main-menu__list > li > ul > li > span{border-bottom: 1px solid <?php echo $section_color_1; ?>;}.more span{border-bottom: 1px dashed <?php echo $section_color_1; ?>;}.slider-posts-wrap .bx-prev:hover, .slider-posts-wrap .bx-next:hover{background-color: <?php echo $section_color_1; ?>;border: 1px solid <?php echo $section_color_1;?>;}#up{border-bottom-color: <?php echo $section_color_1; ?>;}#up:before, .commentlist .comment .reply a{border: 1px solid <?php echo $section_color_1; ?>;}.respond-form .respond-form__button{background-color: <?php echo $section_color_1; ?>;}@media screen and (max-width: 1023px){.main-box{border-top: 50px solid <?php echo $section_color_1; ?>;}
		.m-nav{background: <?php echo $section_color_1; ?>;}.main-menu__list > li > ul > li > span{background: none;}.add-menu > ul > li > a, .add-menu > ul > li > span, .main-menu__list > li > ul > li > a, .main-menu__list > li > ul > li > span{border-bottom: 0;}.sidebar-menu > ul > li .menu-arrow:before, .sidebar-menu > ul > li .menu-arrow:after{background: #85ece7;}}/*2*/.add-menu__toggle{background: <?php echo $section_color_2 ?> url(<?php echo get_bloginfo('template_url') ?>/images/add-ico.png) center no-repeat;}.add-menu > ul > li > a, .related__item-img .related__item-cat > a, .main-menu__list > li > ul > li > a{background: <?php echo $section_color_2; ?>;}#up:hover{border-bottom-color: <?php echo $section_color_2; ?>;}#up:hover:before{border: 1px solid <?php echo $section_color_2; ?>;}a, .sidebar-menu > ul > li > ul > li > span, .sidebar-menu > ul > li > ul > li > a:hover, .sidebar-menu > ul > li > ul > li > span:hover, .sidebar-menu > ul > li > ul > li.current-post-parent > a, .footer-nav ul li a:hover{color: <?php echo $section_color_2; ?>;}.respond-form .respond-form__button:hover{background-color: <?php echo $section_color_2; ?>;}@media screen and (max-width: 1023px){.sidebar-menu > ul > li > a, .main-menu__list li > span, .main-menu__list li > a:hover, .main-menu__list li > span:hover, .main-menu__list li > ul, .main-menu__list > li.current-post-parent > a, .sidebar-menu > ul > li > span, .sidebar-menu > ul > .current-post-parent > a{background: <?php echo $section_color_2; ?>;}.main-menu__list > li > ul > li > a:hover, .main-menu__list > li > ul > li > span:hover, .main-menu__list > li > ul > li.current-post-parent > a{background: none;}}/*3*/.post-info__cat a, .post-info__comment{background: <?php echo $section_color_3; ?>;}.post-info__comment:after{border-color: rgba(0, 0, 0, 0) <?php echo $section_color_3; ?> rgba(0, 0, 0, 0) rgba(0, 0, 0, 0);}/*<1023*/@media screen and (max-width: 1023px){.add-menu > ul > li > a, .sidebar-menu > ul > li > a{background-color: <?php echo $section_color_1; ?>;}.add-menu > ul > li > span, .add-menu > ul > li.current-post-parent > a, .sidebar-menu > ul > li > ul{background-color: <?php echo $section_color_2; ?>;}}</style>
</head>
<body class="<?= from_yandex_class(); ?>">
	<div id="main">
		<div class="wrapper">
			<header class="header">
				<?php
				if( is_front_page() ) { 
					?>
					<img src="<?php echo $logo_upload; ?> " class="logo" alt="<?php bloginfo('name'); ?>">
					<?php 
				} else { 
					?>
					<a href="<?php echo home_url(); ?>">
						<img src="<?php echo $logo_upload; ?> " class="logo" alt="<?php bloginfo('name'); ?>">
					</a>
					<?php 
				} ?>
				<div class="m-nav">
					<?php
					// форма поиска по умолчанию в шапке
					//require 'search-form.php';
					?>
					<!--Поиск от яндекса в шапке-->
					<div class="yandex-mysearch">
					    <div class="ya-site-form ya-site-form_inited_no" onclick="return {'action':'https://vduhovochke.ru/search','arrow':true,'bg':'#D8540A','fontsize':16,'fg':'#000000','language':'ru','logo':'rb','publicname':'Поиск по vduhovochke.ru','suggest':true,'target':'_self','tld':'ru','type':2,'usebigdictionary':true,'searchid':2307351,'input_fg':'#000000','input_bg':'#ffffff','input_fontStyle':'normal','input_fontWeight':'normal','input_placeholder':'Поиск рецепта ...','input_placeholderColor':'#000000','input_borderColor':'#7f9db9'}"><form action="https://yandex.ru/search/site/" method="get" target="_self" accept-charset="utf-8"><input type="hidden" name="searchid" value="2307351"/><input type="hidden" name="l10n" value="ru"/><input type="hidden" name="reqenc" value=""/><input type="search" name="text" value=""/><input type="submit" value="Найти"/></form></div><style type="text/css">.ya-page_js_yes .ya-site-form_inited_no { display: none; }</style><script type="text/javascript">(function(w,d,c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0],e=d.documentElement;if((' '+e.className+' ').indexOf(' ya-page_js_yes ')===-1){e.className+=' ya-page_js_yes';}s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Form.init()})})(window,document,'yandex_site_callbacks');</script>
				    </div>
					<?php

					if ($social_ok || $social_yt || $social_fb || $social_gp || $social_tw || $social_in || $social_vk) {
						?>
						<div class="social-icon">
							<?php
							if ($social_ok) echo "<a href='". $social_ok ."' target='_blank' class='ok'>ok</a>";
							if ($social_yt) echo "<a href='". $social_yt ."' target='_blank' class='yt'>yt</a>";
							if ($social_fb) echo "<a href='". $social_fb ."' target='_blank' class='fb'>fb</a>";
							if ($social_gp) echo "<a href='". $social_gp ."' target='_blank' class='gp'>gp</a>";
							if ($social_tw) echo "<a href='". $social_tw ."' target='_blank' class='tw'>tw</a>";
							if ($social_in) echo "<a href='". $social_in ."' target='_blank' class='in'>in</a>";
							if ($social_vk) echo "<a href='". $social_vk ."' target='_blank' class='vk'>vk</a>";
							?>
						</div>
						<?php
					}

					$nav = wp_nav_menu(
					array(
					    'theme_location' =>'nav_main',
					    'container' => false,
					    'items_wrap' => '<ul class="main-menu__list">%3$s</ul>',
					    'fallback_cb' => false,
					    'echo' => false,
					    'depth' => 2,
					)); 
					if ($nav) {
						?>
						<nav class="main-menu">
					    	<div class="main-menu__inner">
					    		<?php 
					    		echo $nav;

					    		$nav_m = wp_nav_menu(
					    		array(
					    		    'theme_location' =>'nav_m',
					    		    'container' => false,
					    		    'items_wrap' => '<ul class="main-menu__list main-menu__list_m">%3$s</ul>',
					    		    'fallback_cb' => false,
					    		    'echo' => false,
					    		    'depth' => 2,
					    		));
					    		echo $nav_m;

					    		$nav = wp_nav_menu(
					    		array(
					    		    'theme_location' =>'nav_add',
					    		    'container' => false,
					    		    'items_wrap' => '<div class="add-menu"><div class="add-menu__toggle">add-toggle</div><ul>%3$s</ul></div>',
					    		    'fallback_cb' => false,
					    		    'echo' => false,
					    		    'depth' => 1,
					    		)); 
					    		if ($nav) {
					    		    echo $nav;
					    		} ?>
					    	</div>
						</nav>
						<?php
					} ?>
				</div>
			</header>
			<div class="main-box">
				<?php	
				if ( is_category() ) { ?>
				<ul class="breadcrumbs">
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a class="home" href="<?php bloginfo('url'); ?>" itemprop="url"><span itemprop="title"><?php echo $bread_crumbs_home; ?></span></a></li>
					<?php if(function_exists('get_parent_of_subcategory')){ if(get_parent_of_subcategory()){ ?>
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><?php echo get_parent_of_subcategory() ?></li>
					<?php }} ?>
				</ul>
				<?php } ?>
				<?php if ( is_single() ) {
				$post_id = get_the_ID();
				$category_post = get_the_category($post_id);
				$category_post_id = $category_post[0]->term_id;
				?>
				<ul class="breadcrumbs">
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a class="home" href="<?php bloginfo('url'); ?>" itemprop="url"><span itemprop="title"><?php echo $bread_crumbs_home; ?></span></a></li>
					<?php if($category_post[0]->category_parent){ ?>
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="<?php echo get_category_link($category_post[0]->category_parent); ?>" itemprop="url"><span itemprop="title"><?php echo get_cat_name($category_post[0]->category_parent) ?></span></a></li>
					<?php } ?>
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="<?php echo get_category_link($category_post[0]->term_id ); ?>" itemprop="url"><span itemprop="title"><?php echo $category_post[0]->cat_name; ?></span></a></li>
				</ul>
				<?php } ?>

				<?php if ( is_page() and !is_front_page() ) { ?>
				<ul class="breadcrumbs">
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a class="home" href="<?php bloginfo('url'); ?>" itemprop="url"><span itemprop="title"><?php echo $bread_crumbs_home; ?></span></a></li>
				</ul>
				<?php } ?>

				<?php if ( is_author() or is_tag() ) { ?>
				<ul class="breadcrumbs">
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a class="home" href="<?php bloginfo('url'); ?>" itemprop="url"><span itemprop="title"><?php echo $bread_crumbs_home; ?></span></a></li>
				</ul>
				<?php } ?>

				<?php if ( is_404() ) { ?>
				<ul class="breadcrumbs">
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a class="home" href="<?php bloginfo('url'); ?>" itemprop="url"><span itemprop="title"><?php echo $bread_crumbs_home; ?></span></a></li>
				</ul>
				<?php } ?>

				<?php if ( is_search() ) { ?>
				<ul class="breadcrumbs">
					<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a class="home" href="<?php bloginfo('url'); ?>" itemprop="url"><span itemprop="title"><?php echo $bread_crumbs_home; ?></span></a></li>
					<li>Поиск по запросу «<?php echo get_search_query(); ?>»</li>
				</ul>
				<?php } ?>