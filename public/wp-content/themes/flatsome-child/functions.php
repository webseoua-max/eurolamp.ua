<?php
// custom scripts
function eurolamp_scripts() {
	wp_enqueue_script('slick', get_stylesheet_directory_uri() . '/assets/js/slick.min.js', array('jquery'), false, true);
	wp_enqueue_script('eurolamp-custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), false, true);
}
add_action( 'wp_enqueue_scripts', 'eurolamp_scripts' );
// builder for categories
add_action( 'init', function () {
	if ( function_exists( 'add_ux_builder_post_type' ) ) {
		add_ux_builder_post_type( 'categories' );
	}
} );
// off xml
add_filter('xmlrpc_enabled', '__return_false');
//include 
add_filter('flatsome_lightbox_close_btn_inside', '__return_true');
//clasic widget
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );
//url widget
function alter_login_headerurl() {
return '/'; 
 }
add_action('login_headerurl','alter_login_headerurl');
//login page
function my_login_logo() { ?>
		<style type="text/css">
		.login form, .login #login_error, .login .message, .login .success {
		border-radius: 0px;
		font-family: "Exo 2", sans-serif;
		}
		#loginform input[type=text] {
     font-size: 12px;
		}
		#loginform #wp-submit {
		 background: #e4022e;
		 border: none;
		 width: 100%;
     margin-top: 10px;
		 border-radius: 5px;
		 height: 45px;
		}
		#nav, #backtoblog, .privacy-policy-page-link {
		font-family: "Exo 2", sans-serif;
		}
		body.login div#login h1 a {
		background-image: url(/wp-content/uploads/logo.png) !important;
		background-size: contain;
		width: 225px;}
		body {
		background: #444!important;
		}
		.login #backtoblog a, .login #nav a {
		padding: 2px;
		border-radius: 5px;
		}
		#nav a, #backtoblog a {
		color: #fff!important;	
		}
		</style>
		<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );
//polylang
add_action('init', function() {
  pll_register_string('Технология LED', 'Технология LED');
  pll_register_string('Умный свет', 'Умный свет');
  pll_register_string('Лампы ArtDeco', 'Лампы ArtDeco');
  pll_register_string('Лампы LED ArtDeco', 'Лампы LED ArtDeco');
  pll_register_string('Промышленная продукция', 'Промышленная продукция');	
  pll_register_string('Галогенные лампы', 'Галогенные лампы');
  pll_register_string('Перейти в магазин', 'Перейти в магазин');		
	pll_register_string('Облако тэгов:', 'Облако тэгов:');			
	pll_register_string('Все', 'Все');			
	pll_register_string('Новости', 'Новости');
	pll_register_string('Подробнее', 'Подробнее');		
	pll_register_string('Дата публикации', 'Дата публикации');			
			
  
});
//expert
function custom_excerpt_length($length) {
	return 15;
}
add_filter('excerpt_length', 'custom_excerpt_length');

function change_name($name) {
    return 'EuroLamp';
}  
add_filter('wp_mail_from_name','change_name');

function change_email($email) {
    return 'info@eurolamp.ua';
}  
add_filter('wp_mail_from','change_email');

