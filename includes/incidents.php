<?php


/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists('system_status_incidents') ) {

// Register Custom Post Type
function system_status_incidents() {

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
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', ),
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
		'rewrite' 				=> array('slug' => 'incidents','with_front' => false),
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'incidents', $args );

}
add_action( 'init', 'system_status_incidents', 0 );

}



class system_status_incident_meta {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}


		add_action( 'init', array($this, 'register_incident_metafields' ));


	}




	public function register_incident_metafields() {


		// Start Date Meta.
		register_meta('incidents', 'incident_startdate', array(
			'type' 			=> 'string',
			'description' 	=> 'Incident Start Date.',
			'single' 		=> true,
			'show_in_rest' 	=> true,
		));

		// Start Time Meta.
		register_meta('incidents', 'incident_starttime', array(
			'type' 			=> 'string',
			'description' 	=> 'Incident Start Time.',
			'single' 		=> true,
			'show_in_rest' 	=> true,
		));

		// End Date Meta.
		register_meta('incidents', 'incident_enddate', array(
			'type' 			=> 'string',
			'description' 	=> 'Incident End Date.',
			'single' 		=> true,
			'show_in_rest' 	=> true,
		));

		// End Time Meta.
		register_meta('incidents', 'incident_endtime', array(
			'type' 			=> 'string',
			'description' 	=> 'Incident End Time.',
			'single' 		=> true,
			'show_in_rest' 	=> true,
		));

		// Ticket Count Meta.
		register_meta('incidents', 'ticket_ids', array(
			'type' 			=> 'string',
			'description' 	=> 'Array of attached ticket IDs.',
			'single' 		=> false,
			'show_in_rest' 	=> true,
		));

		// Ticket Count Meta.
		register_meta('incidents', 'ticket_count', array(
			'type' 			=> 'integer',
			'description' 	=> 'Incident Ticket Count.',
			'single' 		=> true,
			'show_in_rest' 	=> true,
		));

	}


	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'incident_details',
			__( 'Details', 'system-status' ),
			array( $this, 'render_metabox' ),
			'incidents',
			'advanced',
			'default'
		);

	}

	public function render_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'system_status_nonce_action', 'system_status_nonce' );

		// Retrieve an existing value from the database.
		$system_status_incident_startdate = get_post_meta( $post->ID, 'system_status_incident-startdate', true );
		$system_status_incident_starttime = get_post_meta( $post->ID, 'system_status_incident-starttime', true );
		$system_status_incident_enddate = get_post_meta( $post->ID, 'system_status_incident-enddate', true );
		$system_status_incident_endtime = get_post_meta( $post->ID, 'system_status_incident-endtime', true );
		$system_status_incident_ticketcount = get_post_meta( $post->ID, 'system_status_incident-ticketcount', true );

		// Set default values.
		if( empty( $system_status_incident_startdate ) ) $system_status_incident_startdate = '';
		if( empty( $system_status_incident_starttime ) ) $system_status_incident_starttime = '';
		if( empty( $system_status_incident_enddate ) ) $system_status_incident_enddate = '';
		if( empty( $system_status_incident_endtime ) ) $system_status_incident_endtime = '';
		if( empty( $system_status_incident_ticketcount ) ) $system_status_incident_ticketcount = '';

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label for="system_status_incident-startdate" class="system_status_incident-startdate_label">' . __( 'Start Date', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="date" id="system_status_incident_startdate" name="system_status_incident-startdate" class="system_status_incident_startdate_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $system_status_incident_startdate ) . '">';
		echo '			<p class="description">' . __( 'The Date the Incident started to occur.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="system_status_incident-starttime" class="system_status_incident-starttime_label">' . __( 'Start Time', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="time" id="system_status_incident_starttime" name="system_status_incident-starttime" class="system_status_incident_starttime_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $system_status_incident_starttime ) . '">';
		echo '			<p class="description">' . __( 'The time the incident started.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="system_status_incident-enddate" class="system_status_incident-enddate_label">' . __( 'End Date', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="date" id="system_status_incident_enddate" name="system_status_incident-enddate" class="system_status_incident_enddate_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $system_status_incident_enddate ) . '">';
		echo '			<p class="description">' . __( 'The date the incident ended.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="system_status_incident-endtime" class="system_status_incident-endtime_label">' . __( 'End Time', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="time" id="system_status_incident_endtime" name="system_status_incident-endtime" class="system_status_incident_endtime_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $system_status_incident_endtime ) . '">';
		echo '			<p class="description">' . __( 'The time the incident ended.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="system_status_incident-ticketcount" class="system_status_incident-ticketcount_label">' . __( '# of Tickets Reporting', 'system-status' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="number" id="system_status_incident_ticketcount" name="system_status_incident-ticketcount" class="system_status_incident_ticketcount_field" placeholder="' . esc_attr__( '', 'system-status' ) . '" value="' . esc_attr__( $system_status_incident_ticketcount ) . '">';
		echo '			<p class="description">' . __( 'Total # of tickets that reported the incident.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '</table>';

	}

	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['system_status_nonce'] ) ? $_POST['system_status_nonce'] : '';
		$nonce_action = 'system_status_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;

		// Sanitize user input.
		$system_status_new_incident_startdate = isset( $_POST[ 'system_status_incident-startdate' ] ) ? sanitize_text_field( $_POST[ 'system_status_incident-startdate' ] ) : '';
		$system_status_new_incident_starttime = isset( $_POST[ 'system_status_incident-starttime' ] ) ? sanitize_text_field( $_POST[ 'system_status_incident-starttime' ] ) : '';
		$system_status_new_incident_enddate = isset( $_POST[ 'system_status_incident-enddate' ] ) ? sanitize_text_field( $_POST[ 'system_status_incident-enddate' ] ) : '';
		$system_status_new_incident_endtime = isset( $_POST[ 'system_status_incident-endtime' ] ) ? sanitize_text_field( $_POST[ 'system_status_incident-endtime' ] ) : '';
		$system_status_new_incident_ticketcount = isset( $_POST[ 'system_status_incident-ticketcount' ] ) ? floatval( $_POST[ 'system_status_incident-ticketcount' ] ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'system_status_incident-startdate', $system_status_new_incident_startdate );
		update_post_meta( $post_id, 'system_status_incident-starttime', $system_status_new_incident_starttime );
		update_post_meta( $post_id, 'system_status_incident-enddate', $system_status_new_incident_enddate );
		update_post_meta( $post_id, 'system_status_incident-endtime', $system_status_new_incident_endtime );
		update_post_meta( $post_id, 'system_status_incident-ticketcount', $system_status_new_incident_ticketcount );

	}

}

new system_status_incident_meta;
