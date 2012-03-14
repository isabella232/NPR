<?php 
/**
 * Template name: Articles Template
 */
	$CAT = 'All';
if (isset($_GET['cat']))
	$CAT = $_GET['cat'];
global $post;
$PAGE_ID = $post -> ID;
$url = get_bloginfo('url')."?page_id=$PAGE_ID";
get_header(); ?>
<div id="lh_sidebar">
<ul>
		<li class='sidebar-title'>Categories</li>
		<?php $terms = get_terms("category","orderby=count&hide_empty=1");
		$count = count($terms);
		echo "<li><a href='$url&cat=All' class='category' name='All'>All</a></li>";
		foreach ($terms as $term) {
			echo "<li><a href='$url&cat=$term->name' class='category' name='$term->name'>$term->name</a></li>";
		}
		?>
	</ul>

</div>
<div id="container">
	<?php
	
	if($CAT!='All'){
		$cat_id =  get_cat_ID( $CAT );
		$args = array( 'category'  => $cat_id, 'numberposts'     => 2000);
		$posts = get_posts($args);
	}
	else
		{
			$args = array( 'category'  => $cat_id, 'numberposts'     => 2000);
			$posts = get_posts($args);
		}
	foreach($posts as $post){ 
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