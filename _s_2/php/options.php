<?php

// Options Page Functions
/**
 * Add logo upload options
 */
function wp_gear_manager_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_enqueue_script('jquery');
}

function wp_gear_manager_admin_styles() {
wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'wp_gear_manager_admin_scripts');
add_action('admin_print_styles', 'wp_gear_manager_admin_styles');

function themeoptions_admin_menu() 
{
	// here's where we add our theme options page link to the dashboard sidebar
	add_theme_page("Theme Options", "Theme Options", 'edit_themes', basename(__FILE__), 'themeoptions_page');
}

function themeoptions_page() 
{
	// here's the main function that will generate our options page
	
	if ( $_POST['update_themeoptions'] == 'true' ) { themeoptions_update(); }
	
	//if ( get_option() == 'checked'
	
	?>
	<script language="JavaScript">
jQuery(document).ready(function() {
jQuery('#upload_image_button').click(function() {
formfield = jQuery('#upload_image').attr('name');
tb_show('', 'media-upload.php?type=image&TB_iframe=true');
return false;
});

window.send_to_editor = function(html) {
imgurl = jQuery('img',html).attr('src');
jQuery('#upload_image').val(imgurl);
tb_remove();
}

});
</script>
<div class="wrap">
<h2>Theme Options</h2>
<form method="post"> 
<tr valign="top">
	<td>Upload Image</td>
	<td><label for="upload_image">
		<input id="upload_image" type="text" size="36" name="upload_image" value="<?php echo $gearimage; ?>" />
		<input id="upload_image_button" type="button" value="Upload Image" />
		<br />Enter an URL or upload an image for the banner.
		</label>
	</td>
</tr>
<p class="submit">
<input name="save" type="submit" value="Save changes" />
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>
	<?php
}

function themeoptions_update()
{
	// this is where validation would go
	update_option('mytheme_label_name', $_POST['label']);
	
}

add_action('admin_menu', 'themeoptions_admin_menu');

?>
