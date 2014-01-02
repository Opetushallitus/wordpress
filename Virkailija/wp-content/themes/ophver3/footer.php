<!-- footer -->
	<footer>
		
		<div class="footer-wrapper">
			<div class="footer-item">
				<a href="http://www.oph.fi/" title="Opetushallitus"><img src="<?php echo get_template_directory_uri(); ?>/img/oph_footer_logo.png" alt="Opetushallitus" /></a>
			</div>
			<div class="footer-item">
				<a href="http://www.minedu.fi/OPM/" title="Opetus- ja kulttuuriministeriö"><img src="<?php echo get_template_directory_uri(); ?>/img/okm_footer_logo.png" alt="Opetus- ja kulttuuriministeriö"/></a>
			</div>
		</div>

		<p class="small">
			Copyright &copy; 2013 Opetushallitus - <a href="<?php echo get_page_link (get_page_by_title('rekisteriseloste')->ID) ?>">rekisteriseloste</a>
		</p>

	</footer>

</div><!-- /#container -->
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>

	<script src="//opintopolku.fi/app/lib/jquery.enhanced.cookie.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/vendor/jquery.html5-placeholder-shim.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/plugins.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js" type="text/javascript"></script>
	

	<!-- Piwik -->
	<script src="<?php echo get_template_directory_uri(); ?>/js/piwik.js" type="text/javascript"></script>
	<!-- End Piwik Code -->
	
	<?php wp_footer(); ?>

</body>
</html>