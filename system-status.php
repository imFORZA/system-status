<?php
/**
 * System Status by imFORZA
 *
 * @package system-status
 */

/*
-------------------------------------------------------------------------------
	Plugin Name: System Status
	Plugin URI: https://www.imforza.com
	Description: A WordPress plugin to manage tracking of System Incidents & Maintenance Periods.
	Version: 1.0.0
	Author: imFORZA
	Contributors: bhubbard, sfgarza
	Text Domain: system-status
	Author URI: https://www.imforza.com
	License: GPLv3 or later
	License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
------------------------------------------------------------------------------
*/

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }



if ( ! class_exists( 'SystemStatus' ) ) {
/**
 * TemplatePlugin class.
 *
 * @package system-status
 **/
class SystemStatus {

	/**
	 * Plugin Constructor.
	 */
	public function __construct() {
		/* Define Constants */
		define( 'TEMPLATE_BASE_NAME', plugin_basename( __FILE__ ) );
		define( 'TEMPLATE_BASE_DIR', plugin_dir_path( __FILE__ ) );
		define( 'TEMPLATE_PLUGIN_FILE', TEMPLATE_BASE_DIR . 'system-status.php' );

		/* Include dependencies */
		include_once( 'includes.php' );

		$this->init();
	}

	/**
	 * Initialize system-status.
	 */
	private function init() {
		/* Language Support */
		load_plugin_textdomain( 'system-status', false, dirname( TEMPLATE_BASE_NAME ) . '/languages' );

		/* IDX Broker Plugin Activation/De-Activation. */
		register_activation_hook( TEMPLATE_PLUGIN_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( TEMPLATE_PLUGIN_FILE, array( $this, 'deactivate' ) );

		/* Set menu page */
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		/** Enqueue css and js files */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		/* Add link to settings in plugins admin page */
		add_filter( 'plugin_action_links_' . TEMPLATE_BASE_NAME , array( $this, 'plugin_links' ) );
	}

	/**
	 * Method that runs on admin_menu hook.
	 */
	public function admin_menu() {
	}

	/**
	 * Enqueue CSS.
	 */
	public function admin_scripts() {
		wp_register_style( 'system-status-css', plugins_url( 'assets/css/system-status-min.css', TEMPLATE_PLUGIN_FILE ) );
		wp_enqueue_style( 'system-status-css' );
	}

	/**
	 * Method that executes on plugin activation.
	 */
	public function activate() {
		flush_rewrite_rules();
	}

	/**
	 * Method that executes on plugin de-activation.
	 */
	public function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Add Tools link on plugin page.
	 *
	 * @param  [Array] $links : Array of links on plugin page.
	 * @return [Array]        : Array of links on plugin page.
	 */
	public function plugin_links( $links ) {
		$tools_link = '<a href="#">Tools</a>';
		array_unshift( $links, $tools_link );
		return $links;
	}
}
}


/** Instantiate the plugin. */
new SystemStatus();
