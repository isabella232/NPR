<?php 
/**
 * Template name: Music Template
 */

// echo "<br/><br/><br/><br/>";

 
 
get_header(); ?>
<div id="lh_sidebar">
	<ul>
		<?php 
		echo "<div id='genre-tax'>";
		echo "<h2>Genres</h2>";
		DBHelper::getTaxonomyList('album','music-category');
		echo "</div>";
		echo "<div id='artist-tax'>";
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
		
	
	$albums = DBHelper::getAlbums(); 
	foreach($albums as $album){
		$album->makeView();
	}
	
	
?>
<script>  

/**
 * reposition fullview on resize
 */
$(window).resize(function(){
		setUpFullView();
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

function removeCurrentClass(){
	var elems = new Array("#artist-tax li a","#genre-tax li a");
	var i = 0;
	for(i = 0 ; i < elems.length ; i ++ )
	$(elems[i]).each(function(){
		$(this).removeClass("current");
	});
}
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
			        $("#container").html("");
				    $("#container").append(data);
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
				  }
			    });				  
				}
				return false;
} 
/**
 * position the full view and make div heights match
 */
function setUpFullView(){
	
	var position = ( $("#container").width() )/ 2;
	
	position = position - ( ($(".large-item").width() )/2 );
	$(".large-item").css("margin-left",position+"px");
	var rightH = $(".right-bar").height();
	var leftH = $(".left-bar").height();
	var H = Math.max(rightH,leftH);
	$(".right-bar").height(H);
	$(".left-bar").height(H);
	$(".large-item .close").click(restoreAlbums);
} 
/**
 * remove full view and restore albums
 */
function restoreAlbums(){
	fullOpen = false;
	$(".large-item").remove();
	$("#container div").each(
		function(){
			$(this).fadeIn('200');
		}
	);
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
    itemSelector : '.item-wrapper',
    columnWidth : 244,
    isAnimated: true
  });
}
</script>	
<?php get_footer(); ?>