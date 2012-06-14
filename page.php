<?php get_header(); ?>

<div id="content-row" class="container_12">

	<div id="posts-container" class="grid_9">
	<div id="posts" class="box">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
		<article class="post" id="post-<?php the_ID(); ?>">
			
			<h2 class="title"><a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a></h2>
			
			<div class="entry">

				<?php the_content(''); ?>

			</div>

		</article>

		<?php endwhile; endif; ?>
	
	</div><!-- #posts -->
	</div><!-- #posts-container -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
