<?php


/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * allow_post_type_wpcom function.
 *
 * @access public
 * @param mixed $allowed_post_types
 * @return void
 */
function allow_post_type_wpcom( $allowed_post_types ) {
	$allowed_post_types[] = 'incidents';
	$allowed_post_types[] = 'maintenances';
	$allowed_post_types[] = 'status-notices';
	return $allowed_post_types;
}
add_filter( 'rest_api_allowed_post_types', 'allow_post_type_wpcom' );



if ( ! function_exists( 'incidents' ) ) {


	/**
	 * incidents function.
	 *
	 * @access public
	 * @return void
	 */
	function incidents() {

		$labels = array(
		'name'                  => _x( 'Incidents', 'Post Type General Name', 'system-status' ),
		'singular_name'         => _x( 'Incident', 'Post Type Singular Name', 'system-status' ),
		'menu_name'             => __( 'Incidents', 'system-status' ),
		'name_admin_bar'        => __( 'Incidents', 'system-status' ),
		'archives'              => __( 'Incident Archives', 'system-status' ),
		'parent_item_colon'     => __( 'Parent Incident:', 'system-status' ),
		'all_items'             => __( 'All Incidents', 'system-status' ),
		'add_new_item'          => __( 'Add New Incident', 'system-status' ),
		'add_new'               => __( 'Add Incident', 'system-status' ),
		'new_item'              => __( 'New Incident', 'system-status' ),
		'edit_item'             => __( 'Edit Incident', 'system-status' ),
		'update_item'           => __( 'Update Incident', 'system-status' ),
		'view_item'             => __( 'View Incident', 'system-status' ),
		'search_items'          => __( 'Search Incidents', 'system-status' ),
		'not_found'             => __( 'No Incidents Found', 'system-status' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'system-status' ),
		'featured_image'        => __( 'Incident Image', 'system-status' ),
		'set_featured_image'    => __( 'Set Incident Image', 'system-status' ),
		'remove_featured_image' => __( 'Remove Incident Image', 'system-status' ),
		'use_featured_image'    => __( 'Use as featured Incident Image', 'system-status' ),
		'insert_into_item'      => __( 'Insert into Incident', 'system-status' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Incident', 'system-status' ),
		'items_list'            => __( 'Incidents list', 'system-status' ),
		'items_list_navigation' => __( 'Incidents list navigation', 'system-status' ),
		'filter_items_list'     => __( 'Filter Incidents list', 'system-status' ),
			);

			$args = array(
			'label'                 => __( 'Incident', 'system-status' ),
			'description'           => __( 'An un-expected event or occurrence.', 'system-status' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-warning',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'show_in_rest' 			=> true,
			'rest_base' 			=> __( 'incidents', 'system-status' ),
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rewrite' 				=> array( 'slug' => 'incidents','with_front' => false ),
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			);
			register_post_type( 'incident', $args );

	}
	add_action( 'init', 'incidents', 0 );

}



/**
 * incident_meta class.
 */
class incident_meta {


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

		add_action( 'init', array( $this, 'register_incident_metafields' ) );

	}




	/**
	 * register_incident_metafields function.
	 *
	 * @access public
	 * @return void
	 */
	public function register_incident_metafields() {

		// Start Date Meta.
		register_meta('incident', 'incident_start_date', array(
			'type' 			=> 'string',
			'description' 	=> 'Incident Start Date.',
			'single' 		=> true,
			'show_in_rest' 	=> true,
		));

		// Start Time Meta.
		register_meta('incident', 'incident_start_time', array(
			'type' 			=> 'string',
			'description' 	=> 'Incident Start Time.',
			'single' 		=> true,
			'show_in_rest' 	=> true,
		));

		// End Date Meta.
		register_meta('incident', 'incident_end_date', array(
			'type' 			=> 'string',
			'description' 	=> 'Incident End Date.',
			'single' 		=> true,
			'show_in_rest' 	=> true,
		));

		// End Time Meta.
		register_meta('incident', 'incident_end_time', array(
			'type' 			=> 'string',
			'description' 	=> 'Incident End Time.',
			'single' 		=> true,
			'show_in_rest' 	=> true,
		));

		// Ticket IDs.
		register_meta('incident', 'incident_ticket_ids', array(
			'type' 			=> 'string',
			'description' 	=> 'Array of attached ticket IDs.',
			'single' 		=> true,
			'show_in_rest' 	=> true,
		));

		// Ticket Count Meta.
		register_meta('incident', 'incident_ticket_count', array(
			'type' 			=> 'integer',
			'description' 	=> 'Incident Ticket Count.',
			'single' 		=> true,
			'show_in_rest' 	=> true,
		));

		// Total Incident Length.
		register_meta('incident', 'incident_total_length', array(
			'type' 			=> 'integer',
			'description' 	=> 'Total Length of the Incident.',
			'single' 		=> true,
			'show_in_rest' 	=> true,
		));
	}


	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' ) );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'incident_details',
			__( 'Details', 'system-status' ),
			array( $this, 'render_metabox' ),
			'incident',
			'advanced',
			'default'
		);

	}

	public function render_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'nonce_action', 'nonce' );

		// Retrieve an existing value from the database.
		$incident_start_date = get_post_meta( $post->ID, 'incident_start_date', true );
		$incident_start_time = get_post_meta( $post->ID, 'incident_start_time', true );
		$incident_end_date = get_post_meta( $post->ID, 'incident_end_date', true );
		$incident_end_time = get_post_meta( $post->ID, 'incident_end_time', true );
		$incident_ticket_count = get_post_meta( $post->ID, 'incident_ticket_count', true );
		$incident_total_length = get_post_meta( $post->ID, 'incident_total_length', true );
		$incident_attached_tickets = get_post_meta( $post->ID, 'incident_attached_tickets', true );

		// Set default values.
		if ( empty( $incident_start_date ) ) { $incident_start_date = '';
		}
		if ( empty( $incident_start_time ) ) { $incident_start_time = '';
		}
		if ( empty( $incident_end_date ) ) { $incident_end_date = '';
		}
		if ( empty( $incident_end_time ) ) { $incident_end_time = '';
		}
		if ( empty( $incident_ticket_count ) ) { $incident_ticket_count = '';
		}
		if ( empty( $incident_total_length ) ) { $incident_total_length = '';
		}
		if( empty( $incident_attached_tickets ) ) $incident_attached_tickets = '';

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label for="incident_start_date" class="incident-startdate_label">' . __( 'Start Date', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="date" id="incident-start-date" name="incident_start_time" class="incident-field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $incident_start_date ) . '">';
		echo '			<p class="description">' . __( 'The Date the Incident started to occur.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="incident_start_time" class="incident-starttime_label">' . __( 'Start Time', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="time" id="incident_starttime" name="incident_start_time" class="incident-field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $incident_start_time ) . '">';
		echo '			<p class="description">' . __( 'The time the incident started.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="incident_end_date" class="incident-enddate_label">' . __( 'End Date', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="date" id="incident-end-date" name="incident_end_date" class="incident-field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $incident_end_date ) . '">';
		echo '			<p class="description">' . __( 'The date the incident ended.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="incident_end_time" class="incident-endtime_label">' . __( 'End Time', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="time" id="incident-end-time" name="incident_end_time" class="incident-field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $incident_end_time ) . '">';
		echo '			<p class="description">' . __( 'The time the incident ended.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="incident-ticketcount" class="incident-ticketcount_label">' . __( '# of Tickets Reporting', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="number" id="incident_ticketcount" name="incident_ticket_count" class="incident-field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $incident_ticket_count ) . '" min="0">';
		echo '			<p class="description">' . __( 'Total # of tickets that reported the incident.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		/*
			TODO: Display list or link to attached notices.
		*/

		echo '	<tr>';
		echo '		<th><label for="incident_attached_tickets" class="incident_attached_tickets_label">' . __( 'Attached Tickets', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<textarea id="incident_attached_tickets" name="incident_attached_tickets" class="incident_attached_tickets_field" placeholder="' . esc_attr__( '', 'system-status' ) . '">' . $incident_attached_tickets . '</textarea>';
		echo '			<p class="description">' . __( 'Array of Attached Tickets', 'system-status' ) . '</p>';
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
		$new_incident_start_date = isset( $_POST['incident_start_date'] ) ? sanitize_text_field( $_POST['incident_start_date'] ) : '';
		$new_incident_start_time = isset( $_POST['incident_start_time'] ) ? sanitize_text_field( $_POST['incident_start_time'] ) : '';
		$new_incident_end_date = isset( $_POST['incident_end_date'] ) ? sanitize_text_field( $_POST['incident_end_date'] ) : '';
		$new_incident_end_time = isset( $_POST['incident_end_time'] ) ? sanitize_text_field( $_POST['incident_end_time'] ) : '';
		$new_incident_ticket_count = isset( $_POST['incident_ticket_count'] ) ? floatval( $_POST['incident_ticket_count'] ) : '';
		$new_incident_total_length = isset( $_POST['incident_total_length'] ) ? floatval( $_POST['incident_total_length'] ) : '';
		$new_incident_attached_tickets = isset( $_POST[ 'incident_attached_tickets' ] ) ? sanitize_text_field( $_POST[ 'incident_attached_tickets' ] ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'incident_start_date', $new_incident_start_date );
		update_post_meta( $post_id, 'incident_start_time', $new_incident_start_time );
		update_post_meta( $post_id, 'incident_end_date', $new_incident_end_date );
		update_post_meta( $post_id, 'incident_end_time', $new_incident_end_time );
		update_post_meta( $post_id, 'incident_ticket_count', $new_incident_ticket_count );
		update_post_meta( $post_id, 'incident_total_length', $new_incident_total_length );
		update_post_meta( $post_id, 'incident_attached_tickets', $new_incident_attached_tickets );

	}
}

new incident_meta;
