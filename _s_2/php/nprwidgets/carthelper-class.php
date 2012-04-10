<?php

class CartHelper{
	public static function getCartIcon(){
		$checkout = self::getCheckoutURL();
		$count = wpsc_cart_item_count();
		echo "
		<div id='cart-icon-wrapper'> 
			<a id='cart-icon-button-link' title='Check Out' target='_blank' href='$checkout'>
			<div id='cart-count'>
				<div id='cart-count-text' class='text'>
				$count
				</div>
			</div>
			 
			<div id='cart-icon'></div>
			</a>
		</div>
		 ";?>
		 <script>
		 var button = $("#cart-icon-button-link");
		 button.click(function(e){
		 	e.preventDefault();
	        var display = $("#cart-count-text");
		 	var count = display.text(); 
		
			if(count>0){ 
				alert("opening cart");
				window.open(button.attr('href'), button.attr('title')');
				return false;
			}
			else{
				alert("Sorry your cart is empty");
				return true;
			}
						 	
		 });
		 </script>
		 <?php
	}
	
	public static function echoBuyNow($id){
		?> 
		<div class="wpsc_buy_button_container">
           <?php if(wpsc_product_external_link($id) != '') : ?>
           <?php $action = wpsc_product_external_link( $id ); ?>
           <input class="wpsc_buy_button" type="submit" value="<?php echo wpsc_product_external_link_text( $id, __( 'Buy Now', 'wpsc' ) ); ?>" onclick="return gotoexternallink('<?php echo $action; ?>', '<?php echo wpsc_product_external_link_target( $id ); ?>')">
           <?php else: ?>
          <input type="submit" value="<?php _e('Add To Cart', 'wpsc'); ?>" name="Buy" class="wpsc_buy_button" id="product_<?php echo $id; ?>_submit_button"/>
           <?php endif; ?>
          <div class="wpsc_loading_animation">
           <img title="Loading" alt="Loading" src="<?php echo wpsc_loading_animation_url(); ?>" />
           <?php _e('Updating cart...', 'wpsc'); ?>
          </div><!--close wpsc_loading_animation-->
         </div><!--close wpsc_buy_button_container-->
		<?
	}
	
	public static function getBuyNow($id){

		$price = get_post_meta( $id, '_wpsc_price', true );
	
		 $action =  wpsc_product_external_link(wpsc_the_product_id());
         $action = htmlentities(wpsc_this_page_url(), ENT_QUOTES, 'UTF-8' );
		$buynow = '<form class="product_form" enctype="multipart/form-data" action="'.$action.'" method="post" name="product_'.$id.'" id="product_'.$id.'">
                                                 
       <!-- THIS IS THE QUANTITY OPTION MUST BE ENABLED FROM ADMIN SETTINGS -->
       
       <div class="wpsc_product_price">
                                  <p class="pricedisplay product_'.$id.'">Price: <span id="product_price_'.$id.'" class="currentprice pricedisplay">'.$price.'</span></p>
                  
         <!-- multi currency code -->
                  
                   <p class="pricedisplay" style="display:none;">Shipping:<span class="pp_price"><span class="pricedisplay">'.$price.'</span></span></p>
                
               </div><!--close wpsc_product_price-->
       
       <input type="hidden" value="add_to_cart" name="wpsc_ajax_action">
       <input type="hidden" value="'.$id.'" name="product_id">
     
       <!-- END OF QUANTITY OPTION -->
                        <div class="wpsc_buy_button_container">
          <div class="wpsc_loading_animation">
           <img title="Loading" alt="Loading" src="http://jackmahoney.co.nz/npr/wp-content/plugins/wp-e-commerce/wpsc-theme/wpsc-images/indicator.gif">
           Updating cart…          </div><!--close wpsc_loading_animation-->
                     <input type="submit" value="Add To Cart" name="Buy" class="wpsc_buy_button" id="product_'.$id.'_submit_button">
                      </div><!--close wpsc_buy_button_container-->
                      <div class="entry-utility wpsc_product_utility">
               </div>
             </form>';
		
		return $buynow;
	}
	
	public static function getCheckoutURL(){
		return get_option('shopping_cart_url');
	}
}
?>