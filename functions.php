<?php 
	add_action( 'wp_enqueue_scripts', 'my_scripts_method');
	add_action('wp_enqueue_scripts', 'style_theme');
	add_action('wp_footer', 'scripts_theme');
	add_action( 'after_setup_theme', 'theme_register_nav_menu' );
	add_action( 'widgets_init', 'register_my_widgets' );
	add_action( 'my_action', 'my_action_func');

	add_action('wp_ajax_send_mail', 'send_mail');
    add_action('wp_ajax_nopriv_send_mail', 'send_mail');
	
	function send_mail() {
	 $contactName = $_POST['contactName'];
	 $contactEmail = $_POST['contactEmail'];
	 $contactSubject = $_POST['contactSubject'];
	 $contactMessage = $_POST['contactMessage'];	
	 $to = get_option( 'admin_email' );
	 // подразумевается что $to, $subject, $message уже определены...

	 // удалим фильтры, которые могут изменять заголовок $headers
	 remove_all_filters( 'wp_mail_from' );
	 remove_all_filters( 'wp_mail_from_name' );

	 $headers = array(
	 	'From: Me Myself <me@example.net>',
	 	'content-type: text/html',
	 	'Cc: John Q Codex <jqc@wordpress.org>',
	 	'Cc: iluvwp@wordpress.org', // тут можно использовать только простой email адрес
	 );

	 wp_mail( $to, $contactSubject, $contactMessage, $headers );
	 wp_die();	
	}


	add_action( 'init', 'register_post_types' );
	function register_post_types(){
		register_post_type( 'portfolio', [
			'label'  => null,
			'labels' => [
				'name'               => 'Портфолио', // основное название для типа записи
				'singular_name'      => 'Портфолио', // название для одной записи этого типа
				'add_new'            => 'Добавить работу', // для добавления новой записи
				'add_new_item'       => 'Добавление работф', // заголовка у вновь создаваемой записи в админ-панели.
				'edit_item'          => 'Редактирование работы', // для редактирования типа записи
				'new_item'           => 'Новая работа', // текст новой записи
				'view_item'          => 'Смотреть работу', // для просмотра записи этого типа.
				'search_items'       => 'Искать работу в портфолио', // для поиска по этим типам записи
				'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
				'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
				'parent_item_colon'  => '', // для родителей (у древовидных типов)
				'menu_name'          => 'Портфолио', // название меню
			],
			'description'         => '',
			'public'              => true,
			'publicly_queryable'  => true, // зависит от public
			'exclude_from_search' => false, // зависит от public
			'show_ui'             => true, // зависит от public
			'show_in_nav_menus'   => true, // зависит от public
			'show_in_menu'        => true, // показывать ли в меню адмнки
			'show_in_admin_bar'   => true, // зависит от show_in_menu
			'show_in_rest'        => true, // добавить в REST API. C WP 4.7
			'rest_base'           => null, // $post_type. C WP 4.7
			'menu_position'       => 9,
			'menu_icon'           => 'dashicons-format-gallery',
			'hierarchical'        => false,
			'supports'            => [ 'title','editor','author','thumbnail','excerpt' ], 
			'taxonomies'          => [],
			'has_archive'         => false,
			'rewrite'             => true,
			'query_var'           => true,
			
		] );
	}

	// хук для регистрации
	add_action( 'init', 'create_taxonomy' );
	function create_taxonomy(){

		// список параметров: wp-kama.ru/function/get_taxonomy_labels
		register_taxonomy( 'skills', [ 'portfolio' ], [ 
			'label'                 => '', // определяется параметром $labels->name
			'labels'                => [
				'name'              => 'Навыки',
				'singular_name'     => 'Навык',
				'search_items'      => 'Найти навыки',
				'all_items'         => 'Все навыки',
				'view_item '        => 'Показать навык',
				'parent_item'       => 'Родительский навык',
				'parent_item_colon' => 'Родительский навык:',
				'edit_item'         => 'Изменить навык',
				'update_item'       => 'Обновить навык',
				'add_new_item'      => 'Добавить новый навык',
				'new_item_name'     => 'Название нового навыка',
				'menu_name'         => 'Навыки',
			],
			'description'           => 'Навыки,которые использовались в работе', // описание таксономии
			'public'                => true,
			'hierarchical'          => false,
			'rewrite'               => true,
			//'query_var'             => $taxonomy, // название параметра запроса
			'capabilities'          => array(),
			'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
			'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
			'show_in_rest'          => true, // добавить в REST API
			'rest_base'             => null, // $taxonomy
			
		] );
	}

	
	function my_scripts_method() {
	    wp_deregister_script('jquery');
	    wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js');
		}  
	    
	function style_theme() {
		wp_enqueue_style('style', get_stylesheet_uri());
		wp_enqueue_style('default', get_template_directory_uri() . '/assets/css/default.css' );
		wp_enqueue_style('layout', get_template_directory_uri() . '/assets/css/layout.css' );
		wp_enqueue_style('media-queries', get_template_directory_uri() . '/assets/css/media-queries.css' );
		add_action( 'widgets_init', 'register_my_widgets' );
	}
	
	function scripts_theme() {
		wp_enqueue_script('flexslider', get_template_directory_uri() . '/assets/js/jquery.flexslider.js');
		wp_enqueue_script('doubletaptogo', get_template_directory_uri() . '/assets/js/doubletaptogo.js');
		wp_enqueue_script('init', get_template_directory_uri() . '/assets/js/init.js');
		wp_enqueue_script('modernizr', get_template_directory_uri() . '/assets/js/modernizr.js');
		wp_enqueue_script('main', get_template_directory_uri() . '/assets/js/main.js');
	} 

	function theme_register_nav_menu() {
		register_nav_menu( 'top', 'Меню в шапке' );
		register_nav_menu( 'footer', 'Меню в подвале' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails', array( 'post', 'portfolio') );
		add_theme_support( 'post-formats', array( 'aside', 'video' ) );
		add_image_size( 'post_thumb', 1300, 500, true );
		add_filter( 'excerpt_more', 'new_excerpt_more' );
		function new_excerpt_more( $more ){
			global $post;
			return '<a href="'. get_permalink($post) . '"> Читать дальше...</a>';
		}

		add_filter( 'excerpt_length', function(){
			return 40;
		} );

		// удаляет H2 из шаблона пагинации
		add_filter('navigation_markup_template', 'my_navigation_template', 10, 2 );
		function my_navigation_template( $template, $class ){
			/*
			Вид базового шаблона:
			<nav class="navigation %1$s" role="navigation">
				<h2 class="screen-reader-text">%2$s</h2>
				<div class="nav-links">%3$s</div>
			</nav>
			*/

			return '
			<nav class="navigation %1$s" role="navigation">
				<div class="nav-links">%3$s</div>
			</nav>    
			';
		}

		// выводим пагинацию
		the_posts_pagination( array(
			'end_size' => 2,
		) ); 
	}
	
	function register_my_widgets(){
		register_sidebar( array(
			'name'          => 'Right sidebar',
			'id'            => "right-sidebar",
			'before_widget' => '<div class="widget %2$s">',
			'after_widget'  => "</div>\n",
			'before_title'  => '<h5 class="widgettitle">',
			'after_title'   => "</h5>\n",
		) );

		register_sidebar( array(
			'name'          => 'Top sidebar',
			'id'            => "top-sidebar",
			'before_widget' => '<div class="widget %2$s">',
			'after_widget'  => "</div>\n",
			'before_title'  => '<h5 class="widgettitle">',
			'after_title'   => "</h5>\n",
		) );
	}
	
	function my_search_form( $form ) {
	        $form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >
	        <div><label class="screen-reader-text" for="s">' . __( 'Search for:' ) . '</label>
	        <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="Search here..." />
	        <input type="submit" id="searchsubmit" value="'. esc_attr__( 'Search' ) .'" />
	        </div>	        </form>';
	        return $form;
	    }   
		
		add_filter( 'get_search_form', 'my_search_form', 100 );

		add_filter( 'document_title_separator', 'separator' );
		
		function separator( $sep ){
			$sep = '|';

			return $sep;
		}


		add_filter( 'the_content', 'end_word_func');
		
		function end_word_func ( $content )	{
			$content.= 'Thank for reading!';
			return  $content;
		}

		function my_action_func() {
			echo "We love you!";
		}

		add_shortcode( 'my_short', 'my_short_func' );
		function my_short_func() {
			return 'Shortcode!';
		}

		function Generate_iframe( $atts ) {
			$atts = shortcode_atts( array(
				'href'   => 'https://wp-kama.ru',
				'height' => '300px',
				'width'  => '400px',     
			), $atts );

			return '<iframe src="'. $atts['href'] .'" width="'. $atts['width'] .'" height="'. $atts['height'] .'"> <p>Your Browser does not support Iframes.</p></iframe>';
		}
		add_shortcode('iframe', 'Generate_iframe');
		
		 

	


	

	




