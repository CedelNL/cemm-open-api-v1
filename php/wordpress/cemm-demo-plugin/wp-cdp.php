<?php 
/*
Plugin Name: CEMM Demo Plugin
Plugin URI: http://developer.cemm.nl/
Description: Simple plugin to show CEMM data
Author: Cedel B.V.
Author URI: http://www.cedel.nl/
Text Domain: cemm-demo
Domain Path: /languages/
Version: 1.0
*/

defined( 'ABSPATH' ) or die();

define( 'WPCDP_VERSION', '1.0' );

define( 'WPCDP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

define( 'WPCDP_PLUGIN_FILE',  __FILE__  );


/**
 * The code that runs during plugin activation.
 */
function activate_cdp() {

}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_cdp() {

}

register_activation_hook( __FILE__, 'activate_cdp' );
register_deactivation_hook( __FILE__, 'deactivate_cdp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-cdp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cdp() {

	$plugin = new CEMM_Demo_Plugin();
	$plugin->run();

}
run_cdp();
