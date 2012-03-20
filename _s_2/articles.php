<?php 
/**
 * Template name: Articles Template
 */
	// $CAT = 'All';
// if (isset($_GET['cat']))
	// $CAT = $_GET['cat'];
// global $post;
// $PAGE_ID = $post -> ID;
// $url = get_bloginfo('url')."?page_id=$PAGE_ID";

UI::ajaxheader();
?>
<div id="lh_sidebar">

		<!-- <li class='sidebar-title'>Categories</li>
		<?php $terms = get_terms("category","orderby=count&hide_empty=1");
		$count = count($terms);
		echo "<li><a href='$url&cat=All' class='category' name='All'>All</a></li>";
		foreach ($terms as $term) {
			echo "<li><a href='$url&cat=$term->name' class='category' name='$term->name'>$term->name</a></li>";
		}
		?> -->
		<ul>
		<?php 
		echo "<div id='category-tax' class='taxonomy-div'>"; 
		echo "<h2>Categories</h2>";
		DBHelper::getArticleCategories();
		echo "</div>";
	    echo "<div id='artist-tax' class='taxonomy-div'>"; 
		echo "<h2>Artists</h2>";
		DBHelper::getPostTaxonomyList('music-artist');
		echo "</div>"
		?>
		</ul> 


</div>
<div id="container" class="articles">
	<?php
		$articles = DBHelper::getArticles("All","All");
		foreach($articles as $post){
			article::display();
		}
		article::getLinkScript();
	?>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script src="/npr/wp-content/themes/_s_2/js/masonry.js"></script>
<script>  

/**
 * ajax handle inner links
 */
$("#category-tax li a").click(function(e){
	e.preventDefault();
	var value = $(this).text();
	removeCurrentClass();
	$(this).addClass("current");
	ajaxLoadArticles(value,"All");
});
$("#artist-tax li a").click(function(e){
	e.preventDefault();
	var value = $(this).text();
	removeCurrentClass();
	$(this).addClass("current");
	ajaxLoadArticles("All",value);
});

/**
 * make ajax call to load a series of albums
 */
function ajaxLoadArticles(category,artist){
				if(category!=""){
					showAjaxLoader();
					$.ajax({
			        url:        '<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php',
			        type:       'post',
			        data:       { "action":"displayArticles", "category":category, "artist":artist},
			        success: function(data) {
			        $("#container").html("");
				    $("#container").append(data);
					hideAjaxLoader();
				  }
			    });				  
				}
				return false;
} 


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
<?php UI::ajaxfooter(); ?>