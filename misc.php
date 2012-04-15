<?php
/*add theme support for thumbnails*/
add_theme_support( 'post-thumbnails' );
	add_image_size( 'zealandia-slider', 620, 500, true );
	add_image_size( 'zealandia-slider-thumb', 69, 999, true );
	add_image_size( 'zealandia-search-thumb', 160, 120, true ); 
	add_image_size( 'zealandia-blog-list-thumb', 160, 120, true ); 
	add_image_size( 'zealandia-event-thumb', 120, 100, true ); 
	add_image_size( 'zealandia-species-thumb', 141, 92, true ); 
	add_image_size( 'zealandia-sidebar-thumb', 300 ); 

function createBasicSlider(){
create_slider('basic_slider','basic_slider_image','basic_slider_caption','basic_slider_credits');
}

function createEventSlider($event){
create_event_slider($event,'basic_slider','basic_slider_image','basic_slider_caption');
}

function getShareButtonStrip(){?> 
	<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style ">
		<a class="addthis_button_preferred_1"></a>
		<a class="addthis_button_preferred_2"></a>
		<a class="addthis_button_preferred_3"></a>
		<a class="addthis_button_preferred_4"></a>
		<a class="addthis_button_compact"></a>
		<a class="addthis_counter addthis_bubble_style"></a>
		</div>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f38b99960a7efc3"></script>
	<!-- AddThis Button END -->
	<?php
}
function getHeaderShareButtonStrip(){?>
	<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style ">
		<a class="addthis_button_preferred_1"></a>
		<a class="addthis_button_preferred_2"></a>
		<a class="addthis_button_compact"></a>
		<a class="addthis_counter addthis_bubble_style"></a>
		</div>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f38b99960a7efc3"></script>
	<!-- AddThis Button END -->
	<?php
}
/**
 * homepage feature slider
 */
function create_feature_slider(){
	
	/* load javascript*/?>
	
	<div id="zealandia_featured" class="zealandia_text">
		<div class="anythingSlider">
			<div class="wrapper">
				<ul>
					<?php 
					global $post;
					$count = 0;
					$menu_labels = array();
					while(the_repeater_field('feature_slider')):  
						$count ++; //keeping track to allow for JS manipulation further down
						$image = get_sub_field('feature_slider_image');
						$orange_header = get_sub_field('feature_slider_orange_header');
						$black_header = get_sub_field('feature_slider_black_header');
						$link_text = get_sub_field('feature_slider_link_text');
						$link_page = get_sub_field('feature_slider_link_page');
						$menu_label = get_sub_field('feature_slider_menu_label');
						$menu_labels[] = $menu_label;
						?>
					<li>
						<img width="630" <?php /*height="393"*/?> style='overflow:hidden;' src="<?php echo $image; ?>" class="attachment-zealandia-slider wp-post-image" alt="poolburn1" title="poolburn1" />
						<span><?php echo $orange_header; ?></span>
						<h2><?php echo $black_header; ?></h2>
						<a href="<?php echo $link_page; ?>" class="discover-more"><?php echo $link_text; ?></a>
					</li>
					<?php endwhile; ?>
					<?php /*
					<li>
						<img width="620" height="465" src="http://new.visitzealandia.com/wp-content/uploads/2012/02/PukekoWithOneLegRaised.jpg" class="attachment-zealandia-slider wp-post-image" alt="geese1" title="geese1" /><span>Featured Event</span>
						<h2>An event!</h2>
						<a href="http://localhost/wp/zealandia/events/an-event/" class="discover-more">Discover more</a>
					</li>
					 * */?>
				</ul>
			</div>
		</div>
	</div>
	
	
	<?php /* load javascript*/?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src='/wp-content/themes/new_zealandia/js/zealandia-slider/scripts/jquery.anythingslider.js'></script>
	<script type="text/javascript" src='/wp-content/themes/new_zealandia/js/zealandia-slider/scripts/jquery.easing.1.2.js'></script>
	<script type="text/javascript" src='/wp-content/themes/new_zealandia/js/zealandia-slider/scripts/init.js'></script>
	<script type="text/javascript">
	
	$(document).ready(function() {
		<?php 
		
		for($i = 0 ; $i < $count; $i++) :?>
 		$("#featured-thumbnail-<?php echo ($i+1);?>").empty().append("<?php echo $menu_labels[$i];?>");
 		<?php endfor;?>

	});

	</script>
	<?php
	//finally reset loop
	wp_reset_query();
}


