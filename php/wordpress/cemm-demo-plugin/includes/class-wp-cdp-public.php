<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://developer.cemm.nl
 * @since      1.0.0
 *
 * @package    CEMM_Demo_Plugin
 * @subpackage CEMM_Demo_Plugin/public
 */

use \CedelServiceConnector\Classes\GetRequest;
use \CedelServiceConnector\Classes\HttpsConnection;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CEMM_Demo_Plugin
 * @subpackage CEMM_Demo_Plugin/public
 * @author     Cedel <info@cedel.nl>
 */
class CEMM_Demo_Plugin_Public {

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

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( WPCDP_PLUGIN_FILE ) . 'public/css/site.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( WPCDP_PLUGIN_FILE ) . 'public/js/site.js', array( 'jquery' ), $this->version, false );

		/**
		 * ajaxurl is by default not available in the public fronted. 
		 * This function adds the ajaxurl to the javascript file
		 */
		wp_localize_script( $this->plugin_name, 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );

	}

	/**
	 * AJAX request handle
	 * 
	 * Returns the realtime solar power
	 * 
	 * @since 	1.0.0
	 */
	public function get_realtime_data(){

		// Check if cached data is in the database. API responses are temporarly stored in the
		// database to prevent to much API requests.
		$body = get_site_transient($this->plugin_name . "_cemm_realtime");

		// Make API call if no cached data
		if( ! $body ) {
		 	$options = get_option($this->plugin_name);
		    $api_key = $options['api_key'];

		    if( !empty($api_key) ){
		    	// HttpsConnection is provided by the CedelServiceConnector lib.
			 	$conn = new HttpsConnection();
				$conn->setHost("https://mijn.cemm.nl");

				$req = new GetRequest($conn);
				$req->setPath('open-api/v1/cemm/'.$options['cemm'].'/s01/realtime/');
				$req->setParam('api_key', $api_key);


				$req->send();

				// Check if a valid response is returned by the API
				if($req->hasResponse()){
					// GetResponse returns an instance of HttpResponse.
					$res  = $req->getResponse();

					// Get the body as associative array.
					$body = $res->getBody();


					// Do not cache promises!
					if(! isset($body["promise"])){
						// Cache the respone using the WP Transient API
						set_site_transient($this->plugin_name . "_cemm_realtime", $body, 30);
					}

					// Return the API response
					wp_send_json($body);
				}
				elseif ($req->hasError()) {
					wp_send_json( array("error" => $req->getError()) );
				}
		    }
		    else {
		    	// Return empty response if no API key is set.
		    	wp_send_json(array("error" => 403));
		    }
		}
		else {
			// Return cached response
			wp_send_json($body);
		}

	}

	/**
	 * AJAX request handle
	 * 
	 * Returns the solar month data
	 * 
	 * @since 	1.0.0
	 */
	public function get_month_data(){

		// Check if cached data is in the database. API responses are temporarly stored in the
		// database to prevent to much API requests.
		$body = get_site_transient($this->plugin_name . "_cemm_month");

		// Make API call if no cached data
		if( ! $body ) {
		 	$options = get_option($this->plugin_name);
		    $api_key = $options['api_key'];

		    if( !empty($api_key) ){
		    	// HttpsConnection is provided by the CedelServiceConnector lib.
			 	$conn = new HttpsConnection();
				$conn->setHost("https://mijn.cemm.nl/");

				$req = new GetRequest($conn);
				$req->setPath('open-api/v1/cemm/'.$options['cemm'].'/s01/data/month/');
				$req->setParam('api_key', $api_key);


				$req->send();

				// Check if a valid response is returned by the API
				if($req->hasResponse()){
					// GetResponse returns an instance of HttpResponse.
					$res  = $req->getResponse();

					// Get the body as associative array.
					$body = $res->getBody();
					
					// Do not cache promises!
					if(! isset($body["promise"])){
						// Cache the respone using the WP Transient API
						set_site_transient($this->plugin_name . "_cemm_month", $body, 30);
					}

					// Return the API response
					wp_send_json($body);
				}
				elseif ($req->hasError()) {
					wp_send_json( array("error" => $req->getError()) );
				}
		    }
		    else {
		    	// Return empty response if no API key is set.
		    	wp_send_json(array("error" => 403));
		    }
		}
		else {
			// Return cached response
			wp_send_json($body);
		}

	}

	/**
	 * Register the CEMM widget
	 * 
	 * @since 	1.0.0
	 */
	public function register_widget() {

		register_widget('CEMM_Widget');
		
	}


}
