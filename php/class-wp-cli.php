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

			WP_CLI::line( "Created {$full_name} as post #{$id}" );
			$count_created++;
		}

		WP_CLI::success( "All done! Created {$count_created} presenters" );

	}


}