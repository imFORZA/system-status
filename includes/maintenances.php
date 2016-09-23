<?php

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists('system_status_maintenance') ) {

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
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-admin-tools',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
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
