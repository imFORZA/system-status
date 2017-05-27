<?php

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'maintenance' ) ) {

	// Register Custom Post Type
	function maintenance() {

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
			register_post_type( 'maintenance', $args );

	}
	add_action( 'init', 'maintenance', 0 );

}







/**
 * maint_meta class.
 */
class maint_meta {


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
			'maintenance',
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
		wp_nonce_field( 'nonce_action', 'nonce' );

		// Retrieve an existing value from the database.
		$maint_scheduled_start_date = get_post_meta( $post->ID, 'maint_scheduled_start_date', true );
		$maint_scheduled_start_time = get_post_meta( $post->ID, 'maint_scheduled_start_time', true );
		$maint_scheduled_end_time = get_post_meta( $post->ID, 'maint_scheduled_end_time', true );
		$maint_scheduled_end_date = get_post_meta( $post->ID, 'maint_scheduled_end_date', true );
		$maint_actual_start_date = get_post_meta( $post->ID, 'maint_true_start_date', true );
		$maint_actual_start_time = get_post_meta( $post->ID, 'maint_true_start_time', true );
		$maint_actual_end_date = get_post_meta( $post->ID, 'maint_true_end_date', true );
		$maint_actual_end_time = get_post_meta( $post->ID, 'maint_true_end_time', true );
		$maint_ticket_count = get_post_meta( $post->ID, 'maint_ticket_count', true );
		$maint_total_length = get_post_meta( $post->ID, 'maint_total_length', true );
		$maint_attached_tickets = get_post_meta( $post->ID, 'maint_attached_tickets', true );

