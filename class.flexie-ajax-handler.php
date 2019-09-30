<?php
class Flexie_Ajax_Handler {
	/**
	 * Handles ajax actions through the admin-ajax.php
	 * 
	 * @link       https://flexie.io/
	 * @since      1.0.0
	 *
	 * @package    Flexie-CRM
	 * @author     Flexie CRM
	 */

	static private $initiated = null;

	public static function init(){
        if ( null === self::$initiated ) 
            self :: $initiated = new self;
        return self :: $initiated;
	}
	
	/**
	 * Initializes WordPress Ajax hooks
	 */
	public function Flexie_Ajax_Handler(){
					
		add_action( 'wp_enqueue_scripts', array($this, 'my_enqueue'), 10, 1);
		
		// When add to cart ajax button is clicked
		add_action( 'wp_ajax_flexie_add_to_cart', array($this, 'flexie_ajax_add_to_cart_handler'), 10, 1 );
			
		add_action( 'wp_ajax_nopriv_flexie_add_to_cart', array($this, 'flexie_ajax_add_to_cart_handler'), 10, 1 );
	}

	/**
	 * Register ajax events
	 */
	public function my_enqueue() {
	
		wp_enqueue_script( 'ajax-script', plugin_dir_url(__FILE__) . 'assets/js/flexie_ajax_handler.js', array(), null, true );
	
		wp_localize_script( 'ajax-script', 'flexie_ajax_object',
			array( 
				'ajax_url' =>  admin_url( 'admin-ajax.php' )  
			) 
		);
	}

	/**
	 * Get Cart items
	 */
	public function flexie_ajax_add_to_cart_handler(){
		$cartItems = array();	
		$cart = WC()->cart->get_cart();

		foreach ( $cart as $cart_item ) {
			$product = wc_get_product( $cart_item['product_id'] );
			$quantity = $cart_item['quantity'];
			$cartItem = new stdClass();
			
			$tags = wc_get_product_tag_list( $cart_item['product_id'], '|', '', '' );
			$categories = wc_get_product_category_list( $cart_item['product_id'], '|', '', '' );

			$product_categories = ( $categories ) ? explode( "|", $categories ) : [];
			$product_tags = ( $tags ) ? explode( "|", $tags ) : [];

			//Get Clean tags from array
			$product_tags_clean = array_map(function( $value ) {
				return trim( strip_tags( $value ) );
			}, $product_tags);
		
			//Get Clean categories from array
			$product_categories_clean = array_map(function( $value ) {
				return trim( strip_tags( $value ) );
			}, $product_categories);

			$image_url = wp_get_attachment_url( $product->get_image_id() );

			$cartItem->name				= $product->get_name();
			$cartItem->type				= $product->get_type();
			$cartItem->sku 				= $product->get_sku();
			$cartItem->description 		= $product->get_short_description();
			$cartItem->tags 			= $product_tags_clean;
			$cartItem->categories 		= $product_categories_clean;
			$cartItem->permalink 		= get_permalink( $product->get_id() );
			$cartItem->regular_price 	= $product->get_regular_price();
			$cartItem->sale_price 		= $product->get_sale_price();
			$cartItem->image_url 		= $image_url;
			$cartItem->image_gallery 	= $product->get_gallery_image_ids();
			$cartItem->quantity			= $quantity;

			$cartItems[] = $cartItem;
		}
		
		if ( $cartItems ) {
			if( isset($_COOKIE['track_fx']) ){
					$params = array( 
						'objectType'    => 'cart',
						'metadata'      => $cartItems
					); 
					echo json_encode($params);	
			} else {
				$params = array( 
					'objectType'    => 'cart',
					'metadata'      => $cartItems,
					'email'         => wp_get_current_user()->user_email
				); 
				echo json_encode($params);	
			}
		}
	
		wp_die();
	}
}