<?php

add_action( 'rest_api_init', 'register_incident_metafields' );


/**
 * register_incident_metafields function.
 *
 * @access public
 * @return void
 */
function register_incident_metafields() {

	// Register Rest Field: Incident Start Date.
    register_rest_field( 'incidents',
        'incident_startdate',
        array(
            'get_callback'    => 'get_incident_startdate',
            'update_callback' => null,
            'schema' => array(
				'description' => __( 'Start date for an incident.', 'system-status' ),
				'type' => 'string',
				'context' => array( 'view', 'edit' ),
				),
        )
    );

    // Register Rest Field: Incident Start Time.
    register_rest_field( 'incidents',
        'incident_starttime',
        array(
            'get_callback'    => 'get_incident_starttime',
            'update_callback' => null,
            'schema' => array(
				'description' => __( 'Start time for an incident.', 'system-status' ),
				'type' => 'string',
				'context' => array( 'view', 'edit' ),
				),
        )
    );

    // Register Rest Field: Incident End Date.
    register_rest_field( 'incidents',
        'incident_enddate',
        array(
            'get_callback'    => 'get_incident_enddate',
            'update_callback' => null,
            'schema' => array(
				'description' => __( 'End date for an incident.', 'system-status' ),
				'type' => 'string',
				'context' => array( 'view', 'edit' ),
				),
        )
    );

    // Register Rest Field: Incident End Time.
    register_rest_field( 'incidents',
        'incident_endtime',
        array(
            'get_callback'    => 'get_incident_endtime',
            'update_callback' => null,
            'schema' => array(
				'description' => __( 'End time for an incident.', 'system-status' ),
				'type' => 'string',
				'context' => array( 'view', 'edit' ),
				),
        )
    );

    // Register Rest Field: Incident Ticket Count.
    register_rest_field( 'incidents',
        'incident_ticket_count',
        array(
            'get_callback'    => 'get_incident_ticket_count',
            'update_callback' => null,
            'schema' => array(
				'description' => __( 'Start date for an incident.', 'system-status' ),
				'type' => 'string',
				'context' => array( 'view', 'edit' ),
				),
        )
    );
}



/**
 * Get Incident Start Date.
 *
 * @access public
 * @param mixed $object
 * @param mixed $field_name
 * @param mixed $request
 * @return void
 */
function get_incident_startdate( $object, $field_name, $request ) {
	 return get_post_meta( $object[ 'id' ], 'incident_startdate', true );
}

function get_incident_starttime( $object, $field_name, $request ) {
	 return get_post_meta( $object[ 'id' ], 'incident_starttime', true );
}

function get_incident_enddate( $object, $field_name, $request ) {
	 return get_post_meta( $object[ 'id' ], 'incident_enddate', true );
}

function get_incident_endtime( $object, $field_name, $request ) {
	 return get_post_meta( $object[ 'id' ], 'incident_endtime', true );
}

function get_incident_ticket_count( $object, $field_name, $request ) {
	 return get_post_meta( $object[ 'id' ], 'incident_ticket_count', true );
}

