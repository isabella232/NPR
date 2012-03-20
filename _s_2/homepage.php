<?php 
/**
 * Template name: Homepage Template
 */
get_header(); ?>
<div>
	
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script src="http://bxslider.com/sites/default/files/jquery.bxSlider.min.js" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#article-slider').bxSlider();
    rejig();
  });
  
  $(window).resize(function() {
	rejig();
	});	
  
  function rejig(){
  	var w = getSliderWidth();
  	var h = getSliderHeight();
    $(".bx-window").width(w); 
    $(".bx-wrapper").width(w); 
    $(".bx-next").appendTo("#slider-wrapper");
    $(".bx-prev").prependTo("#slider-wrapper");
    $(".bx-prev").height(h);
    $(".bx-next").height(h);
  }
  
  function getSliderHeight(){
  	return $("#slider-wrapper").height();
  }
  
  function getWindowWidth(){
  	return $(window).width();
  }
  
  
  function getSliderWidth(){
  	var buttonWidth = 40;
  	return getWindowWidth() - ( 2 * $(".bx-prev").width()) - 20;
  }
</script>


<script src="/npr/wp-content/themes/_s_2/js/masonry.js"></script>
<script>  

// $(document).ready(function(){
	// var w = $(window).width() - 240;
  // $('#container').width(w);
	// arrange();
// });
// 
// $(window).resize(function() {
	// var w = $(window).width() - 240;
  // $('#container').width(w);
	// arrange();
// });	
// 
// function arrange(){
  // $('#container').masonry({
    // // options
    // itemSelector : '.item',
    // columnWidth : 244,
    // isAnimated: true
  // });
// }
</script>
<?php UI::ajaxfooter(); ?>