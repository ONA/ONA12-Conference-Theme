<?php

define( 'ONA12_VERSION', '0.1' );

require_once( dirname( __FILE__ ) . '/php/class-session.php' );
require_once( dirname( __FILE__ ) . '/php/class-presenter.php' );

if ( defined( 'WP_CLI' ) && WP_CLI )
	require_once( dirname( __FILE__ ) . '/php/class-wp-cli.php' );

class ONA12 {

	var $session;

	function __construct() {

		$this->session = new ONA12_Session();
		$this->presenter = new ONA12_Presenter;

		add_action( 'after_setup_theme', array( $this, 'action_after_setup_theme' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'init', array( $this, 'action_init' ) );

		add_filter( 'excerpt_length', array( $this, 'filter_excerpt_length' ) );

	}

	/**
	 * Theme customization options
	 */
	function action_after_setup_theme() {

		add_theme_support( 'post-thumbnails' );

		add_image_size( 'ona12-small-square-avatar', 48, 48, true );
		add_image_size( 'ona12-medium-tall-avatar', 120, 160, true );

		// Support for liveblogging on sessions if liveblog exists
		if ( class_exists( 'WPCOM_Liveblog' ) ) {
			add_post_type_support( ONA12_Session::post_type, WPCOM_Liveblog::key );
			add_filter( 'liveblog_force_backwards_compat', '__return_true' );
		}
	}

	/**
	 * Enqueue scripts and styles
	 */
	function enqueue_scripts() {

		// Scripts
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/_/js/modernizr-1.7.min.js', array( 'jquery' ) );

		// Styles
		wp_enqueue_style( 'ona12-css', get_stylesheet_uri(), false, ONA12_VERSION );
		wp_enqueue_style( 'ona12-grid-css', get_stylesheet_directory_uri() . '/960.css', false, ONA12_VERSION );

	}

	/**
	 * Register menus, custom post types, etc.
	 */
	function action_init() {

		register_nav_menus(
			array('navigation-menu' => __( 'Navigation Menu' ) )
		);

		$args = array(
			'label' => __( 'Front Page Slides' ),
			'labels' => array(
				'name' => __( 'Front Page Slides' ),
				'singular_name' => __( 'Front Page Slide' )
			),
			'public' => true,
			'rewrite' => false,
			'has_archive' => true, 
			'supports' => array(
				'title',
				'editor',
				'revisions'
				),
			'taxonomies' => array( 'slidetype' ),
			);
		register_post_type( 'frontpageslide', $args );

		register_sidebar(array(
			'name'           => 'Calendar',
			'id'             => 'calendar-widgets',
			'description'    => 'This fills in the ONA12 calendar.',
			'before_widget'  => '<div id="%1$s" class="grid_2 widget %2$s">',
			'after_widget'   => '</div>',
			'before_title'   => '<h4>',
			'after_title'    => '</h4>'
		));

		register_sidebar(array(
			'name' => 'Sponsors (Top)',
			'id'   => 'sponsors-top-widgets',
			'description'   => 'This fills the top sponsor area.',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>'
		));

		if ( function_exists( 'p2p_register_connection_type' ) ) {
			// Sessions should be able to have speakers
			p2p_register_connection_type( array(
				'name'      => 'sessions_to_presenters',
				'from'      => ONA12_Session::post_type,
				'to'        => ONA12_Presenter::post_type,
				'can_create_post'  => false,
			));
		}
	}

	/**
	 * Make excerpts shorter for sessions so they don't destroy the page
	 */
	function filter_excerpt_length( $length ) {
		if ( ONA12_Session::post_type != get_post_type() )
			return $length;
		return 20;
	}
}

global $ona12;
$ona12 = new ONA12();