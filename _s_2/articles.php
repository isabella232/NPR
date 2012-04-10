<?php 
/**
 * Template name: Articles Template
 */

$INIT_ARTICLE = $_POST['init_article'];
UI::ajaxheader();
?>
<script>
	$(document).ready(function(){
		window.location.hash = "<?php echo str_replace(" ", "-", "Articles"); ?>";
	});
</script> 
<div id="lh_sidebar">


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

</div>

<script>
<?php
$open_article = $_GET['open_article'];
if(isset($open_article)):
?>
/**
 * Load the passed article
 */
	$(document).ready(function(){
			var href = "<?php echo get_bloginfo('url').'/?p='.$open_article;?>";
			href+= "&category=All&artist=All";
			$("#container").remove();
			addToBodyClass("page-white");  
			$("#main").append("<div id='container'></div>");
			setOpen();
			$("#container").load(href, null, hideAjaxLoader); 
	});
<?php else:?>
	/**
	 * load in all articles when page first loads
	 */  
	$(document).ready(function(){
			ajaxLoadArticles("All",'All');
		});
		var max_container_height;
		var fullView = false;
<?php endif; ?>

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
		fullView = false;
		$.ajax({
        url:        '<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php',
        type:       'post',
        data:       { "action":"displayArticles", "category":category, "artist":artist},
        success: function(data) {
        			window.location.hash = "Articles"
	   				$("#container").empty();
			        hideAjaxLoader();
			        reloadMasonry(data);
				    hideAjaxLoader();
				  
	  }
    });				  
	}
	return false;
} 

var max_container_height;
$(document).ready(function(){
	
	max_container_height = $("#container").height();
	
	var w = $(window).width() - 240;
  $('#container').width(w);
	arrange();
});

function setOpen(){
	fullView = true;
}

$(window).resize(function() {
	var w = $(window).width() - 240;
  $('#container').width(w);
	arrange();
});	

function arrange(){
	if(fullView==false){	
	  $('#container').masonry({
	    // options
	    itemSelector : '.item-wrapper', 
	    singleMode : true,
	    isAnimated: true
	  });
	}
}
</script>
<?php UI::ajaxfooter(); ?>