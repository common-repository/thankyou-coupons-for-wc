<?php

/**
 * Admin Options Page
 */

namespace WC_Thankyou_Coupon;

class Settings {

	/**
	 * Hook into the WC filters for to add a Settings tab and update our settings
	 */
	public function __construct() {
		 add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_wctc_settings', array( $this, 'settings_tab' ) );
		add_action( 'woocommerce_update_options_wctc_settings', array( $this, 'update_settings' ) );
	}
	/**
	 * Add a settings tab
	 *
	 * @param array $settings_tabs
	 * @return array
	 */
	public function add_settings_tab( $settings_tabs ) {
		$settings_tabs['wctc_settings'] = __( 'Thankyou Coupons', 'thankyou-coupons-for-wc' );
		return $settings_tabs;
	}


	/**
	 * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
	 *
	 * @uses woocommerce_admin_fields()
	 * @uses self::get_settings()
	 */
	public function settings_tab() {
		woocommerce_admin_fields( self::get_settings() );
	}


	/**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 *
	 * @uses woocommerce_update_options()
	 * @uses self::get_settings()
	 */
	public function update_settings() {
		 woocommerce_update_options( self::get_settings() );
	}

	/**
	 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
	 *
	 * @return array Array of settings for @see woocommerce_admin_fields() function.
	 */
	public static function get_settings() {
		 $settings = array(
			 'section_title'     => array(
				 'title' => __( 'Thankyou Coupons for WC', 'thankyou-coupons-for-wc' ),
				 'type'  => 'title',
				 'desc'  => __( 'Want more features, like to send a coupon via email or display the coupon as a popup? Check out the pro version <a href="https://chrisbibby.com.au/plugins/thankyou-coupons-for-woocommerce/" target="_blank">here</a>', 'thankyou-coupons-for-wc' ),
				 'id'    => 'wc_wctc_section_title',
			 ),
			 'wctc_enable'       => array(
				 'title' => __( 'Enable', 'thankyou-coupons-for-wc' ),
				 'type'  => 'checkbox',
				 'desc'  => __( 'Check this box to enable Thankyou Coupons for WC', 'thankyou-coupons-for-wc' ),
				 'id'    => 'wc_wctc_enabled',
				 'css'   => 'width:200px',
			 ),

			 'coupon_type'       => array(
				 'title'    => __( 'Discount type', 'thankyou-coupons-for-wc' ),
				 'type'     => 'select',
				 'desc_tip' => __( 'Select the coupon type you wish to offer', 'thankyou-coupons-for-wc' ),
				 'id'       => 'wc_wctc_coupon_type',
				 'css'      => 'width:200px',
				 'options'  => array(
					 'fixed_cart' => 'Set amount',
					 'percent'    => 'Percent off',
				 ),
			 ),
			 'coupon_amount'     => array(
				 'title'    => __( 'Coupon amount', 'thankyou-coupons-for-wc' ),
				 'type'     => 'number',
				 'desc_tip' => __( 'Value of the coupon', 'thankyou-coupons-for-wc' ),
				 'id'       => 'wc_wctc_coupon_amount',
				 'css'      => 'width:200px',
				 'default'  => 0,
			 ),
			 'free_shipping'     => array(
				 'title' => __( 'Free shipping', 'thankyou-coupons-for-wc' ),
				 'type'  => 'checkbox',
				 'desc'  => sprintf( __( 'Check this box if the coupon grants free shipping. A <a href="%s" target="_blank">free shipping method</a> must be enabled in your shipping zone and be set to require "a valid free shipping coupon" (see the "Free Shipping Requires" setting).', 'thankyou-coupons-for-wc' ), 'https://docs.woocommerce.com/document/free-shipping/' ),
				 'id'    => 'wc_wctc_free_shipping',
				 'css'   => 'width:200px',
			 ),

			 'coupon_limit'      => array(
				 'title'    => __( 'Usage limit per coupon', 'thankyou-coupons-for-wc' ),
				 'type'     => 'number',
				 'desc_tip' => __( 'How many times this coupon can be used before it is void. (leave blank for unlimited)', 'thankyou-coupons-for-wc' ),
				 'id'       => 'wc_wctc_coupon_limit',
				 'css'      => 'width:200px',
			 ),
			 'user_limit'        => array(
				 'title'    => __( 'Usage limit per user', 'thankyou-coupons-for-wc' ),
				 'type'     => 'number',
				 'desc_tip' => __( 'How many times this coupon can be used by a user before it is void. (leave blank for unlimited)', 'thankyou-coupons-for-wc' ),
				 'id'       => 'wc_wctc_user_limit',
				 'css'      => 'width:200px',
			 ),

			 'individual_use'    => array(
				 'title' => __( 'Individual use only', 'thankyou-coupons-for-wc' ),
				 'type'  => 'checkbox',
				 'desc'  => __( 'Check this box if the coupon cannot be used in conjunction with other coupons.', 'thankyou-coupons-for-wc' ),
				 'id'    => 'wc_wctc_individual_use',
				 'css'   => 'width:200px',
			 ),
			 'restrict_creation' => array(
				 'title' => __( 'Restrict creation if existing coupons are used', 'thankyou-coupons-for-wc' ),
				 'type'  => 'checkbox',
				 'desc'  => __( 'Don\'t create a coupon if any coupon codes are used in the order', 'thankyou-coupons-for-wc' ),
				 'id'    => 'wc_wctc_restrict_creation',
				 'css'   => 'width:200px',
			 ),
			 'restrict_creation_paid'   => array(
				'title' => __( 'Restrict creation for unpaid orders', 'thankyou-coupons-for-wc' ),
				'type'  => 'checkbox',
				'desc'  => __( 'Don\'t create a coupon if the order is not paid at the time checkout completes.', 'thankyou-coupons-for-wc' ),
				'id'    => 'wc_wctc_restrict_paid',
				'css'   => 'width:200px',
			),
			 'minimum_spend'     => array(
				 'title'    => __( 'Minimum Spend ', 'thankyou-coupons-for-wc' ),
				 'type'     => 'number',
				 'desc_tip' => __( 'Customers must spend a certain amount to be eligible for the coupon.', 'thankyou-coupons-for-wc' ),
				 'id'       => 'wc_wctc_coupon_min_spend',
				 'css'      => 'width:200px',
				 'default'  => 0,
			 ),
			 'exclude_tax'       => array(
				 'title' => __( 'Exclude Tax from Minimum Spend', 'thankyou-coupons-for-wc' ),
				 'type'  => 'checkbox',
				 'desc'  => __( 'Exclude tax from the minimum spend calculation', 'thankyou-coupons-for-wc' ),
				 'id'    => 'wc_wctc_free_extax',
				 'css'   => 'width:200px',
			 ),
			 'expires_in_days'   => array(
				 'title'    => __( 'Expires in days', 'thankyou-coupons-for-wc' ),
				 'type'     => 'number',
				 'desc_tip' => __( 'How many full days from today the coupon will remain valid for', 'thankyou-coupons-for-wc' ),
				 'id'       => 'wc_wctc_expires_in_days',
				 'css'      => 'width:200px',
				 'default'  => 1,
			 ),
			 'message'           => array(
				 'title'    => __( 'Message', 'thankyou-coupons-for-wc' ),
				 'type'     => 'textarea',
				 'default'  => 'We\'ve created a personal coupon just for you to use on your next order, next time you shop with us use the following code.',
				 'desc_tip' => __( 'The message you would like to appear on the thankyou page', 'thankyou-coupons-for-wc' ),
				 'id'       => 'wc_wctc_message',
				 'css'      => 'width:600px',
			 ),
			 'expiry_message'    => array(
				 'title'    => __( 'Expiry Message', 'thankyou-coupons-for-wc' ),
				 'type'     => 'textarea',
				 'default'  => 'This coupon expires on {date}',
				 'desc_tip' => __( 'The message you would like to appear on the expiry line of the coupon on the thankyou page', 'thankyou-coupons-for-wc' ),
				 'id'       => 'wc_wctc_expiry_message',
				 'css'      => 'width:600px',
			 ),
			 'coupon_colour'     => array(
				 'title'    => __( 'Coupon Colour', 'thankyou-coupons-for-wc' ),
				 'type'     => 'color',
				 'desc_tip' => __( 'The colour of the coupon on thankyou page', 'thankyou-coupons-for-wc' ),
				 'id'       => 'wc_wctc_coupon_color',
				 'css'      => 'width:100px',
				 'default'  => '#3FB1CE',
			 ),
			 'section_end'       => array(
				 'type' => 'sectionend',
				 'id'   => 'wc_wctc_section_end',
			 ),
		 );

		return apply_filters( 'wc_settings_tab_wctc_settings', $settings );
	}

