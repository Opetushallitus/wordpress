<?php
get_header(); ?>

<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); ?>

<div class="grid_16">
    <h1><a href="<?php bloginfo('wpurl'); ?>">Virkailijan työpöytä</a></h1>
</div>

<?php get_sidebar( 'left' ); ?>

	<div id="content" class="grid_8">
            <div id="entries">

                <div class="header-announcements">
                    <h2>Tiedotteet</h2>
                </div>

                <div id="tabs">
                    <div id="small-info">Valitse näytettävä sisältökategoria:</div>
                        <?php wp_nav_menu(array('theme_location' => 'tab_menu')); ?>
                </div>
                
                
                <?php
                        global $oUserAccessManager;

                        if (isset($oUserAccessManager)) {
                                $iUserId = $user_ID;
                                $oUamAccessHandler = $oUserAccessManager->getAccessHandler();
                                $aUserGroupsForUser = $oUamAccessHandler->getUserGroupsForObject('user', $iUserId);

                                $groups = array();

                                foreach($aUserGroupsForUser as $userGroup){
                                    $groups[] = $userGroup->getId();
                                }
                        }
                ?>
                
                
                <div id="entries-content">
                                       
                    <?php while (have_posts()) : the_post(); ?>
                    
                    <?php if(!(is_plugin_active('user-access-manager/user-access-manager.php')) || in_array(uamIsAccess(true), $groups) || uamIsAdmin()) { ?>
                    
                    <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
                                <div class="entry-title">
                                    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                                </div>
                                 <small><?php the_time('j.n.Y') ?>
                                 Kategoria: 
                                     <?php
                                        $category = get_the_category();
                                        echo $category[0]->cat_name;
                                     ?>
                                 </small>

                                <!--
                                <div class="entry">
                                    <strong><?php the_excerpt('Read the rest of this entry &raquo;'); ?></strong>
                                </div>-->
                                <!-- end entry -->
                          
                        </div><!-- end post -->
                     <?php } ?>     
                        

                <?php 
     endwhile;
 ?>
                    
                </div>
                
            </div><!-- end entries -->
	</div><!-- end content -->

<?php get_sidebar('right'); ?>

<?php get_footer(); ?>
