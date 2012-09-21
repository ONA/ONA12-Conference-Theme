<div id="posts-container" class="grid_9">
	<div id="posts" class="box">

	<?php
		$args = array(
			'post_type' => ONA12_Session::post_type,		
			'posts_per_page' => 100,
			'meta_key' => '_ona12_start_timestamp',
			'orderby' => 'meta_value',
			'order' => 'asc',
			'no_found_rows' => true,
		);
	
		if ( is_tax() ) {
			$queried_term = get_queried_object();	
			$args['tax_query'] = array(
				array(
					'taxonomy' => $queried_term->taxonomy,
					'field' => 'id',
					'terms' => $queried_term->term_id,
				),
			);
		}
	
		$sessions = new WP_Query( $args );
	
		$session_days = array(
			'09/20/2012',
			'09/21/2012',
			'09/22/2012',
		);

		// Some crafty shit to put the current date at the top
		$today = date( 'm/d/Y', ( time() - 25200 ) );
		if ( in_array( $today, $session_days ) ) {
			if ( 1 == array_search( $today, $session_days ) ) {
				$yesterday = array_shift( $session_days );
				$session_days[] = $yesterday;
			} else if ( 2 == array_search( $today, $session_days ) ) {
				$saturday = array_pop( $session_days );
				array_unshift( $session_days, $saturday );
			}
		}
	
		// Load all of the sessions into an array based on start date and time
		$all_sessions = array(
				$session_days[0] => array(),
				$session_days[1] => array(),
				$session_days[2] => array(),
			);
		while( $sessions->have_posts() ) {
			$sessions->the_post();
			$start_timestamp = get_post_meta( get_the_ID(), '_ona12_start_timestamp', true );
			$start_date = date( 'm/d/Y', $start_timestamp );
			$start_time = date( 'g:i a', $start_timestamp );
			$all_sessions[$start_date][$start_time][$post->ID] = $post;
		}
		?>

		<?php if ( is_tax() ): ?>
			<?php $queried_object = get_queried_object(); ?>
			<h2><a href="<?php echo get_site_url( null, '/sessions/' ); ?>"><?php _e( 'All Sessions' ); ?></a>
			<?php
				$term_title_html = ' &rarr; <a href="' . get_term_link( $queried_object ) . '">' . esc_html( $queried_object->name ) . '</a>';
				if ( $queried_object->parent ) {
					$parent_term = get_term_by( 'id', $queried_object->parent, $queried_object->taxonomy );
					$term_title_html = ' &rarr; <a href="' . get_term_link( $parent_term ) . '">' . esc_html( $parent_term->name ) . '</a>' . $term_title_html;
				}
				echo $term_title_html;
			?></h2>
			<?php if ( $queried_object->description ): ?>
			<div class="term-description">
			<?php echo wpautop( $queried_object->description ); ?>	
			</div>
			<?php endif; ?>
		<?php else: ?>
			<h2><?php _e( 'All Sessions' ); ?></h2>
		<?php endif; ?>

<?php foreach( $all_sessions as $session_day => $days_sessions ):
	$day_full_name = date( 'l n.d', strtotime( $session_day ) );
	$day_slugify = sanitize_title( $day_full_name );
?>

<div id="session-day-<?php echo $day_slugify; ?>" class="session-day">
	<?php
		$day_title = '';
		foreach( $all_sessions as $sd => $ds ) {
			$sd_full = date( 'l n.d', strtotime( $sd ) );
			$sd_slugify = sanitize_title( $sd_full );
			$day_title .= '<a class="day-title';
			if ( $sd_slugify == $day_slugify )
				$day_title .= ' active';
			$day_title .= '" href="#' . $sd_slugify . '">' . $sd_full . '</a>';
		}
	
	?>
	<a id="<?php echo $day_slugify; ?>"></a>
	<h3><?php echo $day_title; ?></h3>
	<div class="day-sessions">
	<?php foreach( $days_sessions as $start_time => $posts ): ?>
		<div class="session-time-block">
			<div class="session-start-time"><?php echo $start_time; ?></div>			
			<ul class="session-list session-count-<?php echo count( $posts ); ?>">
			<?php foreach( $posts as $post ): ?>
				<?php setup_postdata( $post ); ?>
				<?php
					$session_details = array();
					$session_types = get_the_terms( $post->ID, 'ona12_session_types' );
					if ( ! empty( $session_types ) ) {
						$session_type = array_shift( $session_types );
						$session_details[] = '<span class="session-type"><a href="' . get_term_link( $session_type ) . '">' . esc_html( $session_type->name ) . '</a></span>';
					}
					
					$session_locations = get_the_terms( $post->ID, 'ona12_locations' );
					if ( ! empty( $session_locations ) ) {
						$session_location = array_shift( $session_locations );
						$session_details[] = '<span class="session-location"><a href="' . get_term_link( $session_location ) . '">' . esc_html( $session_location->name ) . '</a></span>';
					}
				?>
				<li class="single-session">
					<h4 class="session-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					<ul class="session-details"><li><?php echo implode( '</li><li>', $session_details ); ?></li></ul>
					<div class="session-description"><?php the_excerpt(); ?></div>
				</li>
			<?php endforeach; ?>
			</ul>
			<div class="clear-left"></div>
		</div>
	<?php endforeach; ?>
	</div>
</div>
<?php endforeach; ?>

	</div><!-- #posts -->
	</div><!-- #posts-container -->