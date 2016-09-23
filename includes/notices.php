<?php

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists('system_status_notices') ) {

// Register Custom Post Type
function system_status_notices() {

	$labels = array(
		'name'                  => _x( 'Notices', 'Post Type General Name', 'system-status' ),
		'singular_name'         => _x( 'Notice', 'Post Type Singular Name', 'system-status' ),
		'menu_name'             => __( 'Notices', 'system-status' ),
		'name_admin_bar'        => __( 'Notices', 'system-status' ),
		'archives'              => __( 'Notice Archives', 'system-status' ),
		'parent_item_colon'     => __( 'Parent Notice:', 'system-status' ),
		'all_items'             => __( 'All Notices', 'system-status' ),
		'add_new_item'          => __( 'Add New Notice', 'system-status' ),
		'add_new'               => __( 'Add Notice', 'system-status' ),
		'new_item'              => __( 'New Notice', 'system-status' ),
		'edit_item'             => __( 'Edit Notice', 'system-status' ),
		'update_item'           => __( 'Update Notice', 'system-status' ),
		'view_item'             => __( 'View Notice', 'system-status' ),
		'search_items'          => __( 'Search Notices', 'system-status' ),
		'not_found'             => __( 'No Notice Found', 'system-status' ),
		'not_found_in_trash'    => __( 'No Notice found in Trash', 'system-status' ),
		'featured_image'        => __( 'Notice Image', 'system-status' ),
		'set_featured_image'    => __( 'Set Notice Image', 'system-status' ),
		'remove_featured_image' => __( 'Remove Notice Image', 'system-status' ),
		'use_featured_image'    => __( 'Use as Notice Image', 'system-status' ),
		'insert_into_item'      => __( 'Insert into Notice', 'system-status' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Notice', 'system-status' ),
		'items_list'            => __( 'Notices list', 'system-status' ),
		'items_list_navigation' => __( 'Notices list navigation', 'system-status' ),
		'filter_items_list'     => __( 'Filter Notices list', 'system-status' ),
	);
	$args = array(
		'label'                 => __( 'Status Notice', 'system-status' ),
		'description'           => __( 'Notices for Incidents or Maintenances.', 'system-status' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', ),
		'taxonomies'            => array( 'incidents', ' maintenances' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-megaphone',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'status-notices', $args );

}
add_action( 'init', 'system_status_notices', 0 );

}





class system_status_notice_meta {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'notice_details',
			__( 'Details', 'system-status' ),
			array( $this, 'render_notice_metabox' ),
			'status-notices',
			'advanced',
			'default'
		);

	}

	public function render_notice_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'system_status_nonce_action', 'system_status_nonce' );

		// Retrieve an existing value from the database.
		$system_status_notice_incident_id = get_post_meta( $post->ID, 'system_status_notice-incident-id', true );
		$system_status_notice_maintenance_id = get_post_meta( $post->ID, 'system_status_notice-maintenance-id', true );

		// Set default values.
		if( empty( $system_status_notice_incident_id ) ) $system_status_notice_incident_id = '';
		if( empty( $system_status_notice_maintenance_id ) ) $system_status_notice_maintenance_id = '';

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label for="system_status_notice-incident-id" class="system_status_notice-incident-id_label">' . __( 'Incident', 'system-status' ) . '</label></th>';
		echo '		<td>';
		wp_dropdown_pages( array( 'id' => 'system_status_notice_incident_id', 'name' => 'system_status_notice-incident-id', 'class' => 'system_status_notice_incident_id_field', 'selected' => $system_status_notice_incident_id,  'post_type' => 'incidents' ) );
		echo '			<p class="description">' . __( 'The associated Incident.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="system_status_notice-maintenance-id" class="system_status_notice-maintenance-id_label">' . __( 'Maintenance', 'system-status' ) . '</label></th>';
		echo '		<td>';
		wp_dropdown_pages( array( 'id' => 'system_status_notice_maintenance_id', 'name' => 'system_status_notice-maintenance-id', 'class' => 'system_status_notice_maintenance_id_field', 'selected' => $system_status_notice_maintenance_id, 'post_type' => 'maintenances' ) );
		echo '			<p class="description">' . __( 'The associated Maintenance.', 'system-status' ) . '</p>';
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
		$system_status_new_notice_incident_id = isset( $_POST[ 'system_status_notice-incident-id' ] ) ? sanitize_text_field( $_POST[ 'system_status_notice-incident-id' ] ) : '';
		$system_status_new_notice_maintenance_id = isset( $_POST[ 'system_status_notice-maintenance-id' ] ) ? sanitize_text_field( $_POST[ 'system_status_notice-maintenance-id' ] ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'system_status_notice-incident-id', $system_status_new_notice_incident_id );
		update_post_meta( $post_id, 'system_status_notice-maintenance-id', $system_status_new_notice_maintenance_id );

	}

}

new system_status_notice_meta;
