<?php
//Загрузка/отключение скриптов/стилей
function add_styles_scripts(){
	//Jquery
    wp_deregister_script('jquery-core');
    wp_register_script('jquery-core', '//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
    wp_enqueue_script('jquery');
    //Отключить стили плагина toc+
    wp_deregister_style('toc-screen');
}
add_action( 'wp_enqueue_scripts', 'add_styles_scripts' );

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

// Отключаем сам REST API
add_filter('rest_enabled', '__return_false');
// Отключаем фильтры REST API
remove_action( 'xmlrpc_rsd_apis',            'rest_output_rsd' );
remove_action( 'wp_head',                    'rest_output_link_wp_head', 10, 0 );
remove_action( 'template_redirect',          'rest_output_link_header', 11, 0 );
remove_action( 'auth_cookie_malformed',      'rest_cookie_collect_status' );
remove_action( 'auth_cookie_expired',        'rest_cookie_collect_status' );
remove_action( 'auth_cookie_bad_username',   'rest_cookie_collect_status' );
remove_action( 'auth_cookie_bad_hash',       'rest_cookie_collect_status' );
remove_action( 'auth_cookie_valid',          'rest_cookie_collect_status' );
remove_filter( 'rest_authentication_errors', 'rest_cookie_check_errors', 100 );
// Отключаем события REST API
remove_action( 'init',          'rest_api_init' );
remove_action( 'rest_api_init', 'rest_api_default_filters', 10, 1 );
remove_action( 'parse_request', 'rest_api_loaded' );
// Отключаем Embeds связанные с REST API
remove_action( 'rest_api_init',          'wp_oembed_register_route'              );
remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

add_action('do_feed', 'morkovin_fb_disable_feed', 1);
add_action('do_feed_rdf', 'morkovin_fb_disable_feed', 1);
add_action('do_feed_rss', 'morkovin_fb_disable_feed', 1);
add_action('do_feed_rss2', 'morkovin_fb_disable_feed', 1);
add_action('do_feed_atom', 'morkovin_fb_disable_feed', 1);

function morkovin_fb_disable_feed() {
    wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
}
add_filter( 'xmlrpc_methods', 'morkovin_sar_block_xmlrpc_attacks' );
function morkovin_sar_block_xmlrpc_attacks( $methods ) {
   unset( $methods['pingback.ping'] );
   unset( $methods['pingback.extensions.getPingbacks'] );
   return $methods;
}
add_filter( 'wp_headers', 'morkovin_sar_remove_x_pingback_header' );
function morkovin_sar_remove_x_pingback_header( $headers ) {
   unset( $headers['X-Pingback'] );
   return $headers;
}
//Отключаем srcset и sizes для картинок в WordPress
add_filter('wp_calculate_image_srcset_meta', '__return_null' );
add_filter('wp_calculate_image_sizes', '__return_false', 99 );
remove_filter('the_content', 'wp_make_content_images_responsive' );
add_filter('wp_get_attachment_image_attributes', 'unset_attach_srcset_attr', 99 );
function unset_attach_srcset_attr( $attr ){
foreach( array('sizes','srcset') as $key )
    if( isset($attr[ $key ]) ) unset($attr[ $key ]);
    return $attr;
}

include('class.Kama_Make_Thumb.php');

add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('title-tag');

register_nav_menu('nav_main', 'Главное меню');
register_nav_menu('nav_add', 'Дополнительное выпадающее');
register_nav_menu('nav_sidebar', 'Меню в сайдбаре');
register_nav_menu('nav_m', 'Меню для мобильной версии');
register_nav_menu('nav_footer', 'Меню в подвале');

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Верх сайдбара',
		'id' => "sidebar-1",
		'description' => '',
		'before_widget' => '<div class="section section_widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="title">',
		'after_title' => '</div>',
	));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Низ сайдбара',
		'id' => "sidebar-2",
		'description' => '',
		'before_widget' => '<div class="section section_widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="title">',
		'after_title' => '</div>',
	));

// удаляем ссылку с активного пункта меню
function no_link_current_page($menu) {
	return preg_replace('%((current_page_item|current-menu-item)[^<]+)[^>]+>([^<]+)</a>%', '$1<span>$3</span>', $menu);
}
add_filter('wp_nav_menu', 'no_link_current_page');