function create_slider($repeater,$images,$captions,$info){

?>
<div class="slider-wrapper theme-default">
	<div id="basicslider">
	  <?php /*title="<?php the_sub_field($captions);?>"*/
		$i = 0;
		while(the_repeater_field($repeater)): 
		?>
		<div class="basic-slider-slide">
			<div class='image_wrap'>  
				<img src="<?php the_sub_field($images);?>" alt="" class="nivo_image" title="<?php the_sub_field($captions);?>" />
			</div>
		<div class="basic-slider-caption">
			<p><?php the_sub_field($captions);?></p>
			<?php if (get_sub_field("individual_species_gallery_images_credits")):?>
			<p class='credits'>Photo By <?php the_sub_field("individual_species_gallery_images_credits");?></p>
			<?php elseif(get_sub_field("basic_slider_photo_credits")):?>
			<p class='credits'>Photo By <?php the_sub_field("basic_slider_photo_credits");?></p>	
			<?php endif;?>
		</div>
		</div>
		<?php 
		$i++;
		endwhile; ?>
	</div>
	
	
	<div class="thumbs">
	
	   <?php /*title="<?php the_sub_field($captions);?>"*/
		$i = 0;
		while(the_repeater_field($repeater)): 
		?>
	  <a href="#">
	  	<div class='thumbnail_wrapper'>
	  	<?php /**
	  	<img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php the_sub_field($images);?>=&h=50&w=50&zc=1" />
		 */?>
		 <img src="<?php the_sub_field($images);?>"/>
	  	</div>
	  </a>
	
	
		<?php 
		$i++;
		endwhile; ?>
	
	</div>
</div>
<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
<script src="http://bxslider.com/sites/default/files/jquery.bxSlider.min.js" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#basicslider').bxSlider();
  });
  $(function(){
  // assign the slider to a variable
  var slider = $('#basicslider').bxSlider({
    controls: false
  });
//    slider.goToSlide(thumbIndex+1);
  // assign a click event to the external thumbnails
  $('.thumbs a').click(function(e){
  	e.preventDefault();
   var thumbIndex = $('.thumbs a').index(this);
    // call the "goToSlide" public function
    slider.goToSlide(thumbIndex+1);
  
    // remove all active classes
    $('.thumbs a').removeClass('pager-active');
    // assisgn "pager-active" to clicked thumb
    $(this).addClass('pager-active');
    // very important! you must kill the links default behavior
    return false;
  });

  // assign "pager-active" class to the first thumb
  $('.thumbs a:first').addClass('pager-active');
});
</script>


				
					
			
<?php
}

function create_event_slider($event,$repeater,$images,$captions,$info){


?>
<div class="slider-wrapper theme-default event-slider">
						<?php
							echo "<div id='event-slider-headers'>";
							echo "<span>".get_the_title($event->ID)."</span>";
							echo "<h2>".get_the_title($event->ID)."</h2>";
							echo "<a href='".get_permalink($event->ID)."' class='discover-more'>Discover More</a>";
							echo "</div>";
							?>
						<div class="ribbon"></div>
						<div id="slider" class="nivoSlider" height="400px">
							<?php /*title="<?php the_sub_field($captions);?>"*/
						
							$i = 0;
							while(the_repeater_field($repeater,$event->ID)): 
							?>
							<img src="<?php the_sub_field($images,$event->ID);?>" alt="" class="nivo_image" title="<?php the_sub_field($captions,$event->ID);?>" />
							
							<?php 
							$i++;
							endwhile; ?>
						</div>
					</div>
					
					<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
					<script src="/wp-content/themes/new_zealandia/nivo-slider/jquery.nivo.slider.pack.js" type="text/javascript"></script>
					<script src="/wp-content/themes/new_zealandia/nivo-slider/jquery.nivo.slider.js" type="text/javascript"></script>
					<script type="text/javascript">
					$(window).load(function() {
						$('#slider').nivoSlider({
						effect: 'fade',
						captionOpacity: 0.8,
						controlNavThumbs:true,
						directionNavHide: false,
						manualAdvance: true
						});
					});
					</script>
<?php
}

