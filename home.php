<?php
/*
Template Name: Home
*/
?>
<?php get_header('big'); ?>

<div id="content-featured-container">
<div id="content-featured" class="container_12">
	
	<div id="featured-posts">
	<?php $posts = z_get_posts_in_zone('featuredposts'); ?>
	<?php foreach( $posts as $post ) :
				setup_postdata($post);
				$more = 0;
	?>

	<div class="featured-post-container grid_3">
	<article class="featured-post">
		<h3><?php the_title(); ?></h3>
		<?php the_content(''); ?>
	</article><!-- .featured-post -->	
	</div>
	
	<?php endforeach; ?>
	</div><!-- #featured-posts -->

<!-- <div id="sponsors-top" class="content-row container_12">
		<div class="grid_12">
		<h4>Sponsors</h4>

<img src="img/">

			<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Sponsors (Top)')) : else : ?>
	
			<?php endif; ?>

		
		<p class="separator"><a href="" class="jumptext">See all attending sponsors and exhibitors &rsaquo;</a></p>
		
-->
		</div>
	</div>

</div><!-- #content-featured -->

</div><!-- #content-featured-container -->

<div id="home-content" class="content-row container_12">

	<div id="posts-container" class="grid_9">
	<div id="posts" class="box">
	
	<?php rewind_posts(); ?>
	
	<?php query_posts('post_type=post&posts_per_page=3'); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php $more = 0; ?>
	
		<article class="post" id="post-<?php the_ID(); ?>">
			
			<h2><a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a></h2>

			<div id="post-info">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/img/meta-calendar.png" class="meta-icon" /><time datetime="<?php echo date(DATE_W3C); ?>" pubdate class="updated"><?php if (function_exists('ap_date')) { echo '<span class="date">'; ap_date(); echo '</span>'; echo " &mdash; "; } else { } ?><?php if (function_exists('ap_time')) { ap_time(); } else { the_time('F jS, Y'); } ?></time> <img src="<?php bloginfo('stylesheet_directory'); ?>/img/meta-category.png" class="meta-icon" /><?php the_category(', '); ?>
			
			<?php the_tags('<div class="tags-icon"></div>','','') ?>
			
			<? /* <span class="byline author vcard">
			<i>by</i> <span class="fn"><?php the_author() ?></span></span> */ ?>
			<?php /* comments_popup_link('No Comments', '1 Comment', '% Comments', 'comments-link', ''); */ ?>
			</div><!-- #post-info -->
			
			<div class="entry">

				<?php the_content(''); ?>
			
			<p><a href="<?php the_permalink(); ?>" class="jumptext">Read more &rsaquo;</a></p>
			
			</div>

		</article>

	<?php endwhile; ?>
	
	<?php endif; ?>
	
	</div><!-- #posts -->
	</div><!-- #posts-container -->

<?php get_sidebar(); ?>

</div><!-- #content-row -->

<!-- <div id="sponsors-bottom" class="content-row container_12">
	<div class="grid_12">
	<?php include 'sponsors-bottom.php'; ?>
	</div>
</div> -->

<div id="content-calendar" class="container_12 content-row">

	<div class="grid_12">
	<div id="calendar" class="calendar-box">
		<div class="calendar-box-content">
	<h2>Calendar</h2>
	<hr />
	</div><!-- .calendar-box-content -->
	
	<div class="container_12">
	
	<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Calendar')) : else : ?>
	
	<?php endif; ?>
	
	</div><!-- #calendar -->
	</div>

</div><!-- #content-calendar -->

<?php get_footer(); ?>