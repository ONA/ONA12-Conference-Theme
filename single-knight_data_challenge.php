<?php get_header( 'kdc' ); ?>

<div id="content-row" class="container_16">

	<div id="posts-container" class="grid_16">
	<div id="posts" class="box">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<article class="post" id="post-<?php the_ID(); ?>">

			<h2 class="title"><a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a></h2>
			<?php
				$post_content = apply_filters( 'the_content', get_the_content() );
				preg_match_all( '/<p>.+<\/p>/', $post_content, $matches );
				$first_graf = array_shift( $matches[0] );
				$left_column = implode( '', array_slice( $matches[0], 0, ceil( count( $matches[0] ) / 2 ) ) );
				$right_column = implode( '', array_slice( $matches[0], ceil( count( $matches[0] ) / 2 ), count( $matches[0] ) ) );
			?>
			<div class="entry">
				<?php echo $first_graf; ?>
			</div>

			<div class="share-buttons">
				<a href="https://twitter.com/share" class="twitter-share-button" data-via="ONAConf" data-related="ONAConf">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>

			<div id="knight-projects">
			<ul>
			<?php
				$args = array(
						'post_type'        => Knight_Data_Challenge_Announcement::project_post_type,
						'posts_per_page'   => 30,
						'orderby'          => 'title',
						'order'            => 'asc',
					);
				$projects = new WP_Query( $args );
			?>
			<?php if ( $projects->have_posts() ) : ?>

			<?php while ( $projects->have_posts() ) : $projects->the_post(); ?>
				<li>
					<?php
						$args = array(
								'post_parent'       => get_the_ID(),
								'post_type'         => 'attachment',
								'post_status'       => 'any',
								'suppress_filters'  => false,
							);
						$attachments = get_posts( $args );
						if ( ! empty( $attachments ) ) {
							echo '<div class="project-avatars">';
							foreach( $attachments as $attachment ) {
								echo wp_get_attachment_image( $attachment->ID, 'ona12-project-medium-square' );
							}
							echo '</div>';
						}
					?>
					<h4><a target="_blank" href="<?php echo esc_url( Knight_Data_Challenge_Announcement::get_project_field( 'url' ) ); ?>"><?php the_title(); ?></a></h4>
					<div class="project-description">
					<?php the_excerpt(); ?>
					</div>
					<div class="project-details">
						<span class="label">Winners:</span> <span class="value"><?php echo Knight_Data_Challenge_Announcement::get_project_field( 'winners' ); ?></span><br />
						<span class="label">Award:</span> <span class="value"><?php echo Knight_Data_Challenge_Announcement::get_project_field( 'award' ); ?></span><br />
					</div>
				</li>
			<?php endwhile; ?>
			<?php wp_reset_query(); ?>
			<?php endif; ?>
			</ul>
			<div class="clear-both"></div>
			</div>

			<div class="entry">
				<div class="column right-column">
					<?php echo $right_column; ?>
				</div>
				<div class="column left-column">
					<?php echo $left_column; ?>
				</div>
			</div>

			</div>

		</article>

	<?php endwhile; ?>

	<?php else : ?>

		<h2>Not Found</h2>

	<?php endif; ?>
	
	</div><!-- #posts -->
	</div><!-- #posts-container -->

</div><!-- #content-row -->

<?php get_footer(); ?>