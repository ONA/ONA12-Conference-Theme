<?php
/*
Template Name: Register 
*/
get_header(); ?>

<div id="content-featured-container">
<div id="register" class="container_12">

		<div id="register-1" class="grid_4">
		<h4>Career&nbsp;Summit & Job&nbsp;Fair</h4>
		<p>A full day of career-oriented sessions plus a job fair with media companies looking to hire.</p>

<p class="register-button"><a href="http://ona12.journalists.org/register/career-summit-job-fair/">Register &rsaquo;</a></p>

		</div><!-- #register-1 -->
		
		<div id="register-3" class="grid_4">
		<h3>ONA12 General Pass</h3>
		<p class="prices">Members: $499<br />Non-Members: $799<br />Student Members: $150</p>
		<p>Prices through July 31.<br />See below for details.</p>
		
		<p class="register-button"><a href="http://ona12.journalists.org/registration/general-pass">Register &rsaquo;</a></p>

		<p class="register-button"><a href="https://members.journalists.org/membership">Become a Member &rsaquo;</a></p>
		</div><!-- #register-3 -->
		
		<div id="register-5" class="grid_4">
		<h4>Workshop Passes</h4>
		<p>Individual passes for all the hands-on workshops on Thursday.</p>
		
		<p class="register-button"><a href="http://ona12.journalists.org/register/workshops/">Register &rsaquo;</a></p>
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
