<?php get_header(); ?>

<div id="content-row" class="container_12">

	<?php get_sidebar( 'ona12_session_archive' ); ?>

	<?php get_template_part( 'loop', 'session_archive' ); ?>

</div><!-- #content-row -->

<?php get_footer(); ?>