<?php
/*
Plugin Name: WPSC jPlayer Plugin
Plugin URI: http://getshopped.org/extend/premium-upgrades/
Description: A Wp e-Commerce Music Player
Version: 3.0
Author: Instinct
Author URI: http://getshopped.org/extend/premium-upgrades/
*/

define('WPSC_MP3_MODULE_USES_HOOKS', true);
define('WPSC_JPLAYER_VERSION', '2.0.1');
define('WPSC_JPLAYER_FOLDER', dirname(plugin_basename(__FILE__))); // define plugin location

$jplayer_siteurl = get_option('siteurl');
if(is_ssl()) {
	$jplayer_siteurl = str_replace("http://", "https://", $jplayer_siteurl);
}
$jplayer_plugin_url = WP_CONTENT_URL;
if(is_ssl()) {
	$plugin_url_parts = parse_url($jplayer_plugin_url);
	$site_url_parts = parse_url($jplayer_siteurl);
	if(stristr($plugin_url_parts['host'], $site_url_parts['host']) && stristr($site_url_parts['host'], $plugin_url_parts['host'])) {
		$jplayer_plugin_url = str_replace("http://", "https://", $jplayer_plugin_url);
	}
}
define('WPSC_JPLAYER_URL', $jplayer_plugin_url.'/plugins/'.WPSC_JPLAYER_FOLDER);



function wpsc_jplayer_setup_widgets(){
	require_once("widgets/jPlayer_widget.php");
}

if(is_admin()){

	//check if newer version is available
	function jplayerplugin_version( $plugin ) {
		if( strpos( WPSC_JPLAYER_VERSION.'/'.__FILE__,$plugin ) !== false ) {
			$checkfile = "http://getshopped.org/wp-content/uploads/wpsc/updates/jplayerplugin.chk";
			$vcheck = wp_remote_fopen($checkfile);
			if( $vcheck ) {
				$version = WPSC_JPLAYER_VERSION;
				$status = explode('@', $vcheck);
				$theVersion = 0;
				$theMessage = '';
				if(isset($status[1]))
					$theVersion = $status[1];
				if(isset($status[3]))
					$theMessage = $status[3];
				if( (version_compare(strval($theVersion), strval($version), '>') == 1) ) {
					echo '
        <td colspan="5" class="plugin-update" style="line-height:1.2em; font-size:11px; padding:1px;">
          <div style="color:#000; font-weight:bold; margin:4px; padding:6px 5px; background-color:#fffbe4; border-color:#dfdfdf; border-width:1px; border-style:solid; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">'.__("There is a new version of jPlayerPlugin for WP e-Commerce available.", "jplayerplugin").' <a href="'.$theMessage.'" target="_blank">View version '.$theVersion.' details</a>.</div	>
        </td>';
				} else {
					return;
				}
			}
		}
	}
	add_action( 'after_plugin_row', 'jplayerplugin_version' );
	//Add jPlayer admin page
	//require_once("admin-pages/display-admin.page.php");

	/**
	 * Description Function to add admin Pages make sure not to use these generic funciton names, as it will cause a conflict,
	 * you should change this function name and the wpsc_display_admin_pages function name
	 * @access public - admin pages
	 *
	 * @param page hooks array
	 * @param base page
	 * @return new page hooks
	 */
	function wpsc_add_jplayer_admin_pages($page_hooks, $base_page) {
		$page_hooks[] =  add_submenu_page($base_page, __('- jPlayer','wpsc'),  __('- jPlayer','jplayerplugin'), 'administrator', 'wpsc-jplayer-module-admin', 'wpsc_display_jplayer_admin_pages');
		return $page_hooks;
	}
	add_filter('wpsc_additional_pages', 'wpsc_add_jplayer_admin_pages',10, 2);
}

function jptest(){
	echo "success";
}

/**
 * Add the jPlayer to the Products Page via hooks
 * @access public
 *
 * @since 1.0
 * @param product id (int) and product data (object)
 * @return jPlayer XHTML
 */
function wpsc_add_mp3_preview() {
	global $wpdb;
	$args = array(
		'post_status' => 'inherit',
		'post_type' => 'wpsc-preview-file'
	);
	$file_data = get_posts($args);

	if(function_exists('listen_button') && !empty($file_data)){
		echo listen_button($file_data[0]->post_name, $file_data[0]->ID);
	}
}
/**
 * Creates XHTML for jPlayer
 * @access public
 *
 * @since 1.0
 * @param mp3 (deprecated) and file_id (int)
 * @return listen_button (string) XHTML Markup
 */
