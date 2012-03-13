<?php 
	$CAT = 'All';
if (isset($_GET['cat']))
	$CAT = $_GET['cat'];
global $post;
$PAGE_ID = $post -> ID;
$url = get_bloginfo('url')."?page_id=$PAGE_ID";
$loader = get_bloginfo('template_directory')."/loader_video.php";
/**
 * Template name: Video Template
 */
get_header();
?>
<!-- before sidebar -->
<div id="lh_sidebar">
	<ul>
		<?php $terms = get_terms("music-category","orderby=count&hide_empty=0");
		$count = count($terms);
		echo "<li><a href='$url&cat=All' class='category' name='All'>All</a></li>";
		foreach ($terms as $term) {
			echo "<li><a href='$url&cat=$term->name' class='category' name='$term->name'>$term->name</a></li>";
		}
		?>
	</ul>
</div>
<!-- script starts -->
<script type="text/javascript">
// AJAX script 
$(document).ready(function(){
	
	
 $("#lh_sidebar li a").each(
 	function(){
 		var loader = '';
 		var name = $(this).attr("name");  
 		$(this).click(function(e){
 			e.preventDefault();
 			removeOtherClasses();
 			$(this).addClass("current");
 			window.location=$(this).attr("href");
 		});
 	});
});
function removeOtherClasses(){
	$("#lh_sidebar li a").each(
 	function(){
 		$(this).removeClass("current");
 	});
}
</script>
<div id="container">
	<?php $videos = array();
	$args = array('post_type' => 'wpsc-product' , 'numberposts' => 2000);
	$posts = get_posts($args);
	foreach ($posts as $post) {
		$current = new Video($post);
		$videos[] = $current;
		$post_cat_array = wp_get_post_terms($post->ID, 'music-category');
		$display_it = false; 
		foreach($post_cat_array as $cat)
		{
			if($cat->name == $CAT) 
			{
				$display_it = true;
			}
		}
		if($display_it OR $CAT == "All")
			$current->makeVideoBox();
	}
	?>

</div>
<script src="/npr/wp-content/themes/_s_2/js/masonry.js"></script>
<script>
var columnwidth = 242;
var marginsize = 200;
	$(document).ready(function(){
$('.sm-video').animate({
opacity: 1 
}, 500, function() {

});

var w = $(window).width() - marginsize;
$('#container').width(w);
arrange();
}); 

$(window).resize(function() {
var w = $(window).width() - marginsize;
$('#container').width(w);
arrange();
});

function arrange(){
$('#container').masonry({
// options
itemSelector : '.item',
columnWidth : columnwidth,
isAnimated: true
});

$('.category').click(function(){
var category = $(this).attr('name');
window.location.href="<?php echo bloginfo('url') . "?page_id=" . $PAGE_ID;?>
	&cat="+category;
	});

	}
</script>
<?php get_footer();?>