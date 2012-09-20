<?php get_header( 'big' ); ?>

<div id="content-row" class="container_12">

	<div id="posts-container" class="grid_9">
	<div id="posts" class="box">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<aside class="presenter-navigation">
			<a href="<?php echo site_url( '/presenters/' ); ?>"><?php _e( 'All Presenters' ); ?></a>
		</aside>

		<article class="post session" id="post-<?php the_ID(); ?>">

			<div class="presenter-avatar">
				<?php echo ONA12_Presenter::get_avatar( 'ona12-medium-tall-avatar' ); ?>
			</div>
			
			<h2 class="title"><a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a></h2>

			<p class="presenter-affiliation"><?php echo implode( ', ', array( ONA12_Presenter::get('title'), ONA12_Presenter::get('organization') ) ); ?></p>
			<?php if ( ONA12_Presenter::get( 'twitter' ) ) : ?>
			<p class="presenter-twitter"><a href="<?php echo esc_url( 'http://twitter.com/' . ONA12_Presenter::get( 'twitter' ) ); ?>">@<?php echo ONA12_Presenter::get( 'twitter' ); ?></a></p>
			<?php endif; ?>
			
			<div class="entry">
				<?php the_content(); ?>
			</div>

			<?php
				$args = array(
						'post_type'      => ONA12_Session::post_type,
						'connected_to' => get_the_ID(),
						'connected_type' => 'sessions_to_presenters',
					);
				$sessions = new WP_Query( $args );
				if ( $sessions->have_posts() ) :
			?>
			<h3>Sessions</h3>
			<ul class="presenter-sessions">
			<?php while( $sessions->have_posts() ) : $sessions->the_post(); ?>
			<li>
				<div class="session-time-location">
					<?php
						$start_timestamp = (int)get_post_meta( get_the_id(), '_ona12_start_timestamp', true );
						$location = ONA12_Session::get( 'location' );
						$session_details = array(
							date( 'l, g:i a', $start_timestamp ),
						);
						if ( $location ) {
							$session_details[] = $location;
						}
						echo implode( '<br />', $session_details );
					?>
				</div>
				<div class="session-details">
					<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					<?php the_excerpt(); ?>
				</div>
			</li>
			<?php endwhile; ?>
			</ul>
			<?php wp_reset_query(); ?>
			<?php endif ;?>

			<div class="clear-both"></div>

		</article>

	<?php endwhile; ?>

	<?php else : ?>

		<h2>Not Found</h2>

	<?php endif; ?>
	
	</div><!-- #posts -->
	</div><!-- #posts-container -->

</div><!-- #content-row -->

<?php get_footer(); ?>