function jPlayer_button($mp3,$file_id = null) {
	global $wpdb;
	if(empty($_SESSION['jplayer_plugin_ids']))
		$_SESSION['jplayer_plugin_ids'] = array();
	if(is_numeric($file_id)) {
		if(!in_array($file_id,(array)$_SESSION['jplayer_plugin_ids'])){
			$_SESSION['jplayer_plugin_ids'][] = $file_id;
		}

	}
	$file_data = get_post($file_id);
	if(!empty($file_data)) {
		// create the file paths using the database DB
		$real_mp3_path= WPSC_PREVIEW_DIR."".$file_data->post_title;
		$mp3_path= WPSC_PREVIEW_URL."".$file_data->post_title."";
	}

	if(file_exists($real_mp3_path)) {
		$listen_button ='
		<div style="clear:both;position:relative;margin:20px 0;">
		<div id="jquery_jplayer_'.$file_id.'" class="jquery_jplayer"></div>
		<div class="jp-single-player">
			<div class="jp-interface">
				<ul class="jp-controls">
					<li id="jplayer_play_'.$file_id.'" class="jp-play">play</li>
					<li id="jplayer_pause_'.$file_id.'" class="jp-pause">pause</li>
					<li id="jplayer_volume_min_'.$file_id.'" class="jp-volume-min">min volume</li>

					<li id="jplayer_volume_max_'.$file_id.'" class="jp-volume-max">max volume</li>
				</ul>
				<div class="jp-progress">
					<div id="jplayer_load_bar_'.$file_id.'" class="jp-load-bar">
						<div id="jplayer_play_bar_'.$file_id.'" class="jp-play-bar"></div>
					</div>
				</div>
				<div id="jplayer_volume_bar_'.$file_id.'" class="jp-volume-bar">

					<div id="jplayer_volume_bar_value_'.$file_id.'" class="jp-volume-bar-value"></div>
				</div>
				<div id="jplayer_play_time_'.$file_id.'" class="jp-play-time"></div>
				<div id="jplayer_total_time_'.$file_id.'" class="jp-total-time"></div>
			</div>
		</div>

		<div id="demo_status"></div>
		<div id="demo_info"></div>
		</div>
		<div style="clear:both"></div>';
	}
	return $listen_button;
}

/**
 * Add the jPlayer to the Products Page via hooks
 * @access public
 *
 * @since 1.0
 * @param product id (int) and product data (object)
 * @return jPlayer XHTML
 */
function wpsc_jPlayer_inline_mp3_preview() {
	global $wpdb;
	$args = array(
		'post_status' => 'any',
		'post_type' => 'wpsc-preview-file'
		//,		'post_parent' => $product_id
	);
	$file_data = get_posts($args);
	if(function_exists('jPlayer_button')){
		if($file_data != null) {
			echo jPlayer_button($file_data[0]->post_name, $file_data[0]->ID);
		}
	}
}
remove_action('wpsc_product_before_description', 'wpsc_legacy_add_mp3_preview');
$jPlayer_options = get_option('jplayer_style_options');
if('widget' ==$jPlayer_options['jplayer_type']){
	add_action('widgets_init', 'wpsc_jplayer_setup_widgets');
	add_action('wpsc_product_before_description', 'wpsc_add_mp3_preview', 10, 2);
	add_action('wp_footer','jplayer_widget_player');
}else{
	add_action('wpsc_product_before_description', 'wpsc_jPlayer_inline_mp3_preview', 10, 2);
	add_action('wp_print_footer_scripts','jplayer_plugin_dynamic_inline_script');
}


/**
 * Include jPlayer JS file
 * @access public
 *
 * @since 1.0
 */
function wpsc_music_player_js_include(){
	wp_enqueue_script('jplayer-mp3-player', WPSC_JPLAYER_URL."/jquery.jplayer.min.js",array('jquery'), '0.1');
}

/**
 * Include jPlayer CSS file
 * @access public
 *
 * @since 1.0
 */
