<?php 
/**
Template Page for the gallery overview
**/
?>
<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php if (!empty ($gallery)) : ?>

<div class="ngg-galleryoverview" id="<?php echo $gallery->anchor ?>">

<?php if ($gallery->show_slideshow) { ?>
	<!-- Slideshow link -->
	<div class="slideshowlink">
		<a class="slideshowlink" href="<?php echo nextgen_esc_url($gallery->slideshow_link) ?>">
			<?php echo $gallery->slideshow_link_text ?>
		</a>
	</div>
<?php } ?>

<?php if ($gallery->show_piclens) { ?>
	<!-- Piclense link -->
	<div class="piclenselink">
		<a class="piclenselink" href="<?php echo nextgen_esc_url($gallery->piclens_link) ?>">
			<?php _e('[View with PicLens]','nggallery'); ?>
		</a>
	</div>
<?php } ?>
	
	<!-- Thumbnails -->
    <?php $i = 0; ?>
	<?php foreach ( $images as $image ) : ?>
	
	<div id="ngg-image-<?php echo $image->pid ?>" class="ngg-gallery-thumbnail-box" <?php echo $image->style ?> >
		<div class="ngg-gallery-thumbnail" >
			<a href="<?php echo nextgen_esc_url($image->imageURL) ?>"
               title="<?php echo esc_attr($image->description) ?>"
               data-src="<?php echo nextgen_esc_url($image->imageURL); ?>"
               data-thumbnail="<?php echo nextgen_esc_url($image->thumbnailURL); ?>"
               data-image-id="<?php echo esc_attr($image->pid); ?>"
               data-title="<?php echo esc_attr($image->alttext); ?>"
               data-description="<?php echo esc_attr($image->description); ?>"
               <?php echo $image->thumbcode ?> >
				<?php if ( !$image->hidden ) { ?>
				<img title="<?php echo esc_attr($image->alttext) ?>" alt="<?php echo esc_attr($image->alttext) ?>" src="<?php echo nextgen_esc_url($image->thumbnailURL) ?>" <?php echo $image->size ?> />
				<?php } ?>
			</a>
		</div>
	</div>

    <?php if ( $image->hidden ) continue; ?>
    <?php if ($gallery->columns > 0): ?>
        <?php if ((($i + 1) % $gallery->columns) == 0 ): ?>
            <br style="clear: both" />
        <?php endif; ?>
    <?php endif; ?>
    <?php $i++; ?>

 	<?php endforeach; ?>
 	
	<!-- Pagination -->
 	<?php echo $pagination ?>
 	
</div>

<?php endif; ?>
