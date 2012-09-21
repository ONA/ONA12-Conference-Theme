<?php
/**
 * Template name: New Home
 */
?>
<?php get_header(); ?>

<div id="content-row" class="container_16">

	<div id="posts-container">

		<div class="box">

			<div id="session-updates">
				<h4 id="session-updates-title">Session Updates</h4>
				<?php
					$recent_updates = ONA12_New_Home::get_session_updates();
				?>
				<ul>
				<?php foreach( $recent_updates as $recent_update ): ?>
					<li id="liveblog-entry-<?php echo $recent_update->comment_ID; ?>">
					<div class="liveblog-entry-text">
						<?php echo ONA12_New_Home::render_comment_content( $recent_update->comment_content ); ?>
					</div>
					<div class="liveblog-meta">
						<span class="liveblog-session-title"><a href="<?php echo get_permalink( $recent_update->comment_post_ID ); ?>"><?php echo get_the_title( $recent_update->comment_post_ID ); ?></a></span>
						<span class="sep">-</span>
						<span class="liveblog-posted-by">Posted by</span>
						<span class="liveblog-author-name"><?php echo $recent_update->comment_author; ?></span>
					</div>
				</li>
				<?php endforeach; ?>
				</ul>
			</div>

			<div class="clear-both"></div>

		</div>

	</div>

</div>

<?php get_footer(); ?>