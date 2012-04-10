<?php
class UI{
	public static function makePlayButton($link){
		return "
			<div class='action-button'>
				<div class='action-button-inner'>
				<span>WATCH</span>
				</div>
			</div>
		";
	}
	
	public static function getHeaderReplacement(){
		echo "
		
		";
	}
	
	public static function ajaxheader(){
		
		$ajax_is_on = $_COOKIE['ajax_is_on'];
		if($ajax_is_on!="true" or Prefs::usingAjaxPreferred()==false) 
			get_header(); 
	}
	public static function ajaxfooter(){ 
		$ajax_is_on = $_COOKIE['ajax_is_on'];
		if($ajax_is_on!="true" or Prefs::usingAjaxPreferred()==false) 
			get_footer(); 
	} 
	
	public static function setBlack(){
		?>
		<script>
			$(document).ready(function{
				addToBodyClass("black"); 
			});
		</script>
		<?php
	}
	public static function setWhite(){
		?>
		<script>
			$(document).ready(function{
				addToBodyClass("white"); 
			});
		</script>
		<?php
	}
}

?>