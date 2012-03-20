<?php

/** 
 * _s functions and definitions
 *
 * @package _s
 * @since _s 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since _s 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( '_s_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since _s 1.0
 */
function _s_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	//require( get_template_directory() . '/inc/theme-options/theme-options.php' );

	/**
	 * WordPress.com-specific functions and definitions
	 */
	//require( get_template_directory() . '/inc/wpcom.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on _s, use a find and replace
	 * to change '_s' to the name of your theme in all the template files
	 */
	load_theme_textdomain( '_s', get_template_directory() . '/languages' );
 
	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', '_s' ),
	) );

	/**
	 * Add support for the Aside and Gallery Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', ) );
}
endif; // _s_setup
add_action( 'after_setup_theme', '_s_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since _s 1.0
 */
function _s_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', '_s' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', '_s_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function _s_scripts() {
	global $post;

	wp_enqueue_style( 'style', get_stylesheet_uri() );

	wp_enqueue_script( 'jquery' );

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', 'jquery', '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image( $post->ID ) ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', '_s_scripts' );

/**
 * PROJECT BEGINS
 */ 

//debugging functions
function jbug($var) {
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}
function jalert($var) {
	echo "<script>";
	echo" alert('";
	echo $var;
	echo "');";
	echo "</script>";
}
add_theme_support( 'post-thumbnails' );
add_image_size( 'homepage-thumb', 220, 180, true );
add_image_size( 'article-thumb', 400, 180, false );
add_image_size( 'album-thumb', 200, 200, false );
add_image_size( 'video-thumb', 230, 200, true ); 
add_image_size( 'related-thumb', 180, 120, true ); 
add_image_size( 'full', 700, 420, true ); 
include "php/video-class.php";
include "php/ui-class.php";
include "php/player-class.php";
include "php/album-class.php";
include "php/article-class.php";
include "php/options.php";
include "php/dbhelper.php";
include "php/jplayer/jplayer.php";
include "php/nprwidgets/npr_shopping_cart_widget.php";

/**
 * add taxonomy to wpsc post type 
 */
register_taxonomy("music-artist", array( 'post', 'wpsc-product' ), array("hierarchical" => true, "label" => "Artists", "singular_label" => "Category", "rewrite" => true));   
register_taxonomy("music-category", array( 'post', 'wpsc-product' ), array("hierarchical" => true, "label" => "Music Genres", "singular_label" => "Category", "rewrite" => true));   

/**
 * add custom fields
 */
//add_action("admin_init", "portfolio_meta_box");     

/**
 * save data
 */
add_action('save_post', 'npr_save_product_meta');   

function npr_save_product_meta(){  
    global $post;    
  		
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){  
        return $post_id;  
    }else{
    	update_post_meta($post->ID, "video-meta-release", $_POST["video-meta-release"]);    
        update_post_meta($post->ID, "video-meta-embed", $_POST["video-meta-embed"]);  
        update_post_meta($post->ID, "video-meta-track", $_POST["video-meta-track"]);  
        update_post_meta($post->ID, "video-meta-artists", $_POST["video-meta-artists"]);  
        update_post_meta($post->ID, "video-meta-length", $_POST["video-meta-length"]);  
        update_post_meta($post->ID, "video-meta-album", $_POST["video-meta-album"]);  

    }  
}
add_action('save_post', 'npr_save_album_meta');   

function npr_save_album_meta(){  
    global $post;    
  		
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){  
        return $post_id;  
    }else{  
        update_post_meta($post->ID, "album-meta-name", $_POST["album-meta-name"]);  
    }  
}
add_action('save_post', 'npr_save_post_meta');   

function npr_save_post_meta(){  
    global $post;    
  		
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){  
        return $post_id;  
    }else{  
        update_post_meta($post->ID, "post-meta-picturecaption", $_POST["post-meta-picturecaption"]);  
    }  
}

/** 
 * Add metaboxes
 */
add_action("admin_init", "npr_album_box");     
  
function npr_album_box(){
	global $post; 
	//get the category
	$terms = get_terms("wpsc_product_category");
    add_meta_box("album-meta", "Album Information", "npr_album_meta_options", "wpsc-product", "side", "high");  
}    
  
 
add_action("admin_init", "npr_meta_box");     
  
function npr_meta_box(){
	global $post;
	//get the category
	$terms = get_terms("wpsc_product_category");
    add_meta_box("video-meta", "Track Information", "npr_video_meta_options", "wpsc-product", "side", "high");  
}  
  
add_action("admin_init", "npr_post_meta_box");     
  
function npr_post_meta_box(){
    add_meta_box("featuredimage-meta", "Featured Image Settings", "npr_post_meta_options", "post", "side", "high");  
} 

function npr_post_meta_options(){  
        global $post;  
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;  
        $custom = get_post_custom($post->ID);  
       
?> 
<table> 
	<tr>
    	<td><label>Picture Caption:</label></td><td><input name="post-meta-picturecaption" value="<?php echo ($custom["post-meta-picturecaption"][0]); ?>" /></td>
    </tr>
</table>
<?php   
    }    
  