function misc_getCategoryExcerpt(){
	global $post;
	$raw_excerpt = $text;
	if ( '' == $text ) {
	$text = get_the_content('');
	$text = strip_shortcodes( $text );
	$text = apply_filters('the_content', $text);
	$text = str_replace(']]>', ']]&gt;', $text);
	}
	
	$text = strip_tags($text);
	$excerpt_length = apply_filters('excerpt_length', 150);
	$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
	$text = wp_trim_words( $text, $excerpt_length, $excerpt_more ); //since wp3.3
	$words = explode(' ', $text, $excerpt_length + 1);
	if (count($words)> $excerpt_length) {
	array_pop($words);
	$text = implode(' ', $words);
	$text = $text . $excerpt_more;
	} else {
	$text = implode(' ', $words);
	}
	return $text;
}
/**
* returns a black pointer link
* @param string $text link text
* @param string $link link href
* @param string $image image src
*/
function make_pointer_button($text, $link, $image){
	$class = str_replace(" ", '_',$text);
?>
<table class="primarylinks">
			<tr><?php if($image):?>
				<td>
					<a class="primarylinks_label <?php echo $class;?>" href="<?php echo $link; ?>">
						<img class="primarylinks_icon" src="<?php echo $image; ?>"/>
					</a>
				</td>
				<?php endif;?>
				<td class="primarylinks_body <?php echo $class;?>"><?php /* link body */?>
					<a class="primarylinks_label" href="<?php echo $link; ?>">
					<?php echo $text; ?>
					</a>
				</td>
				<td class="primarylinks_end"><?php /* link end */?>
					
				</td>
			</td>
</table>
<?php
}
function return_pointer_button($text, $link, $image){
	$class = str_replace(" ", '_',$text);
$STR = "";
$STR.='<table class="primarylinks">
			<tr>';
			if($image && $image!=0):
				
$STR.= '
				<td>
					<a class="primarylinks_label '.$class.'" href="'.$link.'">
						<img class="primarylinks_icon" src="'.$image.'"/>
					</a>
				</td>';
			endif;

$STR.= "
				<td class='primarylinks_body $class'>
					<a class='primarylinks_label' href='$link'>
					$text
					</a>
				</td>
				<td class='primarylinks_end'>
					
				</td>
			</td>
</table> ";
return $STR;
}
function make_pointer_submit($text, $onclick, $image){
?>
<table class="primarylinks">
			<tr><?php if($image):?>
				<td>
					<a class="primarylinks_label">
						<img class="primarylinks_icon" src="<?php echo $image; ?>"/>
					</a>
				</td>
				<?php endif;?>
				<td class="primarylinks_body"><?php /* link body */?>
					<a class="primarylinks_label" id="pointer-submit" >
					<?php echo $text; ?>
					</a>
				</td>
				<td class="primarylinks_end"><?php /* link end */?>
					
				</td>
			</td>
</table>

<?php
}

function misc_make_back_nav($text){
?>
<table class="primarylinks">
			<tr>
				<td class="primarylinks_body"><?php /* link body */?>
					
					<?php echo $text; ?>
				
				</td>
				<td class="primarylinks_end"><?php /* link end */?>
					
				</td>
			</td>
</table>
<?php
}
/**
 * 
	// Opening HTML
	echo '

<div id="zealandia_featured" class="zealandia_' . $type . '">
	<div class="anythingSlider">
		<div class="wrapper">
			<ul>';

	global $post;
	$myposts = get_posts(
		array(
			'numberposts' => 5,
			'offset'      => 0,
			'post_type'   => $post_type
		)
	);
	$count = 0;
	foreach( $myposts as $post ) :	setup_postdata($post);
		$count++;
		?>
		<li>

			<?php the_post_thumbnail( 'zealandia-slider' ); ?>
			<span>Featured Event</span>
			<h2><?php the_title(); ?></h2>
			<a href="<?php the_permalink(); ?>" class="discover-more">Discover more</a>
		</li>
		<style>
			#featured-thumbnail-<?php echo $count; ?> {background: url(<?php
				$url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'zealandia-slider-thumb' );
				echo $url[0];
			?>);}
		</style><?php
	endforeach;


	// Closing HTML
	echo '
			</ul>
		</div>
	</div>
</div>
';
 */
?>