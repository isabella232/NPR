<?php
/**
 * register custom 
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
  
    register_post_type( 'portfolio' , $args );  
}  
register_taxonomy("project-type", array("portfolio"), array("hierarchical" => true, "label" => "Project Types", "singular_label" => "Project Type", "rewrite" => true));
?>  