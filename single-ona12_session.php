<?php get_header(); ?>

<div id="content-row" class="container_12">

	<?php get_sidebar( 'ona12_session' ); ?>

	<div id="posts-container" class="grid_9">
	<div id="posts" class="box">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<aside class="session-navigation">
	<a href="<?php echo site_url( '/sessions/' ); ?>"><?php _e( 'All Sessions' ); ?></a>
	<?php
	$session_types = wp_get_post_terms( get_queried_object_id(), 'ona12_session_types' );
	$current_track = false;
	if ( count( $session_types ) ) {
		$current_track = $session_types[0]->slug;
		$session_types_html = ' &rarr; <a href="' . get_term_link( $session_types[0] ) . '">' . esc_html( $session_types[0]->name ) . '</a>';
		if ( $session_types[0]->parent ) {
			$parent_session = get_term_by( 'id', $session_types[0]->parent, 'ona12_session_types' );
			$session_types_html = ' &rarr; <a href="' . get_term_link( $parent_session ) . '">' . esc_html( $parent_session->name ) . '</a>' . $session_types_html;
		}
		echo $session_types_html;
	}
	?>
	</aside>

		<article class="post session" id="post-<?php the_ID(); ?>">
			
			<h2 class="title"><a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a></h2>

			<div class="session-details">
				<?php
					$start_timestamp = (int)get_post_meta( get_the_id(), '_ona12_start_timestamp', true );
					$location = wp_get_object_terms( $post->ID, 'ona12_locations', array( 'fields' => 'names' ) );
					$location = ( !empty( $location ) ) ? $location[0] : '';
					$session_details = array(
							date( 'l', $start_timestamp ),
							date( 'g:i a', $start_timestamp ),
							$location,
						);
				echo '<ul><li>' . implode( '</li><li>', $session_details ) . '</li></ul>';
				?>
			</div>
			
			<div class="entry">
				<?php the_content(); ?>
			</div>

		</article>

	<?php endwhile; ?>

	<?php else : ?>

		<h2>Not Found</h2>

	<?php endif; ?>
	
	</div><!-- #posts -->
	</div><!-- #posts-container -->

<?php get_sidebar(); ?>

</div><!-- #content-row -->

<?php get_footer(); ?>