<?php
/**
 * Session management functionality for ONA12
 */

class ONA12_Session {

	const post_type = 'ona12_session';

	/**
	 * Construct the class
	 */
	function __construct() {

		// Register our custom post type and taxonomies
		add_action( 'init', array( $this, 'action_init' ) );

		// Create the liveblog editor role
		add_action( 'admin_init', array( $this, 'action_admin_init' ) );

		// Enqueue necessary resources
		add_action( 'wp_enqueue_scripts', array( $this, 'action_frontend_enqueue' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'action_admin_enqueue' ) );

		// Set up metaboxes and related actions
		add_filter( 'manage_edit-ona12_session_sortable_columns', array( $this, 'manage_sortable_columns' ) );
		add_action( 'admin_menu', array( $this, 'action_admin_menu' ) );
		add_action( 'save_post', array( $this, 'action_save_post' ), 10, 2 );

		// Filter posts on the manage posts view by start time by default
		add_action( 'pre_get_posts', array( $this, 'action_pre_get_posts' ) );

		// Always enable liveblog on this post type
		add_filter( 'get_post_metadata', array( $this, 'filter_get_post_metadata' ), 10, 4 );
		add_filter( 'liveblog_settings', array( $this, 'filter_liveblog_settings' ) );
		add_filter( 'liveblog_edit_cap', array( $this, 'filter_liveblog_edit_cap' ) );

	}

