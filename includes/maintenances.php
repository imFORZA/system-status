<?php

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'system_status_maintenance' ) ) {

	// Register Custom Post Type
	function system_status_maintenance() {

		$labels = array(
		'name'                  => _x( 'Maintenances', 'Post Type General Name', 'system-status' ),
		'singular_name'         => _x( 'Maintenance', 'Post Type Singular Name', 'system-status' ),
		'menu_name'             => __( 'Maintenances', 'system-status' ),
		'name_admin_bar'        => __( 'Maintenances', 'system-status' ),
		'archives'              => __( 'Maintenance Archives', 'system-status' ),
		'parent_item_colon'     => __( 'Parent Maintenance:', 'system-status' ),
		'all_items'             => __( 'All Maintenances', 'system-status' ),
		'add_new_item'          => __( 'Add New Maintenance', 'system-status' ),
		'add_new'               => __( 'Add Maintenance', 'system-status' ),
		'new_item'              => __( 'New Maintenance', 'system-status' ),
		'edit_item'             => __( 'Edit Maintenance', 'system-status' ),
		'update_item'           => __( 'Update Maintenance', 'system-status' ),
		'view_item'             => __( 'View Maintenance', 'system-status' ),
		'search_items'          => __( 'Search Maintenances', 'system-status' ),
		'not_found'             => __( 'No Maintenance Found', 'system-status' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'system-status' ),
		'featured_image'        => __( 'Maintenance Image', 'system-status' ),
		'set_featured_image'    => __( 'Set Maintenance Image', 'system-status' ),
		'remove_featured_image' => __( 'Remove Maintenance Image', 'system-status' ),
		'use_featured_image'    => __( 'Use as featured Maintenance Image', 'system-status' ),
		'insert_into_item'      => __( 'Insert into Maintenance', 'system-status' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Maintenance', 'system-status' ),
		'items_list'            => __( 'Maintenances list', 'system-status' ),
		'items_list_navigation' => __( 'Maintenances list navigation', 'system-status' ),
		'filter_items_list'     => __( 'Filter Maintenances list', 'system-status' ),
			);
			$args = array(
			'label'                 => __( 'Maintenance', 'system-status' ),
			'description'           => __( 'An scheduled event or occurrence.', 'system-status' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-admin-tools',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'show_in_rest' 			=> true,
			'rest_base' 			=> __( 'maintenances', 'system-status' ),
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			);
			register_post_type( 'maintenances', $args );

	}
	add_action( 'init', 'system_status_maintenance', 0 );

}







/**
 * system_status_maint_meta class.
 */
class system_status_maint_meta {


	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}


	/**
	 * init_metabox function.
	 *
	 * @access public
	 * @return void
	 */
	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' ) );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}


	/**
	 * add_metabox function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_metabox() {

		add_meta_box(
			'maint_details',
			__( 'Details', 'system-status' ),
			array( $this, 'render_maint_metabox' ),
			'maintenances',
			'advanced',
			'default'
		);

	}


	/**
	 * render_maint_metabox function.
	 *
	 * @access public
	 * @param mixed $post
	 * @return void
	 */
	public function render_maint_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'system_status_nonce_action', 'system_status_nonce' );

		// Retrieve an existing value from the database.
		$system_status_maint_scheduled_start_date = get_post_meta( $post->ID, 'system_status_maint-scheduled-start-date', true );
		$system_status_maint_scheduled_start_time = get_post_meta( $post->ID, 'system_status_maint-scheduled-start-time', true );
		$system_status_maint_scheduled_end_date = get_post_meta( $post->ID, 'system_status_maint-scheduled-end-date', true );
		$system_status_maint_actual_start_date = get_post_meta( $post->ID, 'system_status_maint-actual-start-date', true );
		$system_status_maint_actual_start_time = get_post_meta( $post->ID, 'system_status_maint-actual-start-time', true );

		// Set default values.
		if ( empty( $system_status_maint_scheduled_start_date ) ) { $system_status_maint_scheduled_start_date = '';
		}
		if ( empty( $system_status_maint_scheduled_start_time ) ) { $system_status_maint_scheduled_start_time = '';
		}
		if ( empty( $system_status_maint_scheduled_end_date ) ) { $system_status_maint_scheduled_end_date = '';
		}
		if ( empty( $system_status_maint_actual_start_date ) ) { $system_status_maint_actual_start_date = '';
		}
		if ( empty( $system_status_maint_actual_start_time ) ) { $system_status_maint_actual_start_time = '';
		}

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label for="system_status_maint-scheduled-start-date" class="system_status_maint-scheduled-start-date_label">' . __( 'Scheduled Start Date', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="date" id="system_status_maint_scheduled_start_date" name="system_status_maint-scheduled-start-date" class="system_status_maint_scheduled_start_date_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $system_status_maint_scheduled_start_date ) . '">';
		echo '			<p class="description">' . __( 'The Scheduled Start Date.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="system_status_maint-scheduled-start-time" class="system_status_maint-scheduled-start-time_label">' . __( 'Scheduled Start Time', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="time" id="system_status_maint_scheduled_start_time" name="system_status_maint-scheduled-start-time" class="system_status_maint_scheduled_start_time_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $system_status_maint_scheduled_start_time ) . '">';
		echo '			<p class="description">' . __( 'The Scheduled Start Time.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="system_status_maint-scheduled-end-date" class="system_status_maint-scheduled-end-date_label">' . __( 'Scheduled End Date', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="date" id="system_status_maint_scheduled_end_date" name="system_status_maint-scheduled-end-date" class="system_status_maint_scheduled_end_date_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $system_status_maint_scheduled_end_date ) . '">';
		echo '			<p class="description">' . __( 'The Scheduled End Date.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="system_status_maint-actual-start-date" class="system_status_maint-actual-start-date_label">' . __( 'Actual Start Date', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="date" id="system_status_maint_actual_start_date" name="system_status_maint-actual-start-date" class="system_status_maint_actual_start_date_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $system_status_maint_actual_start_date ) . '">';
		echo '			<p class="description">' . __( 'The Actual Start Date.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="system_status_maint-actual-start-time" class="system_status_maint-actual-start-time_label">' . __( 'Actual Start Time', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="time" id="system_status_maint_actual_start_time" name="system_status_maint-actual-start-time" class="system_status_maint_actual_start_time_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $system_status_maint_actual_start_time ) . '">';
		echo '			<p class="description">' . __( 'The Actual Start Time', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<td>';
		echo 'Actual End Date/Time. # of Attached Tickets, Array of Attached Ticket IDs, link to attached notices.';
		echo '		</td>';
		echo '	</tr>';

		echo '</table>';

	}


	/**
	 * save_metabox function.
	 *
	 * @access public
	 * @param mixed $post_id
	 * @param mixed $post
	 * @return void
	 */
	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['system_status_nonce'] ) ? $_POST['system_status_nonce'] : '';
		$nonce_action = 'system_status_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) ) {
			return;
		}

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			return;
		}

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Sanitize user input.
		$system_status_new_maint_scheduled_start_date = isset( $_POST['system_status_maint-scheduled-start-date'] ) ? sanitize_text_field( $_POST['system_status_maint-scheduled-start-date'] ) : '';
		$system_status_new_maint_scheduled_start_time = isset( $_POST['system_status_maint-scheduled-start-time'] ) ? sanitize_text_field( $_POST['system_status_maint-scheduled-start-time'] ) : '';
		$system_status_new_maint_scheduled_end_date = isset( $_POST['system_status_maint-scheduled-end-date'] ) ? sanitize_text_field( $_POST['system_status_maint-scheduled-end-date'] ) : '';
		$system_status_new_maint_actual_start_date = isset( $_POST['system_status_maint-actual-start-date'] ) ? sanitize_text_field( $_POST['system_status_maint-actual-start-date'] ) : '';
		$system_status_new_maint_actual_start_time = isset( $_POST['system_status_maint-actual-start-time'] ) ? sanitize_text_field( $_POST['system_status_maint-actual-start-time'] ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'system_status_maint-scheduled-start-date', $system_status_new_maint_scheduled_start_date );
		update_post_meta( $post_id, 'system_status_maint-scheduled-start-time', $system_status_new_maint_scheduled_start_time );
		update_post_meta( $post_id, 'system_status_maint-scheduled-end-date', $system_status_new_maint_scheduled_end_date );
		update_post_meta( $post_id, 'system_status_maint-actual-start-date', $system_status_new_maint_actual_start_date );
		update_post_meta( $post_id, 'system_status_maint-actual-start-time', $system_status_new_maint_actual_start_time );

	}
}

new system_status_maint_meta;
