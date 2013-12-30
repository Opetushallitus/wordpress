<!-- footer navi -->
<div class="container footer-nav">
	<div class="footer-nav-logo">
		<?php if (ICL_LANGUAGE_CODE == 'sv') : ?>
			<img src="<?php echo get_template_directory_uri(); ?>/img/Studieinfo.png" alt="Studieinfo.fi" />
		<?php else : ?>
			<img src="<?php echo get_template_directory_uri(); ?>/img/Opintopolku_FI_logo.png" alt="Opintopolku.fi" />			
		<?php endif ?>
	</div>

	<nav>
		<?php wp_nav_menu( array('theme_location' => 'extra-menu' )) ?>
	</nav>
	
</div>
<!-- /footer navi --> 

<!-- footer -->
	<footer>		
		<div class="footer-wrapper">
			<div class="footer-item">
				<?php if (ICL_LANGUAGE_CODE == 'sv') : ?>
					<a href="http://www.oph.fi/startsidan" title="Utbildningsstyrelsen"><img src="<?php echo get_template_directory_uri(); ?>/img/oph_se_pysty.png" alt="Utbildningsstyrelsen" /></a>
				<?php else : ?>
					<a href="http://www.oph.fi/" title="Opetushallitus"><img src="<?php echo get_template_directory_uri(); ?>/img/oph_footer_logo.png" alt="Opetushallitus" /></a>
				<?php endif ?>
				
			</div>
			<div class="footer-item">
				<?php if (ICL_LANGUAGE_CODE == 'sv') : ?>
					<a href="http://www.minedu.fi/OPM/?lang=sv" title="Opetus- ja kulttuuriministeriö"><img src="<?php echo get_template_directory_uri(); ?>/img/OKM_Sve_logo.png" alt="Undervisnings- och kulturministeriet"/></a>
				<?php else: ?>
					<a href="http://www.minedu.fi/OPM/" title="Opetus- ja kulttuuriministeriö"><img src="<?php echo get_template_directory_uri(); ?>/img/okm_footer_logo.png" alt="Opetus- ja kulttuuriministeriö"/></a>				
				<?php endif ?>
			</div>
		</div>

		<p class="small">
			Copyright &copy; 2013 <?php _e('Opetushallitus') ?> - <a href="<?php echo get_page_link (get_page_by_title( __('rekisteriseloste') )->ID) ?>"><?php _e('rekisteriseloste') ?></a>
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