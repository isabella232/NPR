<?php
class nprproduct{
	
	public $post;
	public $ID;
	public $thumbnail;
	public $title;
	public $permalink;
	public $content;
	public function __construct($the_post){
		$this->post = $the_post;
		$this->ID = $this->post->ID;
		$this->title = get_the_title($this->ID); 
	 	$this->permalink = get_permalink($this->ID);
		$this->thumbnail = get_the_post_thumbnail($this -> ID, "default-product-thumb");
		$this->content = $this->post->post_content;
	}
	
	public function makeView(){
		$buynow = CartHelper::getBuyNow($this->ID);
				echo "
			<div class='item-wrapper default-item-wrapper' id='$this->ID'>
			<div id='$this->ID' class='sm-item item default-item'>
			<div class='inner'>
			<div class='content'>
	    		<h4><a href='$this->permalink'>$this->title</a></h4>
	    	</div> 
			";
		echo $this->thumbnail;
		echo "<p>$this->content</p>";
		echo $buynow;
		echo "</div>
			  </div>
		      </div>";
		$this->getScripts();
	}
	public function getScripts(){
		?>
		<!-- script added by nprproductclass.php -->
		<script>
			/**
			 * Click listeners
			 */
			$("#<?php echo $this->ID;?> a").click(function(event){
				event.preventDefault();
				hideOtherProducts(<?php echo $this->ID; ?>, this);
			});
		</script>
		<?php
	}
	/**
	 * show the expanded view
	 */
	public function showLarge(){
		echo "
		<div class='default-full-view' id='$this->ID'>
		
		</div>
		";
	}
}
?>