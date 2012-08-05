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
			'taxonomies' => 'slidetype',
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
    
        if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => 'Sponsors (Top)',
    		'id'   => 'sponsors-top-widgets',
    		'description'   => 'This fills the top sponsor area.',
    		'before_widget' => '<div id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</div>',
    		'before_title'  => '<h4>',
    		'after_title'   => '</h4>'
    	));
    }

?>