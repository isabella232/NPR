<?php
class article{
	public $post;
	public $ID;
	public $custom;
	public $caption;
	public function __construct($post){
		$this->post = $post;
		$this->custom = get_post_custom($this->post->ID);
		$this->caption = $this->custom["post-meta-picturecaption"][0]; 
	}
	
	public function getCaption(){
		echo "<p class='single-caption'>$this->caption<p>";
	}
	
	public static function display(){
		?>
		
		<div class="sm-item item sm-article">
			<div class="inner">
		<div class="content">
		<?php ?>
	    <h4><a href="<?php if(get_post_meta($post->ID, "url", true)) echo get_post_meta($post->ID, "url", true); else the_permalink(); ?>"><?php the_title(); ?></a></h4>
		 
	    </div>  
		    <a class='read-more' href=<?php the_permalink();?>>
		      <?php 
		      the_post_thumbnail( 'homepage-thumb' ); 
		     
		      ?>
		    </a>
	      <div class="content">
	      	<?php
	      	$content = $post->post_content;
			$content = apply_filters('the_content', $content);
			$content = substr($content, 0 , min(strlen($content),40)); 
			echo $content;
			?>
	      	 <a class='read-more' href=<?php the_permalink();?>>Read More</a>  
	      	 <?php echo the_date();?>   
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
			$("#main").append("<div id='container'></div>")
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