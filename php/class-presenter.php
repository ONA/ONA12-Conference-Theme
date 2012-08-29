<?php
/**
 * Presenter management functionality for ONA12
 */

class ONA12_Presenter {

	const post_type = 'ona12_presenter';

	/**
	 * Construct the class
	 */
	function __construct() {

		// Register our custom post type and taxonomies
		add_action( 'init', array( $this, 'action_init' ) );

		// Enqueue necessary resources
		add_action( 'admin_enqueue_scripts', array( $this, 'action_admin_enqueue' ) );

		// Set up metaboxes and related actions
		add_filter( 'enter_title_here', array( $this, 'filter_enter_title_here' ) );
		add_action( 'add_meta_boxes', array( $this, 'action_add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'action_save_post' ), 10, 2 );

	}

	/**
	 * Register our custom post type and taxonomies
	 */
	function action_init() {

		$args = array(
				'label'          => 'Presenters',
				'labels' => array(
						'name'               => 'Presenters',
						'singular_name'      => 'Presenter',
						'add_new'            => 'Add New Presenter',
						'all_items'          => 'All Presenters',
						'add_new_item'       => 'Add New Presenter',
						'edit_item'          => 'Edit Presenter',
						'new_item'           => 'New Presenter',
						'view_item'          => 'View Presenter',
						'search_items'       => 'Search Presenters',
						'not_found'          => 'Presenter Not Found',
					),
				'menu_position'  => 7,
				'public'         => true,
				'has_archive'    => true,
				'rewrite' => array(
						'slug'   => 'presenters',
						'feeds'  => false,
						'with_front' => true,
					),
				'supports' => array(
						'title',
						'thumbnail',
					),
			);
		register_post_type( self::post_type, $args );

		add_filter( 'manage_' . self::post_type . '_posts_columns', array( $this, 'filter_manage_posts_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'action_manage_posts_custom_column' ), 10, 2 );

	}

	/**
	 * Register necessary scripts and styles
	 */
	function action_admin_enqueue() {
		global $pagenow;

		if ( !in_array( $pagenow, array( 'post.php', 'post-new.php', 'edit.php' ) ) || self::post_type != get_post_type() )
			return;

		wp_enqueue_style( 'ona12-presenter-admin-css', get_stylesheet_directory_uri() . '/css/presenter-admin.css' );

	}

	/**
	 * Unset the default columns and add our own
	 *
	 * @todo add a 'sessions' column
	 */
	function filter_manage_posts_columns( $default ) {

		$custom_columns = array(
				'gravatar'             => __( 'Gravatar', 'ona12' ),
				'title'                => __( 'Name', 'ona12' ),
				'affiliation'          => __( 'Affiliation', 'ona12' ),
			);
		return $custom_columns;
	}

	/**
	 * Add our custom details into the columns we've created
	 */
	function action_manage_posts_custom_column( $column_name, $post_id ) {

		switch( $column_name ) {
			case 'gravatar':
				$email_address = get_post_meta( $post_id, '_ona12_presenter_email_address', true );
				echo get_avatar( $email_address, 48 );
				break;
			case 'affiliation':
				$affiliation = array(
					get_post_meta( $post_id, '_ona12_presenter_title', true ),
					get_post_meta( $post_id, '_ona12_presenter_organization', true ),
				);
				echo implode( ', ', $affiliation );
				break;
		}

	}

	/**
	 * Filter the 'Enter title here' text when you create a new presenter
	 */
	function filter_enter_title_here( $text ) {

		global $pagenow;

		if ( !in_array( $pagenow, array( 'post.php', 'post-new.php', 'edit.php' ) ) || self::post_type != get_post_type() )
			return $text;

		return 'Enter full name here';
	}

	/**
	 * Add our post meta boxes to the theme
	 */
	function action_add_meta_boxes() {
		
		add_meta_box( 'ona12-presenter-information', 'Presenter Details', array( $this, 'presenter_details_meta_box' ), self::post_type, 'normal', 'high');
		
		if ( function_exists( 'p2p_register_connection_type' ) )
			add_meta_box( 'ona12-presenter-associated-posts', 'Associations', array( $this, 'associated_posts_meta_box' ), self::post_type, 'side', 'default');		
		
	}

	/**
	 * Presenter details metabox
	 */
	function presenter_details_meta_box() {
		// post_content are included in the post object
		global $post;
		
		$title = get_post_meta( get_the_ID(), '_ona12_presenter_title', true );
		$organization = get_post_meta( get_the_ID(), '_ona12_presenter_organization', true );	
		$email_address = get_post_meta( get_the_ID(), '_ona12_presenter_email_address', true );	
		$twitter = get_post_meta( get_the_ID(), '_ona12_presenter_twitter', true ); ?>
		
		<div class="inner">
			
			<div class="option-item">
				<h4>Title</h4>
				<input type="text" size="40" id="ona12-presenter-title" name="ona12-presenter-title" value="<?php echo esc_attr( $title ); ?>" />
				<p class="description">No HTML is allowed.</p>
			</div>
			
			<div class="option-item">
				<h4>Organization</h4>
				<input type="text" size="40" id="ona12-presenter-organization" name="ona12-presenter-organization" value="<?php echo esc_attr( $organization ); ?>" />
				<p class="description">Links are allowed but optional.</p>
			</div>

			<div class="option-item">
				<h4>Email Address</h4>
				<input type="text" size="40" id="ona12-presenter-email-address" name="ona12-presenter-email-address" value="<?php echo esc_attr( $email_address ); ?>" />
			</div>
			
			<div class="option-item">
				<h4>Twitter Username</h4>
				http://twitter.com/<input type="text" size="40" id="ona12-presenter-twitter" name="ona12-presenter-twitter" value="<?php echo esc_attr( $twitter ); ?>" />
				<p class="description">Just the username, no URL.</p>
			</div>			
			
			<div class="option-item">
				<h4>Full Biography<span class="required">*</span></h4>
				<textarea id="content" name="content" rows="8" cols="60"><?php echo esc_textarea( $post->post_content ); ?></textarea>
				<p class="description">Basic HTML is allowed. This extended bio appears on the single and all presenters pages.</p>
			</div>
			
			<?php echo wp_nonce_field( 'ona12-presenter-nonce', 'ona12-presenter-nonce' ); ?>
			
		</div><!-- END .inner -->
		
		<?php
		
	}

	/**
	 * Save the data from our metaboxes
	 */
	function action_save_post( $post_id ) {
		
		if ( !isset( $_POST['ona12-presenter-nonce'] ) || !wp_verify_nonce( $_POST['ona12-presenter-nonce'], 'ona12-presenter-nonce' ) )
			return $post_id; 

		// Save our metabox data
		
		$title = sanitize_text_field( $_POST['ona12-presenter-title'] );
		update_post_meta( $post_id, '_ona12_presenter_title', $title );
		
		$organization = strip_tags( $_POST['ona12-presenter-organization'], '<a>' );
		update_post_meta( $post_id, '_ona12_presenter_organization', $organization );

		$email_address = sanitize_email( $_POST['ona12-presenter-email-address'] );
		update_post_meta( $post_id, '_ona12_presenter_email_address', $email_address );
		
		$twitter = str_replace( '@', '', str_replace( 'http://twitter.com/', '', sanitize_text_field( $_POST['ona12-presenter-twitter'] ) ) );
		update_post_meta( $post_id, '_ona12_presenter_twitter', $twitter );			
		
	}

}