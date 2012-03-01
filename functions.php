<?php

function register_my_menus() {
  register_nav_menus(
    array('navigation-menu' => __( 'Navigation Menu' ) )
  );
}

add_action( 'init', 'register_my_menus' );

add_action( 'init', 'create_post_types' );

function create_post_types() {
		register_post_type( 'frontpageslide',
		array(
			'labels' => array(
				'name' => __( 'Front Page Slides' ),
				'singular_name' => __( 'Front Page Slide' )
			),
		'public' => true,
	    'publicly_queryable' => true,
    	'show_ui' => true, 
	    'show_in_menu' => true, 
    	'query_var' => true,
	    'rewrite' => true,
    	'capability_type' => 'post',
	    'has_archive' => true, 
    	'hierarchical' => false,
	    'menu_position' => null,
    	'supports' => array('title','editor','revisions'),
	    'taxonomies' => 'slidetype'
		)
	);
}

    if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => 'Calendar',
    		'id'   => 'calendar-widgets',
    		'description'   => 'This fills in the ONA12 calendar.',
    		'before_widget' => '<div id="%1$s" class="grid_2 widget %2$s">',
    		'after_widget'  => '</div>',
    		'before_title'  => '<h4>',
    		'after_title'   => '</h4>'
    	));
    }

?>