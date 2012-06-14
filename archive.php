<?php get_header(); ?>

<div id="content-row" class="container_12">

	<div id="posts-container" class="grid_9">
	<div id="posts" class="box">

		<?php if (have_posts()) : ?>

 			<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

			<?php /* If this is a category archive */ if (is_category()) { ?>
				<h2>Category: <?php single_cat_title(); ?></h2>

			<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
				<h2>Posts tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>

			<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
				<h2>Archive for <?php the_time('F jS, Y'); ?></h2>

			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
				<h2>Archive for <?php the_time('F, Y'); ?></h2>

			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
				<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>

			<?php /* If this is an author archive */ } elseif (is_author()) { ?>
				<h2 class="pagetitle">Author Archive</h2>

			<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
				<h2 class="pagetitle">Archives</h2>
			
			<?php } ?>
			
			<hr />

			<?php while (have_posts()) : the_post(); ?>
			
		<article class="post" id="post-<?php the_ID(); ?>">
			
			<div id="post-info">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/img/meta-calendar.png" class="meta-icon" /><time datetime="<?php echo date(DATE_W3C); ?>" pubdate class="updated"><?php if (function_exists('ap_date')) { ap_date(); echo " &mdash; "; } else { } ?><?php if (function_exists('ap_time')) { ap_time(); } else { the_time('F jS, Y'); } ?></time><br />
			<img src="<?php bloginfo('stylesheet_directory'); ?>/img/meta-category.png" class="meta-icon" /><?php the_category(', '); ?><br />
			
			<?php the_tags('<div class="tags-icon"></div>','','') ?>
			
			<? /* <span class="byline author vcard">
			<i>by</i> <span class="fn"><?php the_author() ?></span></span> */ ?>
			<?php /* comments_popup_link('No Comments', '1 Comment', '% Comments', 'comments-link', ''); */ ?>
			</div><!-- #post-info -->
			
			<h2 class="title"><a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a></h2>
			
			<div class="entry">

				<?php the_content(''); ?>

			</div>

		</article>

			<?php endwhile; ?>

			<?php /* include (TEMPLATEPATH . '/_/inc/nav.php' ); */ ?>
			
	<?php else : ?>

		<h2>Nothing found</h2>

	<?php endif; ?>
	
	</div><!-- #posts -->
	</div><!-- #posts-container -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
