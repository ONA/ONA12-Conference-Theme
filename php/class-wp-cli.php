<?php
/**
 * WP-CLI commands for ONA12
 */

WP_CLI::add_command( 'ona12', 'ONA12_WPCLI' );

class ONA12_WPCLI extends WP_CLI_Command {

	/**
	 * Help function for this command
	 */
	public static function help() {

		WP_CLI::line( <<<EOB
usage: wp ona12 <parameters>
Possible subcommands:
					import_presenters           Import presenters into a site
					import_sessions             Import sessions into a site
EOB
		);
	}

	/**
	 * Import presenters into a site
	 */
	public function import_presenters( $args, $assoc_args ) {

		$defaults = array(
				'csv'                     => '',
			);

		$this->args = wp_parse_args( $assoc_args, $defaults );

		$handle = fopen( $this->args['csv'], 'r' );
		if ( ! $handle )
			WP_CLI::error( "Error accessing {$this->args['csv']}");
		$c = 0;

		$presenters = array();
		while ( false !== ( $data = fgetcsv( $handle ) ) ) {
			$c++;
			// Set the headers on the file
			if ( 1 == $c ) {
				$headers = $data;
				continue;
			}

			$presenter = array();
			foreach( $headers as $index => $key ) {
				$presenter[$key] = $data[$index];
			}
			$presenters[] = $presenter;

		}

		$count_created = 0;
		foreach( $presenters as $presenter ) {

			$full_name = $presenter['First Name'] . ' ' . $presenter['Last Name'];
			if ( empty( $full_name ) )
				continue; 

			// Skip the presenter if it already exists
			if ( $post = get_page_by_title( $full_name, OBJECT, ONA12_Presenter::post_type ) ) {
				WP_CLI::line( "Skipping {$full_name}, already exists as #{$post->ID}" );
				continue;
			}

			$post = array(
					'post_title'       => $full_name,
					'post_content'     => wp_filter_post_kses( $presenter['Bio'] ),
					'post_type'        => ONA12_Presenter::post_type,
					'post_status'      => 'publish',
				);
			$id = wp_insert_post( $post );

			$title = sanitize_text_field( $presenter['Title'] );
			update_post_meta( $id, '_ona12_presenter_title', $title );
		
			$organization = strip_tags( $presenter['Company / Organization'], '<a>' );
			update_post_meta( $id, '_ona12_presenter_organization', $organization );

			$email_address = sanitize_email( $presenter['Email'] );
			update_post_meta( $id, '_ona12_presenter_email_address', $email_address );
		
			$twitter = str_replace( '@', '', str_replace( 'http://twitter.com/', '', sanitize_text_field( $presenter['Twitter Handle'] ) ) );
			update_post_meta( $id, '_ona12_presenter_twitter', $twitter );

			$session_slug = trim( strtolower( $presenter['Shortened Title'] ) );
			update_post_meta( $id, '_ona12_presenter_session_slug', $session_slug );

			WP_CLI::line( "Created {$full_name} as post #{$id}" );
			$count_created++;
		}

		WP_CLI::success( "All done! Created {$count_created} presenters" );

	}

	/**
	 * Import sessions into a site
	 */
	public function import_sessions( $args, $assoc_args ) {

		$defaults = array(
				'csv'                     => '',
			);

		$this->args = wp_parse_args( $assoc_args, $defaults );

		$handle = fopen( $this->args['csv'], 'r' );
		$c = 0;

		$sessions = array();
		while ( false !== ( $data = fgetcsv( $handle ) ) ) {
			$c++;
			// Set the headers on the file
			if ( 1 == $c ) {
				$headers = $data;
				continue;
			}

			$session = array();
			foreach( $headers as $index => $key ) {
				$session[$key] = $data[$index];
			}
			$sessions[] = $session;

		}

		$count_created = 0;
		foreach( $sessions as $session ) {

			$session_title = $session['Session Name'];
			// Skip the session if it already exists
			if ( $post = get_page_by_title( $session_title, OBJECT, ONA12_Session::post_type ) ) {
				WP_CLI::line( "Skipping {$session_title}, already exists as #{$post->ID}" );
				continue;
			}

			$post = array(
					'post_title'       => $session_title,
					'post_content'     => wp_filter_post_kses( $session['Session Description'] ),
					'post_type'        => ONA12_Session::post_type,
				);
			$post['post_status'] = ( 'Y' == $session['Visible on Site?'] ) ? 'publish' : 'draft';
			$id = wp_insert_post( $post );

			$start_timestamp = strtotime( $session['Start Date & Time'] );
			update_post_meta( $id, '_ona12_start_timestamp', $start_timestamp );
			
			$end_timestamp = strtotime( $session['End Date & Time'] );
			update_post_meta( $id, '_ona12_end_timestamp', $end_timestamp );
			
			$location = sanitize_text_field( $session['Room or Building Name'] );
			if ( ! term_exists( $location, 'ona12_locations' ) )
				$term = (object)wp_insert_term( $location, 'ona12_locations' );
			else
				$term = get_term_by( 'name', $location, 'ona12_locations' );
			if ( ! is_wp_error( $term ) )
				wp_set_post_terms( $id, $term->term_id, 'ona12_locations' );
			
			$session_type = sanitize_text_field( $session['Session Sub-type'] );
			// Some sessions don't have sub-types, in which case we want to use the type
			if ( empty( $session_type ) )
				$session_type = sanitize_text_field( $session['Session Type'] );
			if ( ! term_exists( $session_type, 'ona12_session_types' ) )
				$term = (object)wp_insert_term( $session_type, 'ona12_session_types' );
			else
				$term = get_term_by( 'name', $session_type, 'ona12_session_types' );
			if ( ! is_wp_error( $term ) )
				wp_set_post_terms( $id, $term->term_id, 'ona12_session_types' );

			WP_CLI::line( "Created {$session_title} as post #{$id}" );
			$count_created++;
		}

		WP_CLI::success( "All done! Created {$count_created} sessions" );

	}



}