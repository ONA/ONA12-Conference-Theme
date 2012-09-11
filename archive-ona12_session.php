<?php get_header(); ?>

<div id="content-row" class="container_12">

	<?php get_sidebar( 'ona12_session' ); ?>

	<div id="posts-container" class="grid_9">
	<div id="posts" class="box">

	<?php
		$args = array(
			'post_type' => ONA12_Session::post_type,		
			'posts_per_page' => 100,
			'meta_key' => '_ona12_start_timestamp',
			'orderby' => 'meta_value',
			'order' => 'asc',
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
			'09/20/2011',
			'09/21/2011',
			'09/22/2011',		
		);
	
		// Load all of the sessions into an array based on start date and time
		$all_sessions = array();
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
			<ul>
			<?php foreach( $posts as $post ): ?>
				<?php setup_postdata( $post ); ?>
				<?php
					$session_types = wp_get_post_terms( get_the_id(), 'ona12_session_types' );
					if ( count( $session_types ) ) {
						$session_types_html = '<a href="' . get_term_link( $session_types[0] ) . '">' . esc_html( $session_types[0]->name ) . '</a>)</em></span>';
						$session_types_html = '&nbsp;<span class="session-type"><em>(' . $session_types_html;
					} else {
						$session_types_html = '';
					}
					
					$session_location = wp_get_post_terms( get_the_id(), 'ona12_locations' );
					if ( count( $session_location ) ) {
						$session_where = '<span class="session-location float-right"><a href="' . get_term_link( $session_location[0] ) . '">' . esc_html( $session_location[0]->name ) . '</a>';
						if ( $session_location[0]->parent ) {
							$parent_location = get_term_by( 'id', $session_location[0]->parent, 'ona11_locations' );
							$session_where .= ', <a href="' . get_term_link( $parent_location ) . '">' . esc_html( $parent_location->name ) . '</a>';
						}
						$session_where .= '</span>';
					} else {
						$session_where = '';
					}
				?>
				<li>
					<h4 class="session-title"><?php echo $session_where; ?><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><?php echo $session_types_html; ?></h4>
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

<?php get_sidebar(); ?>

</div><!-- #content-row -->

<?php get_footer(); ?>