<?php

// Options Page Functions

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
	<div class="wrap">
		<div id="icon-themes" class="icon32"><br /></div>
		<h2>Theme Options</h2>
	
		<form method="POST" action="">
			<input type="hidden" name="update_themeoptions" value="true" />

			
			<h4>Company Features</h4>
			<p><input type="text" name="label" id="label" size="32" value="<?php echo get_option('mytheme_ad1image'); ?>"/> Label Name</p>
	
			<p><input type="submit" name="search" value="Update Options" class="button" /></p>
		</form>
	
	</div>
	<?php
}

function themeoptions_update()
{
	// this is where validation would go
	update_option('mytheme_label_name', $_POST['label']);
	
}

add_action('admin_menu', 'themeoptions_admin_menu');

?>
