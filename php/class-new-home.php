<?php
/**
 * A custom homepage for during the conference
 */

class ONA12_New_Home {

	const home_post_type = 'home-featured';

	var $featured_fields = array(
			'byline'       => 'Byline',
			'link'         => 'Link',
		);

	function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'action_frontend_enqueue' ) );
		add_action( 'init', array( $this, 'action_init' ) );
		add_action( 'after_setup_theme', array( $this, 'action_after_setup_theme' ) );
		add_action( 'add_meta_boxes', array( $this, 'action_add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'action_save_post' ) );

		add_filter( 'post_type_link', array( $this, 'filter_post_link' ), 10, 2 );
	}

	function action_init() {
		$this->register_post_type();
	}

	function action_after_setup_theme() {

		add_image_size( 'ona12-featured-thumbnail', 128, 128, true );
		add_image_size( 'ona12-featured-large', 600, 300, true );
	}

	function register_post_type() {

		$args = array(
				'label'          => 'Home Featured',
				'labels' => array(
						'name'               => 'Home Featured',
						'singular_name'      => 'Home Featured',
					),
				'public'         => true,
				'publicly_queryable' => false,
				'has_archive'    => false,
				'rewrite' => array(
						'slug'   => 'home-featured',
						'feeds'  => false,
						'with_front' => true,
					),
				'supports' => array(
						'title',
						'excerpt',
						'thumbnail',
						'zoninator_zones',
					),
			);
		register_post_type( self::home_post_type, $args );

	}

	function action_frontend_enqueue() {

		// @todo Only enqueue if page template is loaded

		wp_enqueue_style( 'ona12-new-home-css', get_stylesheet_directory_uri() . '/css/new-home.css', false, ONA12_VERSION );
	}

	function filter_post_link( $permalink, $post ) {

		if ( ONA12_New_Home::home_post_type != get_post_type( $post->ID ) )
			return $permalink;

		return get_post_meta( $post->ID, '_ona12_featured_link', true );
	}

	function action_add_meta_boxes() {
		
		add_meta_box( 'ona12-featured-details', 'Details', array( $this, 'featured_meta_box' ), self::home_post_type, 'normal', 'high');
	}

	function featured_meta_box() {

		foreach( $this->featured_fields as $slug => $label ) {
			echo '<div class="item">';
			echo '<h4>' . $label . '</h4>';
			$value = get_post_meta( get_the_ID(), '_ona12_featured_' . $slug, true );
			echo '<input type="text" size="40" id="ona12-featured-' . $slug . '" name="ona12-featured-' . $slug . '" value="' . esc_attr( $value ) . '" />';
			echo '</div>';
		}
		wp_nonce_field( 'ona12-featured-nonce', 'ona12-featured-nonce' );
	}


	function action_save_post( $post_id ) {
		
		if ( !isset( $_POST['ona12-featured-nonce'] ) || !wp_verify_nonce( $_POST['ona12-featured-nonce'], 'ona12-featured-nonce' ) )
			return $post_id; 

		foreach ( $this->featured_fields as $slug => $label ) {
			$value = sanitize_text_field( $_POST['ona12-featured-'.$slug] );
			update_post_meta( $post_id, '_ona12_featured_'.$slug, $value );
		}
	}

	public function get_session_updates( $args = array() ) {

		$defaults = array(
				'orderby'           => 'comment_date_gmt',
				'order'             => 'DESC',
				'number'            => 5,
				'type'              => 'liveblog',
				'status'            => 'liveblog',
			);
		$args = array_merge( $args, $defaults );
		add_filter( 'comments_clauses', array( __CLASS__, '_comments_clauses' ) );
		$updates = get_comments( $args );
		remove_filter( 'comments_clauses', array( __CLASS__, '_comments_clauses' ) );
		return $updates;
	}

	public function render_comment_content( $content ) {
		global $wp_embed;

		add_filter( 'embed_defaults', array( __CLASS__, 'filter_embed_defaults' ) );
		$content = $wp_embed->autoembed( $content );
		$content = do_shortcode( $content );
		$content = apply_filters( 'comment_text', $content );
		remove_filter( 'embed_defaults', array( __CLASS__, 'filter_embed_defaults' ) );

		return $content;
	}

	public function filter_embed_defaults( $defaults ) {
		return array( 'width' => 600, 'height' => 0 );
	}

	public function _comments_clauses( $clauses = array() ) {
		global $wpdb;

		// Setup the search clauses
		$needle   = $wpdb->prepare( "comment_type = %s", 'liveblog' );
		$haystack = !empty( $clauses['where'] ) ? $clauses['where'] : '';

		// Bail if not a liveblog query
		if ( ! strstr( $haystack, $needle ) )
			return $clauses;

		$clauses['where'] = $wpdb->prepare( "comment_approved = %s AND comment_type = %s", 'liveblog', 'liveblog' );

		return $clauses;
	}
}