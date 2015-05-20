</div><!-- /#container -->
	<!-- Piwik -->
	<script src="<?php echo get_template_directory_uri(); ?>/js/piwik.js" type="text/javascript"></script>
	<!-- End Piwik Code -->

    <!-- Oppija-raamit -->
    <script id="apply-raamit" src="/oppija-raamit/apply-raamit.js" type="text/javascript"></script>

	<script>
        $("html").on("oppija-raamit-loaded", function() {
            $("body").show()
            $("body").attr("aria-busy","false")
        })
    </script>

	<?php wp_footer(); ?>

</body>
</html>