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
		// Do modifications to the admin
		add_action( 'admin_init', array( $this, 'action_admin_init' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'action_frontend_enqueue' ) );

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

	}

	/**
	 * Modify the admin
	 */
	function action_admin_init() {

		if ( ! $this->is_edit_screen() )
			return;

		// Modify the manage presenters UI
		add_filter( 'manage_' . self::post_type . '_posts_columns', array( $this, 'filter_manage_posts_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'action_manage_posts_custom_column' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'filter_post_row_actions' ), 10, 2 );

		// Sort presenters by name
		add_action( 'pre_get_posts', array( $this, 'action_pre_get_posts' ) );

		// Enqueue necessary resources
		add_action( 'admin_enqueue_scripts', array( $this, 'action_admin_enqueue' ) );

	}

	/**
	 * Whether or not this an edit screen associated with the presenters UI
	 */
	function is_edit_screen() {
		global $pagenow;

		if ( !in_array( $pagenow, array( 'post.php', 'post-new.php', 'edit.php' ) ) 
			|| ( ( isset( $_GET['post_type'] ) && self::post_type != $_GET['post_type'] ) && get_post_type() != self::post_type ) )
			return false;

		return true;
	}

	/**
	 * Enqueue frontend scripts
	 */
	function action_frontend_enqueue() {

		if ( self::post_type != get_post_type() )
			return;

		wp_enqueue_style( 'ona12-presenter-css', get_stylesheet_directory_uri() . '/css/presenter.css', false, ONA12_VERSION );
	}

	/**
	 * Register necessary scripts and styles
	 */
	function action_admin_enqueue() {

		wp_enqueue_style( 'ona12-presenter-admin-css', get_stylesheet_directory_uri() . '/css/presenter-admin.css', false, ONA12_VERSION );

	}

	/**
	 * Unset the default columns and add our own
	 */
	function filter_manage_posts_columns( $default ) {

		$custom_columns = array(
				'gravatar'             => __( 'Gravatar', 'ona12' ),
				'title'                => __( 'Name', 'ona12' ),
				'affiliation'          => __( 'Affiliation', 'ona12' ),
				'sessions'             => __( 'Session(s)', 'ona12' ),
			);
		return $custom_columns;
	}

	/**
	 * Remove 'Quick Edit' because it's not really relevant
	 */
	function filter_post_row_actions( $actions, $post ) {
		unset( $actions['inline hide-if-no-js'] );
		return $actions;
	}

	/**
	 * Sort presenters by name by default
	 */
	function action_pre_get_posts( $query ) {
		global $pagenow;

		if ( 'edit.php' != $pagenow || !$query->is_main_query() )
			return;

		// Order posts by title if there's no orderby set
		if ( !$query->get( 'orderby' ) ) {
			$sort_order = array(
					'orderby'       => 'title',
					'order'         => 'asc',
				);
			foreach( $sort_order as $key => $value ) {
				$query->set( $key, $value );
				$_GET[$key] = $value;
			}
		}

	}

	/**
	 * Add our custom details into the columns we've created
	 */
	function action_manage_posts_custom_column( $column_name, $post_id ) {

		switch( $column_name ) {
			case 'gravatar':
				echo ONA12_Presenter::get_avatar( 'ona12-small-square-avatar', $post_id );
				break;
			case 'affiliation':
				$affiliation = array(
					'<strong>' . get_post_meta( $post_id, '_ona12_presenter_title', true ) . '</em>',
					get_post_meta( $post_id, '_ona12_presenter_organization', true ),
				);
				echo implode( ', ', $affiliation );
				break;
			case 'sessions':
				$sessions = wp_list_pluck( p2p_type( 'sessions_to_presenters' )->get_connected( $post_id )->posts, 'post_title' );
				if ( !empty( $sessions ) ) {
					echo implode( '<br />', $sessions );
				} else {
					echo '<em>None</em>';
				}
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

	/**
	 * Get a given field for a presenter
	 * Can be used within the loop, but not a requirement
	 */
	public function get( $field, $post_id = null ) {

		if ( is_null( $post_id ) )
			$post_id = get_the_ID();

		switch( $field ) {
			case 'name':
			case 'full_name':
				return get_the_title( $post_id );
			case 'title':
				return get_post_meta( $post_id, '_ona12_presenter_title', true );
			case 'organization':
				return get_post_meta( $post_id, '_ona12_presenter_organization', true );
			case 'email_address':
				return get_post_meta( $post_id, '_ona12_presenter_email_address', true );
			case 'twitter':
				return get_post_meta( $post_id, '_ona12_presenter_twitter', true );
		}
		return false;
	}

	/**
	 * Get the avatar for a presenter
	 */
	public function get_avatar( $size = 64, $post_id = null ) {

		if ( is_null( $post_id ) )
			$post_id = get_the_ID();		

		// Default to attached images
		if ( has_post_thumbnail( $post_id ) ) {
			echo get_the_post_thumbnail( $post_id, $size );
			return;
		}

		switch( $size ) {
			case 'ona12-medium-tall-avatar':
				$size = 120;
				break;
			case 'ona12-small-square-avatar':
				$size = 48;
				break;
		}

		$email_address = self::get( 'email_address', $post_id );
		return get_avatar( $email_address, $size );
	}

}