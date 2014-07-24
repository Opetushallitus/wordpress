<?php
/*
 *  Author: Todd Motto | @toddmotto
 *  URL: html5blank.com | @html5blank
 *  Custom functions, support, custom post types and more.
 */

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here

define("ICL_DONT_LOAD_NAVIGATION_CSS", true);

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

if (!isset($content_width))
{
    $content_width = 980;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnailoph
    add_image_size('medium', 570, '', true); // Medium Thumbnail
    add_image_size('oph-small', 100, 160, true);
    add_image_size('oph-medium', 160, 100, true);
    add_image_size('oph-side-column', 220, 140, true);
    add_image_size('oph-mid-column', 460, 280, true);
    add_image_size('oph-max', 700, 280, true);
    //add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');



    // Add Support for Custom Backgrounds - Uncomment below if you're going to use
    /*add_theme_support('custom-background', array(
	'default-color' => 'FFF',
	'default-image' => get_template_directory_uri() . '/img/bg.jpg'
    ));*/

    // Add Support for Custom Header - Uncomment below if you're going to use
    /*add_theme_support('custom-header', array(
	'default-image'			=> get_template_directory_uri() . '/img/headers/default.jpg',
	'header-text'			=> false,
	'default-text-color'		=> '000',
	'width'				=> 1000,
	'height'			=> 198,
	'random-default'		=> false,
	'wp-head-callback'		=> $wphead_cb,
	'admin-head-callback'		=> $adminhead_cb,
	'admin-preview-callback'	=> $adminpreview_cb
    ));*/

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('html5blank', get_template_directory() . '/languages');

	add_post_type_support('page', 'excerpt');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

// HTML5 Blank navigation
function html5blank_nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

// Load HTML5 Blank scripts (header.php)
function html5blank_header_scripts()
{
    if (!is_admin()) {

    	//wp_deregister_script('jquery'); // Deregister WordPress jQuery
    	//wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', array(), '1.9.1'); // Google CDN jQuery
    	//wp_enqueue_script('jquery'); // Enqueue it!

    	//wp_register_script('conditionizr', 'http://cdnjs.cloudflare.com/ajax/libs/conditionizr.js/2.2.0/conditionizr.min.js', array(), '2.2.0'); // Conditionizr
        //wp_enqueue_script('conditionizr'); // Enqueue it!

        //wp_register_script('modernizr', 'http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js', array(), '2.6.2'); // Modernizr
        //wp_enqueue_script('modernizr'); // Enqueue it!

        //wp_register_script('html5blankscripts', get_template_directory_uri() . '/js/scripts.js', array(), '1.0.0'); // Custom scripts
        //wp_enqueue_script('html5blankscripts'); // Enqueue it!
    }
}

// Load HTML5 Blank conditional scripts
function html5blank_conditional_scripts()
{
    if (is_page('pagenamehere')) {
        wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), '1.0.0'); // Conditional script(s)
        wp_enqueue_script('scriptname'); // Enqueue it!
    }
}

