<?php 
/**
 * Template name: Homepage Template
 */
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
	<?php
	for($i = 0 ; $i < 5; $i++){
	$posts = get_posts();
	foreach($posts as $post){
		?>
		<div class="sm-item item">
			<div class="inner">
		<div class="content">
	    <h4><a href="<?php if(get_post_meta($post->ID, "url", true)) echo get_post_meta($post->ID, "url", true); else the_permalink(); ?>"><?php the_title(); ?></a></h4>
	    </div>  
	      <?php 
	      the_post_thumbnail( 'homepage-thumb' ); 
	     
	      ?>
	      <div class="content">
	      	<?php
	      	$content = $post->post_content;
			$content = apply_filters('the_content', $content);
			$content = substr($content, 0 , min(strlen($content),60))."...";
			echo $content;
			?>
	      
		   </div>     
		     </div>          
	  	</div>
  <?php
	}
	}
	?>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script src="/npr/wp-content/themes/_s_2/js/masonry.js"></script>
<script>  

$(document).ready(function(){
	var w = $(window).width() - 240;
  $('#container').width(w);
	arrange();
});

$(window).resize(function() {
	var w = $(window).width() - 240;
  $('#container').width(w);
	arrange();
});	

function arrange(){
  $('#container').masonry({
    // options
    itemSelector : '.item',
    columnWidth : 244,
    isAnimated: true
  });
}
</script>
<?php get_footer(); ?>