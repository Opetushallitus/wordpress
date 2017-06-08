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

<?php
    if(get_bloginfo('language') == "fi") {
?>
            switch (document.domain) {
                case "opintopolku.fi":
                    var script = document.createElement('script');
                    script.setAttribute("id", "oc-start-up");
                    script.setAttribute("data-oc-service", "0e8747a9-e9c5-4988-bdfb-f52371da5eea-150-499D93EAA2342689C02CAD238600CEE5EEBEF0E0");
                    script.setAttribute("data-oc-language", "fi_FI");
                    script.setAttribute("data-main", "//occhat.elisa.fi/chatserver/Scripts/oc-chat");
                    script.src = '//occhat.elisa.fi/chatserver/Scripts/require.js'
                    document.head.appendChild(script);
                    break;

                case "testi.opintopolku.fi":
                    var script = document.createElement('script');
                    script.setAttribute("id", "oc-start-up");
                    script.setAttribute("data-oc-service", "0e8747a9-e9c5-4988-bdfb-f52371da5eea-151-A36EBDE7950F4DDAA05B4AF486AC30C0C04B2E75");
                    script.setAttribute("data-oc-language", "fi_FI");
                    script.setAttribute("data-main", "//occhat.elisa.fi/chatserver/Scripts/oc-chat");
                    script.src = '//occhat.elisa.fi/chatserver/Scripts/require.js'
                    document.head.appendChild(script);
                    break;
            }
<?php
    }
?>
        })
    </script>

	<?php wp_footer(); ?>

</body>
</html>