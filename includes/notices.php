<?php

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'system_status_notices' ) ) {

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
			'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions' ),
			'taxonomies'            => array( 'incidents', ' maintenances' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-megaphone',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'show_in_rest' 			=> true,
			'rest_base' 			=> __( 'notices', 'system-status' ),
			'rest_controller_class' => 'WP_REST_Posts_Controller',
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



if ( ! function_exists( 'system_status_notice_type' ) ) {

	// Register Custom Taxonomy
	function system_status_notice_type() {

		$labels = array(
		'name'                       => _x( 'Notice Type', 'Taxonomy General Name', 'system-status' ),
		'singular_name'              => _x( 'Notice Type', 'Taxonomy Singular Name', 'system-status' ),
		'menu_name'                  => __( 'Notice Type', 'system-status' ),
		'all_items'                  => __( 'All Notice Types', 'system-status' ),
		'parent_item'                => __( 'Parent Notice Type', 'system-status' ),
		'parent_item_colon'          => __( 'Parent Notice Type:', 'system-status' ),
		'new_item_name'              => __( 'New Notice Type Name', 'system-status' ),
		'add_new_item'               => __( 'Add New Notice Type', 'system-status' ),
		'edit_item'                  => __( 'Edit Notice Type', 'system-status' ),
		'update_item'                => __( 'Update Notice Type', 'system-status' ),
		'view_item'                  => __( 'View Notice Type', 'system-status' ),
		'separate_items_with_commas' => __( 'Separate Notice Types with commas', 'system-status' ),
		'add_or_remove_items'        => __( 'Add or remove Notice Types', 'system-status' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'system-status' ),
		'popular_items'              => __( 'Popular Notice Types', 'system-status' ),
		'search_items'               => __( 'Search Notice Types', 'system-status' ),
		'not_found'                  => __( 'Not Found', 'system-status' ),
		'no_terms'                   => __( 'No Notice Types', 'system-status' ),
		'items_list'                 => __( 'Notice Types list', 'system-status' ),
		'items_list_navigation'      => __( 'Notice Types list navigation', 'system-status' ),
			);
			$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => false,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
			'rest_base'          		 => 'notice-type',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			);
			register_taxonomy( 'notice-type', array( 'status-notices' ), $args );

	}
	add_action( 'init', 'system_status_notice_type', 0 );

}







class system_status_notice_meta {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' ) );
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
		$notice_incident_id = get_post_meta( $post->ID, 'notice_incident_id', true );
		$system_status_notice_maintenance_id = get_post_meta( $post->ID, 'system_status_notice_maintenance_id', true );

		$notice_types = get_terms( array(
			'taxonomy' => 'notice-type',
			'hide_empty' => false,
		) );

		// Set default values.
		if ( empty( $notice_incident_id ) ) { $notice_incident_id = '';
		}
		if ( empty( $system_status_notice_maintenance_id ) ) { $system_status_notice_maintenance_id = '';
		}

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label for="notice-type" class="notice-type">' . __( 'Notice Type', 'system-status' ) . '</label></th>';
		echo '		<td>';

		if ( ! empty( $notice_types ) && ! is_wp_error( $notice_types ) ) {
			echo '<select id="notice-type-dropdown" class="widefat" name="notice_type" required>';
			echo '<option value="">Choose...</option>';
			foreach ( $notice_types as $notice_type ) {
				echo '<option value="' . $notice_type->name . '" selected="' . $notice_type->name  . '">' . $notice_type->name . '</option>';
			}
			echo '</select>';
		}

		echo '	 <p class="description">' . __( 'Is this notice related to an incident or maintenance?', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '	<th><label for="notice_incident_id" class="notice_incident_id_label">' . __( 'Incident', 'system-status' ) . '</label></th>';
		echo '	<td>';

		wp_reset_postdata();

		$incidents = new WP_Query( array(
			'post_type'      => 'incidents',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		) );

		if ( ! empty( $incidents ) && ! is_wp_error( $incidents ) ) {
			echo '<select id="notice-type-dropdown" class="widefat" name="notice_incident_id" >';
			echo '<option value="">Choose...</option>';
			foreach ( $incidents as $incident ) {
				if ( ! empty( $incident->ID ) ) {
					echo '<option value="' . $incident->ID . '" selected="' . $notice_incident_id  . '">' . $incident->post_title . '</option>';
				}
			}
			echo '</select>';
		}

		echo '	 <p class="description">' . __( 'The associated Incident.', 'system-status' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="notice_maintenance_id" class="notice_maintenance_id_label">' . __( 'Maintenance', 'system-status' ) . '</label></th>';
		echo '		<td>';

		wp_reset_postdata();

		$maintenances = new WP_Query( array(
			'post_type'      => 'maintenances',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		) );

		if ( ! empty( $maintenances ) && ! is_wp_error( $maintenances ) ) {
			echo '<select id="notice-type-dropdown" class="widefat"  name="notice_maintenance_id" >';
			echo '<option value="">Choose...</option>';
			foreach ( $maintenances as $maintenance ) {
				if ( ! empty( $maintenance->ID ) ) {
					echo '<option value="' . $maintenance->ID . '" selected="' . $notice_maintenance_id . '" >' . $maintenance->post_title . '</option>';
				}
			}
			echo '</select>';
		}

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
		$new_notice_incident_id = isset( $_POST['notice_incident_id'] ) ? sanitize_text_field( $_POST['notice_incident_id'] ) : '';
		$new_notice_maintenance_id = isset( $_POST['notice_maintenance_id'] ) ? sanitize_text_field( $_POST['notice_maintenance_id'] ) : '';

		// Update the meta field in the database.
		wp_set_post_terms( $post_id, 'notice_type', 'status-notices', false );
		update_post_meta( $post_id, 'notice_incident_id', $new_notice_incident_id );
		update_post_meta( $post_id, 'notice_maintenance_id', $new_notice_maintenance_id );

	}
}

new system_status_notice_meta;
