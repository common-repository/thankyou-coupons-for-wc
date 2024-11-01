<?php

namespace WC_Thankyou_Coupon;

/**
 * The main plugin class
 */
class Plugin
{

	/**
	 * Fire up our plugin classes
	 */
	public function __construct()
	{
		add_action('wp_enqueue_scripts', array($this, 'enqueue_css'));
		add_action('before_woocommerce_init', array($this, 'hpos_support'));
		new Coupon;
		new Settings;
	}

	public function enqueue_css()
	{
		if (is_order_received_page()) {
			wp_enqueue_style('wctc-styles', WCTY_COUPON_PLUGIN_URL . 'assets/css/wctc.css', WCTY_PLUGIN_VERSION);
		}
	}

	public function hpos_support()
	{
		if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', 'thankyou-coupons-for-wc/thankyou-coupons-for-wc.php', true);
		}
	}
}
