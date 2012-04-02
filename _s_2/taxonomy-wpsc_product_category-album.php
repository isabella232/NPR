<?php 
// get_header();
?>
<div id="lh_sidebar">
	<ul>
		<?php 
		echo "<div id='genre-tax' class='taxonomy-div'>";
		echo "<h2>Genres</h2>";
		DBHelper::getTaxonomyList('album','music-category');
		echo "</div>";
		echo "<div id='artist-tax' class='taxonomy-div'>";
		echo "<h2>Artists</h2>";
		DBHelper::getTaxonomyList('album','music-artist');
		echo "</div>"
	
		?>
	</ul>
</div>
<div id="container">
	 
		<div class="product_grid_display group">
		
		</div>
	 
	<?php
	/**
	 *  DISPLAY RESULTS OF MUSIC SEARCH
	 */
	// $albums = DBHelper::getAlbums(); 
	// foreach($albums as $album){
		// $album->makeView();
	// }
	
	
?>
<script> 
	$(document).ready(function(){
		ajaxLoadAlbums("All",'null');
	});
 
var max_container_height;
/**
 * reposition fullview on resize
 */
$(window).resize(function(){
		if(fullOpen == true)
		{
			var boxH = $(".large-item").first().height();
			$("#container").height(max_container_height);
		}
}); 
			
var fullOpen = false;
/**
 * show a full view
 */		
function showThis(id){
	if(fullOpen == false){
		ajaxSubmit(id);
	}
	fullOpen = true;
	
}

$("#artist-tax li a").click(function(e){
	e.preventDefault();
	var value = $(this).text();
	removeCurrentClass();
	$(this).addClass("current");
	ajaxLoadAlbums('null',value);
});
$("#genre-tax li a").click(function(e){
	e.preventDefault();
	var value = $(this).text();
	removeCurrentClass();
	$(this).addClass("current");
	ajaxLoadAlbums(value,'null');
});


/**
 * make ajax call to load a series of albums
 */
function ajaxLoadAlbums(genre,artist){
				if(genre!=""){
					
					showAjaxLoader();
					$.ajax({
			        url:        '<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php',
			        type:       'post',
			        data:       { "action":"displayAlbums", "artist":artist , "genre":genre},
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


/**
 * make ajax call to load full view for album
 */
function ajaxSubmit(id){
				if(id!=""){
					showAjaxLoader();
					$.ajax({
			        url:        '<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php',
			        type:       'post',
			        data:       { "action":"fetchAlbum", "id":id},
			        success: function(data) {
				    $("#container").append(data);
					setUpFullView();
					hideAjaxLoader();
					$("#container").scrollTop();
				  }
			    });				  
				}
				return false;
} 
/**
 * position the full view and make div heights match
 */
function setUpFullView(){
	var name = $("#large-item-album-name").attr("Name");
	if(name!="undefined")
	window.location.hash = name; 
	// $("#container").height($(".large-item").height()+100); 
	// var position = ( $("#container").width() )/ 2;
// 	
	// position = position - ( ($(".large-item").width() )/2 );
	// $(".large-item").css("margin-left",position+"px");
	// var rightH = $(".right-bar").height();
	// var leftH = $(".left-bar").height();
	// var H = Math.max(rightH,leftH);
	// $(".right-bar").height(H);
	// $(".left-bar").height(H);
	$(".large-item .close").click(restoreAlbums);
} 
/**
 * remove full view and restore albums
 */
function restoreAlbums(){
	window.location.hash = "Music"; 
	fullOpen = false;
	$(".large-item").remove();
	$("#container div").each(
		function(){
			$(this).fadeIn('200');
		}
	);
}			

$(document).ready(function(){
	
	max_container_height = $("#container").height();
	
	var w = $(window).width() - 225;
  $('#container').width(w);
	arrange();
});

$(window).resize(function() {
	var w = $(window).width() - 225;
  $('#container').width(w);
	//arrange();
});	

function arrange(){
	
  $('#container').masonry({
    // options
    itemSelector : '.item-wrapper', 
    columnWidth : 244,
    isAnimated: true
  });

}



</script>	
<?php 
// get_footer();
 ?>