//Удалить атрибут rel
function my_remove_rel_attr($content) {
    return preg_replace('/\s+rel="attachment wp-att-[0-9]+"/i', '', $content);
}
add_filter('the_content', 'my_remove_rel_attr');

//Удалить дубли контента
function del_number_url_morkovin(){
	$link = $_SERVER['REQUEST_URI'];

	preg_match("/^(.+)\/([0-9]+)$/", $link, $matches);

	if ( is_single() and $matches[2] ) {
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
	}
}
add_action('wp', 'del_number_url_morkovin', -10);

function new_excerpt_more($more) {
	global $post;
	return '';
}
add_filter('excerpt_more', 'new_excerpt_more');

//Количество просмотров записи
function get_post_views($postID) {
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if ($count == '') {
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
		return '0';
	}
	return $count;
}

function set_post_views($postID) {
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if ($count == '') {
		$count = 0;
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
	} else {
		$count++;
		update_post_meta($postID, $count_key, $count);
	}
}

add_filter('the_content', 'set_post_views_in_single');

function set_post_views_in_single($content) {
	if ( is_single() ) {
		set_post_views( get_the_ID() );
	}
	
	return $content;
}

//Хлебные крошки
function get_parent_of_subcategory($cat_id = false) {
	if($cat_id){
		$cat = get_category($cat_id);	
	} else {
		$cat = get_category( get_query_var('cat'),false );
	}
	
	$cat_parent_id = $cat->parent;

	if($cat_parent_id)
	{
		$cat_name = get_cat_name($cat_parent_id);
		$cat_url = get_category_link($cat_parent_id);

		return "<a href=\"$cat_url\" itemprop=\"url\"><span itemprop=\"title\">$cat_name</span></a>";
	}
	else
	{
		$cat_name = "";

		return FALSE;
	}
}

//Комментарии
function mytheme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
<li id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="gravatar"><?php echo get_avatar($comment, $size='50', $default=''); ?></div>
		<div class="comment_content">
			<div class="cauthor">
				<span class="author_name"><?php printf(__('<span class="fn">%s</span>'), get_comment_author_link()) ?></span>
				<span class="comment_date"> | <?php comment_date('j.m.Y H:i');?> <?php edit_comment_link(__('(Edit)'),'  ','') ?></span>
			</div>

			<div class="ctext">
				<?php if ($comment->comment_approved == '0') : ?>
					<p><em><?php _e('Your comment is awaiting moderation.') ?></em></p>
				<?php endif; ?>
				<?php comment_text() ?>

				<?php if (/*comments_open() AND */(get_option('thread_comments') == 1) AND ($depth != $args['max_depth'])) { ?>
				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>
				<?php } ?>
			</div><!-- .ctext -->
		</div><!-- .comment_content -->
	</div>
<?php
}

function src_simple_recent_comments($src_count=7, $src_length=60) {
	global $wpdb;
	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_date, comment_approved, comment_type,
		SUBSTRING(comment_content,1,$src_length) AS com_excerpt
		FROM $wpdb->comments
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
		WHERE comment_approved = '1' AND comment_type = '' AND post_password = ''
		ORDER BY comment_date_gmt DESC
		LIMIT $src_count";
	$comments = $wpdb->get_results($sql);
	foreach ($comments as $comment) {
		// $date = apply_filters('the_time', mysql2date("j F, H:i", $comment->comment_date));
?>
		<li>
			<p><?php echo strip_tags($comment->com_excerpt) ?>...</p>
			от: <?php echo $comment->comment_author ?> <a href="<?php echo get_permalink($comment->ID) ?>#comment-<?php echo $comment->comment_ID ?>"><?php echo $comment->post_title ?></a>
		</li>
<?php
	}
}

function delete_rel($link) {
    return str_replace('rel="category tag"', "", $link);
}
add_filter('the_category', 'delete_rel');

// удаляем лишние отступы у изображений с подписью
add_filter( 'img_caption_shortcode', 'my_img_caption_shortcode', 10, 3 );
function my_img_caption_shortcode( $empty, $attr, $content ){
	$attr = shortcode_atts( array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => '',
		'caption' => ''
	), $attr );
	if ( 1 > (int) $attr['width'] || empty( $attr['caption'] ) ) { return ''; }
	if ( $attr['id'] ) { $attr['id'] = 'id="' . esc_attr( $attr['id'] ) . '" '; }
	return '<div ' . $attr['id']
	. 'class="wp-caption ' . esc_attr( $attr['align'] ) . '" '
	. 'style="max-width: ' . ( (int) $attr['width'] ) . 'px;">'
	. do_shortcode( $content )
	. '<div class="wp-caption-text">' . $attr['caption'] . '</div>'
	. '</div>';
}

