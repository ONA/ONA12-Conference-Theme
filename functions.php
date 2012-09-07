<?php

require_once( dirname( __FILE__ ) . '/php/class-session.php' );
require_once( dirname( __FILE__ ) . '/php/class-presenter.php' );

class ONA12 {

	var $session;

	function __construct() {

		$this->session = new ONA12_Session();
		$this->presenter = new ONA12_Presenter;

		add_action( 'after_setup_theme', array( $this, 'action_after_setup_theme' ) );
		add_action( 'init', array( $this, 'action_init' ) );

	}

	/**
	 * Theme customization options
	 */
	function action_after_setup_theme() {

		add_theme_support( 'post-thumbnails' );

		// Support for liveblogging on sessions if liveblog exists
		if ( class_exists( 'WPCOM_Liveblog' ) )
			add_post_type_support( ONA12_Session::post_type, WPCOM_Liveblog::key );
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
}

global $ona12;
$ona12 = new ONA12();