// Load HTML5 Blank styles
function html5blank_styles()
{
    //wp_register_style('normalize', get_template_directory_uri() . '/normalize.css', array(), '1.0', 'all');
    //wp_enqueue_style('normalize'); // Enqueue it!

    wp_register_style('html5blank', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('html5blank'); // Enqueue it!
}

// Register HTML5 Blank Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'html5blank'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'html5blank'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'html5blank') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Frontpage', 'html5blank'),
        'description' => __('Frontpage widgets', 'html5blank'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Frontpage Lower area', 'html5blank'),
        'description' => __('Frontpage lower part widgets', 'html5blank'),
        'id' => 'widget-area-2',
        'before_widget' => '', //<div id="%1$s" class="%2$s">',
        'after_widget' => '',//</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 43
    register_sidebar(array(
        'name' => __('Right sidebar', 'html5blank'),
        'description' => __('Sidebar widgets on content pages', 'html5blank'),
        'id' => 'widget-area-3',
        'before_widget' => '', //<div id="%1$s" class="%2$s">',
        'after_widget' => '',//</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('Read article', 'html5blank') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function html5blankgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function html5blankcomments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'html5blank_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'html5blank_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'html5blank_styles'); // Add Theme Stylesheet
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
add_action('init', 'create_post_type_html5'); // Add our HTML5 Blank Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
//add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
//add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
//remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo'); // You can place [html5_shortcode_demo] in Pages, Posts now.
add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2'); // Place [html5_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/

//Create 1 Custom Post -  Story
function create_post_type_html5()
{
 	register_taxonomy(
		'story-theme',
		'oph-story',
		array(
			'label' => __( 'Story Theme' ),
			'rewrite' => array( 'slug' => 'theme', 'with_front' => false),
			'hierarchical' => true,
                        'show_admin_column' => true,
		)
	);


    //register_taxonomy_for_object_type('story-theme', 'oph-story'); // Register Taxonomies for Category
    //register_taxonomy_for_object_type('post_tag', 'oph-story');
    register_post_type('oph-story', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Tarinat', 'html5blank'), // Rename these to suit
            'singular_name' => __('Story', 'html5blank'),
            'add_new' => __('Add New', 'html5blank'),
            'add_new_item' => __('Add New Story', 'html5blank'),
            'edit' => __('Edit', 'html5blank'),
            'edit_item' => __('Edit Story', 'html5blank'),
            'new_item' => __('New Story', 'html5blank'),
            'view' => __('View Story', 'html5blank'),
            'view_item' => __('View Story', 'html5blank'),
            'search_items' => __('Search Stories', 'html5blank'),
            'not_found' => __('No Stories found', 'html5blank'),
            'not_found_in_trash' => __('No Stories found in Trash', 'html5blank')
        ),
        'public' => true,
        'exclude_from_search' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'rewrite' => array( 'slug' => 'story', 'with_front' => false ),
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'page-attributes',
            'thumbnail'
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
         'taxonomies' => array(
            'post_tag',
            'oph-koulutus'
        ) // Add Category and Post Tags support

    ));


    //register_taxonomy_for_object_type('story-theme', 'oph-feature'); // Register Taxonomies for Category
    //register_taxonomy_for_object_type('post_tag', 'oph-feature');
    register_post_type('oph-feature', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Features', 'html5blank'), // Rename these to suit
            'singular_name' => __('Feature', 'html5blank'),
            'add_new' => __('Add New', 'html5blank'),
            'add_new_item' => __('Add New Feature', 'html5blank'),
            'edit' => __('Edit', 'html5blank'),
            'edit_item' => __('Edit Feature', 'html5blank'),
            'new_item' => __('New Feature', 'html5blank'),
            'view' => __('View Feature', 'html5blank'),
            'view_item' => __('View Feature', 'html5blank'),
            'search_items' => __('Search Features', 'html5blank'),
            'not_found' => __('No Features found', 'html5blank'),
            'not_found_in_trash' => __('No Features found in Trash', 'html5blank')
        ),
        'public' => true,
        'exclude_from_search' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'page-attributes',
            //'excerpt',
            //'',
            'thumbnail'
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            /*'post_tag',
            'story-theme',*/
        ) // Add Category and Post Tags support
    ));

    // MUUALLA AIHEESTA
    //register_taxonomy_for_object_type('story-theme', 'oph-feature'); // Register Taxonomies for Category
    //register_taxonomy_for_object_type('post_tag', 'oph-feature');
    register_post_type('oph-related', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Related', 'html5blank'), // Rename these to suit
            'singular_name' => __('Related', 'html5blank'),
            'add_new' => __('Add New', 'html5blank'),
            'add_new_item' => __('Add New Related', 'html5blank'),
            'edit' => __('Edit', 'html5blank'),
            'edit_item' => __('Edit Related', 'html5blank'),
            'new_item' => __('New Related', 'html5blank'),
            'view' => __('View Related', 'html5blank'),
            'view_item' => __('View Related', 'html5blank'),
            'search_items' => __('Search Related articles', 'html5blank'),
            'not_found' => __('No Related articles found', 'html5blank'),
            'not_found_in_trash' => __('No Related articles found in Trash', 'html5blank')
        ),
        'public' => true,
        'exclude_from_search' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            //'excerpt',
            'post_order',
            'thumbnail'
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            //'post_tag',
            'oph-koulutus',
            'oph-koulutusaste',
            'oph-ammattiluokitus',
        )
    ));

	// register_taxonomy_for_object_type('story-theme', 'oph-feature'); // Register Taxonomies for Category
    // register_taxonomy_for_object_type('post_tag', 'oph-feature');
    register_post_type('oph-notification', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Tiedoksi', 'html5blank'), // Rename these to suit
            'singular_name' => __('Notification', 'html5blank'),
            'add_new' => __('Add New', 'html5blank'),
            'add_new_item' => __('Add New Notification', 'html5blank'),
            'edit' => __('Edit', 'html5blank'),
            'edit_item' => __('Edit Notification', 'html5blank'),
            'new_item' => __('New Related', 'html5blank'),
            'view' => __('View Related', 'html5blank'),
            'view_item' => __('View Related', 'html5blank'),
            'search_items' => __('Search Related articles', 'html5blank'),
            'not_found' => __('No Related articles found', 'html5blank'),
            'not_found_in_trash' => __('No Related articles found in Trash', 'html5blank')
        ),
        'public' => true,
        'exclude_from_search' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'post_order',
            'thumbnail'
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            //'post_tag',
            'oph-koulutus',
            'oph-koulutusaste',
            'oph-ammattiluokitus',
        )
    ));

    register_post_type('sidebar-content', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Sivupalsta', 'html5blank'), // Rename these to suit
            'singular_name' => __('Notification', 'html5blank'),
            'add_new' => __('Add New', 'html5blank'),
            'add_new_item' => __('Add New Notification', 'html5blank'),
            'edit' => __('Edit', 'html5blank'),
            'edit_item' => __('Edit Notification', 'html5blank'),
            'new_item' => __('New Related', 'html5blank'),
            'view' => __('View Related', 'html5blank'),
            'view_item' => __('View Related', 'html5blank'),
            'search_items' => __('Search Related articles', 'html5blank'),
            'not_found' => __('No Related articles found', 'html5blank'),
            'not_found_in_trash' => __('No Related articles found in Trash', 'html5blank')
        ),
        'public' => true,
        'exclude_from_search' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'post_order',
            'thumbnail'
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true // Allows export in Tools > Export
    ));


}


