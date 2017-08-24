<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    CEMM_Demo_Plugin
 * @subpackage CEMM_Demo_Plugin/admin
 */

use \CedelServiceConnector\Classes\GetRequest;
use \CedelServiceConnector\Classes\HttpsConnection;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CEMM_Demo_Plugin
 * @subpackage CEMM_Demo_Plugin/admin
 * @author     Cedel <info@cedel.nl>
 */
class CEMM_Demo_Plugin_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in CEMM_Demo_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The CEMM_Demo_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url(WPCDP_PLUGIN_FILE) . 'admin/css/admin-style.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url(WPCDP_PLUGIN_FILE) . 'admin/js/cdp-scripts.js', array( 'jquery' ), $this->version, false );

		// Make the plugin name available in the fronted. Use cdp_vars object in javascript.
		wp_localize_script( $this->plugin_name, 'cdp_vars', array( 'plugin_name' => $this->plugin_name ) );

	}

	/**
	 * Register a backend page with setting for communicating with the Open API
	 * 
	 * @since 	1.0.0
	 */
	public function add_plugin_admin_menu() {

		add_menu_page( 'CEMM Open API', 'CEMM Open API', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'), 'dashicons-cloud');

	}

	/**
	 * Callback function loading the backend settings template.
	 * 
	 * @since 	1.0.0
	 */
	public function display_plugin_setup_page() {

	    include_once( WPCDP_PLUGIN_DIR . 'admin/cdp-admin-display.php' );

	}

	/**
	 * Register a settings group to store the API key and the prefered CEMM uid
	 * 
	 * @since 	1.0.0
	 */
	public function options_update() {

	    register_setting( $this->plugin_name, $this->plugin_name, array($this, 'validate') );

	}

	/**
	 * Callback function to validate settings set on the settings page
	 * 
	 * @since 	1.0.0
	 */ 
	public function validate($input) {

	    $valid = array();

	    //Cleanup form input
	    $valid['api_key'] = sanitize_text_field($input['api_key']);
	    $valid['cemm'] 	  = sanitize_text_field($input['cemm']);

	    // Flush cached data 
	    delete_site_transient($this->plugin_name . "_cemm_realtime");
	    delete_site_transient($this->plugin_name . "_cemm_month");

	    return $valid;
	}

	/**
	 * AJAX request handle
	 * 
	 * Returns a list with available CEMM uids. This function needs an API key
	 * 
	 * @since 	1.0.0
	 */
	public function get_available_cemm(){

		// Check if cached data is in the database. API responses are temporarly stored in the
		// database to prevent to much API requests.
		$body = get_site_transient($this->plugin_name . "_available_cemm");

		// Make API call if no cached data
		if( ! $body ) {
		 	$options = get_option($this->plugin_name);
		    $api_key = $options['api_key'];

		    if( !empty($api_key) ){
		    	// HttpsConnection is provided by the CedelServiceConnector lib.
			 	$conn = new HttpsConnection();
				$conn->setHost("https://mijn.cemm.nl");

				$req = new GetRequest($conn);
				$req->setPath('open-api/v1/cemm/');
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
						// Cache the response using the WP Transient API
						set_site_transient($this->plugin_name . "_available_cemm", $body, 30);
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
		    	wp_send_json(array());
		    }
		    
		}
		else {
			// Return cached response
			wp_send_json($body);
		}
		
	}

	public function get_available_io(){

		// Check if cached data is in the database. API responses are temporarly stored in the
		// database to prevent to much API requests.
		$body = get_site_transient($this->plugin_name . "_available_io");

		// Make API call if no cached data
		if( ! $body ) {
		 	$options = get_option($this->plugin_name);
		    $api_key = $options['api_key'];

		    if( !empty($api_key) ){
		    	// HttpsConnection is provided by the CedelServiceConnector lib.
			 	$conn = new HttpsConnection();
				$conn->setHost("https://mijn.cemm.nl");

				$req = new GetRequest($conn);
				$req->setPath('open-api/v1/cemm/io/');
				

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
						set_site_transient($this->plugin_name . "_available_io", $body, 30);
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
		    	wp_send_json(array());
		    }
		}
		else {
			// Return cached response
			wp_send_json($body);
		}
		

	}

}