function wpsc_music_player_css_include(){
	if(file_exists(get_stylesheet_directory().'/jplayerplugin.css')){
		wp_enqueue_style('jplayer-style' , get_stylesheet_directory_uri().'/jplayerplugin.css');
		return;
	}

	// if no jplayer css file in selected theme folder use styles from jplayer plugin
	$player_style = get_option('jplayer_style_options');
	if(!empty($player_style)){
		$style = $player_style['jplayer_style'];
	}else{
		$style = 'blue';
	}
	$jPlayer_options = get_option('jplayer_style_options');
	if('widget' ==$jPlayer_options['jplayer_type'])
		wp_enqueue_style('jplayer-style', WPSC_JPLAYER_URL."/css/jplayer-widget-".$style.".css");
	else
		wp_enqueue_style('jplayer-style', WPSC_JPLAYER_URL."/css/jplayer-".$style.".css");

}
add_action('init','wpsc_music_player_css_include');
add_action('init','wpsc_music_player_js_include');

/**
 * Creates XHTML for jPlayer
 * @access public
 *
 * @since 1.0
 * @param mp3 (deprecated) and file_id (int)
 * @return listen_button (string) XHTML Markup
 */
function listen_button($mp3,$file_id = null) {
	global $wpdb, $wp_query;

	$file_data = get_post($file_id);

	$track = get_post($file_data->post_parent);
	$listen_button = '';
	if (strlen(strstr($file_data->post_title,'.mp3'))>0)
		$listen_button = '<a class="wpsc_play_btn track_'.$file_id.'">Play</a>';
	return $listen_button;
}

function jplayer_widget_player(){
	global $wpsc_query;
	if(( isset($wpsc_query->query_vars['wpsc_product_category']) || (isset($wpsc_query->query_vars['post_type']) && $wpsc_query->query_vars['post_type'] == 'wpsc-product'  )) && $wpsc_query->post_count > 0){

		$jPlayer_query = query_posts('post_type=wpsc-preview-file&nopaging=true&post_status=any&order=ASC');


?>
		<script type='text/javascript'>
		jQuery(document).ready(function($){

		var playItem = 0;

		var myPlayList = [
		<?php
		$playlist = '';
		foreach($jPlayer_query as $tracks){
			$mp3_path= WPSC_PREVIEW_URL."".addslashes($tracks->post_title)."";
			if(strpos($mp3_path,'.mp3') !== false){

				$playlist .= '{ id:"track_'.$tracks->ID.'",
								name:"'. addslashes($tracks->post_title) . '",
								mp3:"' . $mp3_path . '"},';
			}
		}
		if(!empty($playlist)){
			$playlist = substr($playlist, 0, (strlen($playlist)-1));
			echo $playlist;
		}
?>

			];

		// Local copy of jQuery selectors, for performance.
		var jpPlayTime = $("#jplayer_play_time");
		var jpTotalTime = $("#jplayer_total_time");
		var jpStatus = $("#demo_status"); // For displaying information about jPlayer's status in the demo page

		$("#jquery_jplayer").jPlayer({
			ready: function() {
				displayPlayList();
				playListInit(false); // Parameter is a boolean for autoplay.
			},
			swfPath: '<?php echo WPSC_JPLAYER_URL."/js"; ?>'
		})
		.jPlayer("onProgressChange", function(loadPercent, playedPercentRelative, playedPercentAbsolute, playedTime, totalTime) {
			jpPlayTime.text($.jPlayer.convertTime(playedTime));
			jpTotalTime.text($.jPlayer.convertTime(totalTime));
		})
		.jPlayer("onSoundComplete", function() {
			playListNext();
		});

		$("#jplayer_previous").click( function() {
			playListPrev();
			$(this).blur();
			return false;
		});

		$("#jplayer_next").click( function() {
			playListNext();
			$(this).blur();
			return false;
		});

		function displayPlayList() {
			$("#jplayer_playlist ul").empty();
			$(".wpsc_play_btn").live('click', function(){
				var new_id = $(this).attr('class');
				new_id = new_id.split(' ');
				for ( i = 0 ; i < myPlayList.length; i++){
					if (myPlayList[i].id == new_id[1]){
						playListChange( i );
					}
				}

			});
		}

		function playListInit(autoplay) {
			if(autoplay) {
				playListChange( playItem );
			} else {
				playListConfig( playItem );
			}
		}

		function playListConfig( index ) {
			$("#jplayer_playlist_item_"+playItem).removeClass("jplayer_playlist_current").parent().removeClass("jplayer_playlist_current");
			$("#jplayer_playlist_item_"+index).addClass("jplayer_playlist_current").parent().addClass("jplayer_playlist_current");
			playItem = index;
			$("#jquery_jplayer").jPlayer("setFile", myPlayList[playItem].mp3, myPlayList[playItem].ogg);
		}

		function playListChange( index ) {
			playListConfig( index );
			$("#jquery_jplayer").jPlayer("play");
		}

		function playListNext() {
			var index = (playItem+1 < myPlayList.length) ? playItem+1 : 0;
			playListChange( index );
		}

		function playListPrev() {
			var index = (playItem-1 >= 0) ? playItem-1 : myPlayList.length-1;
			playListChange( index );
		}
		});
		</script>
<?php
	}
}
/**
 * JS to be added to the page
 * @access public
 *
 * @since 1.0
 *
 * @return null, displays JS on page
 */
