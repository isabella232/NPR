<?php
class player_track{
	public $guid;
	public $title;
	public $ID;
	public $parent;
}

class player {
	public static function getPlayer(){
		//wpsc_add_mp3_preview();
		self::getPlayer2(); 
	}
	
	
	public static function getPlayer2() {
		
		echo '
		<div id="player-wrapper">
		<div id="jp_screen">
				<h3 class="player_track">player_track: The Beat</h3>
				<h3 class="artist">Artist: Jack Mahoney</h3>
		</div>
		<!--container for everything-->
	<div id="jp_container_1" class="jp-video jp-video-360p">
	
		<!--container in which our video will be played-->
		<div id="jquery_jplayer_1" class="jp-jplayer"></div>
		
		<!--main containers for our controls-->
		<div class="jp-gui">
		    <div class="jp-interface">
		        <div class="jp-controls-holder">
		
					<!--play and pause buttons-->
				    <a href="javascript:;" class="jp-play" tabindex="1">play</a>
				    <a href="javascript:;" class="jp-pause" tabindex="1">pause</a>
				    <span class="separator sep-1"></span>
				 
					<!--progress bar-->
				    <div class="jp-progress">
				        <div class="jp-seek-bar">
							<div class="jp-play-bar"><span></span></div>
						</div>
				    </div>
				 
				    <!--time notifications-->
				    <div class="jp-current-time"></div>
				    <span class="time-sep">/</span>
				    <div class="jp-duration"></div>
				    <span class="separator sep-2"></span>
				 
				    <!--mute / unmute toggle-->
				    <a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a>
				    <a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a>
				 
				    <!--volume bar-->
				    <div class="jp-volume-bar">
				        <div class="jp-volume-bar-value"><span class="handle"></span></div>
				    </div>
				    <span class="separator sep-2"></span>
				 
				    <!--full screen toggle-->
				    <a href="javascript:;" class="jp-full-screen" tabindex="1" title="full screen">full screen</a>
				    <a href="javascript:;" class="jp-restore-screen" tabindex="1" title="restore screen">restore screen</a>
				
		        </div><!--end jp-controls-holder-->
		    </div><!--end jp-interface-->
		</div><!--end jp-gui-->
		
		<!--unsupported message-->
		<div class="jp-no-solution">
		    <span>Update Required</span>
		    Here\'s a message which will appear if the video isn\'t supported. A Flash alternative can be used here if you fancy it.
		</div>
	
	</div><!--end jp_container_1-->
	</div>
		';
		
		
	?>
	
 	<script type="text/javascript">
	    $(document).ready(function(){
	      $("#jquery_jplayer_1").jPlayer({
	        ready: function () { 
	          $(this).jPlayer("setMedia", {
	          	mp3: '<?php echo $player_tracks[0]->guid; ?>'
	          });
	        },
	        swfPath: "/js",
	        supplied: "mp3"
	      });
	    });
	    
	    function updatePlayer(name, artist, guid){
	    	var player = $("#jquery_jplayer_1");
	    	lcdTrack = $("#jp_screen .player_track"); //the track display
	    	lcdArtist = $("#jp_screen .artist"); //the artist display
	    	lcdTrack.html("Track: "+name);
	    	lcdArtist.html("Artists: "+artist);
	    	$("#jquery_jplayer_1").jPlayer({
	        ready: function () { 
	          $(this).jPlayer("setMedia", { 
	          	mp3: guid
	          	
	          }); 
	          $(this).jPlayer("play", 0);
	        },
	        swfPath: "/js",
	        supplied: "mp3",
	    
	      }); 
	       $("#jquery_jplayer_1").jPlayer("setMedia", { 
	          	mp3: guid
	          	
	          }); 
	          $("#jquery_jplayer_1").jPlayer("play", 0);
	    }
	</script>
	
	<?php
	}

}