if (is_admin()) {
	// колонка "ID" для таксономий (рубрик, меток и т.д.) в админке
	foreach (get_taxonomies() as $taxonomy) {
		add_action("manage_edit-${taxonomy}_columns",          'tax_add_col');
		add_filter("manage_edit-${taxonomy}_sortable_columns", 'tax_add_col');
		add_filter("manage_${taxonomy}_custom_column",         'tax_show_id', 10, 3);
	}
	add_action('admin_print_styles-edit-tags.php', 'tax_id_style');
	function tax_add_col($columns) {return $columns + array ('tax_id' => 'ID');}
	function tax_show_id($v, $name, $id) {return 'tax_id' === $name ? $id : $v;}
	function tax_id_style() {print '<style>#tax_id{width:4em}</style>';}

	// колонка "ID" для постов и страниц в админке
	add_filter('manage_posts_columns', 'posts_add_col', 5);
	add_action('manage_posts_custom_column', 'posts_show_id', 5, 2);
	add_filter('manage_pages_columns', 'posts_add_col', 5);
	add_action('manage_pages_custom_column', 'posts_show_id', 5, 2);
	add_action('admin_print_styles-edit.php', 'posts_id_style');
	function posts_add_col($defaults) {$defaults['wps_post_id'] = __('ID'); return $defaults;}
	function posts_show_id($column_name, $id) {if ($column_name === 'wps_post_id') echo $id;}
	function posts_id_style() {print '<style>#wps_post_id{width:4em}</style>';}
}

// отключаем стили YARPP
add_action('wp_print_styles','lm_dequeue_header_styles');
function lm_dequeue_header_styles() { wp_dequeue_style('yarppWidgetCss'); }
add_action('wp_footer','lm_dequeue_footer_styles');
function lm_dequeue_footer_styles() { wp_dequeue_style('yarppRelatedCss'); }

function genesis(){};

//noindex для toc+
add_filter( 'the_content', 'morkovin_noindex_toc', 1000);
function morkovin_noindex_toc($content){
     return preg_replace('/(<div id="toc_container"[^>]+>[^\n]+)/', '<!--noindex-->$1<!--/noindex-->', $content);
}