function jplayer_plugin_dynamic_inline_script(){
	global $wpsc_query,$wpdb;
?>
	<script type='text/javascript'>
	jQuery(document).ready(function($){
		// Local copy of jQuery selectors, for performance.
		//array of players and int variables for uniquely identifying each player
		var jPlayer_array = $(".jquery_jplayer");
		var playerID;
		var fileID;
		var jpPlayTime = new Array();
		var jpTotalTime= new Array();
		var song = new Array();
		<?php
	if(isset($_SESSION['jplayer_plugin_ids'])){
		foreach((array)$_SESSION['jplayer_plugin_ids'] as $fileid){
			$file_data = get_post($fileid);
			$mp3_path= WPSC_PREVIEW_URL."".addslashes($file_data->post_title)."";
			if(!empty($file_data)){
?>
			jpPlayTime_<?php echo $fileid; ?> = $("#jplayer_play_time_<?php echo $fileid; ?>");
			jpTotalTime_<?php echo $fileid; ?> = $("#jplayer_total_time_<?php echo $fileid; ?>");
			$('#jquery_jplayer_<?php echo $fileid; ?>').jPlayer({
					ready: function () {
						this.setFile('<?php echo $mp3_path; ?>');
					},
					swfPath: '<?php echo WP_CONTENT_URL."/plugins/jPlayerPlugin/js"; ?>',
					volume: 50,
					customCssIds: true
				})
				.jPlayer( "cssId", "play", "jplayer_play_<?php echo $fileid; ?>" )
				.jPlayer( "cssId", "pause", "jplayer_pause_<?php echo $fileid; ?>" )
				.jPlayer( "cssId", "loadBar", "jplayer_load_bar_<?php echo $fileid; ?>")
				.jPlayer( "cssId", "playBar", "jplayer_play_bar_<?php echo $fileid; ?>")
				.jPlayer( "cssId", "volumeMin", "jplayer_volume_min_<?php echo $fileid; ?>")
				.jPlayer( "cssId", "volumeMax", "jplayer_volume_max_<?php echo $fileid; ?>")
				.jPlayer( "cssId", "volumeBar", "jplayer_volume_bar_<?php echo $fileid; ?>")
				.jPlayer( "cssId", "volumeBarValue", "jplayer_volume_bar_value_<?php echo $fileid; ?>")
				.jPlayer("onProgressChange", function(loadPercent, playedPercentRelative, playedPercentAbsolute, playedTime, totalTime) {
					jpPlayTime_<?php echo $fileid; ?>.text($.jPlayer.convertTime(playedTime));
					jpTotalTime_<?php echo $fileid; ?>.text($.jPlayer.convertTime(totalTime));
				})
				.jPlayer("onSoundComplete", function() {
					//COMMENT THIS LINE OUT IF YOU WANT THE TRACK TO LOOP
					//this.element.jPlayer("play");
				});
			<?php
			}
		}
	}
?>

	});
	</script>
	<?php
	unset($_SESSION['jplayer_plugin_ids']);
}

//added for backwards compatibility
function wpsc_media_player(){

}
//added for backwards compatibility
if(!function_exists("make_mp3_preview")) {
	function make_mp3_preview(){
	}

}

?>