<?php get_header(); ?>

    <nav class="sidenav">
        <ul>
            <li>Teema 1</li>
            <li>Teema 2</li>
            <li>Teema 3</li>
            <li>Teema 4</li>
            <li>Teema 5</li>
            <li>Teema 6</li>
            <li>Teema 7</li>
            <li>Teema 8</li>
            <li>Teema 9</li>
            <li>Teema 10</li>
            <li>Teema 11</li>
            <li>Teema 12</li>
            <li>Teema 13</li>
        </ul>
    </nav>
	
    <div class="stories">
	<!-- section -->
		<h1><?php _e( 'Tutustu tarinoihin', 'html5blank' ); ?></h1>
	
		<?php get_template_part('loop-oph-story'); ?>
		
		<?php //get_template_part('pagination'); ?>

	<!-- /section -->
    </div>
	

<?php get_footer(); ?>