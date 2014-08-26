</div><!-- /#container -->


	<script src="//opintopolku.fi/app/lib/jquery.enhanced.cookie.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/vendor/jquery.html5-placeholder-shim.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/plugins.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js" type="text/javascript"></script>

	<!-- Piwik -->
	<script src="<?php echo get_template_directory_uri(); ?>/js/piwik.js" type="text/javascript"></script>
	<!-- End Piwik Code -->

    <!-- Oppija-raamit -->
    <script id="apply-raamit" src="https://test-oppija.oph.ware.fi/oppija-raamit/apply-raamit.js" type="text/javascript"></script>

	<script>
        $("html").on("oppija-raamit-loaded", function() {
            $("body").show()
            $("body").attr("aria-busy","false")
        })
    </script>

	<?php wp_footer(); ?>
</div>
</body>
</html>