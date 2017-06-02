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
			define( 'SYSTEMSTATUS_BASE_NAME', plugin_basename( __FILE__ ) );
			define( 'SYSTEMSTATUS_BASE_DIR', plugin_dir_path( __FILE__ ) );
			define( 'SYSTEMSTATUS_PLUGIN_FILE', SYSTEMSTATUS_BASE_DIR . 'system-status.php' );

			/* Include dependencies */
			include_once( 'includes.php' );

			$this->init();
		}

		/**
		 * Initialize system-status.
		 */
		private function init() {
			/* Language Support */
			load_plugin_textdomain( 'system-status', false, dirname( SYSTEMSTATUS_BASE_NAME ) . '/languages' );

			/* IDX Broker Plugin Activation/De-Activation. */
			register_activation_hook( SYSTEMSTATUS_PLUGIN_FILE, array( $this, 'activate' ) );
			register_deactivation_hook( SYSTEMSTATUS_PLUGIN_FILE, array( $this, 'deactivate' ) );

			/* Set menu page */
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );

			/** Enqueue css and js files */
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

			/* Add link to settings in plugins admin page */
			add_filter( 'plugin_action_links_' . SYSTEMSTATUS_BASE_NAME , array( $this, 'plugin_links' ) );

			add_filter( 'template_include', array( $this, 'default_templates' ) );
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
			wp_register_style( 'system-status-css', plugins_url( 'assets/css/system-status-min.css', SYSTEMSTATUS_PLUGIN_FILE ) );
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
			$tools_link = '<a href="#">Settings</a>';
			array_unshift( $links, $tools_link );
			return $links;
		}


		public function default_templates( $template ) {

		global $wp_query;




	if ( is_post_type_archive( 'incident' ) ) {
		if ( file_exists( get_stylesheet_directory() . '/archive-incident.php' ) ) {
			$template = get_stylesheet_directory() . '/archive-incident.php';
			return $template;
		} else {
			return SYSTEMSTATUS_BASE_DIR . '/templates/archive-incident.php';
		}
	}


	if ( is_single() && get_post_type() === 'incident' ) {

		if ( file_exists( get_stylesheet_directory() . '/single-incident.php' ) ) {
			$template = get_stylesheet_directory() . '/single-incident.php';
			return $template;
		} else {
			return SYSTEMSTATUS_BASE_DIR . '/templates/single-incident.php';
		}

	}


	if ( is_post_type_archive( 'maintenance' ) ) {
		if ( file_exists( get_stylesheet_directory() . '/archive-maintenance.php' ) ) {
			$template = get_stylesheet_directory() . '/archive-maintenance.php';
			return $template;
		} else {
			return SYSTEMSTATUS_BASE_DIR . '/templates/archive-maintenance.php';
		}
	}


	if ( is_single() && get_post_type() === 'maintenance' ) {

		if ( file_exists( get_stylesheet_directory() . '/single-maintenance.php' ) ) {
			$template = get_stylesheet_directory() . '/single-maintenance.php';
			return $template;
		} else {
			return SYSTEMSTATUS_BASE_DIR . '/templates/single-maintenance.php';
		}

	}


		return $template;
	}

	}
}


/** Instantiate the plugin. */
new SystemStatus();
