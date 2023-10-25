<?php
/**
 * GoldBelt Heritage functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package GoldBelt_Heritage
 */

if ( ! defined( '_S_VERSION' ) ) {
	define( '_S_VERSION', '1.0.0' );
}
if(!defined('GHF_IMAGES')){define('GHF_IMAGES',get_template_directory_uri() . '/assets/images');}

if ( ! function_exists( 'goldbelt_heritage_setup' ) ) :

	function goldbelt_heritage_setup() {
		load_theme_textdomain( 'goldbelt-heritage', get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		register_nav_menus(
			array(
				'main-header-menu' => esc_html__( 'Main Header Menu', 'goldbelt-heritage' ),
				'main-header-mobile-menu' => esc_html__( 'Main Header Mobile Menu', 'goldbelt-heritage' ),
			)
		);
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);
		add_theme_support(
			'custom-background',
			apply_filters(
				'goldbelt_heritage_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);


		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'goldbelt_heritage_setup' );

function goldbelt_heritage_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'goldbelt_heritage_content_width', 640 );
}
add_action( 'after_setup_theme', 'goldbelt_heritage_content_width', 0 );




function goldbelt_heritage_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'goldbelt-heritage' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'goldbelt-heritage' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'goldbelt_heritage_widgets_init' );


function goldbelt_heritage_scripts() {
	wp_enqueue_style( 'goldbelt-heritage-style', get_stylesheet_uri(), array(), _S_VERSION );

	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), _S_VERSION );
	wp_enqueue_style( 'goldbelt-css', get_template_directory_uri() . '/assets/css/style.css', array(), _S_VERSION );
	wp_enqueue_style( 'goldbelt-heritage-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array('jquery'), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'goldbelt_heritage_scripts' );


require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


function ghf_logo(){
	echo '
	<div class="logo-ghf-main">
		<img src="'.GHF_IMAGES.'/Logo.png"/>
	</div>
	';
}


