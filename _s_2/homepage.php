<?php 
/**
 * Template name: Homepage Template
 */
?>

<?php 
UI::ajaxheader(); ?>
<div id='homepage'>
	<h1>Articles</h1>
	<div >
		<?php 
		$articles = dbhelper::getLatestArticles(2);
		foreach($articles as $article):
		$article->displayLarge();
		endforeach;	
		?>
	</div>
	<h1>Music</h1>
	<div id='albums-slider'>
		<?php 
		
		$albums = dbhelper::getAlbumsOnly();
		foreach($albums as $album){
			$album->display();
		}
		?>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script src="http://bxslider.com/sites/default/files/jquery.bxSlider.min.js" type="text/javascript"></script>
<script src="/npr/wp-content/themes/_s_2/js/masonry.js"></script>
<script>  
$(window).resize(function(){
	stretchSliders(); 
});
$(document).ready(function(){
	fadeInItems();
	$("#albums-slider").bxSlider({
    auto: false,
    autoControls: true,
    displaySlideQty: 10
  	}); 
  	stretchSliders(); 
});
/**
 * Listener for album slider
 */
$("#albums-slider .sm-item").click(function(){
	var id = $(this).attr("id")
	loadAlbum(id);
}); 
/**
 * Listener for articles slider
 */
$(".large-article-display").click(function(){
	var id = $(this).attr("id");
	loadArticle(id);
}); 
/**
 * load a clicked album
 */
function loadAlbum(id){
	setCurrentMenu("Music");
	var href = "http://jackmahoney.co.nz/npr/?page_id=79&open_album="+id;
	showAjaxLoader();
	$("#main").load(href, null, hideAjaxLoader); 
}
/**
 * load a clicked article
 */
function loadArticle(id){
	setCurrentMenu("Articles");
	var href = "http://jackmahoney.co.nz/npr/?page_id=72&open_article="+id;
	showAjaxLoader();
	$("#main").load(href, null, hideAjaxLoader); 
}

function stretchSliders(){
	var maxW = $("#homepage").width();
	$("#homepage .bx-window").width(maxW);
	$("#homepage .bx-wrapper").width(maxW);
}
function fadeInItems(){
	$(".item-wrapper").animate({"opacity":1}, 0);

}

</script>
<?php 
UI::ajaxfooter(); ?>
