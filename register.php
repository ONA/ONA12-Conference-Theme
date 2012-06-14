<?php
/*
Template Name: Register 
*/
get_header(); ?>

<div id="content-featured-container">
<div id="register" class="container_12">

		<div id="register-1" class="grid_4">
		<h4>Career&nbsp;Summit and Job&nbsp;Fair</h4>
		<p>A full day of career-oriented sessions plus a job fair with media companies looking to hire.</p>
		<p>Registration opens soon.</p>
		<p>Interested in recruiting? <a href="https://members.journalists.org/node/145">Register here.</a></p>
		</div><!-- #register-1 -->
		
		<div id="register-3" class="grid_4">
		<h3>ONA12 General Pass</h3>
		<p class="prices">Members: $399<br />Non-Members: $699<br />Student Members: $150</p>
		<p>Prices through June 20.<br />See below for details.</p>
		
		<p class="register-button"><a href="http://members.journalists.org/node/128">Register <img src="<?php bloginfo('stylesheet_directory'); ?>/img/button-jump.png" /></a></p>

		<p class="register-button"><a href="https://members.journalists.org/membership">Become a Member <img src="<?php bloginfo('stylesheet_directory'); ?>/img/button-jump.png" /></a></p>
		</div><!-- #register-3 -->
		
		<div id="register-5" class="grid_4">
		<h4>Workshop Pass</h4>
		<p>One pass for all the hands-on workshops on Thursday at the conference hotel.</p>
		<p>Registration opens soon.</p>
		</div><!-- #register-5 -->

</div><!-- #register -->

</div><!-- #content-featured-container -->

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