	public static function is_enabled() {
	   if ( 'yes' == get_option( 'wc_wctc_enabled' ) ) {
			return true;
		} else {
		return false;
		}
	}

	public static function get_discount_type() {
		$discount_type = get_option( 'wc_wctc_coupon_type' );

		// Fix for bug just in case some people have the old settings saved in options

		if ( 'fixed-cart' == $discount_type ) {
			return 'fixed_cart';
		}
		return $discount_type;
	}

	public static function get_coupon_amount() {
		return get_option( 'wc_wctc_coupon_amount' );
	}

	public static function is_free_shipping() {
		 if ( 'yes' == get_option( 'wc_wctc_free_shipping' ) ) {
			return true;
		} else {
		return false;
		}
	}
	public static function get_coupon_limit() {
		 return get_option( 'wc_wctc_coupon_limit' );
	}

	public static function get_user_limit() {
	   return get_option( 'wc_wctc_user_limit' );
	}
	public static function is_individual_use() {
		if ( 'yes' == get_option( 'wc_wctc_individual_use' ) ) {
			return true;
		} else {
		return false;
		}
	}

	public static function is_restrict_creation() {
		 if ( 'yes' == get_option( 'wc_wctc_restrict_creation' ) ) {
			return true;
		} else {
		return false;
		}
	}

	public static function is_before_tax() {
		if ( 'yes' == get_option( 'wc_wctc_before_tax' ) ) {
			return true;
		} else {
		return false;
		}
	}

	public static function get_min_spend() {
		return get_option( 'wc_wctc_coupon_min_spend' );
	}

	public static function is_min_spend_ex_tax() {
	  if ( 'yes' == get_option( 'wc_wctc_free_extax' ) ) {
			return true;
		} else {
		return false;
		}
	}

	public static function get_expiry_days() {
	  return get_option( 'wc_wctc_expires_in_days' );
	}
	public static function get_coupon_message() {
	   return get_option( 'wc_wctc_message' );
	}
	public static function get_expiry_message() {
	   return get_option( 'wc_wctc_expiry_message' );
	}

	public static function get_coupon_colour() {
		$coupon_colour = get_option( 'wc_wctc_coupon_color' );
		if ( false == $coupon_colour ) {
			return '#ffffff';
		}
		return $coupon_colour;
	}

	public static function restrict_paid() {
		if ( 'yes' == get_option( 'wc_wctc_restrict_paid' ) ) {
			return true;
		}
		return false;
	}
}