//настройки темы
add_action('customize_register', function($customizer){
    
    //общие настройки ------------------------------------------------------------------------
    $customizer->add_section(
        'section_general',
        array(
            'title' => 'Общие настройки',
            'description' => 'Опции',
            'priority' => 35,
        )
    );

    //Описание главной страницы
    $customizer->add_setting(
        'page_home'
    );
    $customizer->add_control(
        'page_home',
        array(
            'type' => 'dropdown-pages',
            'label' => 'Выберите страницу для главной',
            'section' => 'section_general',
        )
    );

    //Текс хлебной крошки
    $customizer->add_setting(
        'bread_crumbs'
    );
    $customizer->add_control(
        'bread_crumbs',
        array(
            'label' => 'Текст первой хлебной крошки',
            'section' => 'section_general',
            'type' => 'text',
        )
    );

    //Вывод даты
    $customizer->add_setting(
        'show_date'
    );
    $customizer->add_control(
        'show_date',
        array(
            'type' => 'select',
            'label' => 'Отображать дату',
            'section' => 'section_general',
            'choices' => array(
                '1' => 'Да',
                '0' => 'Нет',
            ),
        )
    );

    //Отображение количества комментариев
    $customizer->add_setting(
        'show_comments_number'
    );
    $customizer->add_control(
        'show_comments_number',
        array(
            'type' => 'select',
            'label' => 'Отображать кол-во коментов',
            'section' => 'section_general',
            'choices' => array(
                '1' => 'Да',
                '0' => 'Нет',
            ),
        )
    );

    //Цитата
    $customizer->add_setting(
        'excerpt_or_content'
    );
    $customizer->add_control(
        'excerpt_or_content',
        array(
            'type' => 'select',
            'label' => 'Что выводить в анонсе поста',
            'section' => 'section_general',
            'choices' => array(
                '1' => 'Цитата',
                '2' => 'Описание из плагина Yoast SEO',
            ),
        )
    );

    //Количество выводимых постов на главной
    $customizer->add_setting(
        'posts_per_home'
    );
    $customizer->add_control(
        'posts_per_home',
        array(
            'label' => 'Количество постов на главной (по умолчанию 6)',
            'section' => 'section_general',
            'type' => 'text',
        )
    );

    //Поиск
    $customizer->add_setting(
        'selection_search_site'
    );
    $customizer->add_control(
        'selection_search_site',
        array(
            'type' => 'select',
            'label' => 'Поиск по сайту',
            'section' => 'section_general',
            'choices' => array(
                '1' => 'Wordpress (по умолчанию)',
                '2' => 'Яндекс поиск',
            ),
        )
    );

    //Социальные профили ------------------------------------------------------------------------
    $customizer->add_section(
        'section_social',
        array(
            'title' => 'Ссылки на cоц. сети',
            'description' => 'Ссылки на соц. сети',
            'priority' => 35,
        )
    );

    //ок
    $customizer->add_setting(
        'link_ok'
    );
    $customizer->add_control(
        'link_ok',
        array(
            'label' => 'Однокласники',
            'section' => 'section_social',
            'type' => 'text',
        )
    );
    //yt
    $customizer->add_setting(
        'link_yt'
    );
    $customizer->add_control(
        'link_yt',
        array(
            'label' => 'YouTube',
            'section' => 'section_social',
            'type' => 'text',
        )
    );
    //fb
    $customizer->add_setting(
        'link_fb'
    );
    $customizer->add_control(
        'link_fb',
        array(
            'label' => 'Facebook',
            'section' => 'section_social',
            'type' => 'text',
        )
    );
    //gp
    $customizer->add_setting(
        'link_gp'
    );
    $customizer->add_control(
        'link_gp',
        array(
            'label' => 'GP',
            'section' => 'section_social',
            'type' => 'text',
        )
    );
    //tw
    $customizer->add_setting(
        'link_tw'
    );
    $customizer->add_control(
        'link_tw',
        array(
            'label' => 'Twitter',
            'section' => 'section_social',
            'type' => 'text',
        )
    );
    //in
    $customizer->add_setting(
        'link_in'
    );
    $customizer->add_control(
        'link_in',
        array(
            'label' => 'Instagram',
            'section' => 'section_social',
            'type' => 'text',
        )
    );
    //vk
    $customizer->add_setting(
        'link_vk'
    );
    $customizer->add_control(
        'link_vk',
        array(
            'label' => 'Вконтакте',
            'section' => 'section_social',
            'type' => 'text',
        )
    );

    // Стили ------------------------------------------------------------------------
    $customizer->add_section(
        'section_styles',
        array(
            'title' => 'Стили темы',
            'description' => 'Стили',
            'priority' => 35,
        )
    );

    //Цвет 1
    $customizer->add_setting(
        'section_color_1',
        array(
            'default' => '#6969b3',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $customizer->add_control(
        new WP_Customize_Color_Control(
            $customizer,
            'section_color_1',
            array(
                'label' => 'Цвет 1',
                'section' => 'section_styles',
                'settings' => 'section_color_1',
            )
        )
    );

    //Цвет 2
    $customizer->add_setting(
        'section_color_2',
        array(
            'default' => '#5a5aa1',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $customizer->add_control(
        new WP_Customize_Color_Control(
            $customizer,
            'section_color_2',
            array(
                'label' => 'Цвет 2',
                'section' => 'section_styles',
                'settings' => 'section_color_2',
            )
        )
    );

    //Цвет 3
    $customizer->add_setting(
        'section_color_3',
        array(
            'default' => '#5a5aa1',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $customizer->add_control(
        new WP_Customize_Color_Control(
            $customizer,
            'section_color_3',
            array(
                'label' => 'Цвет 3',
                'section' => 'section_styles',
                'settings' => 'section_color_3',
            )
        )
    );

    //Логотип в шапке
    $customizer->add_setting('logo_upload');
    $customizer->add_control(
        new WP_Customize_Image_Control(
            $customizer,
            'logo_upload',
            array(
                'label' => 'Логотип в шапке)',
                'section' => 'section_styles',
                'settings' => 'logo_upload'
            )
        )
    );

    //Логотип в подвале
    $customizer->add_setting('logo_footer_upload');
    $customizer->add_control(
        new WP_Customize_Image_Control(
            $customizer,
            'logo_footer_upload',
            array(
                'label' => 'Логотип в подвале',
                'section' => 'section_styles',
                'settings' => 'logo_footer_upload'
            )
        )
    );

    //Фавикон
    $customizer->add_setting('site_ico');
    $customizer->add_control(
        new WP_Customize_Image_Control(
            $customizer,
            'site_ico',
            array(
                'label' => 'Favicon.ico (16x16)',
                'section' => 'section_styles',
                'settings' => 'site_ico'
            )
        )
    );
});

//Опции темы
//Лого
$logo_upload = get_theme_mod('logo_upload', get_bloginfo('template_url').'/images/logo.png');  //по умолчанию стандартный логотип
$logo_footer_upload = get_theme_mod('logo_footer_upload', false);  //по умолчанию стандартный логотип

//Страница текст которой выводится в описании на главной
$homepage = get_theme_mod('page_home', false); //по умолчанию не выводим

//Хлебные крошки
$bread_crumbs_home = get_theme_mod('bread_crumbs', 'Главная'); //по умолчанию "главная"
if (!$bread_crumbs_home) $bread_crumbs_home = 'Главная'; 

//Вывод даты
$show_date = get_theme_mod('show_date', 1);

//Отображение количества комментариев
$show_comments_number = get_theme_mod('show_comments_number', 1);

//Что выводим в анонсе поста
$excerpt_or_content = get_theme_mod('excerpt_or_content', 1); //по умолчанию цитата

//Поиск по сайту
$selection_search_site = get_theme_mod('selection_search_site', 1); //по умолчанию поиск от вордпресс

//Количество постов на главной
$posts_per_home = get_theme_mod('posts_per_home', 6); //по умолчанию 6

//Соц сети
$social_ok = get_theme_mod('link_ok', '');
$social_yt = get_theme_mod('link_yt', '');
$social_fb = get_theme_mod('link_fb', '');
$social_gp = get_theme_mod('link_gp', '');
$social_tw = get_theme_mod('link_tw', '');
$social_in = get_theme_mod('link_in', '');
$social_vk = get_theme_mod('link_vk', '');

//фавиконка
$site_ico = get_theme_mod('site_ico', get_bloginfo('template_url').'/images/favicon.ico');

//Всплывающая подсказка
add_action( 'admin_enqueue_scripts', 'jdm_tut_pointer_header' );
function jdm_tut_pointer_header() {
    $enqueue = false;

    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

    if ( ! in_array( 'jdm_tut_pointer', $dismissed ) ) {
        $enqueue = true;
        add_action( 'admin_print_footer_scripts', 'jdm_tut_pointer_footer' );
    }

    if ( $enqueue ) {
        wp_enqueue_script( 'wp-pointer' );
        wp_enqueue_style( 'wp-pointer' );
    }
}

function jdm_tut_pointer_footer() {
    $pointer_content = '<h3>Доро пожаловать в тему для марафонцев!</h3>';
    $pointer_content .= '<p>Для настроки темы воспользуйтесь пунктом меню внешний вид - настроить.</p>';
    $pointer_content .= '<p>Тема разработана командой Андрея Морковина.</p>';
    $pointer_content .= '<p><a href="https://docs.google.com/document/d/1u8qLmBg4Dj-8g5uKJHwID6eNYrkJGkisX1_gF7mM40o">Инструкция по работе с темой.</a></p>';
?>
<script type="text/javascript">// <![CDATA[
jQuery(document).ready(function($) {
    $('#menu-appearance').pointer({
        content: '<?php echo $pointer_content; ?>',
        position: {
            edge: 'left',
            align: 'center'
        },
        close: function() {
            $.post( ajaxurl, {
                pointer: 'jdm_tut_pointer',
                action: 'dismiss-wp-pointer'
            });
        }
    }).pointer('open');
});
// ]]></script>
<?php
}

add_filter('widget_text', 'do_shortcode');

remove_action('wp_enqueue_scripts', 'mfbfw_scripts');
remove_action( 'wp_enqueue_scripts', 'mfbfw_styles' );
remove_action( 'wp_head', 'mfbfw_init' );
add_action('wp', 'add_fancybox_scripts_single');
function add_fancybox_scripts_single(){
    if( is_singular() ){
        add_action('wp_enqueue_scripts', 'mfbfw_scripts');
        add_action( 'wp_enqueue_scripts', 'mfbfw_styles' );
        add_action( 'wp_head', 'mfbfw_init' );
    }
}

add_filter( 'the_content', 'morkovin_remove_http_to_https' ); 
function morkovin_remove_http_to_https($content) {
    if ( is_singular() ) {
        $content = str_replace('src="http:', 'src="https:', $content);
    }

    return $content;
}


/**
 * Пересчет времени из минут в часы
 */
if ( ! function_exists( 'artabr_convert_minute_hour' ) ) {
	function artabr_convert_minute_hour( $time ) {
		$hours_postfix = '&nbsp;час';
		$minutes_postfix = '&nbsp;мин';
	    settype( $time, 'integer' );
	    if ( $time < 1 ) {
	        return;
	    }
	    $hours = floor( $time / 60 );
	    $minutes = $time % 60;
	    if ( $time < 60 ) {
	    	$content = $minutes . $minutes_postfix;
	    } else {
   		$content = $hours . $hours_postfix . '&ensp;' . $minutes . $minutes_postfix;
	    }
	    return $content;
	}
}

/**
 * Подсчет общего времени
 */
if ( ! function_exists( 'artabr_calc_total_cook_time' ) ) {
	function artabr_calc_total_cook_time() {
		global $post;
		$prep_time = (int) ( CFS()->get('vd_recipe_ing_prepTime'));
		$cook_time = (int) ( CFS()->get('vd_recipe_ing_cookTime') );
		$total_time = $prep_time + $cook_time;
		return $total_time;
	}
}

/**
 * Пересчет времени в формат ISO8601
 */
if ( ! function_exists( 'schema_convert_minute_hour' ) ) {
	function schema_convert_minute_hour( $time ) {
		$hours_postfix = 'h';
		$minutes_postfix = 'm';
	    settype( $time, 'integer' );
	    if ( $time < 1 ) {
	        return;
	    }
	    $hours = floor( $time / 60 );
	    $minutes = $time % 60;
	    if ( $time < 60 ) {
	    	$content = $minutes . $minutes_postfix;
	    } else {
	   		$content = $hours . $hours_postfix . ' ' . $minutes . $minutes_postfix;
	    }
	    return $content;
	}
}

/**
 * Пересчет времени подготовки в формат ISO8601
 */
if ( ! function_exists( 'schema_prep_time' ) ) {
	function schema_prep_time() {
		global $post;
		$prep_time = schema_convert_minute_hour( CFS()->get('vd_recipe_ing_prepTime') );
		$prep_time_alt = strtoupper( str_replace( array( ' ', '-', ',' ), array( '' ), $prep_time ) );
		$prep_time_schema = 'PT' . $prep_time_alt;
		return $prep_time_schema;
	}
}

/**
 * Время приготовления в формат ISO8601
 */
if ( ! function_exists( 'schema_cook_time' ) ) {
	function schema_cook_time() {
		global $post;
		$cook_time = schema_convert_minute_hour( CFS()->get('vd_recipe_ing_cookTime') );
		$cook_time_alt = strtoupper( str_replace( array( ' ', '-', ',' ), array( '' ), $cook_time ) );
		$cook_time_schema  = 'PT' . $cook_time_alt;
		return $cook_time_schema;
	}
}

/**
 * Пересчет общего времени в формат ISO8601
 */
if ( ! function_exists( 'schema_total_time' ) ) {
	function schema_total_time() {
		global $post;
		$total_time = schema_convert_minute_hour ( artabr_calc_total_cook_time() );
		$total_time_alt = strtoupper( str_replace( array( ' ', '-', ',' ), array( '' ), $total_time ) );
		$total_time_schema = 'PT' . $total_time_alt;
		return $total_time_schema;
	}
}


// ==========================================================================
// Гугл или яндекс реклама
// скрипт вешает класс "from-yandex" на тег <body> если пользователь с яндекса
function from_yandex()
{
    if (isset($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], 'yandex.')) {
        setcookie('from_yandex', 'yes', 0, '/');
        return true;
    }

    if (isset($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], 'google.')) {
        setcookie('from_yandex', 'yes', 1, '/');
        return false;
    }

    if (isset($_COOKIE['from_yandex'])) {
        return true;
    }
    
    return false;
}


function from_yandex_class()
{
    return from_yandex() ? 'from-yandex' : '';
}
    

// function from_yandex_class_($classes)
// {
//     $classes[] = 'from-yandex';
    
//     return $classes;
// }

// if(from_yandex())   add_filter('body_class', 'from_yandex_class_');
// ==========================================================================