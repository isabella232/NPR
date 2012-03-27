<?php 
/**
 * Template name: Articles Template
 */


UI::ajaxheader();
?>
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
/**
 * load in all articles when page first loads
 */  
$(document).ready(function(){
		ajaxLoadArticles("All",'All');
	});
	var max_container_height;
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

$(window).resize(function() {
	var w = $(window).width() - 240;
  $('#container').width(w);
	arrange();
});	

function arrange(){

	
  $('#container').masonry({
    // options
    itemSelector : '.item-wrapper', 
    singleMode : true,
    isAnimated: true
  });

}
</script>
<?php UI::ajaxfooter(); ?>