	/**
	 * Register our custom post type and taxonomies
	 */
	function action_init() {

		$args = array(
				'label'          => 'Sessions',
				'labels' => array(
						'name'               => 'Sessions',
						'singular_name'      => 'Session',
						'add_new'            => 'Add New Session',
						'all_items'          => 'All Sessions',
						'add_new_item'       => 'Add New Session',
						'edit_item'          => 'Edit Session',
						'new_item'           => 'New Session',
						'view_item'          => 'View Session',
						'search_items'       => 'Search Sessions',
						'not_found'          => 'Session Not Found',
					),
				'menu_position'  => 6,
				'public'         => true,
				'has_archive'    => true,
				'rewrite' => array(
						'slug'   => 'sessions',
						'feeds'  => false,
						'with_front' => true,
					),
				'supports' => array(
						'title',
						'thumbnail',
					),
			);
		register_post_type( self::post_type, $args );

		// Register the Locations taxonomy
		$args = array(
			'label' => 'Locations',
			'labels' => array(
				'name' => 'Locations',
				'singular_name' => 'Location',
				'search_items' => 'Search Locations',
				'all_items' => 'All Locations',
				'parent_item' => 'Parent Location',
				'parent_item_colon' => 'Parent Location:',
				'edit_item' => 'Edit Location',
				'update_item' => 'Update Location',
				'add_new_item' => 'Add New Location',
				'new_item_name' => 'New Location',
				'menu_name' => 'Locations',
			),
			'hierarchical' => true,
			'show_tagcloud' => false,
			'rewrite' => array(
				'slug' => 'locations',
				'hierarchical' => true,
			),
		);
		register_taxonomy( 'ona12_locations', array( self::post_type ), $args );

		// Register the Session Types taxonomy
		$args = array(
			'label' => 'Session Types',
			'labels' => array(
				'name' => 'Session Types',
				'singular_name' => 'Session Type',
				'search_items' => 'Search Session Types',
				'all_items' => 'All Session Types',
				'parent_item' => 'Parent Session Type',
				'parent_item_colon' => 'Parent Session Type:',
				'edit_item' => 'Edit Session Type',
				'update_item' => 'Update Session Type',
				'add_new_item' => 'Add New Session Type',
				'new_item_name' => 'New Session Type',
				'menu_name' => 'Session Types',
			),
			'hierarchical' => true,
			'show_tagcloud' => false,
			'rewrite' => array(
				'slug' => 'session-type',
				'hierarchical' => true,
			),
		);
		register_taxonomy( 'ona12_session_types', array( self::post_type ), $args );

		add_filter( 'manage_' . self::post_type . '_posts_columns', array( $this, 'filter_manage_posts_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'action_manage_posts_custom_column' ), 10, 2 );

	}

	/**
	 * Register our liveblog editor role among other things
	 */
	function action_admin_init() {

		if ( ! get_role( 'liveblog' ) ) {
			$caps = array(
					'read'                 => true,
					'publish_liveblog'     => true,
				);
			add_role( 'liveblog', 'Liveblogger', $caps );
		}

		$roles = array(
				'author',
				'editor',
				'administrator',
			);
		foreach( $roles as $role ) {
			$role = get_role( $role );
			if ( ! $role->has_cap( 'publish_liveblog' ) )
				$role->add_cap( 'publish_liveblog' );
		}

	}

	/**
	 * Enqueue frontend scripts
	 */
	function action_frontend_enqueue() {
		
		if ( self::post_type != get_post_type() )
			return;
		
		wp_enqueue_style( 'ona12-session-css', get_stylesheet_directory_uri() . '/css/session.css', false, ONA12_VERSION );
		wp_enqueue_style( 'ona12-liveblog-css', get_stylesheet_directory_uri() . '/css/liveblog.css', false, ONA12_VERSION );
	}

	/**
	 * Register necessary scripts and styles
	 */
	function action_admin_enqueue() {
		global $pagenow;

		if ( !in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) || self::post_type != get_post_type() )
			return;

		// Scripts
		wp_enqueue_script( 'jquery-selectlist', get_stylesheet_directory_uri() . '/js/jquery.selectlist.js', array( 'jquery' ) );
		wp_enqueue_script( 'ona12-jquery-ui-custom-js', get_stylesheet_directory_uri() . '/js/jquery-ui-1.8.13.custom.min.js', array( 'jquery', 'jquery-ui-core' ) );
		wp_enqueue_script( 'ona12-jquery-timepicker-js', get_stylesheet_directory_uri() . '/js/jquery-ui-timepicker.0.9.5.js', array( 'jquery', 'jquery-ui-core', 'ona12-jquery-ui-custom-js' ) );
		wp_enqueue_script( 'ona12-session-admin-js', get_stylesheet_directory_uri() . '/js/session-admin.js', array( 'jquery', 'jquery-selectlist', 'ona12-jquery-timepicker-js' ) );

		// Styles
		wp_enqueue_style( 'ona12-session-admin-css', get_stylesheet_directory_uri() . '/css/session-admin.css' );
		wp_enqueue_style( 'ona12-jquery-ui-custom-css', get_stylesheet_directory_uri() . '/css/jquery-ui-1.8.13.custom.css' );
		
	}

	/**
	 * By default, order the sessions by start time
	 */
	function action_pre_get_posts( $query ) {

		if ( !is_admin() || !$query->is_main_query() || self::post_type != $query->get( 'post_type' ) )
			return;

		$order = ( 'asc' == $query->get('order') ) ? 'desc' : 'asc';
		$query->set( 'order', $order );
		$query->set( 'meta_key', '_ona12_start_timestamp' );
		$query->set( 'orderby', 'meta_value_num' );
	}

	/**
	 * For now, no columns on the list table are sortable
	 */
	function manage_sortable_columns() {
		return array( 'time' => 'time' );
	}

	/**
	 * Unset the default columns and add our own
	 *
	 * @todo add a location column
	 */
	function filter_manage_posts_columns( $default ) {

		$custom_columns = array(
				'cb'                   => $default['cb'],
				'title'                => __( 'Title', 'ona12' ),
				'time'                 => __( 'Time', 'ona12' ),
				'presenters'           => __( 'Presenters', 'ona12' ),
				'short_description'    => __( 'Short Description', 'ona12' ),
				'session_type'         => __( 'Session Type', 'ona12' ),
			);
		return $custom_columns;
	}

	/**
	 * Add our custom details into the columns we've created
	 */
	function action_manage_posts_custom_column( $column_name, $post_id ) {

		switch( $column_name ) {
			case 'time':
				$start_timestamp = get_post_meta( $post_id, '_ona12_start_timestamp', true );
				echo date( 'l, g:i a', $start_timestamp );
				break;
			case 'presenters':
				$presenters = wp_list_pluck( p2p_type( 'sessions_to_presenters' )->get_connected( $post_id )->posts, 'post_title' );
				if ( !empty( $presenters ) ) {
					echo implode( ', ', $presenters );
				} else {
					echo '<em>None</em>';
				}
				break;
			case 'short_description':
				echo get_the_excerpt( $post_id );
				break;
			case 'session_type':
				$session_type_tax = wp_get_object_terms( $post_id, 'ona12_session_types' );
				if ( empty( $session_type_tax ) ) {
					echo '<em>None</em>';
				} else {
					$args = array(
							'post_type' => self::post_type,
							'ona12_session_types' => $session_type_tax[0]->slug,
						);
					$filter_link = add_query_arg( $args, admin_url( 'edit.php' ) );
					echo '<a href="' . esc_url( $filter_link ) . '">' . esc_html( $session_type_tax[0]->name ) . '</a>';
				}
				break;
		}

	}

	/**
	 * Add post meta boxes to the theme
	 */
	function action_admin_menu() {

		add_meta_box( 'ona12-session-information', 'Session Information', array( $this, 'session_information_meta_box' ), self::post_type, 'normal', 'high' );
		add_meta_box( 'ona12-session-date-time-location', 'Session Date, Time, &amp; Location', array( $this, 'date_time_location_meta_box' ), self::post_type, 'side', 'default' );
		remove_meta_box( 'ona12_locationsdiv', self::post_type, 'side' );
		remove_meta_box( 'ona12_session_typesdiv', self::post_type, 'side' );

	}

	/**
	 * Allow user to specify a full description, short description, and session type
	 */
	function session_information_meta_box() {
		global $post;
		
		// post_content and post_excerpt are included in the post object
		
		$session_type_tax = wp_get_object_terms( $post->ID, 'ona12_session_types', array( 'fields' => 'ids' ) );
		$session_type = ( !empty( $session_type_tax ) ) ? (int)$session_type_tax[0] : 0;

		$hashtag = get_post_meta( $post->ID, '_ona12_hashtag', true );
		?>
		
		<div class="inner">
			
			<div class="option-item">
				<h4>Full Description<span class="required">*</span>:</h4>
				<textarea id="content" name="content" rows="8" cols="60"><?php echo esc_textarea( $post->post_content ); ?></textarea>
				<p class="description">Basic HTML is allowed. This extended description appears on the single session page and can be as long as you'd like.</p>
			</div>
			
			<div class="option-item">
				<h4>Short Description:</h4>
				<textarea id="excerpt" name="excerpt" rows="2" cols="60"><?php echo esc_textarea( $post->post_excerpt ); ?></textarea>
				<p class="description">Basic HTML is allowed. If filled out, this short description will appear on pages other than the single session page. One to two sentences is a great length.</p>
			</div>

			<div class="option-item">
				<h4>Hashtag:</h4>
				<input type="text" id="ona12-hashtag" name="ona12-hashtag" value="<?php echo esc_attr( $hashtag ); ?>" />
				<p class="description">Something like "#ONA12Keynote" is great</p>
			</div>
			
			<div class="session-active-wrap option-item">
				<h4>Details</h4>
				
				<div class="line-item pick-session-type">
					<label for="ona12-session-type">Session Type:</label>
					<?php
						$args = array(
							'name' => 'ona12-session-type',
							'taxonomy' => 'ona12_session_types',
							'hide_if_empty' => true,
							'echo' => false,
							'hide_empty' => false,
							'hierarchical' => true,
							'show_option_none' => '-- Pick a Session Type --',
							'selected' => $session_type,
						);
						echo ( $session_type_dropdown = wp_dropdown_categories( $args ) ) ? $session_type_dropdown : 'Please add session types before selecting';
					?>
					<p><span class="description">You can easily <a href="<?php echo add_query_arg( array( 'post_type' => self::post_type, 'taxonomy' => 'ona12_session_types' ), get_admin_url( null, 'edit-tags.php' ) ); ?>">add or edit session types</a>.</p>
				</div>

			</div>
			
			<?php wp_nonce_field( 'ona12-session-nonce', 'ona12-session-nonce' ); ?>
			
		</div><!-- END .inner -->
		
		<?php
	}

	/**
	 * Allow user to specify a date, time and location for the session
	 */
	function date_time_location_meta_box() {
		global $post;
		
		$time_format = 'm/d/Y g:i A';
		$start_timestamp = get_post_meta( $post->ID, '_ona12_start_timestamp', true );
		$start_time = ( $start_timestamp ) ? date( $time_format, $start_timestamp ) : '';
			
		$end_timestamp = get_post_meta( $post->ID, '_ona12_end_timestamp', true );
		$end_time = ( $end_timestamp ) ? date( $time_format, $end_timestamp ) : '';
		
		$location_tax = wp_get_object_terms( $post->ID, 'ona12_locations', array( 'fields' => 'ids' ) );
		$location = ( !empty( $location_tax ) ) ? (int)$location_tax[0] : 0;
		
		?>
		<div class="inner">
			
		<div class="date-time-wrap option-item hide-if-no-js">
			
			<div class="line-item">
			<div class="pick-date">
				<label for="ona12-start" class="primary-label">Start<span class="required">*</span>:</label>
				<input id="ona12-start" name="ona12-start" class="ona12-date-picker" size="25" value="<?php echo esc_attr( $start_time ); ?>" />
			</div>
			</div>
			
			<div class="line-item">
			<div class="pick-date">
				<label for="ona12-end" class="primary-label">End<span class="required">*</span>:</label>
				<input id="ona12-end" name="ona12-end" class="ona12-date-picker" size="25" value="<?php echo esc_attr( $end_time ); ?>" />
			</div>
			</div>
			
			<div class="line-item pick-location">
				<label for="ona12-location">Location:</label>
				<?php
					$args = array(
						'name' => 'ona12-location',
						'taxonomy' => 'ona12_locations',
						'hide_if_empty' => true,
						'echo' => false,
						'hide_empty' => false,
						'hierarchical' => true,
						'show_option_none' => '-- Pick a Location --',
						'selected' => $location,
					);
					echo ( $location_dropdown = wp_dropdown_categories( $args ) ) ? $location_dropdown : 'Please add locations before selecting';
				?>
				<p><span class="description">You can easily <a href="<?php echo add_query_arg( array( 'post_type' => self::post_type, 'taxonomy' => 'ona12_locations' ), get_admin_url( null, 'edit-tags.php' ) ); ?>">add or edit locations</a>.</span></p>
			</div>
			
			<div class="clear-both"></div>
		</div>
		</div>
		<?
	}

	/**
	 * Save the data from our metaboxes
	 */
	function action_save_post( $post_id, $post ) {

		if ( !isset( $_POST['ona12-session-nonce'] ) || !wp_verify_nonce( $_POST['ona12-session-nonce'], 'ona12-session-nonce' ) )
			return; 
		
		if ( wp_is_post_revision( $post ) || wp_is_post_autosave( $post ) )
			return;
			
		// Date, Time & Location settings to save
		$start_timestamp = strtotime( $_POST['ona12-start'] );
		update_post_meta( $post_id, '_ona12_start_timestamp', $start_timestamp );
		
		$end_timestamp = strtotime( $_POST['ona12-end'] );
		update_post_meta( $post_id, '_ona12_end_timestamp', $end_timestamp );
		
		$hashtag = sanitize_text_field( $_POST['ona12-hashtag'] );
		update_post_meta( $post_id, '_ona12_hashtag', $hashtag );

		$location = (isset( $_POST['ona12-location'] ) ) ? (int)$_POST['ona12-location'] : '';
		wp_set_object_terms( $post_id, $location, 'ona12_locations' );
		
		$session_type = (isset( $_POST['ona12-session-type'] ) ) ? (int)$_POST['ona12-session-type'] : '';
		wp_set_object_terms( $post_id, $session_type, 'ona12_session_types' );		
	}

	/**
	 * Always enable the liveblog
	 */
	function filter_get_post_metadata( $value, $object_id, $meta_key, $single ) {

		if ( self::post_type != get_post_type( $object_id ) || 'liveblog' != $meta_key )
			return null;

		return true;
	}

	/**
	 * Change the refresh period to avoid breaking the world
	 */
	function filter_liveblog_settings( $settings ) {
		$settings['refresh_interval'] = 60;
		return $settings;
	}

	/**
	 * Lock down the liveblog cap
	 */
	function filter_liveblog_edit_cap( $cap ) {
		return 'publish_liveblog';
	}

	/**
	 * Get a given field for a session
	 * Can be used within the loop, but not a requirement
	 */
	public function get( $field, $post_id = null ) {

		if ( is_null( $post_id ) )
			$post_id = get_the_ID();

		switch( $field ) {
			case 'title':
				return get_the_title( $post_id );
			case 'start_timestamp':
			case 'end_timestamp':
				return (int)get_post_meta( $post_id, '_ona12_' . $field, true );
			case 'location':
				$session_location = get_the_terms( $post_id, 'ona12_locations' );
				if ( ! empty( $session_location ) ) {
					$session_location = array_shift( $session_location )->name;
				}
				return $session_location;
		}
		return false;
	}

	public function is_current_session() {
		$time = time() - 25200;
		if ( ( $time > ( self::get( 'start_timestamp' ) - 600 ) ) && ( $time < ( self::get( 'end_timestamp' ) + 600 ) ) )
			return true;
		else
			return false;
	}

	/**
	 * Get the livestream embed if there is one at this time
	 */
	public function get_livestream() {

		$post_id = get_the_ID();
		$livestreams = array(
			'Grand Ballroom A'         => '<iframe width="420" height="295" src="http://cdn.livestream.com/embed/onlinenewsassociation?layout=4&color=0xe7e7e7&autoPlay=false&mute=false&iconColorOver=0x888888&iconColor=0x777777&allowchat=false&height=295&width=420" style="border:0;outline:0" frameborder="0" scrolling="no"></iframe>',
			'Grand Ballroom'           => '<iframe width="420" height="295" src="http://cdn.livestream.com/embed/onlinenewsassociation?layout=4&color=0xe7e7e7&autoPlay=false&mute=false&iconColorOver=0x888888&iconColor=0x777777&allowchat=false&height=295&width=420" style="border:0;outline:0" frameborder="0" scrolling="no"></iframe>',
			'Grand Ballroom BC'        => '<iframe width="420" height="295" src="http://cdn.livestream.com/embed/ona09frontendsessions?layout=4&color=0xe7e7e7&autoPlay=false&mute=false&iconColorOver=0x888888&iconColor=0x777777&allowchat=false&height=295&width=420" style="border:0;outline:0" frameborder="0" scrolling="no"></iframe>',
			'Seacliff A-D'             => '<iframe width="420" height="295" src="http://cdn.livestream.com/embed/ona09backendsessions?layout=4&color=0xe7e7e7&autoPlay=false&mute=false&iconColorOver=0x888888&iconColor=0x777777&allowchat=false&height=295&width=420" style="border:0;outline:0" frameborder="0" scrolling="no"></iframe>',
			'Bayview A-B'              => '<iframe width="420" height="295" src="http://cdn.livestream.com/embed/onatrack4?layout=4&color=0xe7e7e7&autoPlay=false&mute=false&iconColorOver=0x888888&iconColor=0x777777&allowchat=false&height=295&width=420" style="border:0;outline:0" frameborder="0" scrolling="no"></iframe>',
		);
		$location = self::get( 'location', $post_id );
		if ( self::is_current_session() && array_key_exists( $location, $livestreams ) )
			return $livestreams[$location];
	}

}