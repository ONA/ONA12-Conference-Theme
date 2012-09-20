<?php

class Knight_Data_Challenge_Announcement {

	const tag_slug = 'knight-data-challenge';
	const project_post_type = 'kdc-project';

	var $fields = array(
			'award'        => 'Award',
			'winners'      => 'Winners',
			'url'          => 'URL (optional)',
		);

	function __construct() {

		add_action( 'init', array( $this, 'action_init' ) );
		add_action( 'wp', array( $this, 'action_wp' ) );
		add_action( 'add_meta_boxes', array( $this, 'action_add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'action_save_post' ) );
		add_action( 'after_setup_theme', array( $this, 'action_after_setup_theme' ) );
	}

	function action_after_setup_theme() {

		add_image_size( 'ona12-project-small-square', 32, 32, true );
		add_image_size( 'ona12-project-medium-square', 64, 64, true );
		add_image_size( 'ona12-project-full-width', 300, 150, true );
		add_image_size( 'ona12-project-half-width', 150, 150, true );

	}

	function action_init() {

		$this->register_post_type();
	}

	function action_wp() {
		if ( ! is_singular() || ! has_tag( self::tag_slug, get_queried_object_id() ) )
			return;

		add_filter( 'body_class', array( $this, 'filter_body_class' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'action_frontend_enqueue' ) );
		add_action( 'template_redirect', array( $this, 'action_template_redirect' ) );
	}

	function register_post_type() {

		$args = array(
				'label'          => 'Knight Projects',
				'labels' => array(
						'name'               => 'Knight Projects',
						'singular_name'      => 'Knight Project',
						'add_new'            => 'Add New Knight Project',
						'all_items'          => 'All Knight Projects',
						'add_new_item'       => 'Add New Knight Project',
						'edit_item'          => 'Edit Knight Project',
						'new_item'           => 'New Knight Project',
						'view_item'          => 'View Knight Project',
						'search_items'       => 'Search Knight Projects',
						'not_found'          => 'Knight Project Not Found',
					),
				'public'         => true,
				'publicly_queryable' => false,
				'has_archive'    => false,
				'rewrite' => array(
						'slug'   => 'knight-data-challenge-projects',
						'feeds'  => false,
						'with_front' => true,
					),
				'supports' => array(
						'title',
						'editor',
						'excerpt',
					),
			);
		register_post_type( self::project_post_type, $args );

	}

	function action_frontend_enqueue() {
		wp_enqueue_style( 'ona12-knight-data-challenge-css', get_stylesheet_directory_uri() . '/css/knight-data-challenge.css', false, ONA12_VERSION );
	}

	function filter_body_class( $body_classes ) {

		if ( ! is_singular() || ! has_tag( self::tag_slug, get_queried_object_id() ) )
			return $body_classes;

		$body_classes[] = 'knight-data-challenge';
		return $body_classes;
	}

	function action_template_redirect() {

		if ( ! is_singular() || ! has_tag( self::tag_slug, get_queried_object_id() ) )
			return;

		locate_template( 'single-knight_data_challenge.php', true, true );
		die();
	}

	function action_add_meta_boxes() {
		
		add_meta_box( 'ona12-project-details', 'Project Details', array( $this, 'project_details_meta_box' ), self::project_post_type, 'normal', 'high');
	}

	function project_details_meta_box() {

		foreach( $this->fields as $slug => $label ) {
			echo '<div class="item">';
			echo '<h4>' . $label . '</h4>';
			$value = get_post_meta( get_the_ID(), '_ona12_project_' . $slug, true );
			echo '<input type="text" size="40" id="ona12-project-' . $slug . '" name="ona12-project-' . $slug . '" value="' . esc_attr( $value ) . '" />';
			echo '</div>';
		}
		wp_nonce_field( 'ona12-project-nonce', 'ona12-project-nonce' );
	}


	function action_save_post( $post_id ) {
		
		if ( !isset( $_POST['ona12-project-nonce'] ) || !wp_verify_nonce( $_POST['ona12-project-nonce'], 'ona12-project-nonce' ) )
			return $post_id; 

		foreach ( $this->fields as $slug => $label ) {
			$value = sanitize_text_field( $_POST['ona12-project-'.$slug] );
			update_post_meta( $post_id, '_ona12_project_'.$slug, $value );
		}
	}

	function get_project_field( $field, $post_id = null ) {

		if ( is_null( $post_id ) )
			$post_id = get_the_ID();

		return get_post_meta( $post_id, '_ona12_project_' . $field, true );
	}

}