/*------------------------------------*\
	ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function html5_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function html5_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}



function my_theme_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'init', 'my_theme_add_editor_styles' );

function add_tags_to_pages(){
	// Add to the admin_init hook of your theme functions.php file
	register_taxonomy_for_object_type('post_tag', 'page');
	//register_taxonomy_for_object_type('category', 'page');
}
add_action( 'init', 'add_tags_to_pages' );

function oph_get_subpages(){
		global $post;

		$parent = array_reverse(get_post_ancestors($post->ID));
		$first_parent = get_page($parent[0]);

		$args = array(
			'sort_order' => 'ASC',
			'sort_column' => 'menu_order',
			'hierarchical' => 1,
			'exclude' => '',
			'include' => '',
			'meta_key' => '',
			'meta_value' => '',
			'authors' => '',
			'child_of' => $parent[0],
			'parent' => -1,
			'exclude_tree' => '',
			'number' => '',
			'offset' => 0,
			'post_type' => 'page',
			'post_status' => 'publish'
			);

		$pages = get_pages($args);

/*		if ($pages) return $pages;
		else {
			if ( $post->post_parent != 0) $args['child_of'] = $post->post_parent;
				$pages = get_pages($args);
		}
*/

		//create array of pages and sub-pages

		$sorted = array();
		foreach ( $pages as $p)
		{
			$sorted[$p->post_id][] = $p;
		}



		//error_log (print_r($pages, true));

		return $pages;

}

