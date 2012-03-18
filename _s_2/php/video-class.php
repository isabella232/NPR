<?php

class Video{
	
	private $post;
	private $embed;
	private $track;
	private $artists;
	private $album;
	private $release;
	private $categories;
	private $category;
	private $identity;
	 
	public function __construct($post){
		$this->post = $post;
		$this->identity = "video-".$post->ID;
			$custom = get_post_custom($post->ID);
			$this->categories = get_terms("wpsc-product-category");
			$this->category = $this->categories->name;
			$this->embed = $custom["video-meta-embed"][0];
			$this->artists = $custom["video-meta-artists"][0];
			$this->track = $custom["video-meta-track"][0];
			$this->album = $custom["video-meta-album"][0];
			$this->release = $custom["video-meta-release"][0];
	}	
	
	public function getCategories(){
		$terms = get_terms("wpsc-product-category");
		 $count = count($terms);
		 if ( $count > 0 ){
		     echo "<ul>";
		     foreach ( $terms as $term ) {
		       echo "<li>" . $term->name . "</li>";
		        
		     }
		     echo "</ul>";
		 }
	}
	public function getCategory(){
		return $this->categories[0]->name;
	}
	public function getVideo(){

			$width = 415;
		return "<iframe width='$width' height='315' src='$this->embed?wmode=opaque' frameborder='0' allowfullscreen></iframe>";
	}
	
	public function getTable(){
		return "
		<table id='video-info-table'>
		<tr>
			<td>Track:</td>
			<td>$this->track</td>
		</tr>
		<tr>
			<td>Artist:</td>
			<td>$this->artists</td>
		</tr>
		<tr>
			<td>Album:</td>
			<td>$this->album</td>
		</tr>
		<tr>
			<td>Release:</td>
			<td>$this->release</td>
		</tr>
		</table>
		";
	}
	
	public function displaySingle(){
		$title = get_the_title($this->post->ID);
		$video = $this->getVideo(500);		
		echo "
		<h1>$title</h1>
		<div class='single-video'>
		$video
		</div>";
		echo $this->getTable();
		echo "Categories: ".$this->getCategories();
		echo the_content($this->post->ID);
	}
	
	public function displaySmall(){
		echo $this->embed;
		echo $this->getTable();
	}
	
	private function getThumbnail(){
		echo "  <div class='video-thumb-overlay'>";
		the_post_thumbnail( 'video-thumb' ); 
		echo "	<div class='play-symbol'></div>
				</div>";
	}
	
	private function getDisplayTitle(){
		?>
		<h4><a href="<?php
				if (get_post_meta($post -> ID, "url", true))
					echo get_post_meta($post -> ID, "url", true);
				else
					the_permalink();
 					?>"><?php the_title();?></a></h4>
 		<?php
	}
	
	
	
	public function makeVideoBox(){
	?>
	
	<div class="item-wrapper">
	<div class="sm-item item sm-video" id="<?php echo $this->identity;?>"> 
		<div class="inner">
			<?php
			//print_r($this->post->wpsc-product-category);
			?>
			<div class='video-frame'>
				<?php echo $this->getVideo();?>
				<div class='video-minimize'>
					<div class='video-minimize-inner'></div>
				</div>
			</div>
			<?php 
			$this -> getThumbnail();
			?>
			<div class="content">
				<?php //echo $this -> getTable();
				echo "<p class='title-wrap'>";
				the_title();

				echo "</p>"; 
				//echo UI::makePlayButton('#')?>
			</div>
		</div>
	</div>
	</div>
	<script type="text/javascript">
		 $(document).ready(function () {
		 	
		 function minimizeVideos(){
		 	$(".sm-video").animate({width: 232}, 'slow'); //change size
			$(".sm-video .video-frame").hide('slow'); //show video frame
			$(".sm-video .video-thumb-overlay").show(); //hide image
		 }
		  $("#<?php echo $this->identity; ?> .video-thumb-overlay").click(function() {
		  	  //reset all others first
		  	  minimizeVideos();
			  //now change individual
			  $("#<?php echo $this->identity; ?>").animate({width: 417}, 'slow'); //change size
			  $("#<?php echo $this->identity; ?> .video-frame").show(); //show video frame
			  $("#<?php echo $this->identity; ?> .video-minimize").show(); //show video minimize button
			  $(".video-minimize").click(function(){
			  	minimizeVideos();
			  });
			  $("#<?php echo $this->identity; ?> .video-thumb-overlay").hide(); //hide image
			  
			});
		});
	</script>
	<?php }
}

?>