function npr_album_meta_options(){  
        global $post;  
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;  
        $custom = get_post_custom($post->ID);  
       
?>  
<table>
	<tr>
    	<?php /**<td><label>Album Name:</label></td><td><input name="album-meta-name" value="<?php echo ($custom["album-meta-name"][0]); ?>" /></td> */?>
    	<td>
    		<select name="album-meta-name">
    			<?php
    			$albums = DBHelper::getAlbumsOnly();
				foreach($albums as $album){
					echo "<option value='$album->name'";
					if($album->name == $custom["album-meta-name"][0])
						echo "selected='selected'";
					echo ">$album->name</option>";					
				}
    			?>
    		</select>
    	</td>
    </tr>
    <tr>
    	<td>
    		<p>Add Album products of the category 'Album' and attach individual tracks to them above</p>
    	</td>
    </tr>
</table>
<?php   
    } 

function npr_video_meta_options(){  
        global $post;  
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;  
        $custom = get_post_custom($post->ID);  
       
?>  
<table>
	<tr>
    	<td><label>Embed URL:</label></td><td><input name="video-meta-embed" value="<?php echo ($custom["video-meta-embed"][0]); ?>" /></td>
    </tr>
	<tr>
    	<td><label>Track:</label></td><td><input name="video-meta-track" value="<?php echo $custom["video-meta-track"][0]; ?>" /></td>
    </tr>
	<tr>
    	<td><label>Length:</label></td><td><input name="video-meta-length" value="<?php echo $custom["video-meta-length"][0]; ?>" /></td>
    </tr>
    <tr> 
    	<td><label>Artists:</label></td><td><input name="video-meta-artists" value="<?php echo $custom["video-meta-artists"][0]; ?>" /></td> 
    </tr>
    <tr>
    	<td><label>Album:</label></td><td><input name="video-meta-album" value="<?php echo $custom["video-meta-album"][0]; ?>" /></td>
    </tr>
    <tr> 
    	<td><label>Release:</label></td><td><input name="video-meta-release" value="<?php $custom["video-meta-release"][0]; ?>" /></td>
    </tr>
</table>
<?php   
    } 


/**
 * Menu 
 */
//add_action( 'init', 'register_navmenus' );
 
function register_navmenus(){
	
	register_nav_menus( array(
		'npr' => __( 'npr', '_s' ),
	) );
	if ( !is_nav_menu( 'npr' )) {
        $menu_id = wp_create_nav_menu( 'npr' );
        $menu = array( 'menu-item-type' => 'custom', 'menu-item-url' => get_home_url('/'),'menu-item-title' => 'JACK' );
        wp_update_nav_menu_item( $menu_id, 0, $menu );
    }
	
}

/**
 * excerpt size
 */
function wp_trim_all_excerpt($text) {
// Creates an excerpt if needed; and shortens the manual excerpt as well
global $post;
  $raw_excerpt = $text;
  if ( '' == $text ) {
    $text = get_the_content('');
    $text = strip_shortcodes( $text );
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
  }
 
$text = strip_tags($text);
$excerpt_length = apply_filters('excerpt_length', 10);
$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
$text = wp_trim_words( $text, $excerpt_length, $excerpt_more ); //since wp3.3
return apply_filters('wp_trim_excerpt', $text, $raw_excerpt); //since wp3.3
}
 
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wp_trim_all_excerpt');


/**
 * AJAX
 */
wp_enqueue_script('jquery');
/**
 * returns the full view for an album
 */
function fetchAlbum(){
	
	$id = $_POST['id'];
	if($id!=''){
	$album = DBHelper::getAlbum($id);
	$view = $album->getFullView(false);
	echo str_replace("\'", "\"", $view);
	}
die();
}
add_action('wp_ajax_fetchAlbum', 'fetchAlbum');
add_action('wp_ajax_nopriv_fetchAlbum', 'fetchAlbum');

function displayAlbums(){
	$genre = $_POST['genre'];
	$artist = $_POST['artist'];
	$albums = DBHelper::getAlbums($genre, $artist);
	foreach($albums as $album){
		$album->makeView();
	}
die();
}
add_action('wp_ajax_displayAlbums', 'displayAlbums');
add_action('wp_ajax_nopriv_displayAlbums', 'displayAlbums');

function displayArticles(){
	global $post;
	$category = $_POST['category'];
	$artist = $_POST['artist'];
	$articles = DBHelper::getArticles($category, $artist);
	foreach($articles as $post){
		article::display();
	}
	echo article::getLinkScript($category,$artist);
	
die();
}
add_action('wp_ajax_displayArticles', 'displayArticles');
add_action('wp_ajax_nopriv_displayArticles', 'displayArticles');

function fetchCartCount(){
	
	echo wpsc_cart_item_count();
	
die();
}
add_action('wp_ajax_fetchCartCount', 'fetchCartCount');
add_action('wp_ajax_nopriv_fetchCartCount', 'fetchCartCount');
