<?php
class Flexie {
	/**
	 * Registers Flexie Tracking Script and defined WooCommerce Webhooks
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
	 * Initializes WordPress hooks
	 */
	public function Flexie(){
		// Add Flexie Tracking script if subdomain is set in Flexie CRM Wordpress plugin.
		if (get_option('flexie_subdomain') != null && get_option('flexie_subdomain'))  {

			add_action('wp_enqueue_scripts', array($this,'load_flexie_script'), 10, 1);	
		
			if(isset($_COOKIE['track_fx'])){
				$this->load_tracking();
			} else {
				if (wp_get_current_user()->user_email != null) {
					if (get_option('flexie_track_pagehit') == "1") {
						LoadTrack::trackPageHit( true );
					} 
					$this->load_tracking();
				}
			} 
		}
	}
	
	/**
	 * Set WooCommerce Webhook Actions
	 */
	private function load_tracking(){
		$track_product = get_option('flexie_track_product') == "1" ? true : false;
		$track_cart = get_option('flexie_track_cart') == "1" ? true : false;
		$track_order = get_option('flexie_track_order') == "1" ? true : false;
		add_action( 'wc_add_to_cart_message', array($this, 'flexie_action_woocommerce_add_to_cart'), 10, 1 );

		if ( $track_product ) { 
			add_action( 'woocommerce_single_product_summary',array( $this, 'flexie_action_woocommerce_single_product_summary' ), 10, 1 );
		}
		if ( $track_cart ) {
			add_action( 'woocommerce_cart_contents', array( $this, 'flexie_action_woocommerce_cart_contents' ), 10, 1 );	
			add_action( 'woocommerce_checkout_order_review', array( $this, 'flexie_action_woocommerce_checkout_order_review' ), 10, 1 );
		} 		
		if ( $track_order ) {
			add_action( 'woocommerce_thankyou', array( $this, 'flexie_action_woocommerce_thankyou' ), 10, 1 ); 		
		}
	}
	
	public function flexie_action_woocommerce_add_to_cart(){
		var_dump("woocommerce_add_cart_item");
	}
	/**
	 * Loads Flexie Tracking Script
	 */
	public function load_flexie_script(){
		wp_enqueue_script( 'flexie_tracking_script', plugin_dir_url(__FILE__).'assets/js/flexie_tracking_script.js', array(), null, false );		
		wp_localize_script( 'flexie_tracking_script', 'flexie_tracking_script_object', 
			array( 
			'domain'	=> 'https://'. get_option('flexie_subdomain').'.flexie.io',
			'trackAll'	=> ( get_option('flexie_track_pagehit') == "1" ? true : false)
			)
		);
	} 
	
	/**
	 * Get Single Product details from WooCommerce
	 * Page: Product Detail
	 */
	public function flexie_action_woocommerce_single_product_summary(){
		global $product;
		
		$productObj = new stdClass();

		$tags = wc_get_product_tag_list( $product->get_id(), '|', '', '' );
		$categories = wc_get_product_category_list( $product->get_id(), '|', '', '' );

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
		$stock_quantity = $product->get_stock_quantity(); 
		
		$productObj->name				= $product->get_name();
		$productObj->tags				= $product_tags_clean;
		$productObj->type				= $product->get_type();
		$productObj->sku 				= $product->get_sku();
		$productObj->description 		= $product->get_short_description();
		$productObj->categories 		= $product_categories_clean;
		$productObj->permalink 			= get_permalink( $product->get_id() );
		$productObj->regular_price 		= floatval( $product->get_regular_price() );
		$productObj->sale_price 		= floatval( $product->get_sale_price() );
		$productObj->image_url 			= ( $image_url ) ? $image_url : '';
		$productObj->image_gallery 		= $product->get_gallery_image_ids();
		$productObj->stock_quantity 	= ( $stock_quantity ) ? $stock_quantity : 0;
		$productObj->stock_status 		= $product->get_stock_status();

		if ( $productObj ) {
			if( isset($_COOKIE['track_fx']) ){
				LoadTrack::trackMetaData( $productObj, 'product', true );
			} else {
				LoadTrack::trackMetaData( $productObj, 'product', false );
			}
		}
	}

