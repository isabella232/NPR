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

class CartHelper{
	public static function echoBuyNow($id){
		?>
		<div class="wpsc_buy_button_container">
           <?php if(wpsc_product_external_link($id) != '') : ?>
           <?php $action = wpsc_product_external_link( $id ); ?>
           <input class="wpsc_buy_button" type="submit" value="<?php echo wpsc_product_external_link_text( $id, __( 'Buy Now', 'wpsc' ) ); ?>" onclick="return gotoexternallink('<?php echo $action; ?>', '<?php echo wpsc_product_external_link_target( $id ); ?>')">
           <?php else: ?>
          <input type="submit" value="<?php _e('Add To Cart', 'wpsc'); ?>" name="Buy" class="wpsc_buy_button" id="product_<?php echo $id; ?>_submit_button"/>
           <?php endif; ?>
          <div class="wpsc_loading_animation">
           <img title="Loading" alt="Loading" src="<?php echo wpsc_loading_animation_url(); ?>" />
           <?php _e('Updating cart...', 'wpsc'); ?>
          </div><!--close wpsc_loading_animation-->
         </div><!--close wpsc_buy_button_container-->
		<?
	}
	
	public static function getBuyNow($id){
		// ob_start();
		// CartHelper::echoBuyNow($id);
		// $buynow = ob_get_contents();
		// ob_end_clean();
		$price = get_post_meta( $id, '_wpsc_price', true );
		
		 $action =  wpsc_product_external_link(wpsc_the_product_id());
         $action = htmlentities(wpsc_this_page_url(), ENT_QUOTES, 'UTF-8' );
		$buynow = '<form class="product_form" enctype="multipart/form-data" action="'.$action.'" method="post" name="product_'.$id.'" id="product_'.$id.'">
                                                 
       <!-- THIS IS THE QUANTITY OPTION MUST BE ENABLED FROM ADMIN SETTINGS -->
       
       <div class="wpsc_product_price">
                                  <p class="pricedisplay product_'.$id.'">Price: <span id="product_price_'.$id.'" class="currentprice pricedisplay">'.$price.'</span></p>
                  
         <!-- multi currency code -->
                  
                   <p class="pricedisplay">Shipping:<span class="pp_price"><span class="pricedisplay">'.$price.'</span></span></p>
                
               </div><!--close wpsc_product_price-->
       
       <input type="hidden" value="add_to_cart" name="wpsc_ajax_action">
       <input type="hidden" value="'.$id.'" name="product_id">
     
       <!-- END OF QUANTITY OPTION -->
                        <div class="wpsc_buy_button_container">
          <div class="wpsc_loading_animation">
           <img title="Loading" alt="Loading" src="http://jackmahoney.co.nz/npr/wp-content/plugins/wp-e-commerce/wpsc-theme/wpsc-images/indicator.gif">
           Updating cart…          </div><!--close wpsc_loading_animation-->
                     <input type="submit" value="Add To Cart" name="Buy" class="wpsc_buy_button" id="product_'.$id.'_submit_button">
                      </div><!--close wpsc_buy_button_container-->
                      <div class="entry-utility wpsc_product_utility">
               </div>
             </form>';
		
		return $buynow;
	}
	
	public static function getCart(){
			the_widget('NPR_WP_Widget_Shopping_Cart');
	}
}

class DBHelper {
	
	/**
	 * echos out a list of categories based on passed product type and taxonomy name
	 */
	public static function getTaxonomyList($product_type, $taxonomy_name){
		//create cat array	
		$labels = array('All');
		//query db
		$args = array('post_type' => 'wpsc-product', 'numberposts' => 2000, 'wpsc_product_category' => $product_type);
		$the_query = new WP_Query($args);
		//iterate albums
		while ($the_query -> have_posts()) :
			$the_query -> the_post();
			$categories = wp_get_post_terms($the_query->post->ID, $taxonomy_name, array("fields" => "all"));
			// // add to labels even if NOT new
			foreach($categories as $cat){
				 $labels [] = $cat->name;
			 } 
		endwhile;
		//clean out duplicates
			$labels  = array_unique($labels);		
		foreach($labels as $label){
			echo "<li><a href='#'>$label</a></li>";
		}
	}
	/**
 	* query albums and match on genre and artist and return album array 
 	*/
	public static function getAlbums($genre = 'All', $artist = 'All') {
		$tracks = array();
		$albums = array();
		//query albums
		$args = array('post_type' => 'wpsc-product', 'numberposts' => 2000, 'wpsc_product_category' => 'album');
		$the_query = new WP_Query($args);
		//iterate albums
		while ($the_query -> have_posts()) :
			$the_query -> the_post();
			$product_cat = $the_query -> query_vars['wpsc_product_category'];
			// create album with that name
			$current = new album(get_the_title(), get_the_ID());
			//now see if album matchs taxonomy arguments
			$valid_genre = true;
			$valid_artist = true;
			if($genre!='All'){
				$valid_genre = false;
				$cats = wp_get_post_terms($the_query->post->ID, 'music-category', array("fields" => "all"));
				foreach($cats as $cat)
				{
					if($cat->name == $genre)
					$valid_genre = true;
				}
			}
			if($artist!='All'){
				$valid_artist = false;
				$cats = wp_get_post_terms($the_query->post->ID, 'music-artist', array("fields" => "all"));
				foreach($cats as $cat)
				{
					if($cat->name == $artist)
					$valid_artist = true;
				}
			}
			if($valid_genre OR $valid_artist)
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
		//get all preview files and attach their guid to each track
		$player_tracks = array();
		//get the files
		$args = array(
		'post_status' => 'any',
		'post_type' => 'wpsc-preview-file'
		);
		$the_query = new WP_Query($args);
		//iterate player tracks (preview files)
		while ($the_query -> have_posts()) :
			$the_query -> the_post();
			$file = $the_query->post;
			$current = new player_track();
			$current->guid = $file->guid; 
			$current->parent = $file->post_parent;
			$current->title = "player_track Name";
			$current->ID = $file->ID;
			$player_tracks [] = $current;
		endwhile;
		
		//iterate tracks and add them to the albums array
		foreach ($tracks as $track) {
			//iterate the preview files and add them to corresponding track
			foreach($player_tracks as $player_track){
				if($player_track->parent == $track->ID)
				$track->player_track = $player_track;
			}
			//now pair tracks with albums
			foreach ($albums as $album) {
				if ($album -> name == $track -> album)
					$album -> addTrack($track);
			}
		}

		return $albums;
		
	}
	/**
	 * get a single album and its tracks by ID
	 */
	public static function getAlbum($id) {
		$tracks = array();
		$album = new album(get_the_title($id),$id);

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
		$player_tracks = array();
		//get the files
		$args = array(
		'post_status' => 'any',
		'post_type' => 'wpsc-preview-file'
		);
		$the_query = new WP_Query($args);
		//iterate player tracks (preview files)
		while ($the_query -> have_posts()) :
			$the_query -> the_post();
			$file = $the_query->post;
			$current = new player_track();
			$current->guid = $file->guid; 
			$current->parent = $file->post_parent;
			$current->title = "player_track Name";
			$current->ID = $file->ID;
			$player_tracks [] = $current;
		endwhile;
		//iterate tracks and add them to the albums array
		foreach ($tracks as $track) {
				foreach($player_tracks as $player_track){
					if($player_track->parent == $track->ID)
					$track->player_track = $player_track;
				}
				if ($album -> name == $track -> album)
					$album -> addTrack($track);
			
		}

		return $album;
		
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