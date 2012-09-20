<?php 
// Only show this widget if there are actually sessions happening right now
	$args = array(
			'post_type'                  => ONA12_Session::post_type,
			'posts_per_page'             => 20,
			'meta_key'                   => '_ona12_start_timestamp',
			'orderby'                    => 'meta_value',
			'order'                      => 'asc',
			'no_found_rows'              => true,
			'meta_query' => array(
					'relation'           => 'AND',
					array(
							'key'              => '_ona12_start_timestamp',
							'value'            => time() - 25200,
							'compare'          => '<',
							'type'             => 'NUMERIC',
						),
					array(
							'key'              => '_ona12_end_timestamp',
							'value'            => time() - 25200,
							'compare'          => '>',
							'type'             => 'numeric',
						),
				),
		);
		$happening_now = new WP_Query( $args );
	?>
<?php if ( $happening_now->have_posts() ): ?>
<section class="widget">

	<h4>Happening Now</h4>
	<ul>
	<?php while( $happening_now->have_posts() ): $happening_now->the_post(); ?>
		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			<?php if ( $location = ONA12_Session::get( 'location' ) ) {
				echo ' - ' . $location;
			} ?>
		</li>
	<?php endwhile; ?>
	</ul>
</section>
<?php endif; ?>