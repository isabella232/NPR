<?php

class nprcomments{
		
	public static function getCommentScript(){
		?>
		<!-- added by nprcomments-class.php -->
		<script>
			$(document).ready(function(){
				setupcomments();
			});
			/**
			 * comments form
			 */
			function setupcomments(){
				$("#commentform").submit(function(event){
					event.preventDefault();
					var serials = $(this).serialize();
					var url = '<?php bloginfo('url');?>/wp-comments-post.php/?'+serials  ;
					$.ajax({
					  url: url,
					  context: document.body
					}).done(function() { 
					  $("#commentform").html("<p>Comment submitted</p>");
					});
				});
			}
		</script>
		<?php 
	}
}

?>