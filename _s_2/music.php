<?php 
/**
 * Template name: Music Template
 */
get_header(); ?>
<div id="lh_sidebar">
	<ul>
		<?php 
		$labels = array('All','Hip Hop','Rock','Country','Up & Coming');
		foreach($labels as $label){
			echo "<li><a href='#'>$label</a></li>";
		}
		?>
	</ul>
</div>
<div id="container">
	
		<div class="product_grid_display group">
		
		</div>
	 
	<?php
	
$args = array('post_type' => 'wpsc-product' , 'numberposts' => 2000, 'wpsc_product_category'  => 'music'
	);
	$the_query = new WP_Query( $args );
	//exit('<pre>'.print_r($the_query,1).'</pre>');
	while ( $the_query->have_posts() ) : $the_query->the_post();
		echo "title = ".get_the_title()."<br/>";
		$product_cat = $the_query->query_vars['wpsc_product_category'];
		echo "category = $product_cat";
		exit('<pre>'.print_r($product_cat,1).'</pre>');
	endwhile;
	
	
?>
	
<?php get_footer(); ?>