		</div><!-- #content-container -->
		
		<div id="footer-container">
		<footer id="footer" class="container_12">
			
			<div class="footer-content contact grid_3">
			<h3 class="center">Have a question?</h3>
			<p class="center">Send us a note at <a href="mailto:ONA12@journalists.org">ONA12@journalists.org</a></p>
			<hr />
			<p>Or, if you have a specific inquiry:</p>
			<p><strong>Programming:</strong><br /><a href="mailto:ona12program@journalists.org">ONA12program@journalists.org</a></p>
			<p><strong>Sponsorships:</strong><br /><a href="mailto:tregan@journalists.org">tregan@journalists.org</a></p>
			<p><strong>Job Fair:</strong><br /><a href="mailto:ona12jobfair@journalists.org">ONA12jobfair@journalists.org</a></p>
			<p><strong>Student Newsroom:</strong><br /><a href="mailto:ona12newsroom@journalists.org">ONA12newsroom@journalists.org</a></p>
			<p><strong>Volunteers:</strong><br /><a href="mailto:ona12volunteers@journalists.org">ONA12volunteers@journalists.org</a></p>
			</div>
			
			<div class="conference-committee footer-content grid_3">
	<dl>
		<dt class="alpha">Conference Chairs</dt>
		<dd>Pam Maples <em>Stanford University</em></dd>
		<dd>Anthony Moor <em>Yahoo</em></dd>
		
		<dt>Board Adviser</dt>
		<dd>Burt Herman <em>Storify</em></dd>
		
		<dt>Programming</dt>
		<dd><a href="mailto:ona12program@journalists.org">David Cohn</a> <em>Spot.us</em></dd>
		
		<dt>Workshop Programming</dt>
		<dd>Robert Hernandez <em>USC&nbsp;Annenberg</em></dd>
		<dd>John Keefe <em>WNYC</em></dd>
		
		<dt>Career Summit</dt>
		<dd>Anna Tauzin <em>National Restaurant Association</em></dd>

	</dl>
			</div>
			
			<div class="conference-committee footer-content grid_3">
	<dl>
		<dt class="alpha">Sponsorships</dt>
		<dd><a href="mailto:tregan@journalists.org">Tom Regan</a> <em>ONA</em></dd>
		<dd><a href="mailto:director@journalists.org">Jane McDonnell</a> <em>ONA</em></dd>
		
		<dt>Volunteers</dt>
		<dd><a href="mailto:ona12volunteers@journalists.org">Laura Cochran</a> <em>USA&nbsp;TODAY</em></dd>
		
		<dt>Student Newsroom</dt>
		<dd><a href="mailto:ona12newsroom@journalists.org">Curt Chandler</a> <em>Penn State</em></dd>
		<dd><a href="mailto:ona12newsroom@journalists.org">Michelle Johnson</a> <em>Boston&nbsp;University</em></dd>
		<dd><a href="mailto:ona12newsroom@journalists.org">Sara Kelly</a> <em>National&nbsp;University</em></dd>
		
		<dt>Streaming</dt>
		<dd>Greg Linch <em>Washington&nbsp;Post</em></dd>
		
		<dt>Website</dt>
		<dd>Daniel Bachhuber <em>Automattic</em></dd>
	</dl>
			</div>
			
			<div class="footer-content grid_3">
			<p>Logo design and illustrations by <a href="http://portfolio.larrybuch.com/">Larry Buchanan</a>.</p>
			
			<p>Site built with <a href="http://wordpress.org/">WordPress</a>, the <a href="http://html5reset.org/">HTML5 Reset WordPress Theme</a> and the <a href="http://960.gs/">960 Grid System</a>. Icons courtesy <a href="http://thenounproject.com/">The Noun Project</a>.</p>
			
			<p class="footer-logo"><a href="http://journalists.org/"><img src="<?php bloginfo('stylesheet_directory') ?>/img/ona-logo.png" /></a></p>
			
			<p>&copy;<?php echo date("Y"); ?>&nbsp;<a href="http://journalists.org/">Online News Association</a>.</p>
			
			</div>
			
			</div><!-- #footer-content -->
		</footer>
		</div><!-- #footer-container -->

	</div><!-- #page-wrap -->

	<?php wp_footer(); ?>

<!-- here comes the javascript -->

<!-- jQuery is called via the Wordpress-friendly way via functions.php -->

<!-- this is where we put our custom functions -->
<script src="<?php bloginfo('template_directory'); ?>/_/js/functions.js"></script>

<!-- Asynchronous google analytics; this is the official snippet.
	 Replace UA-XXXXXX-XX with your site's ID and uncomment to enable. -->
	 
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-6125049-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	
</body>

</html>