function oph_nostot() {
		global $post;

		$args = array(
		    'post_type' => 'oph-feature',
			'posts_per_page'	=> 21,
			'post_status'    => 'publish',
                        'orderby' => 'menu_order',
			'order' => 'ASC',
		);

		$pages = get_posts($args);

		return $pages;
}


/**
* ADMIN
* Add Sub-navi title meta box to the main column on the Post and Page edit screens.
*
*/
function oph_add_nav_title_box() {

    $screens = array( 'post', 'page' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'oph_nav_title',
            __( 'Navigation Title', 'html5blank' ),
            'oph_nav_title_inner_custom_box',
            $screen
        );
    }
}
add_action( 'add_meta_boxes', 'oph_add_nav_title_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function oph_nav_title_inner_custom_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'oph_nav_title_inner_custom_box', 'oph_nav_title_inner_custom_box_nonce' );

  /*
   * Use get_post_meta() to retrieve an existing value
   * from the database and use the value for the form.
   */
  $value = get_post_meta( $post->ID, '_oph_nav_title', true );

  echo '<label for="oph_nav_title">';
       _e( "Title for Navigation elements", 'html5blank' );
  echo '</label> ';
  echo '<input type="text" id="oph_nav_title" name="oph_nav_title" value="' . esc_attr( $value ) . '" size="25" />';

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function oph_nav_title_save_postdata( $post_id ) {

  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['oph_nav_title_inner_custom_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['oph_nav_title_inner_custom_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'oph_nav_title_inner_custom_box' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return $post_id;

  // Check the user's permissions.
  if ( 'page' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;

  } else {

    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  // Sanitize user input.
  $mydata = sanitize_text_field( $_POST['oph_nav_title'] );

  // Update the meta field in the database.
  update_post_meta( $post_id, '_oph_nav_title', $mydata );
}

add_action( 'save_post', 'oph_nav_title_save_postdata' );

/*
 * Used in nav walker ?
 */
function show_short_title( $title, $id)
{

	$short_title = get_post_meta( $id, "_oph_nav_title", true );
	if ( isset($short_title) && $short_title != "")   $title = $short_title;
	return $title;
}

function is_qa()
{
	$productionsites = array(
		'opintopolku.fi',
		'www.opintopolku.fi',
		'studieinfo.fi',
		'www.studieinfo.fi',
	);

	$devsites = array(
		'localhost',
	);

	if ( in_array($_SERVER['HTTP_HOST'], $devsites ) ) echo '<span style="font-size: 20px; line-height: 50px;margin-left: 20px; color: red;">l</span>';
	elseif ( !in_array($_SERVER['HTTP_HOST'], $productionsites ) ) echo '<span style="font-size: 20px; line-height: 50px;margin-left: 20px; color: red;">QA</span>';

}

function oph_taxonomies()
{
    register_taxonomy(
        'oph-koulutus',
    	array( 'page','post', 'oph-related', 'oph-story' ),
    	array(
    		'label' => __( 'Koulutus' ),
    		'rewrite' => array( 'slug' => 'koulutus' ),
    		'hierarchical' => true
    	)
	);

    register_taxonomy(
        'oph-koulutustyyppi',
    	array( 'page', 'post', 'oph-related'),
    	array(
    		'label' => __( 'Koulutustyyppi' ),
    		'rewrite' => array( 'slug' => 'koulutustyyppi' ),
    		'hierarchical' => true
    	)
	);

	register_taxonomy(
	    'oph-huomautukset',
		array( 'page','post','oph-notification'),
		array(
			'label' => __( 'Tiedote kategoriat' ),
			'rewrite' => array( 'slug' => 'oph-huom-kat' ),
			'hierarchical' => true,
            'show_admin_column' => true

		)
	);

    register_taxonomy(
	    'oph-sidebar',
		array( 'sidebar-content'),
		array(
			'label' => __( 'Sivupalsta kategoriat' ),
			'rewrite' => array( 'slug' => 'oph-sidebar-kat' ),
			'hierarchical' => true,
            'show_admin_column' => true
		)
	);
}
add_action( 'init', 'oph_taxonomies' );

/* Left-side sub navigation */
function oph_subnavi()
{
global $post;

        // Fetch pages that are excluded from the top navigation
        $page_excludes = get_pages( array(
                            'meta_key' => '_top_nav_excluded',
                            'hierarchical' => 0,
                            'post_type' => 'page',
                            'post_status' => 'publish'
                         ));

        $exclude_ids = wp_list_pluck($page_excludes, 'ID');

        $parent = array_reverse(get_post_ancestors($post->ID));

        if(empty($parent)) {
            $top_parent = $post->ID;
        } else {
            $top_parent = get_post($parent[0])->ID;
        }

        $ids = get_pages( array(
                'child_of' => $top_parent,
                'post_status' => 'publish',
                'exclude' => $exclude_ids
               ) );

        $ids = wp_list_pluck($ids, 'ID');
        $ids[] = $top_parent;
        $ids = implode(',', $ids);

        add_filter('the_title', 'show_short_title', 10, 2);
        wp_list_pages( array(
            'link_before' => '<span class="w80">',
            'link_after' => '</span>',
            'title_li' => '',
            'sort_column'  => 'menu_order, post_title',
            'depth' => 0,
            'include' => $ids,
            'post_type'    => 'page',
            'post_status'  => 'publish'
            ) );
        remove_filter('the_title', 'show_short_title');
}

/* Get related content based on taxonomies */
function oph_related_taxonomy_query($qtaxonomies, $post_type = 'page')
{
        global $post;


          // Get terms from all the taxonomies
        $taxquery = array('relation' => 'OR');


        foreach ($qtaxonomies as $taxonomy ) {

            $taxs = wp_get_post_terms( $post->ID, $taxonomy);

            //var_dump($taxs);

            if(!empty($taxs)) {
                $tax_ids = array();

                foreach( $taxs as $individual_tax ) {
                    $tax_ids[] = $individual_tax->term_id;
                    //var_dump($individual_tax->term_id);
                }

                $taxquery[] = array(
                    'taxonomy' => $taxonomy,
                    'terms' => $tax_ids,
                    //'operator'  => 'IN'
                );
            } else {
                $taxquery[] = array(
                    'taxonomy' => ' ',
                    'terms' => ' ',
                    //'operator'  => 'IN'
                    );
            }
        }

        $args = array(
                'post_type' => $post_type,
                'post_status'   => 'publish',
                'tax_query' => $taxquery,
                'post__not_in'          => array( $post->ID ),
                'posts_per_page'        => 3,
                'ignore_sticky_posts'   => 1
            );

        //var_dump($args['tax_query']);
        //error_log( print_r ($args, true));

        $my_query = new wp_query( $args );

        //var_dump($my_query);

        return $my_query;
}

/**
 * Register our sidebars and widgetized areas
 */
function oph_widgets_init() {

    register_sidebar( array(
        'name' => 'Footer Widget 1',
        'id' => 'footer_widget_1',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<strong>',
        'after_title' => '</strong>'
    ) );

    register_sidebar( array(
        'name' => 'Footer Widget 2',
        'id' => 'footer_widget_2',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<strong>',
        'after_title' => '</strong>'
    ) );

    register_sidebar( array(
        'name' => 'Footer Widget 3',
        'id' => 'footer_widget_3',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<strong>',
        'after_title' => '</strong>'
    ) );
}
add_action( 'widgets_init', 'oph_widgets_init' );



/*
 * Disable default image linking
 */


function wpb_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );

	if ($image_set !== 'none') {
		update_option('image_default_link_type', 'none');
	}
}
add_action('admin_init', 'wpb_imagelink_setup', 10);

/*
 * Enable CORS for JSON Api
 */

function enable_cors($result) {

    header("Access-Control-Allow-Origin: *");

}

add_action( 'json_api-nav-json_nav', 'enable_cors' );
add_action( 'json_api-core-get_search_results', 'enable_cors' );

/*
 *  Add custom image sizes to Media settings drop down
 */

add_filter( 'image_size_names_choose', 'oph_custom_sizes' );

function oph_custom_sizes( $sizes ) {
    $array = array_merge( $sizes, array(
        'oph-small' => __('Pieni kuva'),
        'oph-medium' => __('Medium kuva'),
        'oph-side-column' => __('Vasemman ja oikean palstan kuva'),
        'oph-mid-column' => __('Keskipalstan kuva'),
        'oph-max' => __('Maxikuva')
    ) );

    // Remove default image sizes (large, medium, thumbnail)
    unset($array['large']);
    unset($array['medium']);
    unset($array['thumbnail']);

    return $array;
}

/*
 * Shortcode for arrow signs
 */

function oph_arrow_sign( $atts, $content = null ) {

    // Attributes
    extract( shortcode_atts(
        array(
            'title' => '',
            'href' => '',
        ), $atts )
    );

    if( $title != null )
    {
        return '<a title="'. $title .'" href="'. $href .'"><span class="sign"><span class="sign-inner">'. $content .'</span></span></a>';
    }
    else
    {
        return '<a href="'. $href .'"><span class="sign"><span class="sign-inner">'. $content .'</span></span></a>';
    }
}

function oph_add_button() {
   if ( current_user_can('edit_posts') && current_user_can('edit_pages') )
   {
     add_filter('mce_external_plugins', 'oph_add_plugin');
     add_filter('mce_buttons_2', 'oph_register_button');
   }
}

function oph_register_button( $buttons ) {
   array_push( $buttons, "oph-sign", "oph-highlight" );

   return $buttons;
}

function oph_add_plugin( $plugin_array ) {
   $plugin_array['oph'] = get_bloginfo('template_url').'/js/oph-tinymce-plugin.js';
   return $plugin_array;
}

// Add arrow sign button to html editor
function oph_add_quicktags() {
    if (wp_script_is('quicktags')){
?>
    <script type="text/javascript">
    QTags.addButton( 'oph_sign', 'arrow sign', '[oph-sign title="" href=""]', '[/oph-sign]', '', 'Add arrow sign', 234 );
    QTags.addButton( 'oph_highlight', 'highlight', '<span class="oph-highlight">', '</span>', '', 'Add highlight', 235 );
    </script>
<?php
    }
}

add_action('admin_print_footer_scripts', 'oph_add_quicktags');
add_action('init', 'oph_add_button');
add_shortcode('oph-sign', 'oph_arrow_sign');

// Fix for YouTube oembedding failing for https:// URLs (to be fixed in Wordpress 3.9)
function youtube_https_oembed() {
    wp_oembed_remove_provider('#https?://(www\.)?youtube\.com/watch.*#i');
    wp_oembed_remove_provider('http://youtu.be/*');
    wp_oembed_add_provider('#https?://(www\.)?youtube\.com/watch.*#i', 'http://www.youtube.com/oembed?scheme=https', true);
    wp_oembed_add_provider('#https?://youtu\.be/.*#i', 'http://www.youtube.com/oembed?scheme=https', true);

}

// Enables fluid width embeds
function oph_oembed_filter($html, $url, $attr, $post_ID) {
    $return = '<figure class="video-container">'.$html.'</figure>';
    return $return;
}

add_action('init', 'youtube_https_oembed');
add_filter('embed_oembed_html', 'oph_oembed_filter', 10, 4) ;


/*
 * Custom WPML language switcher
 */


function language_selector_custom(){
    $languages = icl_get_languages('skip_missing=0&orderby=code');
    if(!empty($languages)){
        echo '<div id="footer_language_list"><ul>';
        foreach($languages as $l){
            echo '<li>';
            if(!$l['active']) echo '<a href="'.$l['url'].'">';
            echo icl_disp_language($l['native_name'], $l['translated_name']);
            if(!$l['active']) echo '</a>';
            echo '</li>';
        }
        echo '</ul></div>';
    }
}




/*
 * Unset columns from page listing
 */

function unset_columns($columns) {

	unset(
		$columns['taxonomy-oph-huomautukset']
	);

	return $columns;
}
add_filter('manage_pages_columns', 'unset_columns');
/*
 * Breadcrumb for single oph-story
 */

function theme_bcn() {
    if(function_exists('bcn_display')) {
        global $post;

        $terms = wp_get_post_terms($post->ID, 'story-theme');
        $bcn_separator = get_option('bcn_options');
        $separator = $bcn_separator['hseparator'];
		$site_name = get_bloginfo('name');

		echo '<a title="'. theme_bcn_title($site_name) .'" href="'. get_bloginfo('url') .'" class="home">'. $site_name .'</a>';
    	echo $separator;

		if(ICL_LANGUAGE_CODE == 'sv') {
			$stories =	get_page_by_path('/stod-for-studievalet/tutustu-tarinoihin', OBJECT, 'page');
			$parent_title = get_the_title($stories->post_parent);
			$parent_link = get_permalink($stories->post_parent);
		} else {
			$stories =	get_page_by_path('/valintojen-tuki/tutustu-tarinoihin', OBJECT, 'page');
			$parent_title = get_the_title($stories->post_parent);
			$parent_link = get_permalink($stories->post_parent);
		}

        echo '<a title="' . theme_bcn_title($parent_title) .'" href="'. $parent_link .'">'. $parent_title .'</a>';
        echo $separator;
        echo '<a title="' . theme_bcn_title($stories->post_title) .'" href="'. get_permalink($stories->ID) .'">'. $stories->post_title .'</a>';
        echo $separator;

		if(is_single()) {
			echo '<a title="'. theme_bcn_title($terms[0]->name) .'" href="'. get_term_link($terms[0], 'story-theme') .'">'. $terms[0]->name .'</a>';
	        echo $separator;
	        echo $post->post_title;
		} else {
			echo single_cat_title();
		}
    }
}

function theme_bcn_title($title) {
	$bcn_title = __('Go To', 'html5blank') .' '. $title .'.';

	return $bcn_title;
}


/*
Delete custom taxonomy terms
*/

/*

function delete_custom_terms($taxonomy){
    global $wpdb;

    $taxonomy = 'oph-koulutus';

    $query = 'SELECT t.name, t.term_id
            FROM ' . $wpdb->terms . ' AS t
            INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt
            ON t.term_id = tt.term_id
            WHERE tt.taxonomy = "' . $taxonomy . '"';

    $terms = $wpdb->get_results($query);

    foreach ($terms as $term) {
        wp_delete_term( $term->term_id, $taxonomy );
    }
}

add_action('init', 'delete_custom_terms');  */

/*
 * Add char counter to excerpt field
 */
function excerpt_count_js() {
        wp_register_script('excerpt_counter', get_template_directory_uri() . '/js/excerpt_counter.js', array('jquery'));
        wp_enqueue_script('excerpt_counter');
}
add_action( 'admin_head-post.php', 'excerpt_count_js');
add_action( 'admin_head-post-new.php', 'excerpt_count_js');

/*
 * Link to Studyinfo.fi
 */

function oph_link_to_en() {

        if(isset($_SERVER['HTTPS'])) {
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else {
            $protocol = 'http';
        }

        $oph_base_url = $protocol . "://" . $_SERVER['HTTP_HOST'];

        return '<li><a href="'.$oph_base_url.'/wp2/en/">In English</a></li>';
}