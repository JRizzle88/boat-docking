<?php

// routes
// route template
// add_action( 'rest_api_init', function () {
//	 register_rest_route( 'myplugin/v1', '/author/(?P<id>\d+)', array(
//		'methods' => 'GET',
//		'callback' => 'my_awesome_func',
//	) );
//}

$url_prefix = 'bd/api/v1';
$routes = [
        [
            'url' => '/reservations',
            'method' => 'GET',
            'callback' => 'get_all_reservations'
        ]
];

//add_action( 'rest_api_init', array($this, 'register_routes'));

function register_routes() {
    // foreach($routes as $route) {
    register_rest_route( $url_prefix, '/reservations', array(
		//'methods' => 'GET',
        'methods' => \WP_REST_Server::READABLE,
		'callback' => array($this, 'get_all_reservations'),
        'args' => array(
			'id' => array(
				'validate_callback' => function($param, $request, $key) {
					return is_numeric( $param );
				}
			),
		),
	) );
    //}
}

function get_all_reservations() {
    $posts = get_posts( array(
		'author' => $data['id'],
	) );

	if ( empty( $posts ) ) {
		return new WP_Error( 'awesome_no_author', 'Invalid author', array( 'status' => 404 ) );
	}

	return $posts[0]->post_title;
}
