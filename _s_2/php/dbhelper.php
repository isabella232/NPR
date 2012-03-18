<?php
function jbug($var) {
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}

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
	
	public function getFullView(){
		$image = str_replace("\"","\'",$this->thumbnail);
		$categories = $this->getCategories();
		$artists = $this->getArtists();
		$trackCount = count($this->tracks);
		$view = "
		<div class='large-item' id='$this->fulldomID'>
			<div class='large-item-inner'>
				<div class='left-bar'>
					<h2>$this->name</h2>
					<div class='album-art'>
					$image
					</div>
					<table class='full-album-meta'>
			
					<tr> 
					 <td class='header'>Artists</td>
					 <td>$artists</td>
					</tr>
				    <tr> 
					 <td class='header'>Released</td>
					 <td>$this->released</td>
					</tr>
					<tr>
					 <td class='header'>Genres</td>
					 <td>$categories</td>
					</tr>
					<tr>
					 <td class='header'>Tracks</td>
					 <td>$trackCount</td>
					</tr>
					</table>
					
				</div>
				<div class='right-bar'>";
				$view.= $this->getTrackList();	
				$view.="</div>
			</div>			
		</div>
		"; 	
		echo  trim( preg_replace( '/\s+/', ' ', $view ) );
	}
	
	public function getScripts(){
		?>
		<script>
		
		
			$(document).ready(function(){
				var holder = "#<?php echo $this->domID; ?>";
				$(holder+" a").click(function(e){
					e.preventDefault();
					$("#container div").each(
					function(){
						$(this).fadeOut('200',function(){
							showThis("<?php $this->getFullView(); ?>");
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
		$list.= "</tr>";
		
			$count = 0;
			foreach ($this->tracks as $track) {
				$artists = $track->printArtists();
				$class = "odd";
				if($count%2==0)
					$class = "even";
				$list.= "<tr class='$class'>";
				$list.= "<td>$track->name</td>";
				$list.= "<td>$track->length</td>";
				$list.= "<td>$artists</td>";
				$list.= "</tr>";
				$count++;
			}
		
		$list.= "</table>";
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
	
	public function __construct($name, $ID) {
		$this -> name = $name;
		$this -> ID = $ID;
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
}

class DBHelper {
	
	/**
 	* query albums and return album array 
 	*/
	public static function getAlbums() {
		$tracks = array();
		$albums = array();
		//query albums
		$args = array('post_type' => 'wpsc-product', 'numberposts' => 2000, 'wpsc_product_category' => 'album');
		$the_query = new WP_Query($args);
		//iterate albums
		while ($the_query -> have_posts()) :
			$the_query -> the_post();
			$product_cat = $the_query -> query_vars['wpsc_product_category'];
			$current = new album(get_the_title(), get_the_ID());
			// create album with that name
			$albums[] = $current;
			// add to albums
		endwhile;

		//query tracks
		$args = array('post_type' => 'wpsc-product', 'numberposts' => 2000, 'wpsc_product_category' => 'track');
		$the_query = new WP_Query($args);
		//iterate tracks
		while ($the_query -> have_posts()) :
			$the_query -> the_post();
			$product_cat = $the_query -> query_vars['wpsc_product_category'];
			$current = new track(get_the_title(), get_the_ID());
			// create track with that name
			$meta = get_post_meta($current -> ID, 'album-meta-name');
			$current -> album = $meta[0];
			$tracks[] = $current;
			// add to track array
		endwhile;
		//iterate tracks and add them to the albums array
		foreach ($tracks as $track) {
			foreach ($albums as $album) {
				if ($album -> name == $track -> album)
					$album -> addTrack($track);
			}
		}

		return $albums;
		
	}
	/**
 	* query albums and return album array 
 	*/
	public static function getAlbumsOnly() {
		global $post;
		$TEMP = $post;
		$tracks = array();
		$albums = array();
		//query albums
		$args = array('post_type' => 'wpsc-product', 'numberposts' => 2000, 'wpsc_product_category' => 'album');
		$the_query = new WP_Query($args);
		//iterate albums
		while ($the_query -> have_posts()) :
			$the_query -> the_post();
			$product_cat = $the_query -> query_vars['wpsc_product_category'];
			$current = new album(get_the_title(), get_the_ID());
			// create album with that name
			$albums[] = $current;
			// add to albums
		endwhile;
		$post = $TEMP;
		return $albums;
	}

}
?>