	/**
	 * Get Cart Content item details from WooCommerce
	 * Page: Cart
	 */
	public function flexie_action_woocommerce_cart_contents(){
		$cartItems = array();	
		
		$cart = WC()->cart->get_cart();

		foreach ( $cart as $cart_item ) {
			
			$product = wc_get_product( $cart_item['product_id'] );
			
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

			$cartItems[] = $cartItem;
		}
		
		if ( $cartItems ) {
			if( isset($_COOKIE['track_fx']) ){
				LoadTrack::trackMetaData( $cartItems, 'cart', true );
			} else {
				LoadTrack::trackMetaData( $cartItems, 'cart', false );
			}
		}
	}

	/**
	 * Get Cart Content during checkout from WooCommerce
	 * Page: Checkout
	 */
	public function flexie_action_woocommerce_checkout_order_review(){
		$cartItems = array();	
		
		$cart = WC()->cart->get_cart();
				
		foreach ( $cart as $cart_item ) {
			$product = wc_get_product( $cart_item['product_id'] );
						
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
			$cartItem->tags				= $product_tags_clean;
			$cartItem->categories 		= $product_categories_clean;
			$cartItem->permalink 		= get_permalink( $product->get_id() );
			$cartItem->regular_price 	= $product->get_regular_price();
			$cartItem->sale_price 		= $product->get_sale_price();
			$cartItem->image_url 		= $image_url;
			$cartItem->image_gallery 	= $product->get_gallery_image_ids();

			$cartItems[] = $cartItem;
		}
		
		if ( $cartItems ) {
			if( isset($_COOKIE['track_fx']) ){
				LoadTrack::trackMetaData( $cartItems, 'checkout', true );	
			} else {
				LoadTrack::trackMetaData( $cartItems, 'checkout', false );	
			}
		}	
	}

	/**
	 * Get Order Details when an order is placed from WooCommerce
	 * Page: Thank you page
	 */
	public function flexie_action_woocommerce_thankyou( $order_id ){
		$order = wc_get_order( $order_id );
		
		$orderObj = new stdClass();

		// Get Order Totals $0.00
		$orderObj->id 					= $order->get_id();
		$orderObj->total 				= $order->get_formatted_order_total();
		$orderObj->currency 			= $order->get_currency();
		$orderObj->total 				= $order->get_total();
		$orderObj->total_discount 		= $order->get_total_discount();
		// Get Order Customer, Billing & Shipping Addresses, Date Paid, Customer Note
		$orderObj->shipping_method 		= $order->get_shipping_method();
		$orderObj->date_paid 			= $order->get_date_paid();
		$orderObj->customer_id 			= $order->get_customer_id();
		$orderObj->billing_first_name 	= $order->get_billing_first_name();
		$orderObj->billing_last_name 	= $order->get_billing_last_name();
		$orderObj->billing_company 		= $order->get_billing_company();
		$orderObj->billing_address 		= $order->get_formatted_billing_address();
		$orderObj->billing_city 		= $order->get_billing_city();
		$orderObj->billing_state 		= $order->get_billing_state();
		$orderObj->billing_postcode 	= $order->get_billing_postcode();
		$orderObj->billing_country 		= $order->get_billing_country();
		$orderObj->billing_email 		= $order->get_billing_email();
		$orderObj->billing_phone 		= $order->get_billing_phone();
		$orderObj->shipping_first_name 	= $order->get_shipping_first_name();
		$orderObj->shipping_last_name 	= $order->get_shipping_last_name();
		$orderObj->shipping_company 	= $order->get_shipping_company();
		$orderObj->shipping_address 	= $order->get_formatted_shipping_address();
		$orderObj->shipping_city		= $order->get_shipping_city();
		$orderObj->shipping_state 		= $order->get_shipping_state();
		$orderObj->shipping_postcode 	= $order->get_shipping_postcode();
		$orderObj->shipping_country 	= $order->get_shipping_country();
		$orderObj->url 					= $order->get_checkout_order_received_url();
		$orderObj->note 				= $order->get_customer_note();
		
		if ( $orderObj )
		{
			if( isset($_COOKIE['track_fx']) ){
				LoadTrack::trackMetaData( $orderObj, 'order_received', true );
			} else {
				LoadTrack::trackMetaData( $orderObj, 'order_received', false );
			}
		}
	}
}

