<?php get_header( get_bloginfo('language') ); ?>

	<!-- section -->
	<section role="main">
	
		<!-- article -->
		<article id="post-error404" class="home">
		<?php if (ICL_LANGUAGE_CODE == 'sv') : ?>
			<h1 class="home">Den sida du söker hittas inte</h1>
			<p>
				Länken är föråldrad eller felaktig. Gå till <a href="<?php echo get_home_url(); ?>">framsidan av Studieinfo</a> för att göra en ny sökning.
			</p>	
		<?php else : ?>
			<h1 class="home">Sivua ei löydy</h1>
			<p>
				Linkki oli virheellinen tai vanhentunut. Siirry <a href="<?php echo get_home_url(); ?>">Opintopolun etusivulle</a> etsimään tietoa.
			</p>
		<?php endif ?>
		</article>
		<!-- /article -->
		
	</section>
	<!-- /section -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>