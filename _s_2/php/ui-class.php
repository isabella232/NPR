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
		global $ajax_is_on;
		if($ajax_is_on) 
		get_header(); 
	}
	public static function ajaxfooter(){
		global $ajax_is_on;
		if($ajax_is_on)  
		get_footer(); 
	} 
}

?>