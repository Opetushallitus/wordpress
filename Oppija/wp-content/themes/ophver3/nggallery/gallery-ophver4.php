<?php 
/**
Template Page for the gallery overview
**/
?>
<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php if (!empty ($gallery)) : ?>

<script language="javascript" type="text/javascript" >
	jQuery(function(){
		$('#gallery').galleryView({
                panel_width: 460,
                panel_height: 280,
                frame_width: 60,
                frame_height: 35,
                enable_overlays: true,
                show_filmstrip_nav: false,
                show_captions: true,
                show_infobar: false,
                filmstrip_style: 'showall',
                });
	});
</script>

<div class="ngg-galleryoverview" id="<?php echo $gallery->anchor ?>">

<?php if ($gallery->show_piclens) { ?>
	<!-- Piclense link -->
	<div class="piclenselink">
		<a class="piclenselink" href="<?php echo nextgen_esc_url($gallery->piclens_link) ?>">
			<?php _e('[View with PicLens]','nggallery'); ?>
		</a>
	</div>
<?php } ?>
	
	<!-- Thumbnails -->
    <ul id="gallery">
        <?php $i = 0; ?>
        
	<?php foreach ( $images as $image ) : ?>
        
        <?php //var_dump($image); ?>
                
        <li id="ngg-image-<?php echo $image->pid ?>" class="ngg-gallery-thumbnail-box" >

            <?php if ( !$image->hidden ) { ?>
            
            <img title="<?php echo esc_attr($image->alttext) ?>" alt="<?php echo esc_attr($image->alttext) ?>" src="<?php echo nextgen_esc_url($image->imageURL) ?>" <?php echo $image->size ?> />
            
                <?php } ?>
               
        </li>

    <?php if ( $image->hidden ) continue; ?>
    <?php if ($gallery->columns > 0): ?>
        <?php if ((($i + 1) % $gallery->columns) == 0 ): ?>
            <br style="clear: both" />
        <?php endif; ?>
    <?php endif; ?>
    <?php $i++; ?>

 	<?php endforeach; ?>
    </ul>	
	<!-- Pagination -->
 	<?php echo $pagination ?>
 	
</div>

<?php endif; ?>
