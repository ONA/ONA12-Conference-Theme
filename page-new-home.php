<?php
/**
 * Template name: New Home
 */
?>
<?php get_header(); ?>

<div id="content-row" class="container_16">

	<div id="posts-container">

		<div class="box">

			<div id="home-featured">
				<?php
					$args = array(
							'post_type'           => ONA12_New_Home::home_post_type,
							'post_per_page'       => 3,
							'no_found_posts'      => true, 
						); 
					$featured_posts = new WP_Query( $args );
					$lead_post = array_shift( $featured_posts->posts ); ?>
				<div id="home-featured-secondary">
					<h4 id="newsroom-title">From The Newsroom</h4>
					<ul>
				<?php foreach( $featured_posts->posts as $secondary_post ): ?>
					<li>
						<h5><a href="<?php echo get_permalink( $secondary_post->ID ); ?>"><?php echo get_the_title( $secondary_post->ID ); ?></a></h5>
						<?php echo apply_filters( 'the_excerpt', $secondary_post->post_excerpt ); ?>
						<?php if ( $byline = get_post_meta( $secondary_post->ID, '_ona12_featured_byline', true ) ) : ?>
						<p class="home-featured-byline">By <?php echo get_post_meta( $secondary_post->ID, '_ona12_featured_byline', true ); ?></p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
					</ul>
				</div>
				<div id="home-featured-primary">
					<?php echo get_the_post_thumbnail( $lead_post->ID, 'ona12-featured-large' ); ?>
					<div id="home-featured-primary-details">
						<h3><a href="<?php echo get_permalink( $lead_post->ID ); ?>"><?php echo get_the_title( $lead_post->ID ); ?></a></h3>
						<?php echo apply_filters( 'the_excerpt', $lead_post->post_excerpt ); ?>
						<?php if ( $byline = get_post_meta( $lead_post->ID, '_ona12_featured_byline', true ) ) : ?>
						<p class="home-featured-byline">By <?php echo $byline; ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="clear-both"></div>

			<div id="sponsor-rail">
				<a href="http://themidway.tumblr.com/"><img src="<?php echo get_stylesheet_directory_uri() . '/img/midway-375.jpg'; ?>" width="300px" /></a>
			</div>


			<div id="session-updates">
				<?php if ( $sessions = z_get_posts_in_zone( 'featured-sessions', array( 'post_status' => 'publish', 'posts_per_page' => 3 ) ) ): ?>
				<h4 id="featured-sessions-title">Featured Sessions</h4>
				<ul id="featured-sessions">
					<?php foreach( $sessions as $session ): ?>
					<?php
						$session_details = array();
						$start_timestamp = ONA12_Session::get( 'start_timestamp', $session->ID );
						$session_details[] = date( 'l g:i a', $start_timestamp );
						if ( $session_location = ONA12_Session::get( 'location', $session->ID, 'object' ) )
							$session_details[] = '<span class="session-location"><a href="' . get_term_link( $session_location ) . '">' . esc_html( $session_location->name ) . '</a></span>';
						// if ( $hashtag = ONA12_Session::get( 'hashtag', $session->ID ) )
							// $session_details[] = '<span class="session-hashtag"><a target="_blank" href="https://twitter.com/i/#!/search/?q=' . urlencode( $hashtag ) . '">' . $hashtag . '</a></span>';
					?>
					<li class="single-session">
						<h5 class="session-title"><a href="<?php echo get_permalink( $session->ID ); ?>"><?php echo get_the_title( $session->ID ); ?></a></h5>
						<ul class="session-details"><li><?php echo implode( '</li><li>', $session_details ); ?></li></ul>
						<div class="session-description"><?php echo apply_filters( 'the_excerpt', $session->post_excerpt ); ?></div>
					</li>
					<?php endforeach; ?>
				</ul>
				<div class="clear-both"></div>
				<?php endif; ?>

				<h4 id="session-updates-title">Session Updates</h4>
				<?php
					$recent_updates = ONA12_New_Home::get_session_updates();
				?>
				<ul id="liveblog-updates">
				<?php foreach( $recent_updates as $recent_update ): ?>
					<li id="liveblog-entry-<?php echo $recent_update->comment_ID; ?>">
					<div class="liveblog-entry-text">
						<?php echo ONA12_New_Home::render_comment_content( $recent_update->comment_content ); ?>
					</div>
					<div class="liveblog-meta">
						<span class="liveblog-session-title"><a href="<?php echo get_permalink( $recent_update->comment_post_ID ); ?>"><?php echo get_the_title( $recent_update->comment_post_ID ); ?></a></span>
						<span class="sep">-</span>
						<span class="liveblog-posted-by">Posted by</span>
						<span class="liveblog-author-name"><?php echo $recent_update->comment_author; ?></span>
					</div>
				</li>
				<?php endforeach; ?>
				</ul>
			</div>

			<div class="clear-both"></div>

		</div>

	</div>

</div>

<?php get_footer(); ?>