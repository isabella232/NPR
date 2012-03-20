<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package _s
 * @since _s 1.0
 */
?>

	</div><!-- #main -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		
		<div class="site-info">
			<?php player::getPlayer(); ?>
		</div><!-- .site-info -->
		<?php
		CartHelper::getCartIcon(); 
		?>
	</footer><!-- .site-footer .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/jQuery.jPlayer.2.1.0/jquery.jplayer.min.js"></script>
<link type="text/css" REL=StyleSheet href="<?php bloginfo('template_directory');?>/js/jQuery.jPlayer.2.1.0/css/styles.css"/>

<script>
/**
 * listen for product form submissions
 */
$("form.product_form").live("submit", function(){
$.ajax({
		        url:        '<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php',
		        type:       'post',
		        data:       { "action":"fetchCartCount"},
		        success: function(data) {
		        		$("#cart-count .text").slideUp('slow', function(){
		        			$("#cart-count .text").remove();
		        			$("#cart-count").append("<div class='text'></div>");
		        			$("#cart-count .text").text(data);
		        		});
		        		
			    		
				    }
				 });
});	

/**
 * player scripts
 */
function updatePlayer(name, artist, guid){
	    	var player = $("#jquery_jplayer_1");
	    	lcdTrack = $("#jp_screen .player_track"); //the track display
	    	lcdArtist = $("#jp_screen .artist"); //the artist display
	    	lcdTrack.html("Track: "+name);
	    	lcdArtist.html("Artists: "+artist);
	    	player.jPlayer({
	        ready: function () { 
	          $(this).jPlayer("setMedia", { 
	          	mp3: guid
	          	
	          }); 
	          $(this).jPlayer("play", 0);
	        },
	        swfPath: "/js",
	        supplied: "mp3",
	    
	      }); 
	      player.jPlayer("setMedia", { 
	          	mp3: guid
	          }); 
	      player.jPlayer("play", 0);
	    }

</script>
</body>
</html>