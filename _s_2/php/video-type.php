<?php
/**
 * register custom post type
 */
add_action('init', 'video_register');    
  
function video_register() {  
    $args = array(  
        'label' => __('Videos'),  
        'singular_label' => __('Video'),  
        'public' => true,  
        'show_ui' => true,  
        'capability_type' => 'post',  
        'hierarchical' => false,  
        'rewrite' => true,  
        'supports' => array('title', 'editor', 'thumbnail')  
       );    
  
    register_post_type( 'video' , $args );  
}  
/**
 * add taxonomy
 */
//register_taxonomy("video-category", array("video"), array("hierarchical" => true, "label" => "Categories", "singular_label" => "Category", "rewrite" => true));  
 
/**
 * add custom fields
 */
add_action("admin_init", "portfolio_meta_box");     
  
function portfolio_meta_box(){  
    add_meta_box("video-meta", "Information", "video_meta_options", "video", "side", "low");  
}    
  
function video_meta_options(){  
        global $post;  
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;  
        $custom = get_post_custom($post->ID);  
        $link = $custom["projLink"][0];  
?>  
<table>
	<tr>
    	<td><label>Track:</label></td><td><input name="video-meta-track" value="<?php echo $link; ?>" /></td>
    </tr>
    <tr> 
    	<td><label>Artists:</label></td><td><input name="video-meta-artists" value="<?php echo $link; ?>" /></td> 
    </tr>
    <tr>
    	<td><label>Album:</label></td><td><input name="video-meta-album" value="<?php echo $link; ?>" /></td>
    </tr>
    <tr> 
    	<td><label>Release:</label></td><td><input name="video-meta-release" value="<?php echo $link; ?>" /></td>
    </tr>
</table>
<?php   
    } 
/**
 * save data
 */
add_action('save_post', 'save_project_link');   
  
function save_project_link(){  
    global $post;    
  
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){  
        return $post_id;  
    }else{  
        update_post_meta($post->ID, "video-meta-track", $_POST["video-meta-track"]);  
        update_post_meta($post->ID, "video-meta-artists", $_POST["video-meta-artists"]);  
        update_post_meta($post->ID, "video-meta-album", $_POST["video-meta-album"]);  
        update_post_meta($post->ID, "video-meta-album", $_POST["video-meta-album"]);  
    }  
}  
?>     