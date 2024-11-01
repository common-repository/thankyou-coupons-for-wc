<?php

/**
 * Plugin Name:     Thankyou Coupons for WC
 * Description:     Generates a personalised dynamic coupon on the WooCommerce Thankyou pages
 * Author:          Chris Bibby
 * Author URI:      https://chrisbibby.com.au
 * Plugin URI       https://chrisbibby.com.au/plugins/thankyou-coupons-for-woocommerce/
 * Version:         2.3.0
 * WC requires at least: 3.7.0
 * WC tested up to: 8.4.0
 *
 * @package         WC Thankyou Coupon
 */




namespace WC_Thankyou_Coupon;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

require __DIR__ . '/vendor/autoload.php';

define('WCTY_COUPON_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WCTY_COUPON_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('WCTY_PLUGIN_VERSION', '2.0.8');

/**
 * Based on the loader from Skyverge WC Plugin Framework https://github.com/skyverge/wc-plugin-framework
 *
 * Checks WP, WC, Plugins and PHP compatibility and bootstraps the plugin
 */

class WC_Thankyou_Coupon_Loader
{




	const MINIMUM_WP_VERSION  = '4.7';
	const MINIMUM_WC_VERSION  = '3.7.0';
	const MINIMUM_PHP_VERSION = '7.2';
	const PLUGIN_NAME         = 'Thankyou Coupons for WC';
	const PLUGIN_SLUG         = 'thankyou-coupons-for-wc';

	/**
	 * Instance of the loader
	 *
	 * @var object
	 */
	protected static $instance;

	/**
	 * Notices
	 *
	 * @var array
	 */
	protected $notices = array();

	/**
	 * Loader constructor
	 */
	public function __construct()
	{
		register_activation_hook(__FILE__, array($this, 'activation_check'));
		register_deactivation_hook(__FILE__, array($this, 'cleanup_options'));
		add_action('admin_init', array($this, 'check_environment'));
		add_action('admin_init', array($this, 'add_plugin_notices'));
		add_action('admin_notices', array($this, 'admin_notices'), 15);

		if ($this->is_environment_compatible()) {
			add_action('plugins_loaded', array($this, 'init_plugin'));
		}
	}

	/**
	 * Cloning instances is forbidden due to singleton pattern.
	 */
	public function __clone()
	{
		_doing_it_wrong(__FUNCTION__, sprintf('You cannot clone instances of %s.', get_class($this)), '1.2.0');
	}
	/**
	 * Unserializing instances is forbidden due to singleton pattern.
	 */
	public function __wakeup()
	{
		_doing_it_wrong(__FUNCTION__, sprintf('You cannot unserialize instances of %s.', get_class($this)), '1.0.0');
	}

	/**
	 * Initialise the plugin and run it
	 *
	 * @return void
	 */
	public function init_plugin()
	{
		if (!$this->plugins_compatible()) {
			return;
		}

		// Fire it up
		new Plugin();
	}

	/**
	 * Activation Check
	 *
	 * @return void
	 */
	public function activation_check()
	{
		if (!$this->is_environment_compatible()) {
			$this->deactivate_plugin();
			wp_die(self::PLUGIN_NAME . ' could not be activated. ' . $this->get_environment_message());
		}
	}

	/**
	 * Checks the environment on loading WordPress, just in case the environment changes after activation.
	 */
	public function check_environment()
	{
		if (!$this->is_environment_compatible() && is_plugin_active(plugin_basename(__FILE__))) {
			$this->deactivate_plugin();
			$this->add_admin_notice('bad_environment', 'error', self::PLUGIN_NAME . ' has been deactivated. ' . $this->get_environment_message());
		}
	}

	/**
	 * Adds notices for out-of-date WordPress, WooCommerce, and / or Memberships versions.
	 */
	public function add_plugin_notices()
	{
		if (!$this->is_wp_compatible()) {
			$this->add_admin_notice(
				'update_wordpress',
				'error',
				sprintf(
					'%s is not active, as it requires WordPress version %s or higher. Please %supdate WordPress &raquo;%s',
					'<strong>' . self::PLUGIN_NAME . '</strong>',
					self::MINIMUM_WP_VERSION,
					'<a href="' . esc_url(admin_url('update-core.php')) . '">',
					'</a>'
				)
			);
			$this->deactivate_plugin();
		}
		if (!$this->is_wc_compatible()) {
			$this->add_admin_notice(
				'update_woocommerce',
				'error',
				sprintf(
					'%s is not active, as it requires WooCommerce version %s or higher. Please %supdate WooCommerce &raquo;%s',
					'<strong>' . self::PLUGIN_NAME . '</strong>',
					self::MINIMUM_WC_VERSION,
					'<a href="' . esc_url(admin_url('update-core.php')) . '">',
					'</a>'
				)
			);
			$this->deactivate_plugin();
		}
	}

	/**
	 * Determines if the required plugins are compatible.
	 *
	 * @return bool
	 */
	protected function plugins_compatible()
	{
		return $this->is_wp_compatible() && $this->is_wc_compatible();
	}

	/**
	 * Determines if the WordPress version is compatible.
	 *
	 * @return bool
	 */
	protected function is_wp_compatible()
	{
		if (!self::MINIMUM_WP_VERSION) {
			return true;
		}
		return version_compare(get_bloginfo('version'), self::MINIMUM_WP_VERSION, '>=');
	}
	/**
	 * Determines if the WooCommerce version is compatible.
	 *
	 * @return bool
	 */
	protected function is_wc_compatible()
	{
		if (!self::MINIMUM_WC_VERSION) {
			return true;
		}
		return defined('WC_VERSION') && version_compare(WC_VERSION, self::MINIMUM_WC_VERSION, '>=');
	}
	/**
	 * Deactivates the plugin.
	 */
	protected function deactivate_plugin()
	{
		deactivate_plugins(plugin_basename(__FILE__));
		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}
	}

	/**
	 * Clean up options after deactivation
	 *
	 * @return void
	 */
	public function cleanup_options()
	{
		$options = array(
			'wc_wctc_enabled',
			'wc_wctc_coupon_type',
			'wc_wctc_free_shipping',
			'wc_wctc_coupon_amount',
			'wc_wctc_user_limit',
			'wc_wctc_coupon_limit',
			'wc_wctc_individual_use',
			'wc_wctc_before_tax',
			'wc_wctc_expires_in_days',
			'wc_wctc_message',
			'wc_wctc_coupon_color',
			'wc_wctc_expiry_message',
			'wc_wctc_restrict_creation',
		);
		foreach ($options as $option) {
			delete_option($option);
		}
	}
	/**
	 * Adds an admin notice to be displayed.
	 *
	 * @param string $slug message slug
	 * @param string $class CSS classes
	 * @param string $message notice message
	 */
	public function add_admin_notice($slug, $class, $message)
	{
		$this->notices[$slug] = array(
			'class'   => $class,
			'message' => $message,
		);
	}
	/**
	 * Displays any admin notices added with \WC_Remove_Product_Sorting_Loader::add_admin_notice()
	 */
	public function admin_notices()
	{
		foreach ((array) $this->notices as $notice_key => $notice) {
			echo "<div class='" . esc_attr($notice['class']) . "'><p>";
			echo wp_kses($notice['message'], array('a' => array('href' => array())));
			echo '</p></div>';
		}
	}
	/**
	 * Determines if the server environment is compatible with this plugin.
	 *
	 * Override this method to add checks for more than just the PHP version.
	 *
	 * @return bool
	 */
	protected function is_environment_compatible()
	{
		return version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=');
	}
	/**
	 * Gets the message for display when the environment is incompatible with this plugin.
	 *
	 * @return string
	 */
	protected function get_environment_message()
	{
		$message = sprintf('The minimum PHP version required for this plugin is %1$s. You are running %2$s.', self::MINIMUM_PHP_VERSION, PHP_VERSION);
		return $message;
	}

	/**
	 * Gets the single instance o the loader
	 *
	 * @return void
	 */
	public static function get_instance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
WC_Thankyou_Coupon_Loader::get_instance();
