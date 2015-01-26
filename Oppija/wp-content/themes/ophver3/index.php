<?php get_header(); ?>
	
<?php if (is_archive()) :  ?>

<?php get_template_part('loop-post-archive'); ?>

<?php else : ?>

<?php get_template_part('loop-page'); ?>

<?php endif; ?>
	
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>