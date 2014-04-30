<?php 
/**
Template Page for the gallery carousel

Follow variables are useable :

	$gallery     : Contain all about the gallery
	$images      : Contain all images, path, title
	$pagination  : Contain the pagination content
	$current     : Contain the selected image
	$prev/$next  : Contain link to the next/previous gallery page
	

 You can check the content when you insert the tag <?php var_dump($variable) ?>
 If you would like to show the timestamp of the image ,you can use <?php echo $exif['created_timestamp'] ?>
**/
?>
<?php if (!defined ('ABSPATH')) die ('No direct access allowed'); ?><?php if (!empty ($gallery)) : ?>

<div class="ngg-galleryoverview">

	<div class="pic">
            <img title="<?php echo esc_attr($current->alttext) ?>" alt="<?php echo esc_attr($current->alttext) ?>" 
                 src="<?php echo nextgen_esc_url($current->url); ?>" />
            <?php //var_dump($images); ?>
        </div>
	
	<ul class="ngg-gallery-list">
	
		<!-- PREV LINK -->	
		<?php if ($prev) : ?>
		<li class="ngg-prev">
			<a class="prev" href="<?php echo nextgen_esc_url($prev) ?>">&#9668;</a>
		</li>
		<?php endif; ?>
		
		<!-- Thumbnail list -->
		<?php foreach ( $images as $image ) : ?>
		<?php if ( $image->hidden ) continue; ?> 
		
                <?php $current_language = ICL_LANGUAGE_CODE;
                $st = nextgen_esc_url($image->pidlink);
                $image_link = str_replace('oph-story', '/'.$current_language.'/oph-story', $st); //var_dump($test) ?>
                
		<li id="ngg-image-<?php echo esc_attr($image->pid) ?>" class="ngg-thumbnail-list <?php if ($image->pid == $current->pid) echo 'selected' ?>" >
			<a href="<?php echo nextgen_esc_url($image_link) ?>" title="<?php echo esc_attr($image->description) ?>" >
				<img title="<?php echo esc_attr($image->alttext) ?>" alt="<?php echo esc_attr($image->alttext) ?>" src="<?php echo nextgen_esc_url($image->thumbnailURL) ?>" <?php echo $image->size ?> />
			</a>
      
		</li>

	 	<?php endforeach; ?>
	 	
	 	<!-- NEXT LINK -->
		<?php if ($next) : ?>
		<li class="ngg-next">
			<a class="next" href="<?php echo nextgen_esc_url($next) ?>">&#9658;</a>
		</li>
		<?php endif; ?>
	 	
	</ul>
    <div style="clear: both;"><?php echo $current->description; ?></div>
 	
</div>

<?php endif; ?>
