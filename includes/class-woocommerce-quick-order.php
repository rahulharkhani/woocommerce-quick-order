<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://profiles.wordpress.org/rahulharkhani/
 * @since      1.0.0
 *
 * @package    Woocommerce_Quick_Order
 * @subpackage Woocommerce_Quick_Order/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woocommerce_Quick_Order
 * @subpackage Woocommerce_Quick_Order/includes
 * @author     Rahul Harkhani <rahul.harkhani11@gmail.com>
 */
class Woocommerce_Quick_Order {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woocommerce_Quick_Order_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WOOCOMMERCE_QUICK_ORDER_VERSION' ) ) {
			$this->version = WOOCOMMERCE_QUICK_ORDER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'woocommerce-quick-order';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		// check WC version
		add_action('admin_notices', [$this,'woocommerce_version_not_compatible']);

		// check PHP version
		add_action('admin_notices', [$this,'php_version_not_compatible']);

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woocommerce_Quick_Order_Loader. Orchestrates the hooks of the plugin.
	 * - Woocommerce_Quick_Order_i18n. Defines internationalization functionality.
	 * - Woocommerce_Quick_Order_Admin. Defines all hooks for the admin area.
	 * - Woocommerce_Quick_Order_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-quick-order-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-quick-order-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-quick-order-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-quick-order-public.php';

		$this->loader = new Woocommerce_Quick_Order_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woocommerce_Quick_Order_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woocommerce_Quick_Order_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woocommerce_Quick_Order_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woocommerce_Quick_Order_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
     * Deactivate self if dependencies are not satisfied
     *
     * @since    1.0.0
     */
	public function deactivate_woocommerce_quick_order_self(){
		deactivate_plugins( WOOCOMMERCE_QUICK_ORDER_PLUGIN_BASE );
		unset($_GET['activate']);
	}

	/**
     * Display notice if Woocommerce version doesn't match
     *
     * @since    1.0.0
     */
    public function woocommerce_version_not_compatible() { 
		if ( in_array( 'woocommerce/woocommerce.php', get_option('active_plugins') ) ) {
			if ( !version_compare( WC_VERSION, WOOCOMMERCE_QUICK_ORDER_REQ_WC_VERSION, '>=' ) ) {
				$message = sprintf( esc_html__( 'GST for WooCommerce requires Woocommerce version %s+, plugin is currently NOT RUNNING.', WOOCOMMERCE_QUICK_ORDER_TEXT_DOMAIN ), WOOCOMMERCE_QUICK_ORDER_REQ_WC_VERSION );
				$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
				echo wp_kses_post( $html_message );
				$this->deactivate_woocommerce_quick_order_self();
			}
		}else{
			$this->deactivate_woocommerce_quick_order_self();
		}
    }
	
	/**
     * Display notice if PHP version doesn't match
     *
     * @since    1.0.0
     */
    public function php_version_not_compatible() { 
		if( !version_compare( PHP_VERSION, WOOCOMMERCE_QUICK_ORDER_REQ_PHP_VERSION, '>=' ) ){
			$message = sprintf( esc_html__( 'GST for WooCommerce requires PHP version %s+, current PHP version is %s', WOOCOMMERCE_QUICK_ORDER_TEXT_DOMAIN ), WOOCOMMERCE_QUICK_ORDER_REQ_PHP_VERSION, PHP_VERSION );
			$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
			echo wp_kses_post( $html_message );
			$this->deactivate_woocommerce_quick_order_self();
		}
    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woocommerce_Quick_Order_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
