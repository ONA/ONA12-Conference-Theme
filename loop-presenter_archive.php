<div id="posts-container" class="grid_16">
	<div id="posts" class="box">

		<h2>Presenters</h2>

		<?php
			$presenter_args = array(
					'post_type'         => ONA12_Presenter::post_type,
					'posts_per_page'    => 150,
					'orderby'           => 'title',
					'order'             => 'asc',
				);
				$presenters = new WP_Query( $presenter_args ); ?>

		<?php if ( $presenters->have_posts() ): ?>

		<?php while( $presenters->have_posts() ): $presenters->the_post(); ?>

		<div class="single-presenter">
			<div class="presenter-avatar">
				<?php echo ONA12_Presenter::get_avatar( 'ona12-medium-tall-avatar' ); ?>
			</div>
			<div class="presenter-details">
				<h3><?php echo ONA12_Presenter::get( 'name' ); ?></h3>
				<p class="presenter-affiliation"><?php echo implode( ',<br />', array( ONA12_Presenter::get('title'), ONA12_Presenter::get('organization') ) ); ?></p>
				<?php if ( ONA12_Presenter::get( 'twitter' ) ) : ?>
				<p class="presenter-twitter"><a href="<?php echo esc_url( 'http://twitter.com/' . ONA12_Presenter::get( 'twitter' ) ); ?>">@<?php echo ONA12_Presenter::get( 'twitter' ); ?></a></p>
				<?php endif; ?>
			</div>
		</div>

		<?php endwhile; ?>

		<div class="clear-both"></div>

		<?php endif; ?>

	</div><!-- #posts -->
</div><!-- #posts-container -->