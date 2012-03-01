<?php get_header(); ?>

<div id="content-row" class="container_12">

	<div id="posts-container" class="grid_8">
	<div id="posts" class="box">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<article class="post" id="post-<?php the_ID(); ?>">
			
			<div id="post-info">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/img/meta-calendar.png" class="meta-icon" /><time datetime="<?php echo date(DATE_W3C); ?>" pubdate class="updated"><?php if (function_exists('ap_date')) { ap_date(); echo " &mdash; "; } else { } ?><?php if (function_exists('ap_time')) { ap_time(); } else { the_time('F jS, Y'); } ?></time><br />
			<img src="<?php bloginfo('stylesheet_directory'); ?>/img/meta-category.png" class="meta-icon" /><?php the_category(', '); ?><br />
			
			<?php the_tags('<div class="tags-icon"></div>','','') ?>
			
			<? /* <span class="byline author vcard">
			<i>by</i> <span class="fn"><?php the_author() ?></span></span> */ ?>
			<?php /* comments_popup_link('No Comments', '1 Comment', '% Comments', 'comments-link', ''); */ ?>
			</div><!-- #post-info -->
			
			<h2><a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a></h2>
			
			<div class="entry">

				<?php the_content(''); ?>

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