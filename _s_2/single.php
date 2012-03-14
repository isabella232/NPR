<?php
/**
 * The Template for displaying all single posts.
 *
 * @package _s
 * @since _s 1.0
 */

get_header(); ?>

		<div id="primary" class="site-content">
			<div id="content" role="main">
		
			<?php while ( have_posts() ) : the_post(); ?>

				<?php //_s_content_nav( 'nav-above' ); ?>

				<?php 
				$post_type = get_post_type( $post->ID);
				
				if($post_type == 'video')
					{
						$video = new Video($post);
						$video->displaySingle();
					}
				else
					displayArticle(); 
				?>

				<?php //_s_content_nav( 'nav-below' ); ?>  

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					//if ( comments_open() || '0' != get_comments_number() )
						//comments_template( '', true );
				?>

			<?php endwhile; // end of the loop. ?>
				
			</div><!-- #content -->
		</div><!-- #primary .site-content -->

<?php get_footer(); ?>
 
<?php 
function displayArticle(){

	echo "<div id='single-post'>
	<div class='post-wrap'>
	<p>"; 

		echo "<div id='feature-images'>
				<div>";
			echo "<div id='feature-images-inner'>";
			echo	the_post_thumbnail('full'); 
			getShareButtons();
			echo "</div>";
				echo "<div id='tags'>";	
				getPostTags();		
				echo "</div>";
		
			echo "</div>";
		echo "</div>";
		echo "<h1>".get_the_title()."</h1><br/>";
		echo "<h3 class='by'>by ";the_author_posts_link();echo"</h3><br/>";
		echo "<h3 class='date'>";the_date();echo"</h3><br/>";
	the_content();
	echo "</p></div>";
	comments_template();
	getCommentsJquery();
	getRelatedPostsBar();
	echo "	</div>";
	
}

function getPostTags(){
	global $post;
	$tags = wp_get_post_tags($post->ID);
		  if ($tags) {
		  echo "<span class='tag-label'>Tags: </span>";
		  $i = 0;
			foreach($tags as $tag) {
				if($i>0)
					echo "<span class='tag-sep'>, </span>";
				echo '<span class="tag-item">' . $title . '<a href="' . get_term_link( $tag, 'post_tag' ) . '" title="' . sprintf( __( "View all posts in %s" ), $tag->name ) . '" ' . '>' . $tag->name.'</a> </span> ';
				
				$i++;
			}
		  }
}

function getCommentsJquery(){
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script>
	$(document).ready(function(){
		$(".comments-title").append(" - <a href='#' id='see-discussion'>See the discussion</a>");
		$("#see-discussion").click(function(e){
			e.preventDefault();
			$("#respond").slideToggle('slow', function() {
			  });
			$(".commentlist").slideToggle('slow', function() {
				  });
		});
	});
</script>
<?php	
}

function getRelatedPostsBar(){
	$current_post_id = $post->ID;	
		
	echo "
	<div id='single-related-posts'>
	<h2>Related Articles</h2>
	<div id='related-articles-slider'>
	";
	wp_reset_query();
	$args = array(
    'numberposts'     => 10 );
	 
	$posts = get_posts($args);
		foreach($posts as $post){
		global $post; 
			if($current_post_id!=$post->ID){
			$excerpt = get_the_excerpt();
			$title = get_the_title();
			$link = get_post_permalink(); 
			echo '<div class="related-item"> 
					<div class="img-wrap">
					<a title="Read More" href="'.$link.'">';
			        the_post_thumbnail( 'related-thumb' ); 
			echo '	</a>
					</div>
					<div class="related-content">
						<h4>'.$title.'</h4>
						<p>'.$excerpt.'</p>
					</div>
				 </div> ';
			} //endif
		} 
		
		echo "
	</div>
	</div>
	";
		?>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script src="http://bxslider.com/sites/default/files/jquery.bxSlider.min.js" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
  	 $('#related-articles-slider').bxSlider({autoControls: true});
  	rejig();
  
  });
  
  function rejig(){
  	var sliderW = $("#single-related-posts").width();
  	$(".bx-wrapper").width(sliderW);
  	$(".bx-window").width(sliderW - 80);
  }
  
  $(window).resize(function() {
	rejig();
	});
 </script>
	<?php
	
}

function getShareButtons(){
	$pics = get_bloginfo('template_directory')."/res";
	echo "
	<div id='article-share-wrapper'>
		<div class='share-item'>
			<a href='#'>
				<img src='$pics/fb_1.png'>
			</a>
		</div>
		<div class='share-item'>
			<a href='#'>
				<img src='$pics/twitter_1.png'>
			</a>
		</div>
		<div class='share-item'>
			<a href='#'>
				<img src='$pics/email.png'>
			</a>
		</div>
		<div class='share-item'>
			<a href='#'>
				<img src='$pics/share_this.png'>
			</a>
		</div>
	</div>
	";

		
}
?>