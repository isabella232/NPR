<?php 
/**
 * Template name: Default Product Template
 */
global $post;
UI::ajaxheader();

$custom = get_post_custom($post->ID);  
$cats_to_display = $custom["default-product-meta"][0];
?>
<div id="lh_sidebar">
	<ul>
	</ul>
		
</div>
<div id="container" class="default_products">
	 
		<div class="product_grid_display group">
		
		</div>

<script> 

$(document).ready(function(){
	loadAppropriate();
});
function loadAppropriate(){
	var categories = '<? echo $cats_to_display;?>';
	console.log("default logged "+categories);
	ajaxLoadProducts(categories);
} 
 
var max_container_height;
/**
 * reposition fullview on resize
 */ 
$(window).resize(function(){
		setUpFullView();
	});
			
var fullOpen = false;

/**
 * hide other products
 */
function hideOtherProducts(id, fromElem){
	console.log("hideOtherProducts");
	$(".item-wrapper").each(function(){
	$(this).remove();
	});
	//showThis(id);
	loadFromInto(fromElem, "#container");
}

/**
 * show a full view
 */		
function showThis(id){
	if(fullOpen == false){
		loadProduct(id);
		fullOpen = true;
		console.log("fullOpen = "+fullOpen);
	}
	else if(fullOpen == true)
	{
		fullOpen = false;
		// $(".large-item").each(function(){
			// $(this).remove();
		// });
		restoreAlbums();
		// $.when($("#container").masonry('reload')).then(enlargen(id));
		// console.log("fullOpen = "+fullOpen);		
	}
	
	
}

function getFullOpen(){
	return fullOpen;
}

function loadProduct(ID){
	showAjaxLoader();
		$.ajax({
		url:        '<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php',
		type:       'post',
		data:       { "action":"loadProduct", "id":ID},
		success: function(data) {
		$("#container").append(data);
		}
	});
	hideAjaxLoader(); 
}



/**
 * make ajax call to load a series of albums
 */
function ajaxLoadProducts(categories){
				if(categories!=""){
					showAjaxLoader();
					$.ajax({
			        url:        '<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php',
			        type:       'post',
			        data:       { "action":"displayProducts", "categories":categories},
			        success: function(data) {
			        $("#container").empty();
			        hideAjaxLoader();
			        //$("#container").append(data);
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
			        data:       { "action":"fetchVideo", "id":id},
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
function restoreProducts(){
	$("#main div").each(function(){
		$(this).remove();
	});
	$("#main").load('<? global $post; echo get_bloginfo('url')."?page_id=$post->ID";?>', null, hideAjaxLoader); 
}			

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

if(fullOpen==false)
  $('#container').masonry({
    // options
    itemSelector : '.item-wrapper', 
    singleMode : true,
    isAnimated: true
  });

} 



</script>	
<?php 
UI::ajaxfooter();
 ?>