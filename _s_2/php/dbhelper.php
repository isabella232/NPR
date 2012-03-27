<?php


function get_category_id($cat_name){
	$term = get_term_by('name', $cat_name, 'category');
	return $term->term_id;
}

class CartHelper{
	public static function getCartIcon(){
		$checkout = self::getCheckoutURL();
		$count = wpsc_cart_item_count();
		echo "
		<div id='cart-icon-wrapper'> 
			<div id='cart-count'>
				<div id='cart-count-text' class='text'>
				$count
				</div>
			</div>
			<a id='cart-icon-button-link' href='$checkout' target='blank'>
			<div id='cart-icon'></div>
			</a>
		</div>
		 ";?>
		 <script>
		 var button = $("#cart-icon-button-link");
		 var display = $("#cart-count-text");
		 var count = display.text();
		 button.click(function(e){
		 	e.preventDefault();
			if(count>0)
				window.location.href = button.attr('href');
			else
				alert("Sorry your cart is empty");		 	
		 });
		 </script>
		 <?php
	}
	
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
                  
                   <p class="pricedisplay" style="display:none;">Shipping:<span class="pp_price"><span class="pricedisplay">'.$price.'</span></span></p>
                
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
	
	public static function getCheckoutURL(){
		return get_option('shopping_cart_url');
	}
}

class DBHelper {
	/**
	 * getLatest for passed post type and return given number
	 */
	public static function getLatest($category, $number){

	$args = array(
    'numberposts' => $number,
    'post_type' => 'wpsc-product',
    'orderby' => 'post_date',
    'order' => 'DESC',
    'category' => $category); 
    $recent_posts = wp_get_recent_posts( $args );
	
    return $recent_posts;
	
	}
	
	/**
	 * returns an array of article posts for given parameters
	 */
	public static function getArticles($category = "All", $artist = "All"){
		wp_reset_query();
		$filtered_posts = array(); //empty array to put posts once filtered
		$cat_id = get_category_id($category);
		$args = array('posttype' => 'post', 'category'  => $cat_id, 'numberposts'  => 2000);
		//if category is all ignore the category parameter 
		if($category=="All")
			$args = array('posttype' => 'post', 'numberposts'     => 2000);
		$posts = get_posts($args);
		//now iterate post and compare to artist. remove if no artist taxonmy items match passed item
		if($artist!="All")
		{
			foreach($posts as $post){
				$valid_artist = false;
				$cats = wp_get_post_terms($post->ID, 'music-artist', array("fields" => "all"));
				$current = new article($post);
				foreach($cats as $cat)
				{
					if($cat->name == $artist)
					$valid_artist = true;
				}
				//if after search post had a matching artist term then add to the filtered posts array
				if($valid_artist==true)
					$filtered_posts [] = $current; 
			}
			return $filtered_posts;
		}
		else{
			$article_posts = array();
			foreach($posts as $post){
				$current = new article($post);
				$article_posts [] = $current;
			}
			return $article_posts;
		}
			
	}
	
	public static function getArticleCategories(){
		global $post;
		$PAGE_ID = $post -> ID;
		$url = get_bloginfo('url')."?page_id=$PAGE_ID";
		
		$terms = get_terms("category","orderby=count&hide_empty=1");
		echo "<li><a href='$url&cat=All' class='category' name='All'>All</a></li>";
		foreach ($terms as $term) {
			if($term->name!="Uncategorized")
			echo "<li><a href='$url&cat=$term->name' class='category' name='$term->name'>$term->name</a></li>";
		}
	}
	
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
	 * echos out a list of categories based on passed product type and taxonomy name
	 */
	public static function getPostTaxonomyList($taxonomy_name){
		//create cat array	
		$labels = array('All');
		//query db
		$args = array('post_type' => 'post', 'numberposts' => 2000);
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
	public static function getVideos($genre = 'All', $artist = 'All') {
		$videos = array();
		//query videos
		$args = array('post_type' => 'wpsc-product', 'numberposts' => 2000, 'wpsc_product_category' => 'video');
		$the_query = new WP_Query($args);
		//iterate videos
		while ($the_query -> have_posts()) :
			$the_query -> the_post();
			// create video with that name
			$current = new video(get_the_title(), get_the_ID());
			
			//now see if video matchs taxonomy arguments
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
				$videos[] = $current;
			// add to videos
		endwhile;


		return $videos;
		
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
	 * get a single video by ID
	 */
	public static function getVideo($id) {
		$video = new video(get_the_title($id),$id);
		return $video;
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