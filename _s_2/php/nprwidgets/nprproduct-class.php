<?php
class nprproduct{
	
	public $post;
	public $ID;
	public $thumbnail;
	
	public function __construct($the_post){
		$this->post = $the_post;
		$this->ID = $this->post->ID;
		$this->thumbnail = get_the_post_thumbnail($this -> ID, "album-thumb");
	}
	
	public function makeView(){
				echo "
			<div class='item-wrapper' id='$this->domID'>
			<div id='$this->ID' class='sm-item item album-item'>
			<div class='inner'>
			<div class='content'>
	    		<h4><a href='$this->permalink'>$this->name</a></h4>
	    	</div> 
			";
		echo $this->thumbnail;
		
		echo "</div>
			  </div>
		      </div>";
	}
}
?>  