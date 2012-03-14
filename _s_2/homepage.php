<?php 
/**
 * Template name: Homepage Template
 */
get_header(); ?>
<h1 class='homepage-indent'><p>Articles</p></h1>
<div>
	<div id="slider-wrapper">
		<div id="article-slider">
			<?php
	
	$args = array(
    'numberposts'     => 5,
    'post_status'     => 'publish' ); 
	$posts = get_posts($args);
	foreach($posts as $post){

			?>
			<div class="sm-item item article-item">
				<div class="inner">
					  
					<div class="content title">
						<h4><a href="<?php if(get_post_meta($post->ID, "url", true)) echo get_post_meta($post->ID, "url", true); else the_permalink(); ?>">Articles</a></h4>
					</div>
			
					
					<?php
					the_post_thumbnail( 'article-thumb' );
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
			?>
		</div>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script src="http://bxslider.com/sites/default/files/jquery.bxSlider.min.js" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#article-slider').bxSlider();
    rejig();
  });
  
  $(window).resize(function() {
	rejig();
	});	
  
  function rejig(){
  	var w = getSliderWidth();
  	var h = getSliderHeight();
    $(".bx-window").width(w); 
    $(".bx-wrapper").width(w); 
    $(".bx-next").appendTo("#slider-wrapper");
    $(".bx-prev").prependTo("#slider-wrapper");
    $(".bx-prev").height(h);
    $(".bx-next").height(h);
  }
  
  function getSliderHeight(){
  	return $("#slider-wrapper").height();
  }
  
  function getWindowWidth(){
  	return $(window).width();
  }
  
  
  function getSliderWidth(){
  	var buttonWidth = 40;
  	return getWindowWidth() - ( 2 * $(".bx-prev").width()) - 20;
  }
</script>


<script src="/npr/wp-content/themes/_s_2/js/masonry.js"></script>
<script>  

// $(document).ready(function(){
	// var w = $(window).width() - 240;
  // $('#container').width(w);
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
</script>
<?php get_footer(); ?>