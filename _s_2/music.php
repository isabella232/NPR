<?php 
/**
 * Template name: Music Template
 */
function jbug($var){
	echo "<pre>";
	var_dump($var); 
	echo "</pre>"; 
}
 
class album{
	public $tracks;
	public $name; 
	public $ID;
	
	public function __construct($name, $ID){
		$this->name = $name; 
		$this->ID = $ID;
		$tracks = array();
	}
	
	public function addTrack($track){
		$this->tracks[] = $track;
	}
	
	public function makeView(){
		echo "
			<div>
			<h1>title = $this->name</h1>";
			echo get_the_post_thumbnail($this->ID);
			$this->getTrackList();
		echo "</div>";
	}
	
	public function getTrackList(){
		echo "<ul>";
		foreach($this->tracks as $track){
			echo "<li>";
			echo $track->name;
			echo "</li>";
		}
		echo "</ul>";
	} 
}
class track{
	public $name;
	public $album;
	public $ID;
	
	public function __construct($name, $ID){
		$this->name = $name; 
		$this->ID = $ID;
	}
}
// echo "<br/><br/><br/><br/>";
$tracks = array();
$albums = array();
	//query albums
	$args = array('post_type' => 'wpsc-product' , 'numberposts' => 2000, 'wpsc_product_category'  => 'album');
	$the_query = new WP_Query( $args );
	//iterate albums
	while ( $the_query->have_posts() ) : 
		$the_query->the_post();
		$product_cat = $the_query->query_vars['wpsc_product_category'];
		$current = new album(get_the_title(), get_the_ID()); // create album with that name
		$albums[] = $current; // add to albums
	endwhile;
	
	//query tracks
	$args = array('post_type' => 'wpsc-product' , 'numberposts' => 2000, 'wpsc_product_category'  => 'track');
	$the_query = new WP_Query( $args );
	//iterate tracks
	while ( $the_query->have_posts() ) : 
		$the_query->the_post();
		$product_cat = $the_query->query_vars['wpsc_product_category'];
		$current = new track(get_the_title(), get_the_ID()); // create track with that name
		$meta = get_post_meta($current->ID, 'album-meta-name');
		$current->album = $meta[0];
		$tracks[] = $current; // add to track array
	endwhile;
	//iterate tracks and add them to the albums array
	foreach($tracks as $track){
		foreach($albums as $album){
			if($album->name = $track->album)
			$album->addTrack($track);
		}
	}
 
 
get_header(); ?>
<div id="lh_sidebar">
	<ul>
		<?php 
		$labels = array('All','Hip Hop','Rock','Country','Up & Coming');
		foreach($labels as $label){
			echo "<li><a href='#'>$label</a></li>";
		}
		?>
	</ul>
</div>
<div id="container">
	
		<div class="product_grid_display group">
		
		</div>
	 
	<?php
	/**
	 *  DISPLAY RESULTS OF MUSIC SEARCH
	 */
	foreach($albums as $album){
		$album->makeView();
	}
	
	
?>
	
<?php get_footer(); ?>