		// Set default values.
		if ( empty( $maint_scheduled_start_date ) ) { $maint_scheduled_start_date = '';
		}
		if ( empty( $maint_scheduled_start_time ) ) { $maint_scheduled_start_time = '';
		}
		if ( empty( $maint_scheduled_end_date ) ) { $maint_scheduled_end_date = '';
		}
		if ( empty( $maint_scheduled_end_time ) ) { $maint_scheduled_end_time = '';
		}
		if ( empty( $maint_actual_start_date ) ) { $maint_actual_start_date = '';
		}
		if ( empty( $maint_actual_start_time ) ) { $maint_actual_start_time = '';
		}
		if ( empty( $maint_ticket_count ) ) { $maint_ticket_count = '';
		}
		if ( empty( $maint_total_length ) ) { $maint_total_length = '';
		}
		if( empty( $maint_attached_tickets ) ) { $maint_attached_tickets = '';
		}

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label for="maint-scheduled-start-date" class="maint-scheduled-start-date_label">' . __( 'Scheduled Start Date', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="date" id="maint_scheduled_start_date" name="maint-scheduled-start-date" class="maint_scheduled_start_date_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $maint_scheduled_start_date ) . '">';
		echo '			<p class="description">' . __( 'The Scheduled Start Date.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="maint-scheduled-start-time" class="maint-scheduled-start-time_label">' . __( 'Scheduled Start Time', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="time" id="maint_scheduled_start_time" name="maint-scheduled-start-time" class="maint_scheduled_start_time_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $maint_scheduled_start_time ) . '">';
		echo '			<p class="description">' . __( 'The Scheduled Start Time.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="maint-scheduled-end-date" class="maint-scheduled-end-date_label">' . __( 'Scheduled End Date', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="date" id="maint_scheduled_end_date" name="maint-scheduled-end-date" class="maint_scheduled_end_date_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $maint_scheduled_end_date ) . '">';
		echo '			<p class="description">' . __( 'The Scheduled End Date.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="maint-scheduled-end-time" class="maint-scheduled-end-time_label">' . __( 'Scheduled End Time', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="time" id="maint_scheduled_end_time" name="maint-scheduled-end-time" class="maint_scheduled_end_time_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $maint_scheduled_end_time ) . '">';
		echo '			<p class="description">' . __( 'The Scheduled end Time.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';


		echo '	<tr>';
		echo '		<th><label for="maint-actual-start-date" class="maint-actual-start-date_label">' . __( 'Actual Start Date', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="date" id="maint_actual_start_date" name="maint-actual-start-date" class="maint_actual_start_date_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $maint_actual_start_date ) . '">';
		echo '			<p class="description">' . __( 'The Actual Start Date.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="maint-actual-start-time" class="maint-actual-start-time_label">' . __( 'Actual Start Time', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="time" id="maint_actual_start_time" name="maint-actual-start-time" class="maint_actual_start_time_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $maint_actual_start_time ) . '">';
		echo '			<p class="description">' . __( 'The Actual Start Time', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';


		echo '	<tr>';
		echo '		<th><label for="maint-actual-end-date" class="maint-actual-end-date_label">' . __( 'Actual End Date', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="date" id="maint_actual_end_date" name="maint-actual-end-date" class="maint_actual_end_date_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $maint_actual_end_date ) . '">';
		echo '			<p class="description">' . __( 'The Actual end Date.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';


		echo '	<tr>';
		echo '		<th><label for="maint-actual-end-time" class="maint-actual-end-time_label">' . __( 'Actual End Time', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="time" id="maint_actual_end_time" name="maint-actual-end-time" class="maint_actual_end_time_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $maint_actual_end_time ) . '">';
		echo '			<p class="description">' . __( 'The Actual end Time', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="maint-ticketcount" class="maint-ticketcount_label">' . __( '# of Tickets Reporting', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="number" id="maint_ticketcount" name="maint_ticket_count" class="maint-field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $maint_ticket_count ) . '" min="0">';
		echo '			<p class="description">' . __( 'Total # of tickets that reported the maint.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="maint_attached_tickets" class="maint_attached_tickets_label">' . __( 'Attached Tickets', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<textarea id="maint_attached_tickets" name="maint_attached_tickets" class="maint_attached_tickets_field" placeholder="' . esc_attr__( '', 'system-status' ) . '">' . $maint_attached_tickets . '</textarea>';
		echo '			<p class="description">' . __( 'Array of Attached Tickets', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<td>';
		echo 'Link to attached notices.';
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
		$nonce_name   = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$nonce_action = 'nonce_action';

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
		$new_maint_scheduled_start_date = isset( $_POST['maint_scheduled_start_date'] ) ? sanitize_text_field( $_POST['maint_scheduled_start_date'] ) : '';
		$new_maint_scheduled_start_time = isset( $_POST['maint_scheduled_start_time'] ) ? sanitize_text_field( $_POST['maint_scheduled_start_time'] ) : '';
		$new_maint_scheduled_end_date = isset( $_POST['maint_scheduled_end_date'] ) ? sanitize_text_field( $_POST['maint_scheduled_end_date'] ) : '';
		$new_maint_scheduled_end_time = isset( $_POST['maint_scheduled_end_time'] ) ? sanitize_text_field( $_POST['maint_scheduled_end_time'] ) : '';
		$new_maint_actual_start_date = isset( $_POST['maint_true_start_date'] ) ? sanitize_text_field( $_POST['maint_true_start_date'] ) : '';
		$new_maint_actual_start_time = isset( $_POST['maint_true_start_time'] ) ? sanitize_text_field( $_POST['maint_true_start_time'] ) : '';
		$new_maint_actual_end_date = isset( $_POST['maint_true_end_date'] ) ? sanitize_text_field( $_POST['maint_true_end_date'] ) : '';
		$new_maint_actual_end_time = isset( $_POST['maint_true_end_time'] ) ? sanitize_text_field( $_POST['maint_true_end_time'] ) : '';
		$new_maint_ticket_count = isset( $_POST['maint_ticket_count'] ) ? floatval( $_POST['maint_ticket_count'] ) : '';
		$new_maint_total_length = isset( $_POST['maint_total_length'] ) ? floatval( $_POST['maint_total_length'] ) : '';
		$new_maint_attached_tickets = isset( $_POST[ 'maint_attached_tickets' ] ) ? sanitize_text_field( $_POST[ 'maint_attached_tickets' ] ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'maint_scheduled_start_date', $new_maint_scheduled_start_date );
		update_post_meta( $post_id, 'maint_scheduled_start_time', $new_maint_scheduled_start_time );
		update_post_meta( $post_id, 'maint_scheduled_end_date', $new_maint_scheduled_end_date );
		update_post_meta( $post_id, 'maint_scheduled_end_time', $new_maint_scheduled_end_time );
		update_post_meta( $post_id, 'maint_actual_start_date', $new_maint_actual_start_date );
		update_post_meta( $post_id, 'maint_actual_start_time', $new_maint_actual_start_time );
		update_post_meta( $post_id, 'maint_actual_end_date', $new_maint_actual_end_date );
		update_post_meta( $post_id, 'maint_actual_end_time', $new_maint_actual_end_time );
		update_post_meta( $post_id, 'maint_ticket_count', $new_maint_ticket_count );
		update_post_meta( $post_id, 'maint_total_length', $new_maint_total_length );
		update_post_meta( $post_id, 'maint_attached_tickets', $new_maint_attached_tickets );

	}
}

new maint_meta;
