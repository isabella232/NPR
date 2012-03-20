<?php 
/**
 * file contains album and track classes
 */
class album {
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
	public function __construct($name, $ID) {
		$this -> name = $name;
		$this -> ID = $ID;
		$this -> domID = str_replace(" ", '', ($this -> name . $this -> ID));
		$this -> fulldomID = $this -> domID . "full";
		$this -> permalink = get_permalink($this->ID);
		$this->thumbnail = get_the_post_thumbnail($this -> ID, "album-thumb");
		$this->categories = wp_get_post_terms($this->ID, 'music-category', array("fields" => "all"));
		$terms = wp_get_post_terms($this->ID, 'music-artist', array("fields" => "all"));
		$this->artists = $terms;
		$tracks = array();
		
		$custom = get_post_custom($this->ID);
		$released = $custom["video-meta-release"][0]; 
	}

	public function addTrack($track) {
		$this -> tracks[] = $track;
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
			<div class='item-wrapper' id='$this->domID'>
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
		<div class='large-item' id='$this->fulldomID'>
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
				<div class='right-bar'>";
				$view.= $this->getTrackList();	 
				$view.="</div>
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
				$(holder+"").click(function(e){
					e.preventDefault();
					ajaxSubmit();
					$("#container div").each(
					function(){
						$(this).fadeOut('200',function(){
							showThis("<?php echo $this->ID ?>");
						});
						}
					);
				});
				
				  
				
								
			});
			
		 
		</script>
		<?php;
	}

	public function getTrackList() {
		$list = "";
		if ($this -> tracks != NULL)
		{
		$list.= "<table class='track-list'>";
		$list.= "<th>Track</th>"; 
		$list.= "<th>Length</th>";
		$list.= "<th>Artists</th>";
		$list.= "<th>Purchase</th>";
		$list.= "</tr>";
		
			$count = 0;
			foreach ($this->tracks as $track) {
				$artists = $track->printArtists();
				$class = "odd";
				$buynow = CartHelper::getBuyNow($track->ID);
				if($count%2==0)
					$class = "even";
				$list.= "<tr class='$class' id='$track->domID'>";
				$list.= "<td class='clickable'>$track->name</td>";
				$list.= "<td class='clickable'>$track->length</td>";
				$list.= "<td class='clickable'>$artists</td>";
				$list.= "<td class='productcol'>$buynow</td>";
				$list.= "</tr>";
				$list.= $track->getPlayerScript();
				$count++;
			}
		
		$list.= "</table>";
		$list.="
		
		";
		}
		return $list;
	}

}

class track {
	public $post;
	public $name;
	public $artists;
	public $length;
	public $album;
	public $ID;
	public $domID;
	public $player_track;
	
	public function __construct($name, $ID) {
		$this -> name = $name;
		$this -> ID = $ID;
		$this -> domID = "track".$name.$ID;
		//now strip domID
		$this->domID = str_replace(" ", '_', $this->domID);
		$this -> post = get_post($this->ID);
		$custom = get_post_custom($this->post->ID);
		$terms = wp_get_post_terms($this->ID, 'music-artist', array("fields" => "all"));
		$this->artists = $terms;
		$this->length = $custom["video-meta-length"][0];
		
	}
	public function printArtists(){
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
	/**
	 * get jQuery to handle click and set player music
	 */
	public function getPlayerScript(){
		$guid = $this->player_track->guid;
		$artists = $this->printArtists();
		$str = "";
		$str.= "
		<script>
		\$('#$this->domID .clickable').click(function(){
			updatePlayer('$this->name','$artists','$guid');
		});
		</script>
		";
		return $str;
	}
}
?>