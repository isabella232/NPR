<?php
class Prefs{
	public static function getStyles(){
		$options = _s_get_theme_options();
		$bg =  $options['sample_select_options'];
		$images = array('black-linen.png','smooth-wall.png','wood.png','wood-dark.png','vichy.png',"white-carbon.png","denim-tile.jpg");
?>
		<!-- style added by prefs-class in /php folder -->
		<style type='text/css'>
		body{
			background: transparent url("<?php bloginfo('template_url');?>/res/bg/<?php echo $images[$bg]; ?>") repeat center center;
		}
		</style>
		<!-- end style -->
<?php	
	}
		
	public static function getLogo(){
		$options = _s_get_theme_options();
		$logo =  $options['logo_path'];
			if($logo==NULL){
						bloginfo( 'name' );
					} 
					else {
						echo "<img src='$logo' id='logo'>";
					}
	}
	
	public static function usingAjaxPreferred(){
		$options = _s_get_theme_options();
		$checked =  $options['sample_checkbox'];
		if($checked == "on")
			return true;
		else
			return false;
	}
}

?>