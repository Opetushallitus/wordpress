</div><!-- /#container -->
	<!-- Piwik -->
	<script src="<?php echo get_template_directory_uri(); ?>/js/piwik.js" type="text/javascript"></script>
	<!-- End Piwik Code -->

    <!-- Oppija-raamit -->
    <script id="apply-raamit" src="https://testi.opintopolku.fi/oppija-raamit/apply-raamit.js" type="text/javascript"></script>

	<script>
        $("html").on("oppija-raamit-loaded", function() {
            $("body").show()
            $("body").attr("aria-busy","false")
        })
    </script>

    <script id="oc-start-up"
            data-oc-service="0e8747a9-e9c5-4988-bdfb-f52371da5eea-151-A36EBDE7950F4DDAA05B4AF486AC30C0C04B2E75"
            data-oc-language="fi_FI"
            src="https://occhat.elisa.fi/chatserver//Scripts/oc-chat.js">
    </script>

	<?php wp_footer(); ?>

</body>
</html>