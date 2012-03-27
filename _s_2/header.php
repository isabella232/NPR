<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package _s
 * @since _s 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script>
	/**
	 * scripts to show ajax loading gif
	 */
	// $("a").click(function(e){
		// e.preventDefault();
		// alert("clicked");
		// loadFromInto($(this), "#main");
	// });

		/**
	 * called by pages using masonry to reload ajax appended divs
	 */
	function reloadMasonry(data){
		$('#container').masonry({
		    // options  
		    itemSelector : ".item-wrapper" , 
		    singleMode: true,
		    isAnimated: true
		  });
		$("#container").append(data);//.masonry( 'appended', data , true);
		
		$.when($("#container").masonry('reload')).then($(".item-wrapper").animate( {opacity:1}, 500 ));
		addToBodyClass("black"); 
	}	 	 
	 
	function addToBodyClass(theClass){
		$("body").removeClass(); //remove all
		$("body").addClass(theClass);
	}
	jQuery(document).ready(function(){ 
		jQuery("body").append("<div id='ajaxloaderdiv'></div>");
		
	});
	function showAjaxLoader(){
		jQuery("#ajaxloaderdiv").show();
	}
	function hideAjaxLoader(){
		jQuery("#ajaxloaderdiv").hide();
	}
	jQuery("#ajaxloaderdiv").click(function(){
		hideAjaxLoader();
	});	
	
	function removeCurrentClass(){
	var elems = new Array("#artist-tax li a","#genre-tax li a","#category-tax li a");
	var i = 0;
	for(i = 0 ; i < elems.length ; i ++ )
	jQuery(elems[i]).each(function(){
		jQuery(this).removeClass("current");
	});
	}
</script>
<?php wp_head(); ?>
</head> 

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">
		<hgroup>
			<span class="site-title">
				<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				
			</span>
			<span class="site-description">
				<?php bloginfo( 'description' ); ?>
			</span>
		</hgroup>
		<!-- nav clicks capture by script below -->
		<nav role="navigation" class="site-navigation main-navigation">
			<h1 class="assistive-text"><?php _e( 'Menu', '_s' ); ?></h1>
			<div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', '_s' ); ?>"><?php _e( 'Skip to content', '_s' ); ?></a></div>

			<?php wp_nav_menu( ); ?>
		</nav>
<script>
	/**
	 * scripts to capture menu clicks
	 */
	<?php 
	/**
	 * GLOBAL SWITCH FOR AJAX PAGE LOADING
	 */
		global $ajax_is_on; 
		$ajax_is_on = true; 
	?>
	/**
	 * function for loading the href off an element into the passed target div
	 */
	function loadFromInto(fromElem, intoElem){
		var from = jQuery(fromElem); 
		var into = jQuery(intoElem);
		var href = from.attr("href");
		var title = from.text();
	  	showAjaxLoader();
	  	location.hash = title;
	  	
	  	into.load(href, null, hideAjaxLoader); 
	}
	
	jQuery(".menu-item a").click(function(event) {
	  event.preventDefault();
	  stripCurrentClasses();
	  jQuery(this).parent().addClass("current_page_item ");
	  loadFromInto(jQuery(this),"#main");
	});
	
	function stripCurrentClasses(){
		jQuery(".menu li").each(function(){
			jQuery(this).removeClass("current_page_item");
			jQuery(this).removeClass("current-menu-item ");
		});
	}

</script>
	</header><!-- #masthead .site-header -->

	<div id="main">