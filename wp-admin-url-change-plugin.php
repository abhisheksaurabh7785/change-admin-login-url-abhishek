<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://abc.com
 * @since             1.0.0
 * @package           Wp_Admin_Url_Change_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       wp-admin-url-change plugin
 * Plugin URI:        https://abc.com
 * Description:       Change Wp admin Url
 * Version:           1.0.0
 * Author:            Abhishek tripathi
 * Author URI:        https://abc.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-admin-url-change-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_ADMIN_URL_CHANGE_PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-admin-url-change-plugin-activator.php
 */
function activate_wp_admin_url_change_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-admin-url-change-plugin-activator.php';
	Wp_Admin_Url_Change_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-admin-url-change-plugin-deactivator.php
 */
function deactivate_wp_admin_url_change_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-admin-url-change-plugin-deactivator.php';
	Wp_Admin_Url_Change_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_admin_url_change_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_wp_admin_url_change_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-admin-url-change-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_admin_url_change_plugin() {

	$plugin = new Wp_Admin_Url_Change_Plugin();
	$plugin->run();

}
run_wp_admin_url_change_plugin();




add_action('plugins_loaded', 'check_woo_plugin_is_activated');


function check_woo_plugin_is_activated(){
	if( ! in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' )), true) ) {
		add_action('admin_init', 'check_woocommerce_activation');
		add_action('admin_notices', 'display_notices');

	}
}

function check_woocommerce_activation() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

function display_notices() {
	?>
	<div class="error notice">
		<p>
			<strong>Warning:</strong>

			Please activate the plugins.
		</p>
	</div>
	<?php
}



// Filter & Function to rename the WordPress logout URL
// add_filter( 'logout_url', 'my_logout_page', 10, 2 );
// function my_logout_page( $logout_url) {
//     return home_url( '/my-secret-login.php');   // The name of your new login file
// }
// // Filter & Function to rename Lost Password URL
// add_filter( 'lostpassword_url', 'my_lost_password_page', 10, 2 );
// function my_lost_password_page( $lostpassword_url ) {
//     return home_url( '/my-secret-login.php?action=lostpassword');   // The name of your new login file
// }



add_action('login_head', 'admin_login_url_change_redirect_error_page');
add_action('init', 'admin_login_url_change_redirect_success_page');
add_action('wp_logout', 'admin_login_url_change_redirect_login_page');
add_action('wp_login_failed', 'admin_login_url_change_redirect_failed_login_page');



/**
* Redirect Error Page
*/

function admin_login_url_change_redirect_error_page(){

	$jh_new_login = wp_unslash(get_option( 'adminurl' ));

	// print_r( $jh_new_login );
	if(!empty($jh_new_login)){
	  if(strpos($_SERVER['REQUEST_URI'], $jh_new_login) === false){
		wp_safe_redirect( home_url( '404' ), 302 );
		exit(); 
	  } 
	}
  }
  
  /**
  * Redirect Success Page
  */
  
  function admin_login_url_change_redirect_success_page(){
	$jh_new_login = wp_unslash(get_option( 'adminurl' ));
	if(!empty($jh_new_login)){
	  $jh_wp_admin_login_current_url_path=wp_parse_url($_SERVER['REQUEST_URI']);
  
	  if($jh_wp_admin_login_current_url_path["path"] == '/'.$jh_new_login){
		wp_safe_redirect(home_url("wp-login.php?$jh_new_login&redirect=false"));
		exit(); 
	  }
	}
  }
  
  /**
  * Redirect Login Page
  */
  
  function admin_login_url_change_redirect_login_page() {
	$jh_new_login = wp_unslash(get_option( 'adminurl' ));
	if(!empty($jh_new_login)){
	  wp_safe_redirect(home_url("wp-login.php?$jh_new_login&redirect=false"));
	  exit();
	}
  }
  
  /**
  * Redirect Login Page for Login Failed
  */
  
  function admin_login_url_change_redirect_failed_login_page($username) {
	$jh_new_login = wp_unslash(get_option( 'adminurl' ));
	if(!empty($jh_new_login)){
	  wp_safe_redirect(home_url("wp-login.php?$jh_new_login&redirect=false"));
	  exit();
	}
  }
  
  