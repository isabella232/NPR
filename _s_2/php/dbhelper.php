<?php
 

function get_category_id($cat_name){
	$term = get_term_by('name', $cat_name, 'category');
	return $term->term_id;
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
				$current = new article($post->ID);
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
				$current = new article($post->ID);
				$article_posts [] = $current;
			}
			return $article_posts;
		}
			
	}

	public static function getLatestArticles($number = 1){
		global $post; 
		$oldpost = $post;
		$args = array('posttype' => 'post', 'numberposts'  => $number);
			// The Query
		$the_query = new WP_Query( $args );
		$articles = array();
		// The Loop
		$count = 0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$current = new article($the_query->post->ID);
			if($count < $number)
				$articles [] = $current;
			$count++;
		endwhile;
		// Reset Post Data
		wp_reset_postdata();
		return $articles;
	}
	
	public static function getArticleCategories(){
		global $post;
		$PAGE_ID = $post -> ID;
		$url = get_bloginfo('url')."?page_id=$PAGE_ID";
		
		$terms = get_terms("category","orderby=count&hide_empty=1");
		echo "<li><a href='$url&cat=All' class='category' name='All'>All</a></li>";
		foreach ($terms as $term) {
			if($term->name!="Uncategorized")
			echo "<li><a href='$url&cat=$term->name' class='category $term->name' name='$term->name'>$term->name</a></li>";
		}
	}
	
	/**
	 * echos out a list of categories based on passed product type and taxonomy name
	 */
	public static function getProductCategories(){
		//create cat array	
		$labels = array();
		//query db
		$args = array('post_type' => 'wpsc-product', 'numberposts' => 200000);
		$the_query = new WP_Query($args);
		//iterate products
		while ($the_query -> have_posts()) :
			$the_query -> the_post();
			$categories = wp_get_post_terms($the_query->post->ID, 'wpsc_product_category', array("fields" => "all"));
			// add to labels even if NOT new
			foreach($categories as $cat){
				 $labels [] = $cat->name;
			 } 
		endwhile;
		//clean out duplicates
		$labels  = array_unique($labels);		
		return $labels;
		wp_reset_query();
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
			echo "<li><a href='#' class='$label'>$label</a></li>";
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

	/**
 	* query albums and return album array 
 	*/
	public static function getProducts($chosen_categories) {
		$args = array('post_type' => 'wpsc-product', 'numberposts' => 20000);	
		$my_posts = get_posts($args);
		$valid_posts = array();
		foreach($my_posts as $the_post){
			$valid = false;
			$categories = wp_get_post_terms($the_post->ID, 'wpsc_product_category', array("fields" => "all"));
			foreach($categories as $cat)
			{
				if(in_array($cat->name, $chosen_categories, true))
				$valid = true;
			}
			if($valid)
			$valid_posts [] = new nprproduct($the_post); 
		}
		return $valid_posts;
	}

}
?>