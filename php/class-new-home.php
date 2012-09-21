<?php
/**
 * A custom homepage for during the conference
 */

class ONA12_New_Home {

	const home_post_type = 'home-featured';

	function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'action_frontend_enqueue' ) );
		add_action( 'init', array( $this, 'action_init' ) );
	}

	function action_init() {
		$this->register_post_type();
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
						'editor',
						'excerpt',
						'thumbnail',
					),
			);
		register_post_type( self::home_post_type, $args );

	}

	function action_frontend_enqueue() {

		// Only enqueue if page template is loaded

		wp_enqueue_style( 'ona12-new-home-css', get_stylesheet_directory_uri() . '/css/new-home.css', false, ONA12_VERSION );
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