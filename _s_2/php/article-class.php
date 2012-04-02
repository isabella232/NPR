<?php
class article{
	public $post;
	public $ID;
	public $title;
	public $custom;
	public $caption;
	public $excerpt;
	public $thumbnail;
	public $largeThumb;
	public $link;
	public $date;
	public function __construct($ID){
		$this->ID = $ID; 
		$this->post = get_post($this->ID);
		$this->title = get_the_title($this->ID);
		$this->custom = get_post_custom($this->ID);
		$this->caption = $this->custom["post-meta-picturecaption"][0]; 
		$this->thumbnail = get_the_post_thumbnail($this->ID, 'homepage-thumb' );
		$this->largeThumb = get_the_post_thumbnail($this->ID, 'article-large-thumb' );
		$this->link = get_permalink($this->ID);
		$this->date = get_the_date($this->ID); 
		$this->excerpt = substr($this->post->post_content, 0 , 250)."...";
	}
	
	public function getCaption(){
		echo "<p class='single-caption'>$this->caption<p>";
	}
	
	public function displayLarge(){
		?>
		<div id='<?php echo $this->ID; ?>' class='large-article-display'>
			
			<h1><?php echo $this->title; ?></h1>
			<p class='content-holder'> 
			<?php echo $this->largeThumb;
			echo $this->excerpt;
			?>
			</p>
		</div>
		<?php
	}
	
	public function display(){
		?>
		
		<div id='<?php echo $this->ID; ?>' class="sm-item item sm-article item-wrapper">
			<div class="inner">
		<div class="content">
		<?php ?>
	    <h4><a href="<?php the_permalink($this->ID); ?>"><?php the_title($this->ID); ?></a></h4>
		 
	    </div>  
		    <a class='read-more' href=<?php echo $this->link;?>>
		      <?php 
		      echo $this->thumbnail;
		     
		      ?>
		    </a>
	      <div class="content">
	      	<?php
	      	$content = $this->post->post_content;
			$content = apply_filters('the_content', $content);
			$content = substr($content, 0 , min(strlen($content),40)); 
			echo $content;
			?>
	      	 <a class='read-more' href=<?php echo $this->link; ?>>Read More</a>  
	      	 <?php echo $this->date;?>   
		   </div> 
		  
		     </div>          
	  	</div>
  <?php
	}
	/**
	 * takes the current category and artist to create a back button on the article single
	 */
	public static function getLinkScript($category = "All", $artist = "All"){
		?>
		<script>
		$("#container a").click(function(e){
			e.preventDefault();
			//adjust href to add cat and artist post variables
			href = $(this).attr("href");
			href += "&category=<?php echo  urlencode($category); ?>";
			href += "&artist=<?php echo  urlencode($artist); ?>";
			$(this).attr("href",href);
			$("#container").remove();
			addToBodyClass("white"); 
			$("#main").append("<div id='container'></div>");
			setOpen();
			loadFromInto($(this),"#container");
		});
		
		
		</script>
		<?php
	}
	
	public static function getMasonry(){
		?>
		// <script>
		// $(document).ready(function(){
			// var w = $(window).width() - 240;
		  // $(	'#container').width(w);
			// arrange();
		// });
// 		
		// $(window).resize(function() {
			// var w = $(window).width() - 240;
		  // $('#container').width(w); 
			// arrange();
		// });	
// 		
		// function arrange(){
		  // $('#container').masonry({
		    // // options
		    // itemSelector : '.item',
		    // columnWidth : 244,
		    // isAnimated: true
		  // });
		// }
		<script>
		<?php
	}
}
?>