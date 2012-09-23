<?php get_header(); ?>

<div id="content-row" class="container_12">

	<?php get_sidebar( 'ona12_single_session' ); ?>

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
					$start_timestamp = ONA12_Session::get( 'start_timestamp' );
					$session_details = array(
							date( 'l', $start_timestamp ),
							date( 'g:i a', $start_timestamp ),
						);
					if ( $location = ONA12_Session::get( 'location' ) )
						$session_details[] = $location;
					if ( $hashtag = ONA12_Session::get( 'hashtag' ) )
						$session_details[] = '<a target="_blank" href="https://twitter.com/i/#!/search/?q=' . urlencode( $hashtag ) . '">' . $hashtag . '</a>';
				echo '<ul><li>' . implode( '</li><li>', $session_details ) . '</li></ul>';
				?>
			</div>

			<?php if ( $video_archive = ONA12_Session::get( 'video_archive' ) ) : ?>
			<div id="session-video-archive">
				<?php echo $video_archive; ?>
			</div>
			<?php endif; ?>

			<?php
				$args = array(
						'post_type'      => ONA12_Presenter::post_type,
						'connected_from' => get_the_ID(),
						'connected_type' => 'sessions_to_presenters',
					);
				$presenters = new WP_Query( $args );
				if ( $presenters->have_posts() ) :
			?>
			<?php $count = ( count( $presenters->posts ) > 1 ) ? 'multiple-presenters' : 'single-presenter'; ?>
			<div class="session-presenters<?php echo ' ' . $count; ?>">
				<h4><?php echo ( 'multiple-presenters' == $count ) ? 'Presenters' : 'Presenter'; ?></h4>
				<ul>
			<?php while( $presenters->have_posts() ): $presenters->the_post(); ?>
				<li>
				<?php if ( 'multiple-presenters' == $count ): ?>
				<div class="presenter-avatar">
					<a href="<?php the_permalink(); ?>"><?php echo ONA12_Presenter::get_avatar( 'ona12-small-square-avatar' ); ?></a>
				</div>
				<div class="presenter-details">
					<h5><a href="<?php the_permalink(); ?>"><?php echo ONA12_Presenter::get( 'name' ); ?></a></h5>
					<p class="presenter-affiliation"><?php echo implode( ',<br />', array( ONA12_Presenter::get('title'), ONA12_Presenter::get('organization') ) ); ?></p>
					<?php if ( ONA12_Presenter::get( 'twitter' ) ) : ?>
					<p class="presenter-twitter"><a href="<?php echo esc_url( 'http://twitter.com/' . ONA12_Presenter::get( 'twitter' ) ); ?>">@<?php echo ONA12_Presenter::get( 'twitter' ); ?></a></p>
					<?php endif; ?>
				</div>
				<?php else : ?>
				<div class="presenter-avatar">
					<a href="<?php the_permalink(); ?>"><?php echo ONA12_Presenter::get_avatar( 'ona12-medium-tall-avatar' ); ?></a>
				</div>
				<div class="presenter-details">
					<h5><a href="<?php the_permalink(); ?>"><?php echo ONA12_Presenter::get( 'name' ); ?></a></h5>
					<p class="presenter-affiliation"><?php echo implode( ',<br />', array( ONA12_Presenter::get('title'), ONA12_Presenter::get('organization') ) ); ?></p>
					<?php if ( ONA12_Presenter::get( 'twitter' ) ) : ?>
					<p class="presenter-twitter"><a href="<?php echo esc_url( 'http://twitter.com/' . ONA12_Presenter::get( 'twitter' ) ); ?>">@<?php echo ONA12_Presenter::get( 'twitter' ); ?></a></p>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				</li>
			<?php endwhile; ?>
				</ul>
			</div>
			<?php
			wp_reset_postdata();
			endif; ?>
			
			<div class="entry">
				<?php
					if ( $livestream = ONA12_Session::get_livestream() )
						echo '<div id="livestream-player">' . $livestream . '</div><div id="livestream-notice">See below the description for live updates from the session.</div>';
				?>
				<?php the_content(); ?>
			</div>

			<div class="clear-left"></div>

		</article>

	<?php endwhile; ?>

	<?php else : ?>

		<h2>Not Found</h2>

	<?php endif; ?>
	
	</div><!-- #posts -->
	</div><!-- #posts-container -->

</div><!-- #content-row -->

<?php get_footer(); ?>