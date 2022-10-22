<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/rahulharkhani/
 * @since      1.0.0
 *
 * @package    Woocommerce_Quick_Order
 * @subpackage Woocommerce_Quick_Order/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Quick_Order
 * @subpackage Woocommerce_Quick_Order/public
 * @author     Rahul Harkhani <rahul.harkhani11@gmail.com>
 */
class Woocommerce_Quick_Order_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		/**
		 * Add short-code for show book search facility on page/post.
		 */
		add_shortcode( 'woo-quick-order', [$this,'woo_quick_order_shortcode'] );

		/**
		 * Create WooCommerce Order programmatically function.
		 */
		add_action( 'wp_ajax_woo_quick_order_create', [$this,'woo_quick_order_create']);
		add_action( 'wp_ajax_nopriv_woo_quick_order_create', [$this,'woo_quick_order_create']);

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Quick_Order_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Quick_Order_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-quick-order-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Quick_Order_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Quick_Order_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-quick-order-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the woo-quick-order shortcode for the display multi-step form to order in the site.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public static function woo_quick_order_shortcode() {

		$productLists = Woocommerce_Quick_Order_Public::get_product_list();

		// Get multi-step form HTML data.
		ob_start();

		include 'partials/multi-step-order-from.php';

		$multi_step_order_html = ob_get_contents();

		ob_clean();

		return $multi_step_order_html;

	}

	/**
	 * Create the function for the return product list data on the site.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public static function get_product_list() {

		global $wpdb;
		$product_list_data = '';
		$args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => -1
		);
		$productLists = new WP_Query( $args );
		return $productLists;
	}

	/**
	 * Create WooCommerce Order programmatically function.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	function woo_quick_order_create() {
		global $wpdb, $woocommerce;
		$userid = $_POST['userid'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$address = $_POST['address'];
		$postcode = $_POST['postcode'];
		$city = $_POST['city'];
		$country = $_POST['country'];
		$productIds = $_POST['productIds'];

		$order = wc_create_order();

		// set customer to order
		if($userid != 0) {
			$order->set_customer_id( $userid );
		}

		// add products
		foreach($productIds as $product) {
			//$order->add_product( wc_get_product( $product->id ), 1 );
			$order->add_product( wc_get_product( $product['id'] ) );
		}

		// add billing and shipping addresses
		$address = array(
			'first_name' => $firstname,
			'last_name'  => $lastname,
			'email'      => $email,
			'phone'      => $phone,
			'address_1'  => $address,
			'address_2'  => '',
			'city'       => $city,
			'state'      => '',
			'postcode'   => $postcode,
			'country'    => $country
		);

		$order->set_address( $address, 'billing' );
		$order->set_address( $address, 'shipping' );

		// add payment method
		$order->set_payment_method( 'bacs' );
		$order->set_payment_method_title( 'Direct bank transfer' );

		// order status
		$order->set_status( 'wc-pending', 'Order is created programmatically' );

		// calculate and save
		$order->calculate_totals();
		$order->save();

		$orderURL = site_url().'/checkout/order-received/'.$order->get_id().'/?key='.$order->order_key;

		$result = array();
		$result['status'] = 1;
		$result['message'] = "successfully order placed!";
		$result['orderid'] = $order->get_id();
		$result['order_key'] = $order->order_key;
		$result['order_url'] = esc_url( $orderURL );
		echo json_encode($result);
		die();
	}

}
