<?php
class video {
	public $tracks;
	public $name;
	public $ID;
	public $domID;
	public $fulldomID;
	public $permalink;
	public $thumbnail;
	public $categories;
	public $artists;
	public $released; 
	public $embedUrl;
	public function __construct($name, $ID) {
		$this -> name = $name;
		$this -> ID = $ID;
		$this -> domID = str_replace(" ", '', ($this -> name ."_". $this -> ID));
		$this -> domID = str_replace(":", '', $this -> domID );
		
		  
		$this -> fulldomID = $this -> domID . "full";
		$this -> permalink = get_permalink($this->ID);
		$this->thumbnail = get_the_post_thumbnail($this -> ID, "video-thumb");
		$this->categories = wp_get_post_terms($this->ID, 'music-category', array("fields" => "all"));
		$terms = wp_get_post_terms($this->ID, 'music-artist', array("fields" => "all"));
		$this->artists = $terms;
		
		$custom = get_post_custom($this->ID);
		$this->embedUrl = $custom["video-meta-embed"][0];
		$this->released = $custom["video-meta-release"][0]; 
	}
	
	public function getVideo(){
		echo "<div class='close' onclick='restoreCurrentOpen();'></div><iframe width='560' height='315' src='http://www.youtube.com/embed/$this->embedUrl?wmode=opaque' frameborder='0' allowfullscreen></iframe>";

	}
	
	public function getCategories(){
		$str = "";
		$count =0;
		foreach($this->categories as $category){
			if($count>0)
			$str.= ", ";
			$str.= "<a href='#'>".$category->name."</a>";
			$count++;			
		}
		return $str;
	}
	
	public function getArtists(){
		$str = '';
		$count = 0;
		foreach($this->artists as $artist){
			if($count>0)
				$str.=", ";
			$str.="$artist->name";
			$count++;
		} 
		return $str;
	}

	public function makeView() {
		echo "
			<div class='item-wrapper video-item-wrapper' id='$this->domID'>
			<div class='sm-item item album-item'>
			<div class='inner'>
			<div class='content'>
	    		<h4><a href='$this->permalink'>$this->name</a></h4>
	    	</div> 
			";
		echo $this->thumbnail;
		
		echo "</div>
			  </div>
		      </div>";
	    $this->getScripts();
	
	}
	
	public function getFullView($echo = true){
		$image = str_replace("\"","\'",$this->thumbnail);
		$categories = $this->getCategories();
		$artists = $this->getArtists();
		$trackCount = count($this->tracks);
		$buynow = CartHelper::getBuyNow($this->ID);
		
		$view = "
		<div class='large-item large-video-item' id='$this->fulldomID'>
			<div class='close'></div>
			<div class='large-item-inner'>
				<div class='left-bar'>
					<h2>$this->name</h2>
					<div class='album-art'>
					$image
					</div>
					<table class='full-album-meta'>
			
					<tr> 
					 <td class='header'>Artists</td>
					 <td class='clickable'>$artists</td>
					</tr>
				    <tr> 
					 <td class='header'>Released</td>
					 <td class='clickable'>$this->released</td>
					</tr>
					<tr>
					 <td class='header'>Genres</td>
					 <td class='clickable'>$categories</td>
					</tr>
					<tr>
					 <td class='header'>Tracks</td>
					 <td class='clickable'>$trackCount</td>
					</tr>
					</table>
					$buynow
				</div>
				
			</div>			
		</div>
		"; 	
		if($echo)
			echo  trim( preg_replace( '/\s+/', ' ', $view ) );
		else
			return  trim( preg_replace( '/\s+/', ' ', $view ) );
	}
	
	public function getScripts(){
		?>
		<script>
		
		
			$(document).ready(function(){
				var holder = "#<?php echo $this->domID; ?>";
				$(holder+" img").click(function(e){
					e.preventDefault();
					ajaxSubmit();
					// $("#container div").each(
					// function(){
						// //$(this).fadeOut('200',function(){
// 							
						// });
						// } 
					// );
					showThis("#<?php echo $this->domID; ?>");
				});
				
				  
				
								
			});
			
		 
		</script>
		<?php;
	}


}
?>